<?php
/**
 * filelist.php
 *
 * @package MCFileManager.pages
 * @author Moxiecode
 * @copyright Copyright © 2005, Moxiecode Systems AB, All rights reserved.
 */

	require_once("includes/general.php");
	require_once("includes/tools.php");

	addNoCacheHeaders();

	$data = array();
	$skipParent = false;
	$errorMsg = "";

	// Save away path
	$path = getRequestParam("path");
	$_SESSION['mc_filemanager_lastpath'] = is_file($path) ? dirname($path) : $path;

	// Get path and config
	verifyAccess($mcFileManagerConfig);
	$path = $mcFileManagerConfig["filesystem.path"];
	$rootPath = $mcFileManagerConfig["filesystem.rootpath"];
	$rootPaths = $mcFileManagerConfig["filesystem.rootpaths"];
	$listRootPaths = $rootPath == "_mc_unknown_root_path_";

	$data['demo'] = checkBool($mcFileManagerConfig['general.demo']) ? "true" : "false";
	$data['demo_msg'] = $mcFileManagerConfig['general.demo_msg'];

	// Fake directory listing, this lists the root
	if ($listRootPaths) {
		$config = $mcFileManagerConfig;
		$fileList = array();
		$even = true;

		foreach ($rootPaths as $dirName => $rootPath) {
			// Force a file factory, UGLY!!
			$mcFileManagerConfig["filesystem.rootpath"] = $rootPath;
			$fileFactory =& new FileFactory($mcFileManagerConfig, $rootPath);
			$file =& $fileFactory->getFile($rootPath);

			$fileItem['name'] = $dirName;
			$fileItem['path'] = $file->getAbsolutePath();
			$fileItem['size'] = getSizeStr($file->length());
			$fileItem['modificationdate'] = date($config['filesystem.datefmt'], $file->lastModified());
			$fileItem['isDir'] = true;
			$fileItem['isParent'] = false;
			$fileItem['isCut'] = false;
			$fileItem['even'] = $even;
			$fileItem['hasReadAccess'] = "true";
			$fileItem['hasWriteAccess'] = "false";

			$even = !$even;
			$fileList[] = $fileItem;
		}

		$toolsCommands = explode(',', $config['general.tools']);
		$newTools = array();
		foreach ($toolsCommands as $command) {
			foreach ($tools as $tool) {
				if ($tool['command'] == $command)
					$newTools[] = $tool;
			}
		}

		$data['disabled_tools'] = $config['general.disabled_tools'];
		$data['tools'] = $newTools;
		$data['short_path'] = "/";
		$data['full_path'] = "/";
		$data['imageManagerURLPrefix'] = removeTrailingSlash($config['imagemanager.urlprefix']);
		$data['files'] = $fileList;
		$data['errorMsg'] = "";
		$data['action'] = "";
		$data['path'] = "_mc_root_path_";
		$data['hasReadAccess'] = "true";
		$data['hasWriteAccess'] = "false";
		$data['hasPasteData'] = "false";

		renderPage("filelist.tpl.php", $data, $config);
	}

	$fileFactory =& new FileFactory($mcFileManagerConfig, $rootPath);
	$targetFile =& $fileFactory->getFile($path);
	$config = $targetFile->getConfig();
	$isParentRootList = count(array_keys($rootPaths)) > 1 && in_array($path, array_values($rootPaths));

	addFileEventListeners($fileFactory);

	// Get rest of input
	$action = getRequestParam("action");
	$value = getRequestParam("value", "");

	// Get selected files
	$selectedFiles = array();
	foreach ($_REQUEST as $name => $value) {
		if (strpos($name, "file_") !== false || strpos($name, "dir_") !== false)
			$selectedFiles[] =& $fileFactory->getFile($value);
	}

	// Do action
	switch ($action) {
		case "delete":
			if (!$targetFile->canWrite() || !checkBool($config["filesystem.writable"]))
				break;

			// Do nothing in demo mode
			if (checkBool($config['general.demo']))
				trigger_error($config['general.demo_msg'], ERROR);

			// No access, tool disabled
			if (in_array("delete", explode(',', $config['general.disabled_tools'])))
				trigger_error($mcLanguage['error_delete_failed'], ERROR);

			foreach ($selectedFiles as $file) {
				if ((!$file->isFile() && count($file->listFiles()) > 0) && !checkBool($config['filesystem.delete_recursive']))
					$errorMsg .= str_replace("{path}", getUserFriendlyPath($file->getAbsolutePath()), $mcLanguage['error_directory_not_empty']);
				else
					$file->delete(checkBool($config['filesystem.delete_recursive']));
			}

			break;

		case "cut":
			if (!$targetFile->canWrite() || !checkBool($config["filesystem.writable"]))
				break;

			$_SESSION['MCFileManager_clipboardAction'] = "cut";
			$fileArray = array();

			foreach ($selectedFiles as $file)
				$fileArray[] = $file->getAbsolutePath();

			$_SESSION['MCFileManager_clipboardFiles'] = $fileArray;

			break;

		case "copy":
			$_SESSION['MCFileManager_clipboardAction'] = "copy";
			$fileArray = array();

			foreach ($selectedFiles as $file)
				$fileArray[] = $file->getAbsolutePath();

			$_SESSION['MCFileManager_clipboardFiles'] = $fileArray;
			break;

		case "paste":
			if (!$targetFile->canWrite() || !checkBool($config["filesystem.writable"]))
				break;

			// Do nothing in demo mode
			if (checkBool($config['general.demo']))
				trigger_error($config['general.demo_msg'], ERROR);

			// No access, tool disabled
			if (in_array("paste", explode(',', $config['general.disabled_tools'])))
				trigger_error("You don't have access to perform the requested action.", ERROR);

			$fileArray = $_SESSION['MCFileManager_clipboardFiles'];

			if (isset($_SESSION['MCFileManager_clipboardAction'])) {
				switch ($_SESSION['MCFileManager_clipboardAction']) {
					case "copy":
						foreach ($fileArray as $file) {
							$fromFile =& $fileFactory->getFile($file);
							$toFile =& $fileFactory->getFile($path, basename($file));

							if (!$toFile->exists())
								$fromFile->copyTo($toFile);
							else
								$errorMsg = str_replace("{path}", getUserFriendlyPath($toFile->getAbsolutePath()), $mcLanguage['error_exists']);
						}

						break;

					case "cut":
						foreach ($fileArray as $file) {
							$fromFile =& $fileFactory->getFile($file);
							$toFile =& $fileFactory->getFile($path, basename($file));

							if (!$toFile->exists())
								$fromFile->renameTo($toFile);
							else
								$errorMsg = str_replace("{path}", getUserFriendlyPath($toFile->getAbsolutePath()), $mcLanguage['error_exists']);
						}

						break;
				}
			}

			foreach ($selectedFiles as $file)
				$fileArray[] = $file->getAbsolutePath();

			// Reset
			$_SESSION['MCFileManager_clipboardAction'] = null;
			$_SESSION['MCFileManager_clipboardFiles'] = null;

			break;
	}

	$data['rootpath'] = $rootPath;

	// Disable parent dir
	if (($pos = strpos($path, "/.")) == strlen($path)-2)
		$skipParent = true;

	// Disable parent dir
	if (($pos = strpos($path, $rootPath)) === false || $pos > 0 || strlen($path) == strlen($rootPath))
		$skipParent = true;

	// Get filtered files
	$fileFilter =& new BasicFileFilter();
	//$fileFilter->setDebugMode(true);
	$fileFilter->setIncludeDirectoryPattern($config['filesystem.include_directory_pattern']);
	$fileFilter->setExcludeDirectoryPattern($config['filesystem.exclude_directory_pattern']);
	$fileFilter->setIncludeFilePattern($config['filesystem.include_file_pattern']);
	$fileFilter->setExcludeFilePattern($config['filesystem.exclude_file_pattern']);
	$fileFilter->setIncludeExtensions($config['filesystem.extensions']);
	$files =& $targetFile->listFilesFiltered($fileFilter);

	$fileList = array();
	$even = true;

	// Add parent dir to root list
	if ($isParentRootList) {
		$fileItem = array();

		$fileItem['name'] = "..";
		$fileItem['path'] = "_mc_root_path_";
		$fileItem['isDir'] = true;
		$fileItem['isParent'] = true;
		$fileItem['even'] = $even;
		$fileItem['size'] = "";
		$fileItem['modificationdate'] = "";
		$fileItem['isCut'] = false;

		$even = !$even;
		$fileList[] = $fileItem;
		$skipParent = true;
	}

	// Add parent dir
	if ($path != "/" && !$skipParent) {
		$fileItem = array();

		$fileItem['name'] = "..";
		$fileItem['path'] = $targetFile->getParent();
		$fileItem['isDir'] = true;
		$fileItem['isParent'] = true;
		$fileItem['even'] = $even;
		$fileItem['size'] = "";
		$fileItem['modificationdate'] = "";
		$fileItem['isCut'] = false;

		$even = !$even;
		$fileList[] = $fileItem;
	}

	// Append directories
	foreach ($files as $file) {
		if (!$file->isDirectory())
			continue;

		$fileItem = array();
		$name = $file->getAbsolutePath();

		$fileItem['name'] = basename($file->getAbsolutePath());
		$fileItem['path'] = $file->getAbsolutePath();
		$fileItem['size'] = "";
		$fileItem['modificationdate'] = date($config['filesystem.datefmt'], $file->lastModified());
		$fileItem['isDir'] = true;
		$fileItem['isParent'] = false;
		$fileItem['isCut'] = false;
		$fileItem['even'] = $even;
		$fileItem['type'] = $even;
		$fileItem['hasReadAccess'] = $file->canRead() && checkBool($config["filesystem.readable"]) ? "true" : "false";
		$fileItem['hasWriteAccess'] = $file->canWrite() && checkBool($config["filesystem.writable"]) ? "true" : "false";

		// Is in clipboard and is cut
		if (isset($_SESSION['MCFileManager_clipboardAction']) && $_SESSION['MCFileManager_clipboardAction'] == "cut") {
			$fileArray = $_SESSION['MCFileManager_clipboardFiles'];

			foreach ($fileArray as $tmpFilePath) {
				if ($file->getAbsolutePath() == $tmpFilePath) {
					$fileItem['isCut'] = true;
					break;
				}
			}
		}

		$even = !$even;
		$fileList[] = $fileItem;
	}

	// List files
	$accessFile = $config["filesystem.local.access_file_name"];
	foreach ($files as $file) {
		if ($file->isDirectory())
			continue;

		// Hide access files
		if ($file->getName() == $accessFile)
			continue;

		$fileItem = array();
		$name = $file->getAbsolutePath();

		$fileItem['name'] = basename($file->getAbsolutePath());
		$fileItem['path'] = $file->getAbsolutePath();
		$fileItem['size'] = getSizeStr($file->length());
		$fileItem['modificationdate'] = date($config['filesystem.datefmt'], $file->lastModified());
		$fileItem['isDir'] = false;
		$fileItem['isParent'] = false;
		$fileItem['isCut'] = false;
		$fileItem['even'] = $even;
		$fileItem['hasReadAccess'] = $file->canRead() && checkBool($config["filesystem.readable"]) ? "true" : "false";
		$fileItem['hasWriteAccess'] = $file->canWrite() && checkBool($config["filesystem.writable"]) ? "true" : "false";

		// Is in clipboard and is cut
		if (isset($_SESSION['MCFileManager_clipboardAction']) && $_SESSION['MCFileManager_clipboardAction'] == "cut") {
			$fileArray = $_SESSION['MCFileManager_clipboardFiles'];

			foreach ($fileArray as $tmpFilePath) {
				if ($file->getAbsolutePath() == $tmpFilePath) {
					$fileItem['isCut'] = true;
					break;
				}
			}
		}

		// File info
		$fileType = getFileType($file->getAbsolutePath());
		$fileItem['icon'] = $fileType['icon'];
		$fileItem['type'] = $fileType['type'];

		$even = !$even;
		$fileList[] = $fileItem;
	}

	$data['files'] = $fileList;
	$data['path'] = $path;
	$data['hasReadAccess'] = $targetFile->canRead() && checkBool($config["filesystem.readable"]) ? "true" : "false";
	$data['hasWriteAccess'] = $targetFile->canWrite() && checkBool($config["filesystem.writable"]) ? "true" : "false";
	$data['hasPasteData'] = isset($_SESSION['MCFileManager_clipboardAction']) ? "true" : "false";

	$toolsCommands = explode(',', $config['general.tools']);
	$newTools = array();
	foreach ($toolsCommands as $command) {
		foreach ($tools as $tool) {
			if ($tool['command'] == $command)
				$newTools[] = $tool;
		}
	}

	$data['disabled_tools'] = $config['general.disabled_tools'];
	$data['tools'] = $newTools;
	$data['short_path'] = getUserFriendlyPath($path);
	$data['full_path'] = getUserFriendlyPath($path);
	$data['errorMsg'] = $errorMsg;
	$data['action'] = $action;
	$data['imageManagerURLPrefix'] = removeTrailingSlash($config['imagemanager.urlprefix']);

	// Render output
	renderPage("filelist.tpl.php", $data, $config);
?>
