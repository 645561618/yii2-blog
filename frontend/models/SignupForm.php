<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\register\LoginUser;
/**
 * Signup form
 */
class SignupForm extends Model
{

    public function signup($post)
    {
        $data = $post['Signup'];
        $user = new User;
        $user->username = $data['username'];
        $user->phone = $data['phone'];
        $user->setPassword($data['password']);
        $user->ip = Yii::$app->getRequest()->getUserIP(); 
        if($user->save(false)){
		return true;
	};
    }


}
