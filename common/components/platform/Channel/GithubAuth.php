<?php
namespace common\components\platform\Channel;

use Yii;
use common\components\platform\Channel\PlatformChannelAbstract;
use common\components\GHelper;
use common\models\UserCenter;
use common\models\Common;

class GithubAuth extends PlatformChannelAbstract
{

	public $chn_name = "github";//config配置platform文件中的platform_channel_qq.php

	protected $client_id ="";

        protected $client_secret = "";

        protected $request_url="";

        protected $token_url="";

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
		$state = Yii::$app->session['g_state'] = base64_encode(md5(uniqid(rand(),true)));
		$url = $this->request_url."client_id=".$this->client_id."&redirect_uri=".$this->redirect_uri."?plat=".base64_encode($this->chn_name)."&state=".$state; 
        	return $url;
    	}


	public function getPlatformUserInfo($params)
	{
		if(isset($params['code'])){
			$code  = $params['code'];
			//获取access_token
			$Token = $this->token_url."client_id=".$this->client_id."&client_secret=".$this->client_secret."&code=".$code;
			$res = Common::curl($Token);
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
                        $url = $this->userinfo_url."access_token=".$access_token;
                        $userInfo = json_decode(Common::curl($url));
                        if($userInfo){
				$user = UserCenter::find()->where(['node_id'=>$userInfo->node_id])->one();
				if(!$user){
					$user = new UserCenter;
					$user->login = $userInfo->login;
					$user->github_login_id = $userInfo->id;
					$user->node_id = $userInfo->node_id;
					$user->nickname = $userInfo->name?$userInfo->name:"";
					$user->email = $userInfo->email?$userInfo->email:"";
					$user->headimgurl = $userInfo->avatar_url?$userInfo->avatar_url:"";
					$user->time = time();
					$user->type = 2;//0:qq;1:微信;2:github
					$user->save(false);
				}
				$data = [
					'uid' => $user->id,
					'nickname' => $userInfo->login,
					'head_img'=> $userInfo->avatar_url,
					'time' => time()+3600,
				];
				return $data;
                        }

	    	}
	    	return [];
	}

}
