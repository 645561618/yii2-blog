<?php

namespace frontend\components\filters;

use Yii;
use yii\base\Action;
use yii\base\ActionFilter;
use yii\web\User;
use yii\web\ForbiddenHttpException;

class AccessControl extends ActionFilter
{
	
    /**
     * This method is invoked right before an action is to be executed.
     * For checking current user has permission to run this action.
     *
     * @param Action $action the action to be executed.
     * @return boolean whether the action should continue to be executed.
     */
    /*public function beforeAction($action)
    {
	if(Yii::$app->user->identity){
		if(Yii::$app->user->Identity->id){
			return true;
		}
	}
	Yii::$app->getResponse()->redirect('/site/login.html');
	return false;
    }*/

}

