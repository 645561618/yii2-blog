<?php
namespace common\components\platform\Channel;

use Yii;
use common\components\platform\Channel\PlatformChannelAbstract;
use common\components\GHelper;
use common\models\UserCenter;

class WxAuth extends PlatformChannelAbstract
{

	public $chn_name = "wx";//config配置platform文件中的platform_channel_qq.php

	protected $appid ="";

        protected $appsecret = "";


	public function init()
	{
        	parent::init();
        	$this->appid = @$this->_config['client_id']?:'';
        	$this->appsecret = @$this->_config['client_secret']?:'';
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
			$Token = $this->token_url."grant_type=authorization_code&client_id=".$this->client_id."&client_secret=".$this->client_secret."&code=".$code."&redirect_uri=".urlencode($this->getCallbackUrl());
			$res = $this->curl($Token);
			$params = array();
			parse_str($res, $params);//字符串解析到变量
			if($params['access_token']){
				$access_token = $params['access_token'];
				return $this->getUserInfo($access_token);	
			}
			return false;
		}

	}


	/**
	 * get third part platform return userinfo
	 * include nickname and avatar
	 * @return [type] [description]
	*/
	public function getUserInfo($access_token){
	    	//get user info
		if(!empty($access_token)){
			//获取oenid
                        $openURL = $this->openid_url."access_token=".$access_token;
                        $response = $this->curl($openURL);
                        if(strpos($response, "callback") !== false){
                                $lpos = strpos($response, "(");
                                $rpos = strrpos($response, ")");
                                $response = substr($response, $lpos + 1, $rpos - $lpos -1);
                        }
                        $user = json_decode($response);
                        $openid = $user->openid;
                        $url = $this->userinfo_url."access_token=".$access_token."&oauth_consumer_key=".$this->client_id."&openid=".$openid;
                        $result = $this->curl($url);
                        $userInfo = json_decode($result);
                        if($userInfo->ret==0){
				$user = UserCenter::find()->where(['openid'=>$openid])->one();
				if(!$user){
					$user = new UserCenter;
					$user->openid = $openid;
					$user->nickname = $userInfo->nickname;
					$user->gender = $userInfo->gender;
					$user->province = $userInfo->province;
					$user->city = $userInfo->city;
					$user->headimgurl = $userInfo->figureurl_qq_1;
					$user->time = time();
					$user->save(false);
				}
				$data = [
					'uid' => $user->id,
					'nickname' => $userInfo->nickname,
					'head_img'=> $userInfo->figureurl_qq_1,
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


		
	




}
