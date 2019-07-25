<?php
namespace common\components;
use Yii;
use yii\base\Exception;
use yii\base\Application;
use yii\base\Component;

class Common extends Component {
    public $config_error = null;

    public $config_path = null;

    protected $_configs = array();

    /**
     * function_description
     *
     *
     * @return
     */
    public function init() {

        if (is_null($this->config_path)) {
            $this->config_path = dirname($this->config_error);
        }

        $this->loadConfig();

        parent::init();
    }

    /**
     * load config
     *
     *
     * @return
     */
    protected function loadConfig() {
        $this->_configs = require(__DIR__.'/../'.$this->config_error);
    }



    /**
     * function_description
     *
     * @param $app_id:
     *
     * @return AppChannel or Error
     */
    public function showError($code) {
        // get channel
        if (!isset($this->_configs['error'][$code]['msg'])) {
            return false;
        }
        $className = $this->_configs['error'][$code]['msg'];
        return $className;
    }
    
    public function rJsonSpeciesAndDogInfo($code,$uid,$data,$token,$seqnum){
        $returnrst=array();
        $returnrst['code']=$code;
        $returnrst['uid']=$uid;
        $returnrst['token']=$token;
        $returnrst['seqnum']=$seqnum;
        $returnrst['pets_arr']=$data;
        $returnrst['msg']=$this->showError($code);
        return "for;;;".json_encode($returnrst)."end;;;";
    }
}
