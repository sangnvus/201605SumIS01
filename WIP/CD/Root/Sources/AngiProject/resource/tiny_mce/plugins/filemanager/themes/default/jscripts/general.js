/**
 * general.js
 *
 * @author Moxiecode
 * @copyright Copyright © 2005, Moxiecode Systems AB, All rights reserved.
 */

function autoResize() {
	var isMSIE = (navigator.appName == "Microsoft Internet Explorer");
	var isOpera = (navigator.userAgent.indexOf("Opera") != -1);

	if (isOpera)
		return;

	if (isMSIE) {
		window.resizeTo(10, 10);

		var elm = document.body;
		var width = elm.offsetWidth;
		var height = elm.offsetHeight;
		var dx = (elm.scrollWidth - width) + 4;
		var dy = elm.scrollHeight - height;

		window.resizeBy(dx, dy);
	} else {
		window.scrollBy(1000, 1000);
		if (window.scrollX > 0 || window.scrollY > 0) {
			window.resizeBy(window.innerWidth * 2, window.innerHeight * 2);
			window.sizeToContent();
			window.scrollTo(0, 0);
			var x = parseInt(screen.width / 2.0) - (window.outerWidth / 2.0);
			var y = parseInt(screen.height / 2.0) - (window.outerHeight / 2.0);
			window.moveTo(x, y);
		}
	}
}

function switchClass(element_id, class_name) {
	var element = document.getElementById(element_id);
	if (element != null && !element.classLock) {
		element.oldClassName = element.className;
		element.className = class_name;
	}
}

function restoreClass(element_id) {
	var element = document.getElementById(element_id);
	if (element != null && element.oldClassName && !element.classLock) {
		element.className = element.oldClassName;
		element.oldClassName = null;
	}
}

function setClassLock(element_id, lock_state) {
	var element = document.getElementById(element_id);
	if (element != null)
		element.classLock = lock_state;
}

// Due to some stange MSIE bug this script was needed
function fixImagesBug() {
	var isMSIE = (navigator.appName == "Microsoft Internet Explorer");

	if (isMSIE) {
		var elements = document.getElementsByTagName("img");

		for (var i=0; i<elements.length; i++) {
			if (!elements[i].complete)
				elements[i].src = elements[i].src;
		}
	}
}

function cleanFileName(filename) {
	var outfile = "";

	for (var i=0; i<filename.length; i++) {
		var chr = filename.charAt(i).toLowerCase();

		if (chr == ' ')
			chr = "_";

		if (chr == '\xE5' || chr == '\xE4')
			chr = "a";

		if (chr == '\xF6')
			chr = "o";

		if (chr == ' ')
			chr = "_";

		if ((chr >= 'a' && chr <= 'z') || (chr >= '0' && chr <= '9') || chr == "_" || chr == ".")
			outfile += chr;
	}

	return outfile;
}

function openPop(url, width, height, scroll) {
	var isMSIE = (navigator.appName == "Microsoft Internet Explorer");
	var x = parseInt(screen.width / 2.0) - (width / 2.0);
	var y = parseInt(screen.height / 2.0) - (height / 2.0);

	if (typeof(scroll) == "undefined")
		scroll = "no";

	if (isMSIE) {
		// Pesky MSIE + XP SP2
		width += 15;
		height += 35;

		//var features = "resizable:no;scroll:no;status:no;center:yes;help:no;dialogWidth:" + width + "px;dialogHeight:" + height + "px;";
		//window.showModalDialog(url, window, features);
	}

	var win = window.open(url, "MCFileManagerPopup", "top=" + y + ",left=" + x + ",scrollbars="+ scroll +",modal=yes,width=" + width + ",height=" + height + ",resizable=yes");
	win.focus();
}

// Fix for DIVs with scroll:auto
if (window.addEventListener) {
	window.addEventListener("load", function() {
		var divs = document.getElementsByTagName("div");
		for (var i=0; i<divs.length; i++) {
			divs[i].addEventListener('DOMMouseScroll', function(e) {
				var st = e.currentTarget.scrollTop + (e.detail * 12);

				e.currentTarget.scrollTop = st < 0 ? 0 : st;
				e.preventDefault();				
			}, false);
		}
	}, false);
}
