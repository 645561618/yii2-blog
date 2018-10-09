<?php

namespace backend\components;

use Yii;
use yii\web\User;
use common\models\login\Login;
use common\components\CityWeather;
/**
 * extends web user.
 * add permission control
 * add menu with permission
 */
class ManagerUser extends User
{
    /**
     * @var file path for permissions configuration file.
     */
    public $permsConfigFile='@app/config/permissions.php';

    /**
     * @var perminssion list for routers
     */
    protected $perms = [];

    /**
     * @var file path for manager menu configuration.
     */
    public $menuConfigFile="@app/config/menu.php";

    /**
     *
     */
    protected $allMenu = [];



    /**
     * load permission configuration from config file
     */
    public function init()
    {
        parent::init();
        $this->perms = require(Yii::getAlias($this->permsConfigFile));
        $this->allMenu = require(Yii::getAlias($this->menuConfigFile));
    }
    
    public function getAllPerms(){
        $r = [];
        foreach($this->perms as $p) {
            if (is_array($p)) {
               // $r += $p;
               foreach ($p as $k=>$v) {
                   $r[] = $v;
               }
            } else {
               $r[] = $p;
            }
        }
        return $r;
    }

    
    public function can($permissionName, $params=[], $allowCaching=true)
    {
        if ($this->getIsGuest()) {
            return false;
        }

        if ($this->getId() == 1) {
            return true;
        }

        return parent::can($permissionName, $params, $allowCaching);
    }

    public function canRoute($route) {
        if ($this->getId() == 1) {
            return true;
        }
        $allow_perms = @$this->perms[$route] ?: [];

        if (!is_array($allow_perms)) {
            $allow_perms = [$allow_perms];
        }

        foreach ($allow_perms as $p_name) {
            if ($this->can($p_name)) {
                return true;
            }
        }
        return false;
    }
    //get menu top list
    public function getTopMenu() {
        return array_filter($this->allMenu,function($menu){
            return $this->canRoute($menu['route']);
        });
    }
    //get menu left sidebar list
    public function getSubMenu($topMenuName) {
        return array_filter($this->allMenu[$topMenuName]['subs'], function($menu){
            return $this->canRoute($menu['route']);
        });
    }


    //根据城市获取天气
    public function WeatherInfo()
    {   
        $ip = Yii::$app->request->userIP;
	return CityWeather::WeatherInfo($ip);
   }


  	//根据ip获取城市
  	public function getCity(){
		$ip = Yii::$app->request->userIP;	
		return CityWeather::getCity($ip);
  	}

    public function getUserEmail(){
	$id = Yii::$app->user->id;
	$key = "user_email_".$id;
	$list = Yii::$app->cache->get($key);
	if(empty($list)){
		$model = Login::findOne($id);
		$list = $model->email; 
                Yii::$app->cache->set($key,$list,3600);
	}
	return $list;
    }



}

