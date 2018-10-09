<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Common;

class TestController extends Controller
{
    //生成器使用
    public function actionRun()
    {
	$res = $this->getOpenIdData();
	//foreach($res as $key=>$value){
	//	echo $key."-->".$value."\n";
	//}	
    }


    public function getOpenIdData()
    {
	$access_token = Yii::$app->cache->get('access_token');
        if(!$access_token){
                $access_token = Common::getAccessToken();
	}	

	$result  = $this->getData($access_token);
	foreach($result as $k=>$v){
		//yield $v;
		echo $v."\r\n";
	}

    }

    public function getData($access_token)
    {

        if($openid_Data = Yii::$app->cache->get('openid')){
                $this->getOpenid($openid_Data,$access_token);
        }else{
                $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token;
                $result = json_decode(Common::https_request($url));
                $openid_Data = $result->data->openid;
		return $openid_Data;
        }
    }


   





}





