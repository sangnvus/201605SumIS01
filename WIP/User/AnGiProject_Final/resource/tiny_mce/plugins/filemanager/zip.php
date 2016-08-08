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
	require_once("classes/pclzip/pclzip.lib.php");

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
	$data['filename'] = "";
	$data['demo'] = checkBool($config['general.demo']) ? "true" : "false";
	$data['demo_msg'] = $config['general.demo_msg'];

	// Create zip
	if ($submitted && $targetFile->canWrite() && checkBool($config["filesystem.writable"])) {
		$targetFile =& $fileFactory->getFile($path, $filename . ".zip", MC_IS_FILE);
		$config = $targetFile->getConfig();

		// Setup first filter
		$fileFilterA =& new BasicFileFilter();
		$fileFilterA->setIncludeFilePattern($config['filesystem.include_file_pattern']);
		$fileFilterA->setExcludeFilePattern($config['filesystem.exclude_file_pattern']);
		if (!$fileFilterA->accept($targetFile)) {
			$data['errorMsg'] = $config['filesystem.invalid_file_name_msg'];
			renderPage("zip.tpl.php", $data);
		}

		/*
		// Setup second filter
		$fileFilterB =& new BasicFileFilter();
		$fileFilterB->setIncludeFilePattern($config['zip.include_file_pattern']);
		$fileFilterB->setExcludeFilePattern($config['zip.exclude_file_pattern']);
		if (!$fileFilterB->accept($targetFile)) {
			$data['errorMsg'] = $config['zip.invalid_file_name_msg'];
			renderPage("zip.tpl.php", $data);
		}
		*/

		$archive = new PclZip($targetFile->getAbsolutePath());

		$files = array();
		for ($i=0; ($absPath = getRequestParam("file" . $i, false)); $i++) {
			$file =& $fileFactory->getFile($absPath);
			$files[] = $file->getAbsolutePath();
		}

		$list = $archive->create(implode(',', $files), PCLZIP_OPT_REMOVE_PATH, $targetFile->getParent());

		if ($list == 0)
			$data['errorMsg'] = $archive->errorInfo(true);
		else
			$targetFile->importFile();
	}

	// Render output
	renderPage("zip.tpl.php", $data);
?>
