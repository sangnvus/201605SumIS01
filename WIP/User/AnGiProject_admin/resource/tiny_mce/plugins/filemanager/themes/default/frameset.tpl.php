<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $this->langCode; ?>" xml:lang="<?php echo $this->langCode; ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->lang['title']; ?></title>
<script language="javascript" type="text/javascript">
var js = "<?php echo $this->data['js']?>";
var formname = "<?php echo $this->data['formname']?>";
var elementnames = "<?php echo $this->data['elementnames']?>";
var path = "<?php echo $this->data['path']?>";

function insertURL(url) {
	var win, close = false;

	if (window.opener) {
		win = window.opener;
		close = true;
	} else
		win = parent;

	// Crop away query
	if ((pos = url.indexOf('?')) != -1)
		url = url.substring(0, url.indexOf('?'));

	// Handle custom js call
	if (win && js != "") {
		eval("win." + js + "(url);");

		if (close)
			top.close();

		return;
	}

	// Handle form item call
	if (win && formname != "") {
		var elements = elementnames.split(',');

		for (var i=0; i<elements.length; i++) {
			var elm = win.document.forms[formname].elements[elements[i]];
			if (elm && typeof elm != "undefined")
				elm.value = url;
		}
	}

	if (close)
		top.close();
}

function switchToImageManager(url_prefix, path) {
	var url = url_prefix + "/images.php?formname=" + formname + "&elementnames=" + elementnames + "&js=" + js + "&path=" + escape(path);
	top.location.href = url;
}
</script>
</head>

<?php if ($this->data['showpreview']) { ?>
	<frameset rows="*" cols="*,300" framespacing="0" frameborder="no" border="0">
<?php } else { ?>
	<frameset rows="*" cols="*,0" framespacing="0" frameborder="no" border="0">
<?php } ?>
	<frame name="filelist" src="filelist.php?path=<?php echo htmlentities($this->data['path'])?>#<?php echo htmlentities($this->data['previewfilename']) ?>" frameborder="no" scrolling="no" noresize="noresize" marginwidth="0" marginheight="0" />
<?php if ($this->data['showpreview']) { ?>
	<frame name="preview" src="preview.php?path=<?php echo htmlentities($this->data['previewpath']) ?>" frameborder="no" scrolling="no" noresize="noresize" marginwidth="0" marginheight="0" />
</frameset>
<?php } ?>

<noframes>
<body>
	This file browser requires a frameset.
</body>
</noframes>
</html>
