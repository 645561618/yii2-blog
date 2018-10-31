<?php
namespace common\components\platform\Channel;

use Yii;
use common\components\platform\Channel\PlatformChannelAbstract;
use common\components\GHelper;
use common\models\UserCenter;

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
                        $url = $this->userinfo_url."access_token=".$access_token;
                        $userInfo = json_decode($this->curl($url));
                        if($userInfo){
				$user = UserCenter::find()->where(['node_id'=>$userInfo->node_id])->one();
				if(!$user){
					$user = new UserCenter;
					$user->login = $userInfo->login;
					$user->github_login_id = $userInfo->id;
					$user->node_id = $userInfo->node_id;
					$user->nickname = $userInfo->name;
					$user->email = $userInfo->email;
					$user->headimgurl = $userInfo->avatar_url;
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



        //CURL 设置请求头和响应头（github API接口需要）
        public function curl($url){
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                // 设置请求头, 有时候需要,有时候不用,看请求网址是否有对应的要求
                $header[] = "Content-type: application/x-www-form-urlencoded";
                $user_agent = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.146 Safari/537.36";
                curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
                // 返回 response_header, 该选项非常重要,如果不为 true, 只会获得响应的正文
                curl_setopt($ch, CURLOPT_HEADER, false);
                // 是否不需要响应的正文,为了节省带宽及时间,在只需要响应头的情况下可以不要正文
                curl_setopt($ch, CURLOPT_NOBODY, false);
                // 使用上面定义的$user_agent
                curl_setopt($ch, CURLOPT_USERAGENT,$user_agent);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

                curl_setopt($ch, CURLOPT_URL, $url);
                $res =  curl_exec($ch);
                // 获得响应结果里的：头大小
                //$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                // 根据头大小去获取头信息内容
                //$header = substr($res, 0, $headerSize);
                curl_close($ch);
                return $res;
        }


		
	




}
