<?php

namespace frontend\components;

use Yii;
use yii\web\User;

class FrontendUser extends User
{
    protected $perms = [];

    public function init()
    {
        parent::init();
    }
    
    
    public function can($permissionName, $params=[], $allowCaching=true)
    {

        if ($this->getId() == 1) {
            return true;
        }

        return parent::can($permissionName, $params, $allowCaching);
    }

    public function canRoute($route) {
        if ($this->getId() == 1) {
            return true;
        }

    }

}

