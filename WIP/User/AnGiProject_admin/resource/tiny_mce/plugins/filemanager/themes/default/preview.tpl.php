<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $this->langCode; ?>" xml:lang="<?php echo $this->langCode; ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo  $this->lang['title']; ?></title>
<link href="themes/<?php echo $this->theme ?>/css/general.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="themes/<?php echo $this->theme ?>/jscripts/general.js"></script>
</head>
<body id="previewBody">
<?php if ($this->data['type'] == "dir") { ?>
<fieldset>
    <legend align="left"><?php echo  $this->lang['directory_information']; ?></legend>
	<div style="margin: 2px; width: 100%; overflow: hidden;">
		<div class="previewSubTitle"><?php echo  $this->lang['directory']; ?></div>
		<div class="previewText"><?php echo $this->data['filename']?></div>
	</div>
	<div style="float: left; margin: 2px; width: 45%;">
		<div class="previewSubTitle"><?php echo  $this->lang['subdirectories']; ?></div>
		<div class="previewText"><?php echo $this->data['subdirs']?></div>
		<div class="previewSubTitle"><?php echo  $this->lang['files']; ?></div>
		<div class="previewText"><?php echo $this->data['files']?></div>
		<div class="previewSubTitle"><?php echo  $this->lang['total_file_size']; ?></div>
		<div class="previewText"><?php echo $this->data['filessize']?></div>
	</div>
	<div style="float: right; margin: 2px; width: 45%;">
		<div class="previewSubTitle"><?php echo  $this->lang['creationdate']; ?></div>
		<div class="previewText"><?php echo $this->data['creationdate']?></div>
		<div class="previewSubTitle"><?php echo  $this->lang['modificationdate']; ?></div>
		<div class="previewText"><?php echo $this->data['modificationdate']?></div>
		<div class="previewSubTitle"><?php echo  $this->lang['access']; ?></div>
		<div class="previewText"><?php echo $this->lang[$this->data['readable']]?> / <?php echo $this->lang[$this->data['writable']]?></div>
	</div>
	<br style="clear: both" />
</fieldset>
<?php } else if ($this->data['type'] == "file") { ?>
<fieldset>
    <legend align="left"><?php echo  $this->lang['file_information']; ?></legend>
	<div style="float: left; margin: 2px; width: 45%; overflow: hidden;">
		<div class="previewSubTitle"><?php echo  $this->lang['filename']; ?></div>
		<div class="previewText"><? if ($this->data['download']) { ?><a href="stream.php?path=<?php echo $this->data['path']?>&mode=download" class="downloadLink"><? } ?><?php echo $this->data['filename']?></a></div>
		<div class="previewSubTitle"><?php echo  $this->lang['file_size']; ?></div>
		<div class="previewText"><?php echo $this->data['filesize']?></div>
		<div class="previewSubTitle"><?php echo  $this->lang['file_description']; ?></div>
		<div class="previewText"><?php echo $this->lang[$this->data['description']]; ?></div>
	</div>

	<div style="float: right; margin: 2px; width: 45%;">
		<div class="previewSubTitle"><?php echo  $this->lang['creationdate']; ?></div>
		<div class="previewText"><?php echo $this->data['creationdate']?></div>
		<div class="previewSubTitle"><?php echo  $this->lang['modificationdate']; ?></div>
		<div class="previewText"><?php echo $this->data['modificationdate']?></div>
		<div class="previewSubTitle"><?php echo  $this->lang['access']; ?></div>
		<div class="previewText"><?php echo $this->lang[$this->data['readable']]?> / <?php echo $this->lang[$this->data['writable']]?></div>
	</div>

	<br style="clear: both" />
</fieldset>

<fieldset class="previewFieldSet">
    <legend align="left"><?php echo  $this->lang['preview']; ?></legend>
	<?php if ($this->data['preview'] && $this->data['previewurl']) {?>
		<iframe id="previewIframe" name="previewIframe" unselectable="true" atomicselection="true" src="<?php echo $this->data['previewurl']?>" width="100%" height="200" marginwidth="0" marginheight="0" topmargin="0" leftmargin="0" frameborder="0" border="0"></iframe>
	<?php } else { ?>
		<div id="previewNoPreviewBox">
			<span id="previewNoPreviewText"><?php echo  $this->lang['no_preview']; ?></span>
		</div>
	<?php } ?>
</fieldset>

<br />

	<div style="float: left">
		<?php if ($this->data['previewurl']) {?>
			<input type="button" id="view" name="view" value="<?php echo  $this->lang['button_view']; ?>" onclick="window.open('<?php echo $this->data['previewurl']?>','previewWin');" class="button" />
		<?php } else { ?>
			<input type="button" id="view" name="view" value="<?php echo  $this->lang['button_view']; ?>" disabled="disabled" class="button" />
		<?php } ?>
	</div>

	<div style="float: right">
		<?php if ($this->data['previewurl']) {?>
			<input type="button" id="view" name="view" value="<?php echo  $this->lang['button_select']; ?>" onclick="parent.insertURL('<?php echo $this->data['previewurl']?>');" class="button" />
		<?php } else { ?>
			<input type="button" id="insert" name="insert" value="<?php echo  $this->lang['button_select']; ?>" disabled="disabled" class="button" />
		<?php } ?>
	</div>

	<br style="clear: both" />
<?php } ?>
<?php if ($this->data['type'] == "root") { ?>
<fieldset>
    <legend align="left"><?php echo  $this->lang['directory_information']; ?></legend>
	<div style="margin: 2px; width: 100%; overflow: hidden;">
		<div class="previewSubTitle"><?php echo  $this->lang['directory']; ?></div>
		<div class="previewText"><?php echo $this->data['filename']?></div>
	</div>
	<div style="float: left; margin: 2px; width: 45%;">
		<div class="previewSubTitle"><?php echo  $this->lang['subdirectories']; ?></div>
		<div class="previewText"><?php echo $this->data['subdirs']?></div>
		<div class="previewSubTitle"><?php echo  $this->lang['files']; ?></div>
		<div class="previewText">0</div>
		<div class="previewSubTitle"><?php echo  $this->lang['total_file_size']; ?></div>
		<div class="previewText">--</div>
	</div>
	<div style="float: right; margin: 2px; width: 45%;">
		<div class="previewSubTitle"><?php echo  $this->lang['creationdate']; ?></div>
		<div class="previewText">--</div>
		<div class="previewSubTitle"><?php echo  $this->lang['modificationdate']; ?></div>
		<div class="previewText">--</div>
		<div class="previewSubTitle"><?php echo  $this->lang['access']; ?></div>
		<div class="previewText"><?php echo $this->lang[$this->data['readable']]?> / <?php echo $this->lang[$this->data['writable']]?></div>
	</div>
	<br style="clear: both" />
</fieldset>
<? } ?>
</body>
</html>
