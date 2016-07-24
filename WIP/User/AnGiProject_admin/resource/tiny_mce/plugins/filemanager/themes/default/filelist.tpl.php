<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $this->langCode; ?>" xml:lang="<?php echo $this->langCode; ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo  $this->lang['title']; ?></title>
<link href="themes/<?php echo $this->theme ?>/css/filelist.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
	var demo = <?php echo  $this->data['demo'] ?>;
	var demoMsg = "<?php echo  $this->data['demo_msg'] ?>";
	var disabledTools = '<?php echo  $this->data['disabled_tools']?>';
	var hasReadAccess = <?php echo  $this->data['hasReadAccess']?>;
	var hasWriteAccess = <?php echo  $this->data['hasWriteAccess']?>;
	var hasPasteData = <?php echo  $this->data['hasPasteData']?>;
	var path = "<?php echo  $this->data['path']?>";
	var errorMsg = "<?php echo  $this->data['errorMsg']?>";
	var imageManagerURLPrefix = "<?php echo  $this->data['imageManagerURLPrefix']?>";
	var confirm_cut = "<?php echo  $this->lang['confirm_cut']; ?>";
	var confirm_copy = "<?php echo  $this->lang['confirm_copy']; ?>";
	var confirm_paste = "<?php echo  $this->lang['confirm_paste']; ?>";
	var confirm_delete = "<?php echo  $this->lang['confirm_delete']; ?>";
	var confirm_unzip = "<?php echo  $this->lang['confirm_unzip']; ?>";
	var zip_removed = "<?php echo  $this->lang['zip_removed']; ?>";
</script>
<script language="javascript" type="text/javascript" src="themes/<?php echo $this->theme ?>/jscripts/general.js"></script>
<script language="javascript" type="text/javascript" src="themes/<?php echo $this->theme ?>/jscripts/filelist.js"></script>
</head>
<body onload="init(errorMsg,'<?php echo htmlentities($this->data['action']); ?>');">

<div id="toolbar">

<div class="toolbaritems">
<div style="float: left">
<nobr>
<?php
	foreach ($this->data['tools'] as $item) {
		if (isset($item['location']) && $item['location'] == "right")
			continue;

		if ($item['command'] == "separator")
			echo '<img src="themes/' . $this->theme . '/images/separator.gif" width="2" height="20" class="mceSeparatorLine" />';
		else
			echo '<a id="' . $item['command'] . '" class="mceButtonDisabled" href="javascript:execFileCommand(\'' . $item['command'] . '\');" onmousedown="return false;"><img src="themes/' . $this->theme . '/images/' . $item['icon'] . '" alt="' . $this->lang[$item['command']] . '" title="' . $this->lang[$item['command']] . '" border="0" width="20" height="20" /></a>';
	}
?>
</nobr>
</div>
<div style="float: right">
<nobr>
<?php
	foreach ($this->data['tools'] as $item) {
		if (isset($item['location']) && $item['location'] == "left")
			continue;

		if ($item['command'] == "separator")
			echo '<img src="themes/' . $this->theme . '/images/separator.gif" width="2" height="20" class="mceSeparatorLine" />';
		else
			echo '<a id="' . $item['command'] . '" class="mceButtonDisabled" href="javascript:execFileCommand(\'' . $item['command'] . '\');" onmousedown="return false;"><img src="themes/' . $this->theme . '/images/' . $item['icon'] . '" alt="' . $this->lang[$item['command']] . '" title="' . $this->lang[$item['command']] . '" border="0" width="20" height="20" /></a>';
	}
?>
</nobr>
</div>
<br style="clear: both" />
</div>

<div class="filelistPath" title="<?php echo $this->data['full_path']?>"><?php echo $this->data['short_path']?></div>
<table border="0" cellpadding="2" cellspacing="0" width="100%">
	<tr id="fileListHeadReal" class="filelistHeadRow">
		<td id="selectCol1" width="1" nowrap="nowrap" align="center" valign="middle"><a href="javascript:execFileCommand('toggleall');" title="<?php echo  $this->lang['toggle_all'] ?>" onmousedown="return false;"><img id="toggleall" src="themes/<?php echo $this->theme ?>/images/box.gif" width="10" height="10" alt="<?php echo  $this->lang['toggle_all'] ?>" border="0" hspace="3" /></a></td>
		<td id="iconCol1" width="20" nowrap="nowrap"><img src="themes/<?php echo $this->theme ?>/images/spacer.gif" width="10" height="1" /></td>
		<td id="fnameCol1" width="100%" class="filelistHeadCol"><?php echo $this->lang['filename']; ?></td>
		<td id="fsizeCol1" width="1" nowrap="nowrap" class="filelistHeadCol"><?php echo $this->lang['size']; ?></td>
		<td id="fmodCol1" width="1%" nowrap="nowrap" class="filelistHeadCol"><?php echo $this->lang['modified']; ?></td>
		<td width="16" nowrap="nowrap" class="filelistHeadCol">&nbsp;</td>
	</tr>
</table></div>

