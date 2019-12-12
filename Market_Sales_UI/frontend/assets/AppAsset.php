<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'vendor/font-awesome-4.7.0/css/font-awesome.min.css',
        'vendor/magnific-popup/magnific-popup.css',
        'vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css',
        'vendor/jquery-datatables-bs3/assets/css/datatables.css',
        'css/theme.css',
        'css/default.css',
        'css/theme-custom.css',
        'css/tooltip.css'
    ];
    public $js = [
        'vendor/jquery-browser-mobile/jquery.browser.mobile.js',
        'vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js',
        'vendor/nanoscroller/nanoscroller.js',
        'vendor/magnific-popup/magnific-popup.js',
        'vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js',
        'vendor/jquery-datatables/media/js/jquery.dataTables.js',
        'vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js',
        'vendor/jquery-datatables-bs3/assets/js/datatables.js',
        'scripts/theme.js',
        'scripts/theme.custom.js',
        'scripts/theme.init.js',
        'scripts/bootstrap-notify.js',
        'scripts/examples.modals.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

}
