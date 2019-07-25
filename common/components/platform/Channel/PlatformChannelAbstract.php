<?php
namespace common\components\platform\Channel;

use Yii;

abstract class PlatformChannelAbstract {
    public $chn_name = "";
    /**
     * function_description
     *
     *
     * @return
     */
    public function __construct() {
        $this->init();
    }

    /**
     * function_description
     *
     *
     * @return
     */
    public function init() {
        $this->loadConfig();
    }

    /**
     * function_description
     *
     *
     * @return
     */
    public function loadConfig() {
        $this->_config = require(__DIR__."/../../../config/platform/platform_channel_".$this->chn_name.".php");
    }

    abstract public function getLoginUrl();

    abstract public function getPlatformUserInfo($receive_params);

    public function getCallbackUrl(){
        //return "http://www.hxinq.com/home/bind?plat=".$this->chn_name;
	return "http://www.hxinq.com?plat=".base64_encode($this->chn_name);
    }
}
