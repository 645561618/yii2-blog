<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
	'db' => [
            'class' => 'yii\db\Connection',
            'charset' => 'utf8',
        ],
	'backenddb' => [
		'class' => 'yii\db\Connection',
                'charset' => 'utf8',	
	],
	'PublicService'=>[
            'class'=> 'common\components\PublicSafe',
        ],
	'AliyunOss'=>[
		'class' => 'common\components\AliyunOss',
	],
	'platform'=>[
            'class'=>'common\components\platform\PlatformLogin',
            'config_file'=>Yii::getAlias('config/platform')."/global.php",
        ],


    ],
];
