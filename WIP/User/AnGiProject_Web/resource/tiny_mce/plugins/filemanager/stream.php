<?php
/**
 * stream.php
 *
 * @package MCFileManager.pages
 * @author Moxiecode
 * @copyright Copyright © 2005, Moxiecode Systems AB, All rights reserved.
 */

	require_once("includes/general.php");
	require_once("includes/stream.php");
	require_once("classes/FileSystems/FileFactory.php");
	require_once("classes/FileSystems/LocalFileImpl.php");

	verifyAccess($mcFileManagerConfig);
	$path = getRequestParam("path");
	$rootpath = getRequestParam("rootpath", toUnixPath(getRealPath($mcFileManagerConfig, 'filesystem.rootpath')));
	$fileFactory =& new FileFactory($mcFileManagerConfig, $rootpath);
	$targetFile =& $fileFactory->getFile($path);
	$config = $targetFile->getConfig();
	$mode = getRequestParam("mode", "stream");
	$mimeType = mapMimeTypeFromUrl($path, $config['stream.mimefile']);
	$file =& $fileFactory->getFile($path);

	// Setup first filter
	$fileFilterA =& new BasicFileFilter();
	$fileFilterA->setIncludeFilePattern($config['filesystem.include_file_pattern']);
	$fileFilterA->setExcludeFilePattern($config['filesystem.exclude_file_pattern']);
	$fileFilterA->setIncludeExtensions($config['filesystem.extensions']);

	// Setup second filter
	$fileFilterB =& new BasicFileFilter();
	$fileFilterB->setIncludeFilePattern($config['download.include_file_pattern']);
	$fileFilterB->setExcludeFilePattern($config['download.exclude_file_pattern']);
	$fileFilterB->setIncludeExtensions($config['download.extensions']);

	if (!$fileFilterA->accept($targetFile) || !$fileFilterB->accept($targetFile))
		trigger_error("Error: Requested file is not valid for download, check your config.", ERROR);

	addFileEventListeners($fileFactory);

	if ($file->exists()) {
		header("Content-type: " . $mimeType);

		if ($mode == "download")
			header("Content-Disposition: attachment; filename=" . $file->getName());

		addNoCacheHeaders();

		readfile($file->getAbsolutePath());
	} else {
		header('HTTP/1.0 404 Not found');
		header('status: 404 Not found');

		echo "Requested resource could not be found.";
	}
?>