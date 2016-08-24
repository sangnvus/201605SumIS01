/**
 * filelist.js
 *
 * @author Moxiecode
 * @copyright Copyright © 2005, Moxiecode Systems AB, All rights reserved.
 */

var selectedFiles = new Array();
var selectedDirs = new Array();

function isDisabled(command) {
	for (var i=0; i<disabledTools.length; i++) {
		if (command == disabledTools[i])
			return true;
	}

	return false;
}

function keepSessionAlive() {
	var img = new Image();

	img.src = "session_keepalive.php?rnd=" + new Date().getTime();

	window.setTimeout('keepSessionAlive();', 1000 * 60);
}

keepSessionAlive();

function execFileCommand(command) {
	var formObj = document.forms['filelistForm'];

	if (isDisabled(command))
		return;

	// If command disabled then do nothing
	if (!isCommandEnabled(command))
		return;

	switch (command) {
		case "toggleall":
			for (var i=0; i<formObj.elements.length; i++) {
				if (formObj.elements[i].type == "checkbox" && !formObj.elements[i].disabled)
					formObj.elements[i].checked = formObj.elements[i].checked ? false : true;
			}

			updateTools();
			break;

		case "createdir":
			openPop("createdir.php?path=" + escape(path), 350, 250);
			break;

		case "createdoc":
			openPop("createdoc.php?path=" + escape(path), 565, 370);
			break;

		case "upload":
			openPop("upload.php?path=" + escape(path), 500, 430, "yes");
			break;

		case "imagemanager":
			parent.switchToImageManager(imageManagerURLPrefix, path);
			break;

		case "props":
			var selPath = "";

			if (selectedFiles.length > 0)
				selPath = selectedFiles[0];

			if (selectedDirs.length > 0)
				selPath = selectedDirs[0];

			openPop("fileprops.php?path=" + escape(selPath), 350, 250);
			break;

		case "cut":
			if (confirm(confirm_cut)) {
				document.forms['filelistForm'].action.value = "cut";
				document.forms['filelistForm'].submit();
			}
			break;

		case "copy":
			if (confirm(confirm_copy)) {
				document.forms['filelistForm'].action.value = "copy";
				document.forms['filelistForm'].submit();
			}
			break;

		case "paste":
			if (demo) {
				alert(demoMsg);
				return;
			}

			if (confirm(confirm_paste)) {
				document.forms['filelistForm'].action.value = "paste";
				document.forms['filelistForm'].submit();
			}
			break;

		case "delete":
			if (demo) {
				alert(demoMsg);
				return;
			}

			if (confirm(confirm_delete)) {
				document.forms['filelistForm'].action.value = "delete";
				document.forms['filelistForm'].submit();
			}

			break;

		case "refresh":
			if (!document.forms['filelistForm'].x) {
				showPreview(path);
				document.forms['filelistForm'].action.value = "refresh";
				document.forms['filelistForm'].submit();
				document.forms['filelistForm'].x = true;
			}
			break;

		case "zip":
			openPop("zip.php?path=" + escape(path), 350, 250, "yes");
			break;

		case "unzip":
			var filepos;
			var filename;
			var selectedList = "";
			var removedFiles = "";

			if (selectedFiles.length > 0) {
				for (var i=0; i<selectedFiles.length; i++) {
					filepos = selectedFiles[i].lastIndexOf("/");
					dotpos = selectedFiles[i].lastIndexOf(".");
					filename = selectedFiles[i].substring(filepos+1);
					fileext = selectedFiles[i].substring(dotpos+1);
					fileext = fileext.toLowerCase();
					
					if (fileext != "zip")
						removedFiles += filename + "\n\n";
					else
						selectedList += filename + ",";
				}
				selectedList = selectedList.substring(0,selectedList.length-1);
				removedFiles = removedFiles.substring(0,removedFiles.length-1);

				if (removedFiles != "")
					alert(zip_removed +"\n"+ removedFiles);

			}
			if (selectedList != "") {
				if (confirm(confirm_unzip)) {
					openPop("unzip.php?path=" + escape(path) + "&files="+ selectedList, 540, 360, "yes");
				}
			}
	
			break;
	}
}

function isCommandEnabled(command) {
	var elm = document.getElementById(command);

	return elm && elm.commandEnabled;
}

function setCommandEnabled(command, state) {
	if (isDisabled(command))
		return;

	var elm = document.getElementById(command);
	if (elm)
		elm.commandEnabled = state;

	if (command == "toggleall")
		return;

	if (state) {
		setClassLock(command, false);
		switchClass(command, 'mceButtonNormal');
	} else {
		switchClass(command, 'mceButtonDisabled');
		setClassLock(command, true);
	}
}

