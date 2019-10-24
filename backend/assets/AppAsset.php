<?php

namespace backend\assets;

use yii\web\AssetBundle;


class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'statics/css/font-awesome-4.4.0/css/font-awesome.min.css',
        '/layui/css/layui.css',
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
    //定义按需加载JS方法，注意加载顺序在最后 
    public static function addScript($view, $jsfile) {  
        $view->registerJsFile($jsfile, [AppAsset::className(), 'depends' => 'backend\assets\AppAsset']);  
    }  
      
   //定义按需加载css方法，注意加载顺序在最后  
    public static function addCss($view, $cssfile) {  
        $view->registerCssFile($cssfile, [AppAsset::className(), 'depends' => 'backend\assets\AppAsset']);  
    }  
}
