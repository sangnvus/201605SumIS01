<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $this->langCode; ?>" xml:lang="<?php echo $this->langCode; ?>">
<head>
<title><?php echo  $this->lang['title']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="themes/<?php echo $this->theme ?>/css/dialog.css" rel="stylesheet" type="text/css" media="all" />
<script language="javascript" type="text/javascript" src="themes/<?php echo $this->theme ?>/jscripts/general.js"></script>
<script language="javascript" type="text/javascript">
var demo = <?php echo  $this->data['demo'] ?>;
var demoMsg = "<?php echo  $this->data['demo_msg'] ?>";

<?php if ($this->data['submitted'] && !$this->data['errorMsg']) { ?>
	if (window.opener)
		window.opener.execFileCommand("refresh");

	top.close();
<?php } ?>

function updateFileName(elm) {
	var fileName = elm.value;

	var pos = fileName.lastIndexOf('/');
	pos = pos == -1 ? fileName.lastIndexOf('\\') : pos;

	if (pos > 0)
		elm.value = cleanFileName(fileName.substring(pos+1));
}

function validateForm(form) {
	var filename = form.filename.value;

	if (demo) {
		alert(demoMsg);
		return false;
	}

	if (filename == "") {
		alert('You must write a file name.');
		return false;
	}

	return true;
}

function init() {
<?php if ($this->data['errorMsg']) { ?>
	alert("<?php echo $this->data['errorMsg']?>");
<?php } ?>
}
</script>
</head>
<body onload="init();">
<form name="createdir" method="post" action="fileprops.php" onsubmit="return validateForm(this);">
<div class="mcBorderBottomWhite">
	<div class="mcHeader mcBorderBottomBlack">
		<div class="mcWrapper">
			<div class="mcHeaderLeft">
				<div class="mcHeaderTitle"><?php echo  $this->lang['title']; ?></div>
				<div class="mcHeaderTitleText"><?php echo  $this->lang['description']; ?></div>
			</div>
			<div class="mcHeaderRight">&nbsp;</div>
			<br style="clear: both" />
		</div>
	</div>
</div>
<div class="mcContent">
	<table border="0" cellspacing="0" cellpadding="4">
	  <tr>
		<td nowrap="nowrap"><?php echo  $this->lang['properties_on']; ?></td>
		<td><span title="<?php echo $this->data['full_path']?>"><?php echo $this->data['short_path']?></span></td>
	  </tr>
	  <tr>
		<td nowrap="nowrap"><?php echo  $this->lang['name']; ?></td>
		<td><input name="filename" id="filename" type="text" value="<?php echo $this->data['filename']?>" class="inputText" size="35" maxlength="255" style="width: 150px" onchange="updateFileName(this);" /></td>
	  </tr>
	</table>
	<input type="hidden" name="path" value="<?php echo htmlentities($this->data['path']) ?>" />
	<input type="hidden" name="submitted" value="true" />
</div>
<div class="mcFooter mcBorderTopBlack">
	<div class="mcBorderTopWhite">
		<div class="mcWrapper">
			<div class="mcFooterLeft"><input type="submit" name="Submit" value="<?php echo  $this->lang['button_save']; ?>" class="button" /></div>
			<div class="mcFooterRight"><input type="button" name="Cancel" value="<?php echo  $this->lang['button_cancel']; ?>" class="button" onclick="top.close();" /></div>
			<br style="clear: both" />
		</div>
	</div>
</div>
</form>
</body>
</html>



