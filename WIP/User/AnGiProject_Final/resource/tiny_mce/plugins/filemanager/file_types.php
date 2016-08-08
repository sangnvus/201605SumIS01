<?php
/**
 * file_types.php
 *
 * @package MCFileManager.pages
 * @author Moxiecode
 * @copyright Copyright © 2005, Moxiecode Systems AB, All rights reserved.
 *
 * This file contains a the filetype lookup table, this is where extensions are matched to images and descriptions.
 */

	// File types lookup table
	$mcFileManagerFileTypes = array(
		// Format: Types, icon, long info lang key, preview
		array(array("exe", "com"), "exe.gif", "exe", false),
		array(array("gif", "jpg", "png", "bmp", "tif"), "image.gif", "image", true),
		array(array("zip", "sit", "rar", "gz", "tar"), "archive.gif", "archive", false),
		array(array("htm", "html", "php", "jsp", "asp"), "html.gif", "html", true),
		array(array("mov", "mpg", "avi", "asf", "mpeg", "wmv"), "movie.gif", "movie", false),
		array(array("aif", "aiff", "wav", "mp3"), "sound.gif", "sound", false),
		array(array("swf"), "swf.gif", "Flash file", true),
		array(array("ppt"), "powerpoint.gif", "powerpoint", false),
		array(array("rtf"), "document.gif", "document", false),
		array(array("doc"), "word.gif", "word", false),
		array(array("pdf"), "pdf.gif", "pdf", false),
		array(array("xls"), "excel.gif", "excel", false),
		array(array("txt"), "txt.gif", "txt", true),
		array(array("xml", "xsl", "dtd"), "xml.gif", "xml", true)
	);
?>
