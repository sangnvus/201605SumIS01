<?php
/**
 * preview.php
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
	$path = getRequestParam("path");

	if ($path == "")
		renderPage("blank.tpl.php", $data);

	if ($path == "_mc_root_path_") {
		$data['subdirs'] = count(array_keys($mcFileManagerConfig["filesystem.rootpaths"]));
		$data["type"] = "root";
		$data['readable'] = "readable";
		$data['writable'] = "not_writable";
		$data['filename'] = "/";

		renderPage("preview.tpl.php", $data);
	}

	$rootpath = getRequestParam("rootpath", toUnixPath(getRealPath($mcFileManagerConfig, 'filesystem.rootpath')));
	$redirect = getRequestParam("redirect");
	$fileFactory =& new FileFactory($mcFileManagerConfig, $rootpath);
	$targetFile =& $fileFactory->getFile($path);
	$config = $targetFile->getConfig();

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

	$data['download'] = ($fileFilterA->accept($targetFile) && $fileFilterB->accept($targetFile));

	addFileEventListeners($fileFactory);

	$file =& $fileFactory->getFile($path);
	if (!$file->exists())
		renderPage("blank.tpl.php", $data);

	$data['path'] = $file->getAbsolutePath();
	$data['filename'] = basename(getUserFriendlyPath($file->getAbsolutePath()));
	if ($data['filename'] == "")
		$data['filename'] = "/";
	$data['filesize'] = getSizeStr($file->length());
	$fileType = getFileType($file->getAbsolutePath());
	$data['icon'] = $fileType['icon'];
	$data['type'] = $fileType['type'];
	$data['preview'] = $fileType['preview'];
	$data['previewurl'] = "";
	$data['modificationdate'] = date($config['filesystem.datefmt'], $file->lastModified());
	$data['creationdate'] = date($config['filesystem.datefmt'], $file->creationDate());
	$data['readable'] = ($file->canRead() && checkBool($config["filesystem.readable"]) ? "readable" : "not_readable");
	$data['writable'] = ($file->canWrite() && checkBool($config["filesystem.writable"]) ? "writable" : "not_writable");
	$data['type'] = $file->isDirectory() ? "dir" : "file";
	$data['description'] = $fileType['type'];

	// Count files and dirs
	if ($file->isDirectory()) {
		// Get filtered files
		$fileFilter =& new BasicFileFilter();
		$fileFilter->setIncludeDirectoryPattern($config['filesystem.include_directory_pattern']);
		$fileFilter->setExcludeDirectoryPattern($config['filesystem.exclude_directory_pattern']);
		$fileFilter->setIncludeFilePattern($config['filesystem.include_file_pattern']);
		$fileFilter->setExcludeFilePattern($config['filesystem.exclude_file_pattern']);
		$files = $file->listFilesFiltered($fileFilter);

		//$files = $file->listFiles();

		$fileCount = 0;
		$dirCount = 0;
		$sizeSum = 0;

		foreach ($files as $file) {
			if ($file->isFile())
				$fileCount++;
			else
				$dirCount++;

			$sizeSum += $file->length();
		}

		$data['files'] = $fileCount;
		$data['subdirs'] = $dirCount;
		$data['filessize'] = getSizeStr($sizeSum);
	} else {
		$path = $file->getAbsolutePath();
		$wwwroot = removeTrailingSlash(toUnixPath(getWWWRoot($config)));
		$urlprefix = removeTrailingSlash(toUnixPath($config['preview.urlprefix']));
		$urlsuffix = toUnixPath($config['preview.urlsuffix']);

		$pos = strpos($path, $wwwroot);
		if ($pos !== false && $pos == 0)
			$data['previewurl'] = $urlprefix . substr($path, strlen($wwwroot)) . $urlsuffix;
	}

	// Redirect
	if ($redirect == "true") {
		header('location: ' . $data['previewurl']);
		die;
	}

	// Render output
	renderPage("preview.tpl.php", $data);
?>
