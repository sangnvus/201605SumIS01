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

<?php if ($this->data['action'] == "submit" && !$this->data['errorMsg']) { ?>
	if (window.opener)
		window.opener.execFileCommand("refresh");

	top.close();
<?php } ?>

function updateFileName(elm) {
	var fileName = elm.value;

	var pos = fileName.lastIndexOf('/');
	pos = pos == -1 ? fileName.lastIndexOf('\\') : pos;

	if (pos > 0)
		document.forms['uploadForm'].filename0.value = cleanFileName(fileName.substring(pos+1));
}

function validateForm(form) {
	var docname = form.docname.value;

	if (demo) {
		alert(demoMsg);
		return false;
	}

	if (docname == "") {
		alert('<?php echo  $this->lang["required_filename"] ?>');
		return false;
	}

	return true;
}

function preview(path) {
	var iframe = document.getElementById('previewNewDocIframe');

	iframe.src = 'preview.php?path=' + escape(path) + "&redirect=true";
}

function init() {
<?php if ($this->data['errorMsg']) { ?>
	alert("<?php echo  isset($this->lang[$this->data['errorMsg']]) ? $this->lang[$this->data['errorMsg']] : $this->data['errorMsg'] ?>");
<?php } ?>
}
</script>
</head>
<body onload="init();">
<form name="createdoc" method="post" action="createdoc.php" onsubmit="return validateForm(this);">
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
	<table border="0" cellspacing="4" cellpadding="0" width="100%">
		<tr>
			<td valign="top">
				<table border="0" cellspacing="0" cellpadding="4" width="100%">
				<?php if (count($this->data['templates']) > 1) { ?>
					  <tr>
						<td><?php echo  $this->lang['template']; ?> </td>
						<td>
						<select name="template" style="width: 200px" onchange="preview(this.options[this.selectedIndex].value);">
						  <option value="" selected><?php echo  $this->lang['select_template']; ?></option>
				<?php foreach ($this->data['templates'] as $title => $path) { ?>
						  <option value="<?php echo $path?>" <?php echo ($path == $this->data['template'] ? "SELECTED" : "")?>><?php echo $title?></option>
				<?php } ?>
						</select></td>
					  </tr>
				<?php } ?>
				  <tr>
					<td nowrap="nowrap"><?php echo  $this->lang['filename']; ?>&nbsp;&nbsp;</td>
					<td><input name="docname" type="text" class="inputText" id="docname" size="35" maxlength="100" style="width: 200px" value="<?php echo $this->data['docname']?>" /></td>
				  </tr>
				  <?php foreach ($this->data['fields'] as $field => $title) { ?>
					  <tr>
						<td nowrap="nowrap"><?php echo $title?>:&nbsp;&nbsp;</td>
						<td><input id="field_<?php echo $field?>" name="field_<?php echo $field?>" type="text" class="inputText" size="35" maxlength="255" style="width: 200px" value="<?php echo $this->data['field_' . $field]?>" /></td>
					  </tr>
				  <?php } ?>
				  <tr>
					<td><?php echo  $this->lang['create_in']; ?></td>
					<td><span title="<?php echo $this->data['full_path']?>"><?php echo $this->data['short_path']?></span></td>
				  </tr>
				</table>
			</td>
			<td rowspan="2" valign="right">
				<iframe id="previewNewDocIframe" name="previewNewDocIframe" unselectable="true" atomicselection="true" src="preview.php?path=<?php echo urlencode($this->data['previewurl'])?>&redirect=true" width="200" height="200" marginwidth="0" marginheight="0" topmargin="0" leftmargin="0" frameborder="0" border="0" style="margin-top: 4px; margin-left: 8px; border: 1px solid gray; background-color: white"></iframe>
			</td>
		</tr>
	</table>
	<input type="hidden" name="path" value="<?php echo htmlentities($this->data['path']) ?>" />
	<input type="hidden" name="action" value="submit" />	
</div>
<div class="mcFooter mcBorderTopBlack">
	<div class="mcBorderTopWhite">
		<div class="mcWrapper">
			<div class="mcFooterLeft"><?php if (count($this->data['templates']) <= 0) { ?><input type="submit" name="Submit" value="<?php echo  $this->lang['button_create']; ?>" class="button" disabled="disabled" /><?php } else { ?><input type="submit" name="Submit" value="<?php echo  $this->lang['button_create']; ?>" class="button" /><?php } ?></div>
			<div class="mcFooterRight"><input type="button" name="Cancel" value="<?php echo  $this->lang['button_cancel']; ?>" class="button" onclick="top.close();" /></div>
			<br style="clear: both" />
		</div>
	</div>
</div>
</form>
</body>
</html>