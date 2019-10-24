<?php
namespace common\components\platform\Channel;

use Yii;
use common\components\platform\Channel\PlatformChannelAbstract;
use common\components\GHelper;
use common\models\UserCenter;

class WeiboAuth extends PlatformChannelAbstract
{

	public $chn_name = "weibo";//config配置platform文件中的platform_channel_weibo.php

	protected $client_id ="";

        protected $client_secret = "";

        protected $request_url="";

        protected $token_url="";

        protected $openid_url="";

        protected $userinfo_url="";

	public function init()
	{
        	parent::init();
        	$this->client_id = @$this->_config['client_id']?:'';
        	$this->client_secret = @$this->_config['client_secret']?:'';
        	$this->request_url = @$this->_config['request_url'] ?:'';
        	$this->redirect_uri = @$this->_config['redirect_uri'] ?:'';
        	$this->token_url = @$this->_config['token_url']?:'';
        	$this->userinfo_url = @$this->_config['userinfo_url']?:'';
    	}


	public function getLoginUrl()
	{
		$state = Yii::$app->session['state'] = base64_encode(md5(uniqid(rand(),true)));
		$url = $this->request_url."response_type=code&client_id=".$this->client_id."&redirect_uri=".urlencode($this->getCallbackUrl())."&state=".$state; 
        	return $url;
    	}


	public function getPlatformUserInfo($params)
	{
		if(isset($params['code'])){
			$code  = $params['code'];
			//获取access_token
			$url = $this->token_url."grant_type=authorization_code&client_id=".$this->client_id."&client_secret=".$this->client_secret."&code=".$code."&redirect_uri=".urlencode($this->getCallbackUrl());
			$res = $this->post($url, array());
            $token = json_decode($res, true);
            if($token){
                    return $this->getUserInfo($token);
            }
			return false;
		}

	}


	/**
	 * get third part platform return userinfo
	 * include nickname and avatar
	 * @return [type] [description]
	*/
	public function getUserInfo($token){
	    	//get user info
		if(!empty($token)){
			//获取oenid
                 $url = $this->userinfo_url."access_token=".$token['access_token']."&uid=".$token['uid'];
                $result = $this->curl($url);
                $userInfo = json_decode($result);
                if($userInfo){
					$user = UserCenter::find()->where(['openid'=>$userInfo->id])->one();
                    if(!$user){
                            $user = new UserCenter;
                            $user->openid = $userInfo->id;
                            $user->nickname = $userInfo->name;
                            $user->gender = $userInfo->gender;
                            $user->province = $userInfo->province;
                            $user->city = $userInfo->city;
                            $user->headimgurl = $userInfo->profile_image_url;
                            $user->time = time();
                            $user->type = 1;//0:qq;1:weibo;2:github
                            $user->location = $userInfo->location;
                            $user->save(false);
                    }
                    $data = [
                            'uid' => $user->id,
                            'nickname' => $userInfo->name,
                            'head_img'=> $userInfo->profile_image_url,
                            'time' => time()+3600,
                    ];
                    return $data;

                }

	    	}
	    	return [];
	}



	public function curl($url){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            $res =  curl_exec($ch);
            curl_close($ch);
            return $res;
    }

    /*
     * post method
 	*/
	public function post( $url, $param )
	{
	   $oCurl = curl_init ();
	  curl_setopt ( $oCurl, CURLOPT_SAFE_UPLOAD, false);
	  if (stripos ( $url, "https://" ) !== FALSE) {
	    curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
	    curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, false );
	  }

	  curl_setopt ( $oCurl, CURLOPT_URL, $url );
	  curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
	  curl_setopt ( $oCurl, CURLOPT_POST, true );
	  curl_setopt ( $oCurl, CURLOPT_POSTFIELDS, $param );
	  $sContent = curl_exec ( $oCurl );
	  $aStatus = curl_getinfo ( $oCurl );
	  curl_close ( $oCurl );
	  if (intval ( $aStatus ["http_code"] ) == 200) {
	    return $sContent;
	  } else {
	    return false;
	  }
	}

		
	




}
