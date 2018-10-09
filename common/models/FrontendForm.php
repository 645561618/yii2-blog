<?php

namespace common\models;

use Yii;
use yii\base\Model;
use common\models\register\LoginUser;
/**
 * LoginForm is the model behind the login form.
 */
class FrontendForm extends Model
{
	public $username;
	public $password;
	public $rememberMe = true;

	private $_user = false;

	 public function validatePassword($password,$password_hash)
	 {
        	return \Yii::$app->getSecurity()->validatePassword($password, $password_hash);
    	 }

	/**
	 * Logs in a user using the provided username and password.
	 * @return boolean whether the user is logged in successfully
	 */
	public function login($post)
	{
		$user = LoginUser::findOne(['username'=>$post['Login']['username']]);
		if($user !== null){	
			if($this->validatePassword($post['Login']['password'],$user->password_hash)){
				return Yii::$app->user->login($user, $this->rememberMe ? : 0);
			}
			return false;			

		}else{
			return false;
		}

	}

	/**
	 * Finds user by [[username]]
	 *
	 * @return User|null
	 */
	private function getUser($username)
	{
		return $this->_user = LoginUser::findByUsername($username);
	}
}

