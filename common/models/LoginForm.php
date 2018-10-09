<?php
namespace common\models;

use Yii;
use yii\base\Model;
use backend\models\LoginBack;
use backend\models\Log;
use common\components\CityWeather;
/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $verifyCode;
    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
	
	    ['verifyCode','captcha'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username' => '用户名：',
            'password' => '密码：',
	    'verifyCode' =>'验证码：',
        ];
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码不正确');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(),15*60);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = LoginBack::findByUsername($this->username);
        }

        return $this->_user;
    }

   public function loginLog(){
        $userIP = Yii::$app->request->userIP;
	//$username = Yii::$app->user->identity->username;
        $data = CityWeather::getCity($userIP);
	$model = Log::find()->where(['id'=>Yii::$app->user->id,'ip'=>$userIP])->one();
	if(!$model){
		$model = new Log();
	}
	$model->username = $this->username;	
	$model->ip = $userIP;
	$model->data = $data;
	$model->created = time();
	$model->save(false);


    }




}
