<?php
/**
 * createdoc.php
 *
 * @package MCFileManager.pages
 * @author Moxiecode
 * @copyright Copyright © 2005, Moxiecode Systems AB, All rights reserved.
 */

	require_once("includes/general.php");
	require_once("classes/FileSystems/FileFactory.php");
	require_once("classes/FileSystems/LocalFileImpl.php");

	$data = array();
	verifyAccess($mcFileManagerConfig);
	$path = getRequestParam("path", toUnixPath(getRealPath($mcFileManagerConfig, 'filesystem.path')));
	$rootpath = getRequestParam("rootpath", toUnixPath(getRealPath($mcFileManagerConfig, 'filesystem.rootpath')));
	$action = getRequestParam("action", "");
	$docname = getRequestParam("docname", "");
	$template = getRequestParam("template", false);
	$fileFactory =& new FileFactory($mcFileManagerConfig, $rootpath);
	$targetFile =& $fileFactory->getFile($path);
	$config = $targetFile->getConfig();
	$fields = preg_split('/,/', $config['createdoc.fields'], -1, PREG_SPLIT_NO_EMPTY);

	addFileEventListeners($fileFactory);

	$data['path'] = $path;
	$data['short_path'] = getUserFriendlyPath($path, 30);
	$data['full_path'] = getUserFriendlyPath($path);
	$data['action'] = getRequestParam("action", "");
	$data['errorMsg'] = "";
	$data['docname'] = $docname;
	$data['template'] = $template;
	$data['demo'] = checkBool($config['general.demo']) ? "true" : "false";
	$data['demo_msg'] = $config['general.demo_msg'];

	$data['fields'] = array();
	for ($i=0; $i<count($fields); $i+=2)
		$data['fields'][$fields[$i]] = $fields[$i+1];

	for ($i=0; $i<count($fields); $i+=2)
		$data['field_' . $fields[$i]] = getRequestParam("field_" . $fields[$i], "");

	$templates = explode(',', $config['filesystem.file_templates']);
	$data['templates'] = array();
	foreach ($templates as $templateName) {
		if ($templateName != "")
			$data['templates'][basename($templateName)] = $templateName;
	}

	$data['previewurl'] = "";
	$keys = array_keys($data['templates']);
	if (count($keys) == 1)
		$data['previewurl'] = $data['templates'][$keys[0]];

	// Do action
	if ($action == "submit" && $targetFile->canWrite() && checkBool($config["filesystem.writable"])) {
		// Do nothing in demo mode
		if (checkBool($config['general.demo']))
			trigger_error($config['general.demo_msg'], ERROR);

		// No access, tool disabled
		if (in_array("createdoc", explode(',', $config['general.disabled_tools'])))
			trigger_error("You don't have access to perform the requested action.", ERROR);

		// No docname specified
		if (!$docname) {
			$data['errorMsg'] = "error_missing_document_name";
			renderPage("createdoc.tpl.php", $data);
		}

		// No template selected
		if (!$template && count(array_keys($templates)) > 1) {
			$data['errorMsg'] = "error_missing_template_selection";
			renderPage("createdoc.tpl.php", $data);
		}

		// No template defined, use first template
		if (!$template)
			$template = $data['templates'][$keys[0]];

		if (strpos(basename($template), ".") > 0) {
			$ar = explode('.', basename($template));
			$ext = array_pop($ar);
		}

		$templateFile =& $fileFactory->getFile($template);
		if (!$templateFile->exists() || $template == "" || $template === false) {
			$data['errorMsg'] = "error_missing_template";
			renderPage("createdoc.tpl.php", $data);
		}

		$toFile =& $fileFactory->getFile($path, $docname . "." . $ext, MC_IS_FILE);

		// Hmm, it looks strange, deny ;,:,\,/,>,< characters
		if (preg_match('/[;\\\\\\/:><]/i', $docname) > 0) {
			$data['errorMsg'] = "error_invalid_file_name";
			renderPage("createdoc.tpl.php", $data);
		}

		// Setup first filter
		$fileFilterA =& new BasicFileFilter();
		$fileFilterA->setIncludeFilePattern($config['filesystem.include_file_pattern']);
		$fileFilterA->setExcludeFilePattern($config['filesystem.exclude_file_pattern']);
		if (!$fileFilterA->accept($toFile)) {
			$data['errorMsg'] = $config['filesystem.invalid_file_name_msg'];
			renderPage("createdoc.tpl.php", $data);
		}

		// Setup second filter
		$fileFilterB =& new BasicFileFilter();
		$fileFilterB->setIncludeFilePattern($config['createdoc.include_file_pattern']);
		$fileFilterB->setExcludeFilePattern($config['createdoc.exclude_file_pattern']);
		if (!$fileFilterB->accept($toFile)) {
			$data['errorMsg'] = $config['createdoc.invalid_file_name_msg'];
			renderPage("createdoc.tpl.php", $data);
		}

		// File exists
		if ($toFile->exists()) {
			$data['errorMsg'] = "error_exists";
			renderPage("createdoc.tpl.php", $data);
		}

		$templateFile->copyTo($toFile, true);

		// Replace title
		$fileData = file_get_contents($toFile->getAbsolutePath());

		// Replace all fields
		for ($i=0; $i<count($fields); $i+=2)
			$fileData = str_replace('${' . $fields[$i] . '}', htmlentities(getRequestParam("field_" . $fields[$i], "")), $fileData);

		if (($fp = fopen($toFile->getAbsolutePath(), "w")) != null) {
			fwrite($fp, $fileData);
			fclose($fp);
		}
	}

	// Render output
	renderPage("createdoc.tpl.php", $data);
?>
