<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ckfinder
{
    // TÊN CỦA PROJECT
    public function setProjectPath($path){
        define('CKFINDER_PROJECT_PATH', $path);
    }
    
    // ĐƯỜNG DẪN TỚI FOLDER CHỨA CKFINDER
    public function setCkfinderSourcePath($path){
        define('CKFINDER_SOURCE_PATH', $path);
    }
    
    // THIẾT LẬP QUYỀN
    public function setAuthentication($status){
        define('CKFINDER_CAN_USE', $status);
    }
    
    // THIẾT LẬP QUYỀN
    public function setConnectorPath($path){
        define('CKFINDER_CONNECTOR_PATH', $path);
    }
    
    // THIẾT LẬP FOLDER UPLOAD
    public function setFolderUpload($path){
        define ('CKFINDER_FOLDER_UPLOAD', $path);
    }
    
    // BẮT ĐẦU XỬ LÝ CONNECTOR
    public function startConnector(){
        require_once './'.CKFINDER_SOURCE_PATH.'/core/connector/php/connector.php';
    }
    
    // HÀM TẠO MÃ HTML CKFINDER
    public function getHTML(){ ?>
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
        <html>
            <head>
                <title>CKFinder</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <meta name="robots" content="noindex, nofollow" />
                <script type="text/javascript" src="<?php echo get_instance()->config->base_url(CKFINDER_SOURCE_PATH.'/ckfinder.js'); ?>"></script>
                <style type="text/css">
                    body, html, iframe, #ckfinder {
                        margin: 0;
                        padding: 0;
                        border: 0;
                        width: 100%;
                        height: 100%;
                        overflow: hidden;
                    }
                </style>
            </head>
            <body class="CKFinderFrameWindow">
                <div id="ckfinder"></div>
                <script type="text/javascript">
                    //<![CDATA[
                    (function ()
                    {
                        var config = {};
                        var get = CKFinder.tools.getUrlParam;
                        var getBool = function (v)
                        {
                            var t = get(v);

                            if (t === null)
                                return null;

                            return t == '0' ? false : true;
                        };

                        var tmp;
                        if (tmp = get('configId'))
                        {
                            var win = window.opener || window;
                            try
                            {
                                while ((!win.CKFinder || !win.CKFinder._.instanceConfig[ tmp ]) && win != window.top)
                                    win = win.parent;

                                if (win.CKFinder._.instanceConfig[ tmp ])
                                    config = CKFINDER.tools.extend({}, win.CKFinder._.instanceConfig[ tmp ]);
                            }
                            catch (e) {
                            }
                        }

                        if (tmp = get('startupPath') || get('start'))
                            config.startupPath = decodeURIComponent(tmp);

                        config.id = get('id') || '';

                        if ((tmp = getBool('rlf')) !== null)
                            config.rememberLastFolder = tmp;

                        if ((tmp = getBool('dts')) !== null)
                            config.disableThumbnailSelection = tmp;

                        if (tmp = get('data'))
                            config.selectActionData = tmp;

                        if (tmp = get('tdata'))
                            config.selectThumbnailActionData = tmp;

                        if (tmp = get('type'))
                            config.resourceType = tmp;

                        if (tmp = get('langCode'))
                            config.language = tmp;

                        if (typeof (config.selectActionFunction) == 'undefined')
                        {
                            // Try to get desired "File Select" action from the URL.
                            var action;
                            if (tmp = get('CKEditor'))
                            {
                                if (tmp.length)
                                    action = 'ckeditor';
                            }
                            if (!action)
                                action = get('action');

                            var parentWindow = (window.parent == window) ? window.opener : window.parent;
                            switch (action)
                            {
                                case 'js':
                                    var actionFunction = get('func');
                                    if (actionFunction && actionFunction.length > 0)
                                        config.selectActionFunction = parentWindow[ actionFunction ];

                                    actionFunction = get('thumbFunc');
                                    if (actionFunction && actionFunction.length > 0)
                                        config.selectThumbnailActionFunction = parentWindow[ actionFunction ];
                                    break;

                                case 'ckeditor':
                                    var funcNum = get('CKEditorFuncNum');
                                    if (parentWindow['CKEDITOR'])
                                    {
                                        config.selectActionFunction = function (fileUrl, data)
                                        {
                                            parentWindow['CKEDITOR'].tools.callFunction(funcNum, fileUrl, data);
                                        };

                                        config.selectThumbnailActionFunction = config.selectActionFunction;
                                    }
                                    break;

                                default:
                                    if (parentWindow && parentWindow['FCK'] && parentWindow['SetUrl'])
                                    {
                                        action = 'fckeditor';
                                        config.selectActionFunction = parentWindow['SetUrl'];

                                        if (!config.disableThumbnailSelection)
                                            config.selectThumbnailActionFunction = parentWindow['SetUrl'];
                                    }
                                    else
                                        action = null;
                            }
                            config.action = action;
                        }

                        // Always use 100% width and height when nested using this middle page.
                        config.width = config.height = '100%';
                        config.connectorPath = '<?php echo CKFINDER_CONNECTOR_PATH; ?>';
                        var ckfinder = new CKFinder(config);
                        ckfinder.replace('ckfinder', config);
                    })();
                    //]]>
                </script>
            </body>
        </html>
    <?php }
}