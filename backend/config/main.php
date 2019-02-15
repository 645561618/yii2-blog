<?php

$rootDir = dirname(dirname(__DIR__));

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
   'vendorPath' => $rootDir . '/vendor',
   'basePath' => dirname(__DIR__),
   'controllerNamespace' => 'backend\controllers',
    //'layout' => 'top',
    'language' => 'zh-CN',
    'bootstrap' => ['log'],
    'modules' => [
	'datecontrol' => [
            'class' => 'kartik\datecontrol\Module',
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
	//markdown编辑器
	'markdown' => [ 
        	'class' => 'kartik\markdown\Module',
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
			'app' => 'Yii::$app',
			'html' => '\yii\helpers\Html',
			'pos_begin' => \yii\web\View::POS_BEGIN,
                        'activeform' => '\yii\bootstrap\ActiveForm',
                        'dialog' => '\yii\jui\Dialog',
                        'appasset' => 'backend\assets\AppAsset',
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
	'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
	    'class' => backend\components\ManagerUser::className(),
            'identityClass' => 'backend\models\LoginBack',//'common\models\User',
            'enableAutoLogin' => true,
        ],
	'RoleMenu' => [
            'class' => 'backend\components\RoleMenu',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            	'name' => 'advanced-backend',
	    	//'autoStart'=>true,
		//'cookieMode'=>'only',
	    	'timeout' => 10,
        ],

	'mailer' => [
                'class' => 'yii\swiftmailer\Mailer',
                'useFileTransport' =>false,//这句一定有，false发送邮件，true只是生成邮件在runtime文件夹下，不发邮件
                'transport' => [
                        'class' => 'Swift_SmtpTransport',
                        'host' => 'smtp.qq.com',  //每种邮箱的host配置不一样
                        //'username' => '645561618@qq.com',
                        'username' => 'hx.qiang@qq.com',
                        'password' => 'yyarkcamuaagbfhi',
                        'port' => '465',
                        //'encryption' => 'tls', //不加密
                        'encryption' => 'ssl', //加密

                ],   
                'messageConfig'=>[
                       'charset'=>'UTF-8',
                       'from'=>['hx.qiang@qq.com'=>'hx.qiang']
                ],     
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
	    //'suffix' => '.html',
            'rules' => [
		'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];
