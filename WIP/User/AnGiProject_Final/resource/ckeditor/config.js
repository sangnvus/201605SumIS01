/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
    // config.uiColor = '#AADC6E';
    config.toolbarGroups = [
             { name: 'document', groups: ['mode', 'document', 'doctools'] },
             { name: 'clipboard', groups: ['clipboard', 'undo'] },
             { name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing'] },
             { name: 'forms', groups: ['forms'] },
             { name: 'basicstyles', groups: ['basicstyles', 'cleanup'] },
             { name: 'links', groups: ['links'] },
             { name: 'insert', groups: ['insert'] },
             { name: 'colors', groups: ['colors'] },
             '/',
             { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph'] },
             { name: 'styles', groups: ['styles'] },
             { name: 'tools', groups: ['tools'] },
             '/',
             { name: 'others', groups: ['others'] },
             { name: 'about', groups: ['about'] }
    ];
    
    config.filebrowserBrowseUrl = 'http://localhost/AnGiProject/resource/ckfinder/ckfinder.html';
    config.filebrowserImageBrowseUrl = 'http://localhost/AnGiProject/resource/ckfinder/ckfinder.html?type=Images';
    config.filebrowserFlashBrowseUrl = 'http://localhost/AnGiProject/resource/ckfinder/ckfinder.html?type=Flash';
    config.filebrowserUploadUrl = 'http://localhost/AnGiProject/resource/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
    config.filebrowserImageUploadUrl = 'http://localhost/AnGiProject/resource/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
    config.filebrowserFlashUploadUrl = 'http://localhost/AnGiProject/resource/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
    
    config.removeButtons = 'Save,NewPage,Preview,Print,Templates,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,BidiLtr,BidiRtl,Language,CreateDiv,Superscript,Subscript,Strike,SpecialChar,PageBreak,Iframe,Smiley,HorizontalRule,Flash,Anchor,ShowBlocks,About';
    config.language = 'vi';
};
