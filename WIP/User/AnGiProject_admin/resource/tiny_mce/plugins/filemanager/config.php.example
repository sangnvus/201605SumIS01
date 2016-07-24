<?php
	// Include your custom classes here
	// require_once("classes/Plugins/ExampleAuthenticatorImpl.class.php");
	// require_once("classes/Plugins/LoggingFileEventListener.class.php");

	// General options
	$mcFileManagerConfig['general.theme'] = "default";
	$mcFileManagerConfig['general.user_friendly_paths'] = true;
	$mcFileManagerConfig['general.tools'] = "createdir,createdoc,separator,refresh,upload,zip,unzip,props,separator,cut,copy,paste,delete";
	$mcFileManagerConfig['general.disabled_tools'] = "";
	$mcFileManagerConfig['general.login_page'] = "login.php";
	$mcFileManagerConfig['general.language'] = "en"; // en, sv
	$mcFileManagerConfig['general.demo'] = false;
	$mcFileManagerConfig['general.demo_msg'] = "Application is running in demo mode, action is restricted.";
	$mcFileManagerConfig['general.allow_override'] = "*";

	// Preview options
	$mcFileManagerConfig['preview'] = true;
	$mcFileManagerConfig['preview.wwwroot'] = ''; // absolute or relative from this script path (c:/Inetpub/wwwroot).
	$mcFileManagerConfig['preview.urlprefix'] = "http://" . $_SERVER['HTTP_HOST'] . "/"; // domain name
	$mcFileManagerConfig['preview.urlsuffix'] = "";
	$mcFileManagerConfig['preview.allow_override'] = "*";

	// General file system options
	$mcFileManagerConfig['filesystem'] = "LocalFileImpl";
	$mcFileManagerConfig['filesystem.path'] = 'files'; // absolute or relative from this script path.
	$mcFileManagerConfig['filesystem.rootpath'] = 'files'; // absolute or relative from this script path.
	// $mcFileManagerConfig['filesystem.rootpath'] = 'files/${user}';
	$mcFileManagerConfig['filesystem.datefmt'] = "Y-m-d H:i";
	$mcFileManagerConfig['filesystem.include_directory_pattern'] = '';
	$mcFileManagerConfig['filesystem.exclude_directory_pattern'] = '';
	$mcFileManagerConfig['filesystem.invalid_directory_name_msg'] = "Error: The name of the directory is invalid.";
	$mcFileManagerConfig['filesystem.include_file_pattern'] = '';
	$mcFileManagerConfig['filesystem.exclude_file_pattern'] = '/^\./i';
	$mcFileManagerConfig['filesystem.invalid_file_name_msg'] = "Error: The name of the file is invalid. It has a . character in the beginning.";
	$mcFileManagerConfig['filesystem.extensions'] = "gif,jpg,htm,html,pdf,zip,txt";
	$mcFileManagerConfig['filesystem.invalid_extension_msg'] = "Error: The extension of the file is invalid.";
	$mcFileManagerConfig['filesystem.file_templates'] = '${rootpath}/templates/document.htm,${rootpath}/templates/another_document.htm';
	$mcFileManagerConfig['filesystem.directory_templates'] = '${rootpath}/templates/directory,${rootpath}/templates/another_directory';
	$mcFileManagerConfig['filesystem.file_event_listeners'] = "";
	$mcFileManagerConfig['filesystem.readable'] = "true";
	$mcFileManagerConfig['filesystem.writable'] = "true";
	$mcFileManagerConfig['filesystem.delete_recursive'] = true;
	$mcFileManagerConfig['filesystem.force_directory_template'] = false;
	$mcFileManagerConfig['filesystem.allow_override'] = "*";

	// Upload options
	$mcFileManagerConfig['upload.maxsize'] = "10MB";
	$mcFileManagerConfig['upload.include_file_pattern'] = '';
	$mcFileManagerConfig['upload.exclude_file_pattern'] = '/\.php$|\.shtm$/i';
	$mcFileManagerConfig['upload.invalid_file_name_msg'] = "Error: The file name is invalid, only a-z, 0-9 and _ characters are allowed.";
	$mcFileManagerConfig['upload.extensions'] = "gif,jpg,htm,html,pdf,txt,zip";
	$mcFileManagerConfig['upload.invalid_extension_msg'] = "Error: Invalid extension: Valid extensions are: gif,jpg,htm,pdf.";
	$mcFileManagerConfig['upload.allow_override'] = "*";

	// Download options
	$mcFileManagerConfig['download.include_file_pattern'] = "";
	$mcFileManagerConfig['download.exclude_file_pattern'] = "";
	$mcFileManagerConfig['download.extensions'] = "gif,jpg,htm,html,pdf,txt,zip";
	$mcFileManagerConfig['download.allow_override'] = "*";

	// Create document options
	$mcFileManagerConfig['createdoc.fields'] = "title,Document title";
	$mcFileManagerConfig['createdoc.include_file_pattern'] = '';
	$mcFileManagerConfig['createdoc.exclude_file_pattern'] = '/[^a-z0-9_\.]/';
	$mcFileManagerConfig['createdoc.invalid_file_name_msg'] = "Error: The name of the document is invalid. Only a-z, 0-9 and _ characters are allowed.";

	// Create directory options
	$mcFileManagerConfig['createdir.include_directory_pattern'] = '';
	$mcFileManagerConfig['createdir.exclude_directory_pattern'] = '/[^a-z0-9_\.]/';
	$mcFileManagerConfig['createdir.invalid_directory_name_msg'] = "Error: The name of the directory is invalid. Only a-z, 0-9 and _ characters are allowed.";
	$mcFileManagerConfig['createdir.allow_override'] = "*";

	// Rename options
	$mcFileManagerConfig['rename.include_file_pattern'] = '';
	$mcFileManagerConfig['rename.exclude_file_pattern'] = '/[^a-z0-9_\.]/';
	$mcFileManagerConfig['rename.invalid_file_name_msg'] = "Error: The name of the file is invalid. Only a-z, 0-9 and _ characters are allowed.";
	$mcFileManagerConfig['rename.include_directory_pattern'] = '';
	$mcFileManagerConfig['rename.exclude_directory_pattern'] = '/[^a-z0-9_\.]/';
	$mcFileManagerConfig['rename.invalid_directory_name_msg'] = "Error: The name of the directory is invalid. Only a-z, 0-9 and _ characters are allowed.";
	$mcFileManagerConfig['rename.allow_override'] = "*";

	// Authenication with Session
	$mcFileManagerConfig['authenticator'] = "BaseAuthenticator";
	$mcFileManagerConfig['authenticator.session.logged_in_key'] = "isLoggedIn";
	$mcFileManagerConfig['authenticator.session.groups_key'] = "groups";
	$mcFileManagerConfig['authenticator.session.user_key'] = "user";
	$mcFileManagerConfig['authenticator.allow_override'] = "*";

	// Local filesystem options
	$mcFileManagerConfig['filesystem.local.access_file_name'] = "mc_access";
	$mcFileManagerConfig['filesystem.local.allow_override'] = "access_file_name";
	$mcFileManagerConfig['filesystem.local.file_mask'] = "";
	$mcFileManagerConfig['filesystem.local.directory_mask'] = "";
	$mcFileManagerConfig['filesystem.allow_override'] = "*";

	// Stream options
	$mcFileManagerConfig['stream.mimefile'] = "mime.types";
	$mcFileManagerConfig['stream.allow_override'] = "*";

	// Image manager options
	$mcFileManagerConfig['imagemanager.urlprefix'] = "../imagemanager";
	$mcFileManagerConfig['imagemanager.allow_override'] = "*";

	// LoggingFileEventListener plugin options
	/*$mcFileManagerConfig['LoggingFileEventListener.path'] = "logs";
	$mcFileManagerConfig['LoggingFileEventListener.prefix'] = "mcfilemanager";
	$mcFileManagerConfig['LoggingFileEventListener.max_size'] = "100k";
	$mcFileManagerConfig['LoggingFileEventListener.max_files'] = "10";*/
?>