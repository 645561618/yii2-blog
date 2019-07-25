<?php
namespace backend\components;

use Yii;
use yii\base\Component;
use common\models\Manager;
user backend\models\LoginBack;
class RoleMenu extends Component{
    public $config_path;

    protected $_configs = [];

    public function init() {
        $this->loadConfig();
    }

    protected function loadConfig(){

        if (empty($this->config_path)) {
            $this->config_path = __DIR__.'/../'.Yii::getAlias('config')."/menu.php";
        }
        $all_configs = require($this->config_path);
        $userId = Yii::$app->user->id;
        $roles = '';
        if(Yii::$app->user->identity->username == 'admin'){
            $this->_configs = $all_configs['Administrator'];
        }else{
            $this->_configs = $all_configs['editor'];
        }
    }

    public function getConfig($uid,$config){
        $rs = LoginBack::find($uid);
        if($rs->username == 'admin'){
            return $config['Administrator'];
        }else{
            return $config['editor'];
        }
    }
    public function getTops(){
        return $this->_configs;
    }
}

