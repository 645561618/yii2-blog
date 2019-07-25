<?php
namespace common\models\register;
use Yii;
use common\components\BackendActiveRecord;
use yii\web\IdentityInterface;
class LoginUser extends BackendActiveRecord implements IdentityInterface
{
	
	
	public $password;	

        private $_user = false;
	
	 const STATUS_ACTIVE = 10;	

	public static function tablename()
	{
		return "user";
	}

	public static function findIdentity($id)
	{
		return static::findOne($id);
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return null|User
	 */
	public static function findByUsername($username)
	{
		return static::findOne(['username' => $username, 'status' => static::STATUS_ACTIVE]);
	}

	/**
	 * @return int|string|array current user ID
	 */
	public function getId()
	{
		return $this->getPrimaryKey();
	}
	public function getUsername() {
        return $this->user->username;
    }
    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }


	public static function findIdentityByAccessToken($token, $type = null)
     {
          throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
     }	
	
     public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
 


}

