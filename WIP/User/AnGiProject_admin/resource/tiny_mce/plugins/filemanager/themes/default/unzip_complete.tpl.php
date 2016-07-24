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

function init() {
	if (window.opener)
		window.opener.execFileCommand("refresh");
<?php if ($this->data['errorMsg']) { ?>
	alert("<?php echo $this->data['errorMsg']?>");
<?php } ?>
}
</script>
<style type="text/css">
	thead td { border-left: 1px solid #808080; border-bottom: 1px solid #808080; border-top: 1px solid white; border-left: 1px solid white; border-right: 1px solid #808080; background-color: #D4D0C8; margin: 0px; padding: 1px; padding-left: 4px;}
	tbody td { padding-top: 2px; padding-bottom: 2px; padding-left: 4px; }
	.trEven { background-color: white; }
	.trOdd { background-color: #EEEEEE; }
	.statusred {color: #BB0000; }

</style>
</head>
<body onload="init();">
<form name="unzipForm" method="post" action="unzip.php">
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
		<td nowrap="nowrap"></td>
		<td></td>
	  </tr>
	</table>
	<?php $i = 0; ?>
	<?php foreach($this->data['out'] as $zipfile) { ?>
	<table border="0" cellspacing="0" cellpadding="4">
	  <tr>
		<td nowrap="nowrap"><strong><?php echo  $this->lang['zipfile_name']; ?> </strong></td>
		<td><?php echo  $zipfile['name']; ?></td>
	  </tr>
	  <tr>
		<td nowrap="nowrap"><strong><?php echo  $this->lang['current_folder']; ?> </strong></td>
		<td><?php echo  $this->data['short_path']; ?></td>
	  </tr>
	  <tr>
		<td nowrap="nowrap"><strong><?php echo  $this->lang['overwrite_files']; ?> </strong></td>
		<td><input type="checkbox" disabled="disabled" name="overwrite_<?php echo  $i; ?>" id="overwrite_<?php echo  $i; ?>" value="yes" <?php echo  (($zipfile['overwrite'] == "yes") ? "checked" : ""); ?> /></td>
	  </tr>
	</table>
	<table border="0" cellspacing="0" cellpadding="4" width="100%">
	  <tr>
		<td colspan="2">
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<thead>
				<tr>
					<td>&nbsp;</td>
					<td><strong><?php echo  $this->lang['name']; ?></strong></td>
					<td><strong><?php echo  $this->lang['size']; ?></strong></td>
					<td><strong><?php echo  $this->lang['csize']; ?></strong></td>
					<td><strong>&nbsp;</strong></td>
					<td><strong><?php echo  $this->lang['exists']; ?></strong></td>
					<td><strong><?php echo  $this->lang['status']; ?></strong></td>
					<td><strong><?php echo  $this->lang['created']; ?></strong></td>
				</tr>
				</thead>
				<tbody>
		<?php $even = true; ?>
		<?php foreach($zipfile['contents'] as $item) { ?>
		<?php $even = !$even; ?>
		<?php if ($item['folder'] != 1) { ?>
				<tr class="<?php echo  $even ? "trEven" : "trOdd"; ?>">
					<td align="center"><img src="themes/<?php echo $this->theme ?>/images/filetypes/<?php echo $item['icon']?>" width="16" height="16" alt="<?php echo $item['type']?>" title="<?php echo $item['type']?>" border="0" /></td>
					<td title="<?php echo  $item['filename']; ?>"><?php echo  $item['friendlypath']; ?></td>
					<td><?php echo  $item['size']; ?></td>
					<td><?php echo  $item['compressed_size']; ?></td>
					<td><?php echo  $item['ratio'] ?>%</td>
					<td><?php echo  $this->lang[$item['exists']] ?></td>
					<td title="<?php echo  $item['status_message']; ?>" class="<?php echo  ($item['status'] == "Failed") ? "statusred" : ""; ?>"><?php echo  $this->lang[$item['status']]; ?></td>
					<td nowrap="nowrap"><?php echo  $item['mtime']; ?></td>
				</tr>
		<?php } else { ?>
				<tr class="<?php echo  $even ? "trEven" : "trOdd"; ?>">
					<td align="center"><img src="themes/<?php echo $this->theme ?>/images/filetypes/folder.gif" width="16" height="16" alt="Directory" title="Directory" border="0" /></td>
					<td title="<?php echo  $item['filename']; ?>"><?php echo  $item['friendlypath']; ?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td><?php echo  $this->lang[$item['exists']] ?></td>
					<td title="<?php echo  $item['status_message']; ?>" class="<?php echo  ($item['status'] == "Failed") ? "statusred" : ""; ?>"><?php echo  $this->lang[$item['status']]; ?></td>
					<td nowrap="nowrap"><?php echo  $item['mtime']; ?></td>
				</tr>
		<?php } ?>
		<?php } ?>
				</tbody>
				</table>
		</td>
	  </tr>
	</table>
	<?php if ($i != count($this->data['out'])-1) {?>
	<hr />
	<?php } ?>
	<?php $i++; ?>
	<?php } ?>
</div>
<div class="mcFooter mcBorderTopBlack">
	<div class="mcBorderTopWhite">
		<div class="mcWrapper">
			<div class="mcFooterLeft"><input type="button" name="Back" value="<?php echo  $this->lang['button_back']; ?>" class="button" onclick="history.go(-1);" /></div>
			<div class="mcFooterRight"><input type="button" name="Close" value="<?php echo  $this->lang['button_close']; ?>" class="button" onclick="top.close();" /></div>
			<br style="clear: both" />
		</div>
	</div>
</div>
</form>
</body>
</html>


