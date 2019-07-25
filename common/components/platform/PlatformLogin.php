<?php
namespace common\components\platform;

use Yii;
use yii\base\Exception;
use yii\base\Application;
use yii\base\Component;

class PlatformLogin extends Component
{

	public $config_file = null;

	public $config_path = null;

	protected $_configs = array();

	protected $_login_class_path_prefix = "Channel";


	public function init()
	{
		// register the cooperation path alias
        	if (Yii::getAlias("platform") === false) {
            		Yii::setAlias("platform", realpath(dirname(__FILE__)));
        	}

        	if (is_null($this->config_file)) {
            		throw new CooperationException('Platform config_file can not be null');
        	}
        	include(__DIR__."/error.php");

        	if (is_null($this->config_path)) {
            		$this->config_path = dirname($this->config_file);
        	}

        	$this->loadConfig();

        	parent::init();
	}


	/**
     	* load config
     	* @return
     	*/
    	protected function loadConfig() {
        	$this->_configs = require(__DIR__.'/../../'.$this->config_file);
    	}

	/**
     	* function_description
     	*
     	* @param $app_id:
     	*
     	* @return url string or Error
     	*/
    	public function getPlatformLoginUrl($plat,$web_url=null) {
        	$channel = $this->getPlatformChannel($plat);
	        if ($channel instanceof IError) {
        	    return $channel;
	        }
        	$url = $channel->getLoginUrl($web_url);
	        if(empty($url)){
        	    return false;
        	}
	        return $url;
    	}

    	/**
	* function_description
     	*
     	* @param $app_id:
     	*
     	* @return AppChannel or Error
     	*/
    	protected function getPlatformChannel($plat) {
        	// get channel
	        if (!isset($this->_configs['channels'][$plat]['className'])) {
        	    return false;
        	}
	        $className = $this->_configs['channels'][$plat]['className'];
        	$className = "common\components\platform\Channel"."\\".$className;
	        $channel = new $className;
     		return $channel;
    	}
    	/**
     	* receive third part platform params then go on our logic
     	* @param  [type] $plat           [description]
     	* @param  [type] $receive_params [description]
     	* @return [type]                 [description]
     	*/
    	public function receiveCallback($plat,$receive_params){
        	$plat = $this->getPlatformChannel($plat);
        	$userInfo = $plat->getPlatformUserInfo($receive_params);
	        return $userInfo;
    	}

    	/**
     	* receive third part platform params then go on our logic
     	* @param  [type] $plat           [description]
     	* @param  [type] $receive_params [description]
     	* @return [type]                 [description]
     	*/
    	public function receiveCgiCallback($plat,$receive_params){
        	$plat = $this->getPlatformChannel($plat);
	        $userInfo = $plat->getPlatformCgiUserInfo($receive_params);
        	return $userInfo;
    	}

    	/**
     	* receive third part platform params then go on our logic
     	* @param  [type] $plat           [description]
     	* @param  [type] $receive_params [description]
     	* @return [type]                 [description]
     	*/
    	public function recCallback($plat,$receive_params){
        	$plat = $this->getPlatformChannel($plat);
	        $userInfo = $plat->getPlatformOpenId($receive_params);
        	return $userInfo;
    	}

    	/**
     	* receive third part platform params then go on our logic
     	* @param  [type] $plat           [description]
     	* @param  [type] $receive_params [description]
     	* @return [type]                 [description]
     	*/
    	public function getSub($plat,$openid){
        	$plat = $this->getPlatformChannel($plat);
	        $userInfo = $plat->getPlatformCgiUser($openid);
        	return $userInfo;
    	}

    	public function handleRequest($request){

    	}

    	/**
     	* get js sign by plat
     	* for now,we only have weixin platform
     	* @param  string $plat
     	* @return code and data
     	*/
    	public function getJsSignByPlat($plat,$url){
        	$plat = $this->getPlatformChannel($plat);
	        //get weixin token
        	$sign = $plat->getJsSign($url);
	        if($sign !== false){    
        	    return $sign;
        	}
	        return false;
    	}


    	public function getAccessToken($plat){
        	$plat = $this->getPlatformChannel($plat);
	        //get weixin token
        	$access_token = $plat->getJsWeixinToken();
	        if($access_token !== false){    
        	    return $access_token;
        	}
	        return false;
    	}

    	public function getOpenidSession($plat,$code)
    	{
        	$plat = $this->getPlatformChannel($plat);
	        //get weixin token
        	$info = $plat->getOpenidSession($code);
	        if($info !== false){    
        	    return $info;
        	}
	        return false;
    	}









}
