<?php
namespace common\components;

use Yii;
use backend\models\WxUserInfoBack;

class Weixin
{

	protected $_appid = "wxac7d1cf75ef6e2d1";

	protected $_appsecret = "4fd5e59494f004af02100ce63e9d638a";

	protected $access_token;

	protected $_tokenUrl = "https://api.weixin.qq.com/cgi-bin/token?";

	protected $_openidUrl = "https://api.weixin.qq.com/cgi-bin/user/get?";

	protected $_userInfoUrl = "https://api.weixin.qq.com/cgi-bin/user/info?";

	protected $_templateUrl = "https://api.weixin.qq.com/cgi-bin/message/template/send?";

	public function __construct()
	{
		$this->getAccessToken();	
	}

	public function getAccessToken()
    	{   
        	$url = $this->_tokenUrl."grant_type=client_credential&appid=".$_appid."&secret=".$_appsecret;
	        $ch = curl_init();
        	curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        	$output = curl_exec($ch);
	        curl_close($ch);
        	$jsoninfo = json_decode($output, true);
	        if($jsoninfo){
        	    	$access_token = $jsoninfo["access_token"];
        	}else{
            		$access_token = false;
        	}
        	Yii::$app->cache->set('access_token',$access_token,3600);
    }

	
	//https请求(支持GET和POST)
    	public function https_request($url,$data = null)
    	{   
        	$curl = curl_init();
	        curl_setopt($curl, CURLOPT_URL, $url);
        	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        	if (!empty($data)){
            		curl_setopt($curl, CURLOPT_POST, 1);
            		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        	}
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	        $output = curl_exec($curl);
	        curl_close($curl);
	        return $output;
    	}



	//获取粉丝列表openid
   	public function getData($access_token)
   	{    
        	if($openid_Data = Yii::$app->cache->get('openid')){
                	self::getOpenid($openid_Data,$access_token);
        	}else{  
                	$url = $this->_openidUrl."access_token=".$access_token;
                	$result = json_decode(Common::https_request($url));
                	$openid_Data = $result->data->openid;
                	Yii::$app->cache->set('openid',$openid_Data,3600);
                	self::getOpenid($openid_Data,$access_token);
        	}
   	}


   	//循环获取用户信息
   	public static function getOpenid($openid_Data,$access_token)
   	{
        	if($openid_Data){
                	foreach($openid_Data as $v){
                        	if($userData=Yii::$app->cache->get($v)){
                                	self::SaveData($userData);//保存微信粉丝数据
                        	}else{
                                	$user_url = $this->_userinfoUrl."access_token=".$access_token."&openid=".$v."&lang=zh_CN";
                                	$userData = json_decode(Common::https_request($user_url));
                                	Yii::$app->cache->set($v,$userData,3600);
                                	self::SaveData($userData);//保存微信粉丝数据
                        	}
                	}
         	}

   	}


	//关注微信公众号获取用户信息
   	public function getFollowUserInfo($openid)
   	{
        	$access_token = Yii::$app->cache->get('access_token');
        	if(!$access_token)
        	{
                	$access_token = self::getAccessToken();
        	}
        	$user_url = $this->_userinfoUrl."access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
        	$userData = json_decode(Common::https_request($user_url));
        	self::SaveData($userData);//保存微信粉丝数      
   	}

   	//重新关注和取消关注更新数据
   	public function UpdateUserInfo($openid)
	{
        	$model = WxUserInfoBack::find()->where(['openid'=>$openid])->one();
        	if($model){
                	if(intval($model->subscribe)==0){
                        	$model->subscribe=1;
                	}elseif(intval($model->subscribe)==1){
                        	$model->subscribe=0;
                	}
                	$model->save(false);
        	}

   	}

   	//发送模板消息
   	public function send_template_message($data)
   	{
        	$access_token = self::getAccessToken();
        	$url = $this->_templateUrl."access_token=".$access_token;
        	$res = self::https_request($url,$data);
        	return json_decode($res,true);
   	}






}