function buttonEventHandler(e) {
	var isMSIE = (navigator.appName == "Microsoft Internet Explorer");
	e = isMSIE ? window.event : e;
	var srcElm = isMSIE ? e.srcElement : e.target;

	if (typeof(isDisabled) == "undefined")
		return;

	if (isDisabled(srcElm.getAttribute('id')))
		return;

	switch (e.type) {
		case "mouseover":
			switchClass(srcElm.getAttribute('id'), 'mceButtonOver');
			break;

		case "mouseup":
		case "mouseout":
			switchClass(srcElm.getAttribute('id'), 'mceButtonNormal');
			break;

		case "mousedown":
			switchClass(srcElm.getAttribute('id'), 'mceButtonDown');
			break;
	}
}

function resizeColumn(name1, name2) {
	var elm1 = document.getElementById(name1);
	var elm2 = document.getElementById(name2);

	if (elm2.clientWidth > elm1.clientWidth)
		elm1.width = elm2.clientWidth + 2;
}

function init(error_msg, action) {
	var isGecko = navigator.userAgent.indexOf('Gecko') != -1;

	disabledTools = disabledTools.split(',');

	// Setup init data
	resizeColumn('selectCol1', 'selectCol2');
	resizeColumn('iconCol1', 'iconCol2');
	resizeColumn('fsizeCol1', 'fsizeCol2');
	resizeColumn('fmodCol1', 'fmodCol2');

	document.getElementById('fileListHead').style.display = 'none';
	document.getElementById('fileListHeadReal').style.display = 'block';

	var filelist = document.getElementById('filelist');
	if (isGecko)
		document.getElementById('spacerCol').style.paddingRight = (filelist.scrollHeight - filelist.offsetHeight > 0) ? "0px" : "16px";

	// Lock down all tools
	setCommandEnabled('createdir', hasWriteAccess);
	setCommandEnabled('createdoc', hasWriteAccess);
	setCommandEnabled('refresh', true);
	setCommandEnabled('upload', hasWriteAccess);
	setCommandEnabled('props', false);
	setCommandEnabled('cut', false);
	setCommandEnabled('copy', false);
	setCommandEnabled('paste', hasWriteAccess && hasPasteData);
	setCommandEnabled('delete', false);
	setCommandEnabled('unzip', false);
	setCommandEnabled('zip', false);
	setCommandEnabled('toggleall', true);
	setCommandEnabled('imagemanager', true);
	//setCommandEnabled('search', true);
	//setCommandEnabled('help', true);

	fixImagesBug();

	if (error_msg != "")
		alert(error_msg);

	showPreview(path);
}

function showPreview(path) {
	if (parent.frames && parent.frames['preview'])
		parent.frames['preview'].document.location = "preview.php?path=" + path;
}

function updateTools() {
	selectedFiles = new Array();
	selectedDirs = new Array();
	var previewPath;

	var formElm = document.forms['filelistForm'];

	for (var i=0; i<formElm.elements.length; i++) {
		var element = formElm.elements[i];
		if (element.checked) {
			if (element.name.indexOf('dir_') != -1)
				selectedDirs[selectedDirs.length] = element.value;
			else
				selectedFiles[selectedFiles.length] = element.value;
		}
	}

	// Show hide tools
	if (selectedDirs.length > 0 || selectedFiles.length > 0) {
		if (hasWriteAccess) {
			setCommandEnabled('cut', true);
			setCommandEnabled('delete', true);
			setCommandEnabled('zip', true);

			for (var i=0; i<selectedFiles.length; i++) {
				if (/\.zip$/gi.test(selectedFiles[i])) {
					setCommandEnabled('unzip', true);
					break;
				}
			}

			if ((selectedDirs.length + selectedFiles.length) == 1)
				setCommandEnabled('props', true);
			else
				setCommandEnabled('props', false);
		}

		setCommandEnabled('copy', true);
	} else {
		setCommandEnabled('cut', false);
		setCommandEnabled('copy', false);
		setCommandEnabled('delete', false);
		setCommandEnabled('props', false);
		setCommandEnabled('unzip', false);
		setCommandEnabled('zip', false);
	}
}

function triggerSelect(elm) {
	var previewPath;

	updateTools();

	if (selectedDirs.length == 0 && selectedFiles.length == 0)
		previewPath = path;
	else
		previewPath = elm.value;

	showPreview(previewPath);
}

function openFile(path) {
}
