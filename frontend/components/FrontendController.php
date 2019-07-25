<?php

namespace frontend\components;

use Yii;
use yii\web\Controller;

class FrontendController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \frontend\components\filters\AccessControl::className(),
            ],
        ];
    }

    public function init() {
        parent::init();

        $this->getUser();
    }

    public function getUser()
    {
	$session = Yii::$app->session;
        if (!empty($session['UserLogin'])){
		if($session['UserLogin']['time']<time()){
			Yii::$app->session->remove('UserLogin');
		}	
        }
    }

}
