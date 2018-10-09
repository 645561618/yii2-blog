<?php

namespace backend\components\filters;

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
    public function beforeAction($action)
    {
        $route = $action->getUniqueId();
        $user = Yii::$app->getUser();
        if ($user->canRoute($route)) {
            return true;
        }

        $this->denyAccess($user);
        return false;
    }

    /**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     * @param User $user the current user
     * @throws ForbiddenHttpException if the user is already logged in.
     */
    protected function denyAccess($user)
    {
        if ($user && $user->getIsGuest()) {
            $user->loginRequired();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

}

