<?php
/**
 * default_config.php
 *
 * @package MCFileManager.includes
 * @author Moxiecode
 * @copyright Copyright © 2005, Moxiecode Systems AB, All rights reserved.
 *
 * This file contains the default config values. These values are extended by the user config.
 */

	// Config options for the Moxiecode FileManager
	$mcFileManagerConfig = array();

	// General options
	$mcFileManagerConfig['general.demo'] = false;
	$mcFileManagerConfig['general.debug'] = false;
	$mcFileManagerConfig['general.demo_msg'] = "This application is running in demostration mode, this action is restricted.";
	$mcFileManagerConfig['general.error_log'] = "";
	$mcFileManagerConfig['general.theme'] = "default";
	$mcFileManagerConfig['general.language'] = "en";
	$mcFileManagerConfig['general.user_friendly_paths'] = true;
	$mcFileManagerConfig['general.tools'] = "createdir,createdoc,separator,refresh,upload,zip,unzip,props,separator,cut,copy,paste,delete";
	$mcFileManagerConfig['general.disabled_tools'] = "";
	$mcFileManagerConfig['general.login_page'] = "login.php";
	$mcFileManagerConfig['general.allow_override'] = "*";

	// Preview options
	$mcFileManagerConfig['preview'] = true;
	$mcFileManagerConfig['preview.wwwroot'] = "";
	$mcFileManagerConfig['preview.urlprefix'] = "http://" . $_SERVER['HTTP_HOST'] . "/";
	$mcFileManagerConfig['preview.urlsuffix'] = "";
	$mcFileManagerConfig['preview.allow_override'] = "*";

	// General file system options
	$mcFileManagerConfig['filesystem'] = "LocalFileImpl";
	$mcFileManagerConfig['filesystem.path'] = "";
	$mcFileManagerConfig['filesystem.rootpath'] = "";
	$mcFileManagerConfig['filesystem.datefmt'] = "Y-m-d H:i";
	$mcFileManagerConfig['filesystem.include_directory_pattern'] = "";
	$mcFileManagerConfig['filesystem.exclude_directory_pattern'] = "";
	$mcFileManagerConfig['filesystem.invalid_directory_name_msg'] = "Error: The name of the directory is invalid.";
	$mcFileManagerConfig['filesystem.include_file_pattern'] = "";
	$mcFileManagerConfig['filesystem.exclude_file_pattern'] = '/^\./i';
	$mcFileManagerConfig['filesystem.invalid_file_name_msg'] = "Error: The name of the file is invalid.";
	$mcFileManagerConfig['filesystem.extensions'] = "";
	$mcFileManagerConfig['filesystem.invalid_extension_msg'] = "Error: The extension of the file is invalid.";
	$mcFileManagerConfig['filesystem.file_templates'] = '';
	$mcFileManagerConfig['filesystem.directory_templates'] = '';
	$mcFileManagerConfig['filesystem.file_event_listeners'] = "";
	$mcFileManagerConfig['filesystem.readable'] = true;
	$mcFileManagerConfig['filesystem.writable'] = true;
	$mcFileManagerConfig['filesystem.delete_recursive'] = false;
	$mcFileManagerConfig['filesystem.force_directory_template'] = false;
	$mcFileManagerConfig['filesystem.allow_override'] = "*";

	// Upload options
	$mcFileManagerConfig['upload.maxsize'] = "100MB";
	$mcFileManagerConfig['upload.include_file_pattern'] = "";
	$mcFileManagerConfig['upload.exclude_file_pattern'] = "";
	$mcFileManagerConfig['upload.invalid_file_name_msg'] = "Error: The name of the file is invalid.";
	$mcFileManagerConfig['upload.extensions'] = "";
	$mcFileManagerConfig['upload.invalid_extension_msg'] = "Error: The extension of the file is invalid.";
	$mcFileManagerConfig['upload.allow_override'] = "*";

	// Download options
	$mcFileManagerConfig['download.include_file_pattern'] = "";
	$mcFileManagerConfig['download.exclude_file_pattern'] = "";
	$mcFileManagerConfig['download.extensions'] = "";
	$mcFileManagerConfig['download.allow_override'] = "*";

	// Create document options
	$mcFileManagerConfig['createdoc.fields'] = "";
	$mcFileManagerConfig['createdoc.include_file_pattern'] = '';
	$mcFileManagerConfig['createdoc.exclude_file_pattern'] = '';
	$mcFileManagerConfig['createdoc.invalid_file_name_msg'] = "Error: The name of the document is invalid.";
	$mcFileManagerConfig['createdoc.allow_override'] = "*";

	// Create directory options
	$mcFileManagerConfig['createdir.include_directory_pattern'] = '';
	$mcFileManagerConfig['createdir.exclude_directory_pattern'] = '';
	$mcFileManagerConfig['createdir.invalid_directory_name_msg'] = "Error: The name of the directory is invalid.";
	$mcFileManagerConfig['createdir.allow_override'] = "*";

	// Rename options
	$mcFileManagerConfig['rename.include_file_pattern'] = '';
	$mcFileManagerConfig['rename.exclude_file_pattern'] = '';
	$mcFileManagerConfig['rename.invalid_file_name_msg'] = "Error: The name of the document is invalid.";
	$mcFileManagerConfig['rename.include_directory_pattern'] = '';
	$mcFileManagerConfig['rename.exclude_directory_pattern'] = '';
	$mcFileManagerConfig['rename.invalid_directory_name_msg'] = "Error: The name of the directory is invalid.";
	$mcFileManagerConfig['rename.allow_override'] = "*";

	// Authenication with Session
	$mcFileManagerConfig['authenticator'] = "BaseAuthenticator";
	$mcFileManagerConfig['authenticator.session.logged_in_key'] = "FileManager_IsLoggedIn";
	$mcFileManagerConfig['authenticator.session.groups_key'] = "FileManager_Groups";
	$mcFileManagerConfig['authenticator.allow_override'] = "*";

	// Local filesystem options
	$mcFileManagerConfig['filesystem.local.access_file_name'] = "mc_access";
	$mcFileManagerConfig['filesystem.local.file_mask'] = "";
	$mcFileManagerConfig['filesystem.local.directory_mask'] = "";
	$mcFileManagerConfig['filesystem.local.file_owner'] = "";
	$mcFileManagerConfig['filesystem.local.directory_owner'] = "";
	$mcFileManagerConfig['filesystem.local.allow_override'] = "*";

	// Stream options
	$mcFileManagerConfig['stream.mimefile'] = "mime.types";
	$mcFileManagerConfig['stream.allow_override'] = "*";

	// Image manager options
	$mcFileManagerConfig['imagemanager.urlprefix'] = "../imagemanager";
	$mcFileManagerConfig['imagemanager.allow_override'] = "*";
?>