<div id="filelist">
<form name="filelistForm" method="post" action="filelist.php">
	<table border="0" cellpadding="2" cellspacing="0" width="100%">
		<tr id="fileListHead" class="filelistHeadRow" style="height: 0px;">
			<td id="selectCol2" width="1" nowrap="nowrap" align="center" valign="middle">&nbsp;</td>
			<td id="iconCol2" width="1" nowrap="nowrap">&nbsp;</td>
			<td id="fnameCol2" width="100%" class="filelistHeadCol"><?php echo $this->lang['filename']; ?></td>
			<td id="fsizeCol2" width="1" nowrap="nowrap" class="filelistHeadCol"><nobr><?php echo $this->lang['size']; ?></nobr></td>
			<td id="fmodCol2" width="1%" nowrap="nowrap" class="filelistHeadCol" colspan="2"><?php echo $this->lang['modified']; ?></td>
			<td id="spacerCol" width="0"></td>
		</tr>

		<tr height="0">
			<td width="1" nowrap="nowrap"></td>
			<td width="1" nowrap="nowrap"></td>
			<td width="100%" nowrap="nowrap"></td>
			<td width="1" nowrap="nowrap"></td>
			<td width="1%" nowrap="nowrap"></td>
			<td id="spacerCol" width="0"></td>
		</tr>

		<?php $count = 0; ?>
		<?php foreach ($this->data['files'] as $file) { ?>
		  <?php if ($file['isParent']) { ?>
				<tr class="<?php echo ($file['even'] ? "filelistRowEven" : "filelistRowOdd")?>">
					<td width="1"><input type="checkbox" name="dir_<?php echo ($count++)?>" value="<?php echo $file['path']?>" disabled="disabled" /></td>
					<td><a href="filelist.php?path=<?php echo $file['path']?>" onmousedown="return false;"><img src="themes/<?php echo $this->theme ?>/images/filetypes/up_folder.gif" width="16" height="16" alt="<?php echo $this->lang['parent_dir']?>" title="<?php echo $this->lang['parent_dir']?>" border="0" /></a></td>
					<td class="filelistFileName"><a href="filelist.php?path=<?php echo $file['path']?>" onmousedown="return false;"><?php echo $file['name']?></a></td>
					<td nowrap="nowrap">&nbsp;</td>
					<td nowrap="nowrap"><?php echo $file['modificationdate']?></td>
					<td id="spacerCol" width="0"></td>
				</tr>
		  <?php } else if ($file['isDir']) { ?>
				<tr class="<?php echo ($file['even'] ? "filelistRowEven" : "filelistRowOdd")?>">
					<td width="1"><input type="checkbox" name="dir_<?php echo ($count++)?>" value="<?php echo $file['path']?>" onclick="triggerSelect(this);" <?php if ($file['hasWriteAccess'] == "false" || $file['hasReadAccess'] == "false") {?>disabled="disabled"<?php } ?> /></td>
					<td><?php if ($file['hasReadAccess'] == "true") {?><a href="filelist.php?path=<?php echo $file['path']?>" onmousedown="return false;"><img src="themes/<?php echo $this->theme ?>/images/filetypes/folder.gif" width="16" height="16" alt="<?php echo $this->lang['dir']?>" title="<?php echo $this->lang['dir']?>" border="0" class="<?php echo ($file['isCut'] ? "cutFile" : "")?>" /></a><?php } else {?><img src="themes/default/images/filetypes/folder.gif" width="16" height="16" alt="Directory" title="Directory" border="0" class="<?php echo ($file['isCut'] ? "cutFile" : "")?>" /><?php }?></td>
					<td class="filelistFileName"><?php if ($file['hasReadAccess'] == "true") {?><a href="filelist.php?path=<?php echo $file['path']?>" onmousedown="return false;"><?php echo $file['name']?></a><?php } else {?><span class="disabledFileName"><?php echo $file['name']?></span><?php }?></td>
					<td nowrap="nowrap">&nbsp;</td>
					<td nowrap="nowrap"><?php echo $file['modificationdate']?></td>
					<td id="spacerCol" width="0"></td>
				</tr>
		  <?php } else { ?>
				<tr class="<?php echo ($file['even'] ? "filelistRowEven" : "filelistRowOdd")?>">
					<td width="1"><input type="checkbox" name="file_<?php echo ($count++)?>" value="<?php echo $file['path']?>" onclick="triggerSelect(this);" <?php if ($file['hasWriteAccess'] == "false" || $file['hasReadAccess'] == "false") {?>disabled="disabled"<?php }?> /></td>
					<td><a name="<?php echo $file['name']?>" onmousedown="return false;"></a><a href="javascript:openFile('<?php echo $file['path']?>');"><img src="themes/<?php echo $this->theme ?>/images/filetypes/<?php echo $file['icon']?>" width="16" height="16" alt="<?php echo $this->lang[$file['type']]?>" title="<?php echo $this->lang[$file['type']]?>" border="0" class="<?php echo ($file['isCut'] ? "cutFile" : "")?>" /></a></td>
					<td class="filelistFileName"><?php if ($file['hasReadAccess'] == "true") {?><a href="javascript:openFile('<?php echo $file['path']?>');" onclick="showPreview('<?php echo $file['path']?>');" onmousedown="return false;"><?php echo $file['name']?></a><?php } else {?><span class="disabledFileName"><?php echo $file['name']?></span><?php }?></td>
					<td nowrap="nowrap"><?php echo $file['size']?></td>
					<td nowrap="nowrap"><?php echo $file['modificationdate']?></td>
					<td id="spacerCol" width="0"></td>
				</tr>
		  <?php } ?>
		<?php } ?>
	</table>

	<input type="hidden" name="action" value="" />
	<input type="hidden" name="path" value="<?php echo htmlentities($this->data['path']) ?>" />
</form>
</div>

</body>
</html>
