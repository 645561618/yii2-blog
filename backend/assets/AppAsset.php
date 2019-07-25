<?php

namespace backend\assets;

use yii\web\AssetBundle;


class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'statics/css/font-awesome-4.4.0/css/font-awesome.min.css',
        'statics/css/layout.css',
        'statics/css/site.css',
    ];
    public $js = [
        'statics/js/jquery-ui.js',
        'statics/js/toggles.js',
        'statics/js/layout.js',
        'statics/js/site.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
