<?php
/**
 * fileprops.php
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
	$path = getRequestParam("path", "");
	$rootpath = getRequestParam("rootpath", toUnixPath(getRealPath($mcFileManagerConfig, 'filesystem.rootpath')));
	$fileFactory =& new FileFactory($mcFileManagerConfig, $rootpath);
	$targetFile =& $fileFactory->getFile($path);
	$config = $targetFile->getConfig();

	addFileEventListeners($fileFactory);

	$filename = getRequestParam("filename", false);
	$submitted = getRequestParam("submitted", false);
	$data['path'] = $path;
	$data['submitted'] = $submitted;
	$data['short_path'] = getUserFriendlyPath($path, 30);
	$data['full_path'] = getUserFriendlyPath($path);
	$data['errorMsg'] = "";
	$data['demo'] = checkBool($config['general.demo']) ? "true" : "false";
	$data['demo_msg'] = $config['general.demo_msg'];

	if (!$filename) {
		$filename = $targetFile->getName();

		if (strpos($filename, ".") > 0)
			$filename = substr($filename, 0, strrpos($filename, "."));
	}

	$data['filename'] = $filename;

	if ($submitted) {
		// Do nothing in demo mode
		if (checkBool($config['general.demo']))
			trigger_error($config['general.demo_msg'], ERROR);

		// No access, tool disabled
		if (in_array("props", explode(',', $config['general.disabled_tools'])))
			trigger_error("You don't have access to perform the requested action.", ERROR);

		$ext = false;
		if (strpos(basename($path), ".") > 0) {
			$ar = explode('.', basename($path));
			$ext = array_pop($ar);
		}

		$fromFile =& $fileFactory->getFile($path);
		$toFile =& $fileFactory->getFile($fromFile->getParent(), $ext ? ($filename . "." . $ext) : $filename);

		// Hmm, it looks strange, deny ;,:,\,/,>,< characters
		if (preg_match('/[;\\\\\\/:><]/i', $filename) > 0) {
			$data['errorMsg'] = "Error: The name of the file you are trying to create is invalid.";
			renderPage("fileprops.tpl.php", $data);
		}

		// Setup first filter
		$fileFilter =& new BasicFileFilter();
		$fileFilter->setIncludeFilePattern($config['filesystem.include_file_pattern']);
		$fileFilter->setExcludeFilePattern($config['filesystem.exclude_file_pattern']);
		if (!$fileFilter->accept($toFile)) {
			$data['errorMsg'] = $config['filesystem.invalid_file_name_msg'];
			renderPage("fileprops.tpl.php", $data);
		}

		// Setup first filter
		$fileFilter =& new BasicFileFilter();
		$fileFilter->setIncludeFilePattern($config['filesystem.include_directory_pattern']);
		$fileFilter->setExcludeFilePattern($config['filesystem.exclude_directory_pattern']);
		if (!$fileFilter->accept($toFile)) {
			$data['errorMsg'] = $config['filesystem.invalid_directory_name_msg'];
			renderPage("fileprops.tpl.php", $data);
		}

		// Setup second filter
		$fileFilter =& new BasicFileFilter();
		$fileFilter->setIncludeFilePattern($config['rename.include_file_pattern']);
		$fileFilter->setExcludeFilePattern($config['rename.exclude_file_pattern']);
		if (!$fileFilter->accept($toFile)) {
			$data['errorMsg'] = $config['rename.invalid_file_name_msg'];
			renderPage("fileprops.tpl.php", $data);
		}

		// Setup second filter
		$fileFilter =& new BasicFileFilter();
		$fileFilter->setIncludeFilePattern($config['rename.include_directory_pattern']);
		$fileFilter->setExcludeFilePattern($config['rename.exclude_directory_pattern']);
		if (!$fileFilter->accept($toFile)) {
			$data['errorMsg'] = $config['rename.invalid_directory_name_msg'];
			renderPage("fileprops.tpl.php", $data);
		}

		$fromFile->renameTo($toFile);
		$data['filename'] = $filename;
	}

	// Render output
	renderPage("fileprops.tpl.php", $data);
?>
