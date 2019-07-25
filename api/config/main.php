<?php
	
$rootDir = dirname(dirname(__DIR__));

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'vendorPath' => $rootDir . '/vendor',
    'bootstrap' => ['log'],
    'layout' => false,
    'language' => 'zh-CN',
    'controllerNamespace' => 'api\controllers',
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
            'basePath' => '@app/modules/v1',
        ],
    ],
    'extensions' => require($rootDir . '/vendor/yiisoft/extensions.php'),
    'components' => [
	'view' => [
		'renderers' => [
                	'twig' => [
	                    'class' => 'yii\twig\ViewRenderer',
        	            'options' => [
                	        'cache' => false,
                       		'debug' => true,
	                        'strict_variables' => true,
        	            ],
                	 'extensions' => ['common\extensions\Twig\GTwigExtension'],
                   	 'globals' => [
                        	'html' => '\yii\helpers\Html',
	                        'pos_begin' => \yii\web\View::POS_BEGIN,
        	                'activeform' => '\yii\bootstrap\ActiveForm',
                	        'dialog' => '\yii\jui\Dialog',
                       		'appasset' => 'frontend\assets\AppAsset',
                   	 ],
                   	 'functions' => [
                       		't' => '\Yii::t',
	                        'json_encode' => '\yii\helpers\Json::encode',
        	                'form_begin' =>'\yii\bootstrap\ActiveForm::begin',
                	        'form_end' =>'\yii\bootstrap\ActiveForm::end',
	                    ],
        	        ],
            	],
            	'defaultExtension' => 'twig',
        ],
        'request' => [
	    'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
	'message' => [
            'class' => 'common\components\Common',
            'config_error' => Yii::getAlias('config') . "/global.php",
        ],
        'user' => [
	    'class' => frontend\components\FrontendUser::className(),
            'identityClass' => 'common\models\register\LoginUser',
            'enableAutoLogin' => true,
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
		    'categories' => ['yii\*'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
		'POST v1/shop' => 'v1/shop/index',

		'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',	
		'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',	
            ],
        ],
        
    ],
    'params' => $params,
];
