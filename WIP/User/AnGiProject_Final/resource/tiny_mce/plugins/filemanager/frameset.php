<?php
/**
 * frameset.php
 *
 * @package MCFileManager.pages
 * @author Moxiecode
 * @copyright Copyright © 2005, Moxiecode Systems AB, All rights reserved.
 */

	// Use specified session instead
	if (isset($_REQUEST['sessionid']))
		session_id($_REQUEST['sessionid']);

	require_once("includes/general.php");
	require_once("classes/FileSystems/FileFactory.php");
	require_once("classes/FileSystems/LocalFileImpl.php");

	$data = array();

	// Verify access and get path and rootpath
	verifyAccess($mcFileManagerConfig);
	$path = $mcFileManagerConfig["filesystem.path"];
	$rootPath = $mcFileManagerConfig["filesystem.rootpath"];
	$listRootPaths = $rootPath == "_mc_unknown_root_path_";

	if ($listRootPaths) {
		$data['path'] = $path;
		$data['previewpath'] = "_mc_root_path_";
		$data['previewfilename'] = "_mc_root_path_";
		$data['rootpath'] = $rootPath;
		$data['showpreview'] = checkBool($mcFileManagerConfig['preview']);
		$data['formname'] = getRequestParam("formname", "");
		$data['elementnames'] = getRequestParam("elementnames", "");
		$data['js'] = getRequestParam("js", "");

		renderPage("frameset.tpl.php", $data);
	}

	$url = getRequestParam("url", "");
	$fileFactory =& new FileFactory($mcFileManagerConfig, $rootPath);

	// Invalid path, use root path
	if (!$fileFactory->verifyPath($path))
		$path = $rootPath;

	// Get file and config
	$targetFile =& $fileFactory->getFile($path);
	$config = $targetFile->getConfig();

	addFileEventListeners($fileFactory);

	$previewpath = $path;

	// Get parent dir if path points to a file
	$fileFactory =& new FileFactory($mcFileManagerConfig, $rootPath);
	$file =& $fileFactory->getFile($path);
	if ($file->exists()) {
		if ($file->isFile())
			$path = $file->getParent();

		$previewpath = $file->getAbsolutePath();
	} else {
		$path = toUnixPath(getRealPath($mcFileManagerConfig, 'filesystem.path'));
		$previewpath = toUnixPath(getRealPath($mcFileManagerConfig, 'filesystem.path'));
	}

	$data['path'] = $path;
	$data['previewpath'] = $previewpath;
	$data['previewfilename'] = basename($previewpath);
	$data['rootpath'] = $rootPath;
	$data['showpreview'] = checkBool($mcFileManagerConfig['preview']);
	$data['formname'] = getRequestParam("formname", "");
	$data['elementnames'] = getRequestParam("elementnames", "");
	$data['js'] = getRequestParam("js", "");

	// Render output
	renderPage("frameset.tpl.php", $data);
?>
