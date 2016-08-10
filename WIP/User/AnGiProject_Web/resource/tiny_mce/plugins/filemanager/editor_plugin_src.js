// Setup file_browser_callback option
function TinyMCE_filemanager_initInstance(inst) {
	inst.settings['file_browser_callback'] = 'mcFileManager.filebrowserCallBack';
};

function TinyMCE_filemanager_getInfo() {
	return {
		longname : 'MCFileManager PHP',
		author : 'Moxiecode Systems',
		authorurl : 'http://tinymce.moxiecode.com',
		infourl : 'http://tinymce.moxiecode.com/paypal/item_filemanager.php',
		version : "1.9"
	};
};

function TinyMCE_filemanager_getTinyMCEBaseURL() {
	var nl, i, src;

	if (!tinyMCE.baseURL) {
		nl = document.getElementsByTagName('script');
		for (i=0; i<nl.length; i++) {
			src = "" + nl[i].src;

			if (/(tiny_mce\.js|tiny_mce_dev\.js|tiny_mce_gzip)/.test(src))
				return src = src.substring(0, src.lastIndexOf('/'));
		}
	}

	return tinyMCE.baseURL;
};

// Load mcfilemanager.js script
if (typeof(mcFileManager) == "undefined")
	document.write('<sc'+'ript language="javascript" type="text/javascript" src="' + TinyMCE_filemanager_getTinyMCEBaseURL() + '/plugins/filemanager/jscripts/mcfilemanager.js"></script>');
