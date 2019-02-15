<?php
namespace common\components;

use Yii;
use yii\base\Component;
use OSS\OssClient;
use OSS\Croe\OssException;

class AliyunOss
{

    public static $oss;

    public function __construct()
    {
        parent::__construct();
        $accessKeyId = Yii::$app->params['oss']['accessKeyId'];                 //获取阿里云oss的accessKeyId
        $accessKeySecret = Yii::$app->params['oss']['accessKeySecret'];         //获取阿里云oss的accessKeySecret
        $endpoint = Yii::$app->params['oss']['endPoint'];                       //获取阿里云oss的endPoint
        self::$oss = new OssClient($accessKeyId, $accessKeySecret, $endpoint);  //实例化OssClient对象
    }
	

}
