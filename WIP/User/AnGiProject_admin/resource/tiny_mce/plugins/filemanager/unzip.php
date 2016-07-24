<?php
/**
 * upload.php
 *
 * @package MCFileManager.pages
 * @author Moxiecode
 * @copyright Copyright © 2005, Moxiecode Systems AB, All rights reserved.
 */

	require_once("includes/general.php");

	// we need to remove e_notice, cause plczip has some notice errors.
	error_reporting(E_ALL ^ E_NOTICE);
	require_once("classes/pclzip/pclzip.lib.php");
	require_once("classes/FileSystems/LocalFileImpl.php");

	@set_time_limit(20*60); // 20 minutes execution time

	$data = array();
	verifyAccess($mcFileManagerConfig);
	$path = getRequestParam("path", toUnixPath(getRealPath($mcFileManagerConfig, 'filesystem.path')));
	$rootpath = getRequestParam("rootpath", toUnixPath(getRealPath($mcFileManagerConfig, 'filesystem.rootpath')));
	$fileFactory =& new FileFactory($mcFileManagerConfig, $rootpath);
	$targetFile =& $fileFactory->getFile($path);
	$config = $targetFile->getConfig();

	addFileEventListeners($fileFactory);

	$data['errorMsg'] = "";
	$data['path'] = $path;
	$data['short_path'] = getUserFriendlyPath($path, 30);
	$data['full_path'] = getUserFriendlyPath($path);
	$data['demo'] = checkBool($config['general.demo']) ? "true" : "false";
	$data['demo_msg'] = $config['general.demo_msg'];

	$action = getRequestParam("action", "");

	// Get selected files
	$selectedFiles = array();
	$requestFiles = getRequestParam("files");
	$requestFilesArr = split(",", $requestFiles);

	foreach ($requestFilesArr as $filename)
		$selectedFiles[] =& $fileFactory->getFile(toUnixPath($path . "/" . $filename));

	$zipList[] = array();
	$i = 0;

	$fileFilter =& new BasicFileFilter();
	$isDir = false;
	$contentOut = array();
	$parentDir = "";

	$template = "unzip.tpl.php";

	foreach ($selectedFiles as $file) {
		$zip = new PclZip($file->getAbsolutePath());
		$excludedDirArray = array();
		$excludedFileArray = array();
		$contents = $zip->listContent();
		// Loop contents and check against filsystem options.
		foreach($contents as $zippedfile) {
			if (strlen($zippedfile['filename']) > 15)
				$zippedfile['friendlypath'] = "... " . substr($zippedfile['filename'], strlen($zippedfile['filename'])-15);
			else
				$zippedfile['friendlypath'] = $zippedfile['filename'];

			$zippedfile['mtime'] = date($config['filesystem.datefmt'], $zippedfile['mtime']);

			// Default status message
			$zippedfile['status_message'] = "Accepted";

			$isDir = ($zippedfile['folder'] == 1) ? true : false;

			$filepath = "";
			$absolutefilepath = "";
			if ($isDir) {
				$filepath = $zippedfile['filename'];
			} else {
				$pathpos = strrpos(toUnixPath($zippedfile['filename']), "/");
				
				if ($pathpos !== false)
					$filepath = substr(toUnixPath($zippedfile['filename']), 0, $pathpos);
			}

			$absolutefilepath = removeTrailingSlash(toUnixPath($targetFile->getAbsolutePath() ."/". $zippedfile['filename']));

			$fileObject = $fileFactory->getFile($absolutefilepath, "", $isDir ? MC_IS_DIRECTORY : MC_IS_FILE);

			if (($fileObject->getParent() != $parentDir) || ($parentDir == "")) {
				$config = $fileObject->getConfig();
				
				$fileFilter->setIncludeDirectoryPattern($config['filesystem.include_directory_pattern']);
				$fileFilter->setExcludeDirectoryPattern($config['filesystem.exclude_directory_pattern']);
				$fileFilter->setIncludeFilePattern($config['filesystem.include_file_pattern']);
				$fileFilter->setExcludeFilePattern($config['filesystem.exclude_file_pattern']);
				$fileFilter->setIncludeExtensions($config['filesystem.extensions']);
				
				$parentDir = $fileObject->getParent();
			}

			$fileType = getFileType($fileObject->getAbsolutePath());
			$zippedfile['icon'] = $fileType['icon'];
			$zippedfile['type'] = $fileType['type'];

			if ($fileObject->exists())
				$zippedfile['exists'] = "Yes";
			else
				$zippedfile['exists'] = "No";

			if ($isDir) {
				if ($zippedfile['status'] != "ok") {
					$zippedfile['status'] = "Denied";
					$zippedfile['status_message'] = "File not accepted cause of error in zip.";
					$excludedDirArray[] = removeTrailingSlash($zippedfile['filename']);
					continue;
				}

				if ($fileFilter->accept($fileObject)) {
					$zippedfile['status'] = "Accepted";
				} else {
					$zippedfile['status_message'] = "Folder is excluded cause of configurated filters.";
					$zippedfile['status'] = "Denied";
					$excludedDirArray[] = removeTrailingSlash($zippedfile['filename']);
				}

			} else {
				

				if ($zippedfile['size'] != 0)
					$zippedfile['ratio'] = abs(round(($zippedfile['compressed_size'] / $zippedfile['size'])*100)-100);
				else {
					$zippedfile['ratio'] = "0";
				}

				$zippedfile['size'] = getSizeStr($zippedfile['size']);
				$zippedfile['compressed_size'] = getSizeStr($zippedfile['compressed_size']);

				if ($zippedfile['status'] != "ok") {
					$zippedfile['status'] = "Denied";
					$zippedfile['status_message'] = "File not accepted cause of error in zip.";
					$excludedFileArray[] = $zippedfile['filename'];
					continue;
				}

				if ($fileFilter->accept($fileObject)) {
					if ($filepath != "") {
						if (!in_array($filepath, $excludedDirArray)) {
							$zippedfile['status'] = "Accepted";
						} else {
							$zippedfile['status_message'] = "Excluded cause of previous filters for folders.";
							$zippedfile['status'] = "Denied";
							$excludedFileArray[] = $zippedfile['filename'];
						}
					} else
						$zippedfile['status'] = "Accepted";
				} else {
					$zippedfile['status_message'] = "Excluded by filters.";
					$zippedfile['status'] = "Denied";
					$excludedFileArray[] = $zippedfile['filename'];
				}
			}	
			
			$contentOut[] = $zippedfile;
		}

		$zipList[$i]['name'] = $file->getName();
		$zipList[$i]['absolutepath'] = $file->getAbsolutePath();
		$zipList[$i]['shortname'] = substr($file->getName(), 0 , strrpos($file->getName(), "."));
		$zipList[$i]['contents'] = $contentOut;
		$contentOut = array();
		$i++;
	}

	// Take the array we just made, and unzip it.
	if ($action == "unzip" && $data['demo'] != "true" && $targetFile->canWrite() && checkBool($config["filesystem.writable"])) {
		$checks = array();

		if (isset($_REQUEST['checks']))
			$checks = $_REQUEST['checks'];

		$parentExists = false;
		$unziplist = array();
		$resultList = array();
		$unzipfolder = "";
		$exists = false;
		$itemsresult = array();
		$i = 0;
		foreach($zipList as $zipfile) {
			$zip = new PclZip($zipfile['absolutepath']);
			$unziplist = isset($checks[$zipfile['name']]) ? $checks[$zipfile['name']] : array();
			$overwrite = getRequestParam("overwrite_". $i, "");
			$zipcontents = $zipfile['contents'];

			foreach($zipcontents as $file) {
				$pdirArr = array();
				if (in_array($file['filename'], $unziplist)) {
					// we need to double check that they are accepted.
					if ($file['status'] == "Accepted") {
						$isDir = ($file['folder'] == 1) ? true : false;
						$absolutefilepath = removeTrailingSlash(toUnixPath($targetFile->getAbsolutePath() ."/". $file['filename']));
						$fileObject = $fileFactory->getFile($absolutefilepath, "", $isDir ? MC_IS_DIRECTORY : MC_IS_FILE);

						$exists = $fileObject->exists();

						// Ignore directories that exists
						if ($isDir && $exists)
							continue;
						else if ($isDir && !$exists)
							$fileObject->mkdir();

						if (($exists) && ($overwrite == "yes")) {
							$fileObject->delete();
							$zip->extract(PCLZIP_OPT_PATH, $targetFile->getAbsolutePath(),PCLZIP_OPT_BY_NAME, $file['filename']);
						} else if (!$exists)
							$zip->extract(PCLZIP_OPT_PATH, $isDir ? $targetFile->getAbsolutePath() : $targetFile->getAbsolutePath(),PCLZIP_OPT_BY_NAME, $file['filename']);
					}
				}
			}
			

			// Check what changed
			foreach ($zipcontents as $filecheck) {
				$isDir = ($filecheck['folder'] == 1) ? true : false;

				$absolutefilepath = removeTrailingSlash(toUnixPath($targetFile->getAbsolutePath() ."/". $filecheck['filename']));
				$filecheckObj = $fileFactory->getFile($absolutefilepath, "", $isDir ? MC_IS_DIRECTORY : MC_IS_FILE);
				
				// Default status message
				$filecheck['status_message'] = "Passed";

				if ($filecheck['status'] == "Accepted") {
					if ($filecheck['exists'] == "No") {
						if ($filecheckObj->exists()) {
							$filecheckObj->importFile();
							$filecheck['status'] = "Passed";
							$filecheck['exists'] = "Yes";
							$itemsresult[] = $filecheck;
						} else {
							$filecheck['status'] = "Failed";
							$filecheck['status_message'] = "Unzip operation failed, file was not unzipped.";
							$itemsresult[] = $filecheck;
						}

					} else if (($filecheck['exists'] == "Yes") && ($overwrite == "yes")) {
						if ($filecheckObj->exists()) {
							$filecheckObj->importFile();
							$filecheck['status'] = "Passed";
							$itemsresult[] = $filecheck;
						} else {
							$filecheck['status'] = "Failed";
							$filecheck['status_message'] = "Overwrite operation failed, fatal error.";
							$itemsresult[] = $filecheck;
						}
					} else if (($filecheck['exists'] == "Yes") && ($overwrite != "yes")) {
							$filecheck['status'] = "Failed";
							$filecheck['status_message'] = "Unzip operation failed, overwrite not set.";
							$itemsresult[] = $filecheck;
					}
				}
			}

			$resultList[$i]['name'] = $zipfile['name'];
			$resultList[$i]['overwrite'] = $overwrite;
			$resultList[$i]['shortname'] = substr($zipfile['name'], 0, strrpos($zipfile['name'], "."));
			$resultList[$i]['contents'] = $itemsresult;
			$itemsresult = array();
			$i++;
		}

		$data['out'] = $resultList;

		$template = "unzip_complete.tpl.php";
	} else {
		$data['out'] = $zipList;
		$data['files'] = getRequestParam("files");
	}

	// Render output
	renderPage($template, $data);
?>
