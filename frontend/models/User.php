<?php
namespace bbsapi\models;

use Yii;
use yii\db\Exception;
use yii\web\IdentityInterface;
use common\models\register\LoginUser;
use yii\db\Query;

use bbsapi\models\MobileStat;
use bbsapi\models\Task;
use backend\models\aichong\CreditRule;
use bbsapi\models\CreditLimitLog;
use bbsapi\models\UserPoints;
use bbsapi\models\UserPointsTxn;
use bbsapi\models\CommonMemberCount;

// use config\platform;
// use frontend\config\platform\platform_channel_qq;
// use frontend\config\platform\platform_channel_weibo;

/**
 * Class User
 * @package common\models
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 */
class User extends LoginUser implements IdentityInterface
{
    /**
     * @var string the raw password. Used to collect password input and isn't saved in database
     */
    /**
     * @var string the raw password. Used to collect password input and isn't saved in database
     */
    // public $password;
    protected $_configs = array();
    public $config_file = null;
    public $_user = false;
    // public $username;
    // public $password;
    public $_identity;
    public $rememberMe = true;
    public $auth_key;

    const STATUS_SUCCESS = 10000;//成功
    const STATUS_FAIL = 10001;//失败
    const STATUS_UNAME_EMPTY = 10602;//用户名不为空
    const STATUS_UNAME_ERROR = 10603;//用户名不符合规则
    const STATUS_USERNAME_ISSET = 10604;//用户名已经存在
    const STATUS_PWD_EMPTY = 10605;//     密码不为空
    const STATUS_PWD_ERROR = 10606;//密码不符合规则
    const STATUS_CONPWD_EMPTY = 10607;//确认密码不为空
    const STATUS_COMPARE_PWD = 10608;//密码和确认密码不相等
    const STATUS_EMAIL_EMPTY = 10609;//邮箱不为空
    const STATUS_EMAIL_ERROR = 10610;//邮箱验证错误
    const STATUS_EMAIL_ISSET = 10611;//邮箱已经存在
    const STATUS_USER_PWD_ERROR = 10501;//用户名或密码不正确
    const PLAT_BINDED = 11113;//已经被绑定
    const TOKEN_ERROR = 11111;
    const HOME_INFO_EMPTY = 11114;

    const STATUS_INFO_EMPTY = 11200;
    const KEYWORDS_IS_EMPTY = 11201;
    const STATUS_INFO_NOMORE = 11112;
    const STATUS_PARAMS_ERROR = 11115;

    const EXPIRE_DATE = 2592000;//30

    const SEARCH_USER_SUCCESS = 20000;
    const SEARCH_USER_FAIL = 20001;
    const USER_INFO_GET_SUCCESS = 20002;
    const USER_INFO_GET_FAIL = 20003;
    const USER_NO_HAVE_FRIEND = 20004;
    const USER_HAVE_FRIEND = 20005;
    const STATUS_IS_NEW = 20006;
    const STATUS_NOT_NEW = 20007;

    const IS_NEW_REGISTER = 1;
    const IS_NOT_NEW_REGISTER = 0;


    const REGISTER_USER_GROUP_ID = 10;
    const USERNAME_OR_PASSWORD_ERROR = 20201;
    const USER_EMAIL_IS_SET = 20105;
    const USER_EMAIL_IS_NOT_CHECK = 20104;
    const USER_PASSWORD_IS_NOT_CHECK = 20103;
    const USER_NAME_IS_SET = 20102;
    const USER_NAME_IS_NOT_CHECK = 20101;

    const PASS_LENGTH_TOO_SHORT = 6;
    const PASS_LENGTH_TOO_LONG = 16;


    const USER_INFO_PERFECT_INTEGRAL = 10;
    const USER_INFO_PERFECT_POINT = 10;
    const USER_BIND_PHONE = 10;

    public static function tableName()
    {
        return "pre_ucenter_members";
    }

    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['rememberMe', 'boolean'],
            ['email', 'unique', 'message' => 'This email address has already been taken.', 'on' => 'signup'],
            ['email', 'exist', 'message' => 'There is no user with such email.', 'on' => 'requestPasswordResetToken'],
            ['password', 'safe'],
            ['phone','safe'],
        ];
    }

    public function validatePassword($password)
    {
        return \Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->uid;
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

    public static function findIdentityByAccessToken($token,$type=null)
    {

    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        $identity = $this->checkUser();
        if ($identity == static::STATUS_SUCCESS) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }


    public function checkUser()
    {
        $isemail = $this->checkEmail($this->username);
        $user = '';
        if ($isemail) {
            $user = "email='" . $this->username . "'";
        } else {
            $user = "username='" . $this->username . "'";
        }
        $record = static::findOne(['username' => $this->username]);
        if (empty($record)) {
            return static::STATUS_USER_PWD_ERROR;
        } else {
            $hashpwd = md5(md5($this->password) . $record['salt']);//
            // $hashpwd = $this->hashPassword($this->password,$record->username);
            if ($isemail) {
                $record['username'] = $record['email'];
            }
            if ($record['username'] == $this->username && $record['password'] == $hashpwd) {
                return static::STATUS_SUCCESS;
            } else {
                return static::STATUS_USER_PWD_ERROR;
            }
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    private function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findOne(['username' => $this->username]);
        }
        $isemail = $this->checkEmail($this->username);
        if ($isemail && $this->_user === NUll) {
            $this->_user = static::findOne(['email' => $this->username]);
        }
        return $this->_user;
    }

    //验证用户的注册信息
    public function checkUserRegister($request_params, $isShow = false)
    {
        $error = '';
        if (empty($request_params)) {
            return static::STATUS_UNAME_EMPTY;
        }
        if (empty($request_params['username'])) {
            return static::STATUS_UNAME_EMPTY;
        }
        $isset_user = $this->checkUserisset($request_params['username']);
        if (!empty($isset_user)) {
            return static::STATUS_USERNAME_ISSET;
        }
        $check_username = $this->checkUsername($request_params['username']);
        if (!$check_username) {
            return static::STATUS_UNAME_ERROR;
        }
        if (empty($request_params['password'])) {
            return static::STATUS_PWD_EMPTY;
        }
        $pwd_len = strlen($request_params['password']);
        if ($pwd_len < 6 || $pwd_len > 16) {
            return static::STATUS_PWD_ERROR;
        }
        if (empty($request_params['email'])) {
            return static::STATUS_EMAIL_EMPTY;
        }
        $is_email = $this->checkEmailisset($request_params['email']);
        if (!empty($is_email)) {
            return static::STATUS_EMAIL_ISSET;
        }
        $right_email = $this->checkEmail($request_params['email']);
        if (!$right_email) {
            return static::STATUS_EMAIL_ERROR;
        }
        return false;
    }


    //检验用户名是否合法
    public function checkUsername($username)
    {
        if (preg_match("/^[a-zA-Z]{1}([a-zA-Z0-9]|[._]){3,15}$/", $username)) {
            return true;
        } else {
            return false;
        }
    }

    //验证邮箱
    public function checkEmail($email)
    {
        if (preg_match("/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/i", $email)) {
            return true;
        } else{
            return false;
        }
    }

    // 验证用户名是否存在
    public function checkUserisset($user)
    {
        $data = '';
        $sql = "select * from pre_ucenter_members where username='$user'";
        $command = static::getDb()->createCommand($sql);
        $data = $command->queryAll();
        if (!empty($data)) {
            return true;
        } else {
            return false;
        }
    }

    // 验证邮箱是否存在
    public function checkEmailisset($email)
    {
        $data = '';
        $sql = 'select uid from pre_ucenter_members where email="' . $email . '"';
        $command = static::getDb()->createCommand($sql);
        $data = $command->queryAll();
        if (!empty($data)) {
            return true;
        } else {
            return false;
        }
    }

    // //检查数据库中用户名是否存在
    public function createAccount($data)
    {
        $user = new User;
        $username = $data['username'];
        $result = $this->getUsername($username);
        if (!empty($result)) {
            $num = $this->getUniqidName($result);
            if ($num == false) {
                $username = $username . "(1)";
            } else {
                $username = $username . "($num)";
            }
        }
        return $username;
    }

    public function getUsername($param)
    {
        $rs = static::find()->where("username like '$param%'")->all();
        return $rs;
    }

    public static function getUcenterUsername($param){
        $rs = NdUser::find()->where("user_name like '$param%'")->all(Yii::$app->get('dogucenterdb'));
        return $rs;
    }

    public function getUniqidName($result)
    {
        $array = array();
        foreach ($result as $value) {
            preg_match("/\((\d)\)/", $value['username'], $match);
            if (!empty($match)) {
                $array[] = $match['1'];
            }    # code...
        }
        if (!empty($array)) {
            $max = max($array);
            return $max + 1;
        } else {
            return false;
        }
    }

    //用户信息添加
    public function registerUserinfo($data)
    {
        if (!empty($data)) {
            $userip = Yii::$app->getRequest()->getUserIP();//ip地址
            $encrypted_password = md5($data['password']);
            $username = trim($data['username']);
            $password = $encrypted_password;
            $userip = $userip;
            $email = $data['email'];
            $salt = substr(uniqid(rand()), -6);
            $passwordmd5 = md5($password . $salt);//
            $isBad = 0;
            $db = static::getDb();
            $transaction = $db->beginTransaction();
            try {
                $sql = "INSERT INTO `pre_ucenter_members` SET `secques`='', `username`='$username', `password`='$passwordmd5', `email`='$email', `regip`='hidden', `regdate`='" . time() . "', `salt`='$salt'";
                $r = $this->queryExecute($sql);

                // $db->createCommand($sql1)->execute();

                $uid = static::getDb()->getLastInsertID("pre_ucenter_members");
                // if($uid<=0){
                //     return false;
                // }

                $sql = "REPLACE INTO `pre_ucenter_memberfields` SET uid='$uid'";
                $r = $this->queryExecute($sql);
                // if($r === false){
                //     return false;
                // }

                $password = md5($this->random(10));
                $sql = "REPLACE INTO `pre_common_member` SET `uid`=$uid,`username`='$username',`password` = '$password',`email`='$email',`adminid` = '0',`groupid` = '10',`regdate` = '" . time() . "',`emailstatus` = '0',`credits` = '0',`timeoffset` = '9999' ";
                $r = $this->queryExecute($sql);


                $sql = "REPLACE INTO `pre_common_member_status` SET `uid` = '$uid',`regip` = '$userip',`lastip` = '$userip',`lastvisit` = '" . time() . "',`lastactivity` = '" . time() . "',`lastpost` = '0',`lastsendmail` = '0'";
                $r = $this->queryExecute($sql);


                $sql = "REPLACE INTO `pre_common_member_count` SET `uid` = '$uid'";
                $r = $this->queryExecute($sql);


                $sql = "REPLACE INTO `pre_common_member_profile` SET `uid` = '$uid',`nickname` = '$username'";
                $r = $this->queryExecute($sql);


                $sql = "REPLACE INTO `pre_common_member_field_forum` SET `uid` = '$uid'";
                $r = $this->queryExecute($sql);


                $sql = "REPLACE INTO `pre_common_member_field_home` SET `uid` = '$uid'";
                $r = $this->queryExecute($sql);


                $datetime = date("Ymd");
                $sql = "INSERT INTO `pre_common_statuser` SET `uid` = '$uid',`daytime` = '$datetime',`type` = 'login'";
                $r = $this->queryExecute($sql);

                // $db->createCommand($sql2)->execute();


                $sql = "SELECT COUNT(*) FROM `pre_common_stat` WHERE `daytime` = '$datetime'";
                $r = $this->queryExecute($sql);
                if ($r) {
                    $this->queryExecute("UPDATE `pre_common_stat` SET `register` = `register`+1 WHERE `daytime` = '$datetime'");
                } else {
                    $this->queryExecute("INSERT INTO `pre_common_stat` SET `daytime` = '$datetime',`register` = 1");
                }

                // 创建用户中心数据
                // dogucenter 数据库  nd_user数据表
                $user_id = $uid;
                $user_name = $username;
                $user_pass = $password;
                $user_nickname = $username;
                $user_server_id = 1;
                $user_cdate = time();
                $user_email = $email;
                $dogname_temp = $username . "的狗狗";

                // 创建一个新狗狗
                $sql = "insert into dog_doginfo(dog_userid,dog_name,dog_species,dog_birth_y,dog_birth_m,dog_birth_d,dog_gender,dog_cdate) values ('" . $uid . "','" . $dogname_temp . "','30','" . date("Y") . "','" . date("m") . "','" . date("d") . "','2',UNIX_TIMESTAMP())";
                $r = $this->queryExecute($sql);

                $user_dog_id = static::getDb()->getLastInsertID("dog_doginfo");
                $user_dog_name = $dogname_temp;
                $mem_dog_default = $user_dog_id;
                // $sql = "insert into nd_user(`user_id`,`user_name`,`user_pass`,`user_nickname`,`user_dog_id`,`user_dog_name`,`user_server_id`,`user_cdate`,`user_email`) values ('".$user_id."','".$user_name."','".$user_pass."','".$user_nickname."','".$user_server_id."','".$user_cdate."','".$user_email."','".$user_dog_id."','".$user_dog_name."')";
                $sql = "insert into nd_user(`user_id`,`user_name`,`user_pass`,`user_nickname`,`user_dog_id`,`user_dog_name`,`user_server_id`,`user_cdate`,`user_email`) values ('" . $user_id . "','" . $user_name . "','" . $user_pass . "','" . $user_nickname . "','" . $user_dog_id . "','" . $user_dog_name . "','" . $user_server_id . "','" . $user_cdate . "','" . $user_email . "')";
                $command = static::getUcenterdb()->createCommand($sql);
                $r = $command->execute();

                $sql = "INSERT IGNORE INTO supe_userspaces (
                uid, username, dateline, province, city )
                VALUES (
                '$uid', '$username', UNIX_TIMESTAMP(), '未知', '')";
                $r = $this->queryExecute($sql);

                $sql = "INSERT INTO dog_member (
                uid, mem_nickname,mem_dog_default )
                VALUES (
                '$uid', '$username','$mem_dog_default' )";
                $r = $this->queryExecute($sql);

                $sql = "INSERT INTO dog_member_info (user_id) VALUES ('$uid')";
                $r = $this->queryExecute($sql);

                // 创建一个新相册
                $sql = "INSERT INTO dog_album (
                abm_userid, abm_title, abm_cdate )
                VALUES (
                '$uid', '新相册', UNIX_TIMESTAMP() )";
                $r = $this->queryExecute($sql);

                $transaction->commit();
                return $uid;//返回用户注册后的uid
            } catch (Exception $e) {
                $transaction->rollBack();
                return false;
            }
        } else {
            return false;
        }
    }

    public function queryExecute($sql)
    {
        $command = static::getDb()->createCommand($sql);
        $r = $command->execute();
        return $r;
    }

    /**
     * get common member rand password
     * @param  [type]  $length  [description]
     * @param  integer $numeric [description]
     * @return [type]           [description]
     */
    private function random($length, $numeric = 0)
    {
        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        if ($numeric) {
            $hash = sprintf('%0' . $length . 'd', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
            $max = strlen($chars) - 1;
            for ($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
    }

    //加载数据
    public function load($data, $formName = null)
    {
        $this->setAttributes($data);
        return true;
    }


    public function outToken($uid)
    {
        // $model = UserToken::findOne(['goumin_uid'=>$uid]);
        // $model->topken = '0';
        // $model->topken_time = '0';
        // $model->save();
        UserToken::updateAll(['topken' => '0', 'topken_time' => '0'], "goumin_uid='$uid'");
    }

    public function getLoginName($uid)
    {
        $info = User::findOne($uid);
        if ($info) {
            return $info->username;
        }
        return false;
    }

    public function getUserInfo($uid)
    {
        $r = static::findOne($uid);
        $data = [];
        if (!empty($r)) {
            $data['username'] = $r->username;
            $data['regdate'] = floor((time() - $r->regdate) / (3600 * 24));
        }
        return $data;
    }

    public function checkPlatInfo($data)
    {
        if (empty($data)) {
            return false;
        }
        if (!isset($data['platform_uid']) || empty($data['platform_uid'])) {
            return false;
        }
        if (!isset($data['platform']) || empty($data['platform'])) {
            return false;
        }
        if (!isset($data['username']) || empty($data['username'])) {
            return false;
        }
        return true;
    }


    public function userData($data)
    {
        $username = $this->createAccount($data);//'as'.rand(100,9999);测试数据
        $params = [];
        $params['username'] = $username;
        $params['password'] = '';
        $params['email'] = rand(100, 99999) . 'abc@goumin.com';
        return $params;
    }


    public function ifFirstLogin($plat, $params)
    {
        if ($plat == 'weibo') {
            $source = 2;
        } elseif ($plat == 'qq') {
            $source = 3;
        }
        $checkerror = $this->checkParams($params);
        if (!$checkerror) {
            $userbind = UserToken::findOne(['source_id' => $params['plat_openid'], 'source' => $source]);
            if (empty($userbind)) {
                return static::STATUS_IS_NEW;
            }
            return static::STATUS_NOT_NEW;
        }
        return static::STATUS_INFO_EMPTY;
    }

    public function requestLogin($plat, $params)
    {
        if ($plat == 'weibo') {
            $source = 3;
        } elseif ($plat == 'qq') {
            $source = 2;
        }
        $data = [];
        $checkerror = $this->checkParams($params);
        if (!$checkerror) {
            $info = Yii::$app->platform->receiveCallback($plat, $params);
            // $data =['platform_uid'=>'14567'.rand(100,99999),'platform'=>$plat];//测试数据
            // $data =['platform_uid'=>'B87A653614F7CE1EDF36A7F5787dAF0B9','platform'=>$plat];
            $status = $this->checkPlatInfo($info);
            if ($status) {
                $result = $this->requestApiLogin($info, $params, $source);
                list($rs, $qs) = $result;
                if ($rs === true) {
                    $user = User::findOne($qs[0]);
                    $data = ['uid' => $qs[0], 'token' => $qs[1], 'username' => $qs[2], 'nickname' => $qs[2], 'isnew' => $qs[3],'avatar'=>$user->avatar];
                    return [true, $data];
                } else {
                    return [$rs, $data];
                }
            }
        }
        return [User::STATUS_INFO_EMPTY, $data];
    }

    public function requestApiLogin($data, $params, $source)
    {
        $info = ['source_id' => $data['platform_uid'], 'source' => $source];
        $model = new User;
        $userbind = DogOtherMember::findOne(['source_id' => $data['platform_uid'], 'source' => $source]);
        if (empty($userbind)) {
            $params = $this->userData($data);
            $username = $params['username'];
            $uid = $model->registerUserinfo($params);
            if (!empty($uid)) {
                DogOtherMember::getToken($uid, $info);
                return [true, [$uid, UserToken::getToken($uid), $username, static::IS_NEW_REGISTER]];
            }
            return [User::STATUS_FAIL, []];
        } else {
            $user = User::findOne($userbind['goumin_uid']);
            if (!empty($user)) {
                $uid = $userbind['goumin_uid'];
                return [true, [$uid, UserToken::getToken($uid), $user->username, static::IS_NOT_NEW_REGISTER]];
            }
            return [User::STATUS_FAIL, []];
        }
    }

    public function responseBind($plat, $params)
    {
        if ($plat == 'weibo') {
            $source = 3;
        } elseif ($plat == 'qq') {
            $source = 2;
        }
        $data = [];
        $checkerror = $this->checkParams($params);
        if (!$checkerror) {
            $data = Yii::$app->platform->receiveCallback($plat, $params);
            // $data =['platform_uid'=>'14567'.rand(100,99999),'platform'=>$plat];//测试数据
            // $data =['platform_uid'=>'1456771255','platform'=>$plat];
            $status = $this->checkPlatInfo($data);
            if ($status) {
                $code = $this->requestBind($data, $source, $params['uid']);
            } else {
                $code = User::STATUS_FAIL;
            }
            return $code;
        }
        return false;
    }

    public function requestBind($data, $source, $uid)
    {
        $model = new User;
        $platUser = new UserToken;
        $info = ['source_id' => $data['platform_uid'], 'source' => $source];
        $userbind = UserToken::findOne(['source_id' => $data['platform_uid'], 'source' => $source, 'goumin_uid' => $uid]);
        if (empty($userbind)) {
            $token = $this->getToken($uid, $info);
            if ($token) {
                return static::STATUS_SUCCESS;
            }
            return static::STATUS_FAIL;
        } else {
            return static::PLAT_BINDED;
        }
    }

    public function checkParams($params, $status = false)
    {
        if (empty($params)) {
            return true;
        }
        if (empty($params['plat']) || (empty($params['plat_openid']) && empty($params['plat_token']))) {
            return true;
        }
        if ($status) {
            if (empty($params['status'])) {
                return true;
            }
        }
        return false;
    }


    public function checkUserIsFriend($uid, $otherid)
    {
        $query = static::getDb()->createCommand("SELECT df_id FROM dog_friend WHERE ask_user_id = {$uid} and ret_user_id = {$otherid}")->queryOne();
        if (!empty($query)) {
            return "1";
        }
        return "0";
    }

    public function getUserInfoAndDogInfo($params)
    {
        $status = $this->checkParamsForUserInfoAndDogInfo($params);
        if ($status) {
            return [$status, []];
        }
        $result = $this->getUserAndDogData($params["userid"]);
        if ($result) {
            return [self::STATUS_SUCCESS, $result];
        }
        return [self::STATUS_INFO_NOMORE, []];

    }

    public function getUserAndDogData($userid)
    {
        $query = new Query;
        $userdata = $query->select("t1.username,t1.uid,t2.extcredits1,t2.extcredits2,t3.grouptitle")
            ->from("pre_ucenter_members t1,pre_common_member_count t2,pre_common_usergroup t3,pre_common_member t4")
            ->where("t1.uid = t2.uid and t2.uid=t4.uid and t3.groupid = t4.groupid and t1.uid = {$userid}")
            ->one();
        if ($userdata) {
            $userdata["avatar"] = "http://www.goumin.com/api/getHeadImage.php?head_id=" . $userdata["uid"];
            $userdata["doginfo"] = $query->select("dog_id,dog_name")->from("dog_doginfo")->where("dog_userid = {$userid}")->all();
            if ($userdata["doginfo"]) {
                foreach ($userdata["doginfo"] as $key => $value) {
                    $userdata["doginfo"][$key]["dog_id"] = $value["dog_id"];
                    $userdata["doginfo"][$key]["dog_name"] = $value["dog_name"];
                    $userdata["doginfo"][$key]["dog_avatar"] = self::getDogAvatar($value["dog_id"]);
                }
            } else {
                $userdata["doginfo"] = [];
            }
            return $userdata;
        } else {
            return [];
        }
    }

    public function checkParamsForUserInfoAndDogInfo($data)
    {
        if (empty($data) || !isset($data["uid"])) {
            return self::STATUS_INFO_EMPTY;
        }
        if (!isset($data["userid"]) || empty($data["userid"])) {
            return self::STATUS_PARAMS_ERROR;
        }
        return false;
    }


    public static function getDogAvatar($dogid)
    {
        // print_r($dogid);exit;
        $query = new Query;
        if ($dogid) {
            $result = $query->select("head_id,head_fileext,head_cdate")->from("dog_head_image")->where("head_dogid = '{$dogid}'")->orderBy("head_id DESC")->one(Yii::$app->get('dogdb'));
            if (empty($result)) {
                return "http://s.goumin.com/imgs/cover-s.jpg";
            }
        } else {
            return "http://s.goumin.com/imgs/cover-s.jpg";
        }
        if ($result['head_id'] > 0) {
            $sub[0] = $result['head_id'];
            $sub[1] = $sub[0] >> 8;
            $sub[2] = $sub[1] >> 8;
            $sub[3] = $sub[2] >> 8;
            $sub[4] = $sub[3] >> 8;
            if ($result['head_cdate'] < time() - 3600) {
                $dir = 'http://hd2.goumin.com';
            } else {
                $dir = 'http://www.goumin.com';
            }
            $dir .= '/attachments/head/' . $sub[4] . '/' . $sub[3] . '/' . $sub[2] . '/' . $sub[1] . '/' . $result['head_id'] . 's.' . $result['head_fileext'];
            $result['head_image'] = $dir;
        } else {
            $result['head_image'] = 'http://s.goumin.com/imgs/cover-s.jpg';
        }
        return $result['head_image'];
    }

    public function getUserFriendData($uid)
    {
        $query = new Query;
        // select df_id, df_status from dog_friend where df_status<=20 and ask_user_id='$ask_user_id' and ret_user_id='$ret_user_id' limit 1
        $friendids = $query->select("ret_user_id")->from("dog_friend")->where("df_status = 1 and ask_user_id = {$uid}")->all();
        $uids = [];
        foreach ($friendids as $key => $value) {
            $uids[] = $value["ret_user_id"];
        }
        $uidstr = implode(",", $uids);
        $data = $query->select("t1.username,t1.uid,t3.grouptitle")
            ->from("pre_ucenter_members t1,pre_common_member t2,pre_common_usergroup t3")
            ->where("t1.uid = t2.uid and t2.groupid=t3.groupid and t1.uid in ($uidstr)")
            ->all();
        $result = [];
        foreach ($data as $key => $value) {
            $result[$key]["uid"] = $value["uid"];
            $result[$key]["username"] = $value["username"];
            $result[$key]["avatar"] = "http://www.goumin.com/api/getHeadImage.php?head_id=" . $value['uid'];
            $result[$key]["level"] = $value["grouptitle"];
        }

        return $result;
    }

    public static function getUserAvatar($uid)
    {
        $head = Upload::findOne(['uid' => $uid, 'type' => 5]);
        if ($head) {
            return Yii::$app->params['diaryDomain'] . $head['image'];
        }
        $data = (new Query)->select(["mem_dog_default"])
            ->from(["dog_member d"])
            ->where(["d.uid" => $uid])
            ->scalar(Yii::$app->get('dogdb'));
        $avatar = self::getDogAvatar($data);
        return $avatar;
    }


    public static function getUidByUsername($username)
    {
        $user = static::findOne(['username' => $username]);
        if (!empty($user)) {
            return $user->uid;
        }
        return "";
    }

    public static function getUserNameByUid($uid)
    {
        $result = User::findOne($uid);
        if ($result) {
            return $result->username;
        }
        return false;
    }

    public function checkParam($param)
    {
        if (empty($param["userid"])) {
            return false;
        }
        return true;
    }

    public function getCommonUser()
    {
        return $this->hasOne(CommonMember::className(), ["uid" => "uid"]);
    }

    public function getCommonUserCount()
    {
        return $this->hasOne(CommonMemberCount::className(), ["uid" => "uid"]);
    }

    public function getUserGroups()
    {
        return $this->hasOne(CommonUserGroup::className(), ["groupid" => "groupid"])
            ->via('commonUser');
    }

    public function getUserDogs()
    {
        return $this->hasMany(Pets::className(), ["dog_userid" => "uid"]);
    }

    public function getInfo()
    {
        return $this->hasOne(UserInfo::className(), ["uid" => "uid"]);
    }

    public function getDefaultDogId()
    {
        return $this->info->mem_dog_default;
    }

    /**
     *    avatar saveed in Upload
     */
    public function getHeadPicture()
    {
        return $this->hasOne(Upload::className(), ['uid' => 'uid'])->andWhere(["type" => 5]);
    }


    public function getNickname()
    {
        if ($this->info) {
            return $this->info->mem_nickname;
        } 
        return "";
    }

    public function getGender()
    {
        if ($this->info) {
            return $this->info->mem_gender;
        }
        return '';
    }

    public function getBirthday()
    {
        if ($this->info) {
            return strtotime($this->info->mem_birth_y."-".$this->info->mem_birth_m."-".$this->info->mem_birth_d);
        }
        return "";
    }

    public function getQq()
    {
        if ($this->info) {
            return $this->info->contact_qq;
        }
        return "";
    }

    public function getCity()
    {
        if ($this->info) {
            return $this->info->mem_city;
        }
        return "";
    }

    public function getProvince()
    {
        if ($this->info) {
            return $this->info->mem_province;
        }
        return "";
    }

    public function getAnnounce()
    {
        if ($this->info) {
            return $this->info->mem_announce;
        }
        return "";
    }

    public function getMsn()
    {
        if ($this->info) {
            return $this->info->contact_msn;
        }
        return "";
    }

    public function getUInfo()
    {
        return $this->hasOne(UserInfo::className(), ["uid" => "uid"])->where("mem_dog_default <> 0");
    }

    public function getHeadImage()
    {
        return $this->hasOne(DogHeadImage::className(), ["head_dogid" => "mem_dog_default"])->via('uInfo');
    }

    // fetch user's head picture
    public function getAvatar()
    {
        $hp = $this->headPicture;
        if ($hp) {
            return Yii::$app->params['diaryDomain'] . $hp->image;
        }

        // return isset($this->headImage) ? $this->headImage->getUrl() : 'http://s.goumin.com/imgs/cover-s.jpg';
        return 'http://s.goumin.com/imgs/cover-s.jpg';
    }
    //get background image
    public function getBgImage()
    {
        $bg_image = $this->backgroundImage;
        if ($bg_image) {
            return Yii::$app->params['diaryDomain'] . $bg_image->image;
        }
        return '';
    }
    public function getBackgroundImage()
    {
        return $this->hasOne(Upload::className(), ['uid' => 'uid'])->andWhere(["type" => 10]);
    }

    public function getGroupid()
    {
        return $this->userGroups->groupid;
    }

    public function getGrouptitle()
    {
        if ($this->userGroups) {
            return $this->userGroups->grouptitle;
        }
        return "";
    }

    public function getGrouplevel()
    {
        if ($this->userGroups) {
            return $this->userGroups->level;
        }
        return "";
    }

    public function getUserInfoData($userid, $uid)
    {
        // static::setMaster();
        $result = User::find()->where(["uid" => $userid])->with("userGroups", "commonUserCount", "userDogs")->one();
        if ($result) {
            $result = yii\helpers\ArrayHelper::toArray($result, [
                "bbsapi\models\User" => [
                    'username' => function ($result) {
                        return htmlspecialchars_decode($result->info->mem_nickname);
                    },
                    'avatar' => function ($result) {
                        return User::getUserAvatar($result->uid);
                    },
                    'grouptitle' => function ($result) {
                        if (empty($result->userGroups)) {
                            return "";
                        }
                        return $result->userGroups->grouptitle;
                    },
                    'extcredits1' => function ($result) {
                        return $result->commonUserCount->extcredits1;
                    },
                    'extcredits2' => function ($result) {
                        return $result->commonUserCount->extcredits2;
                    },
                    'is_follow' => function ($result) use ($userid, $uid) {
                        return Follow::getFollowStatus($userid, $uid);
                    },
                    'follownums' => function ($result) use ($userid) {
                        return Follow::getFollowCount($userid);
                    },
                    'fansnums' => function ($result) use ($userid) {
                        return Follow::getFansCount($userid);
                    },
                    'plid' => function () use ($userid, $uid) {
                        $list = PmLists::findOne(["min_max" => PmLists::convertUserId($userid, $uid)]);
                        if (empty($list)) {
                            return 0;
                        }
                        return $list->plid;
                    },
                    'dogs' => function ($result) {
                        $doglist = [];
                        if (!empty($result->userDogs)) {
                            foreach ($result->userDogs as $key => $value) {
                                $doglist[$key]["dog_id"] = $value["dog_id"];
                                $doglist[$key]["dog_name"] = htmlspecialchars_decode($value["dog_name"]);
                                $doglist[$key]["dog_status"] = $value["dog_status"];
                                $doglist[$key]["dog_avatar"] = Pets::getPetAvatar($value["dog_id"], $value["dog_userid"]);
                            }
                        }
                        return $doglist;
                    },
                ]
            ]);
        }
        return $result;
    }

    public function checkParamForSearchUser($params)
    {
        if (empty($params)) {
            return false;
        }
        if (!isset($params["words"]) || !isset($params["page"]) || !isset($params["count"])) {
            return false;
        }
        return true;
    }


    public function searchUser($words, $page, $count, $uid)
    {
        $page = !empty($page) ? $page : 1;
        $count = !empty($count) ? ($count > 20 ? 20 : $count) : 20;
        $result = UserInfo::find()->where(["like", "mem_nickname", $words])->with(["userGroups", "users"])->limit($count)->offset(($page - 1) * $count)->all();
        if (!$result) {
            return false;
        } else {
            $result = yii\helpers\ArrayHelper::toArray($result, [
                "bbsapi\models\UserInfo" => [
                    'uid' => function ($data) {
                        return intval($data->uid);
                    },
                    'username' => function ($data) {
                        return htmlspecialchars_decode($data->mem_nickname);
                    },
                    'avatar' => function ($data) {
                        return User::getUserAvatar($data->uid);
                    },
                    'grouptitle' => function ($data) {
                        if (empty($data->userGroups)) {
                            return "";
                        }
                        return $data->userGroups["grouptitle"];
                    },
                    'is_follow' => function ($data) use ($uid) {
                        return Follow::getFollowStatus($data->uid, $uid);
                    }
                ]
            ]);
            return $result;
        }
    }

    public static function registerRobotUserinfo($username)
    {
        $mail = ["@qq.com","@163.com","@sina.com","@126.com"];
            $userip = "";//ip地址
            $password = md5("robot_goumin_password");
            $email = substr(uniqid(rand()), -rand(6,9)).array_rand($mail);
            $salt = substr(uniqid(rand()), -6);
            $passwordmd5 = md5($password . $salt);//
            $extcredits1 = mt_rand(6,201);
            $extcredits2 = mt_rand(2,104);

            $time = mt_rand(1407168000,time());

            $db = static::getDb();
            $transaction = $db->beginTransaction();
            try {
                $sql = "INSERT INTO `pre_ucenter_members` SET `secques`='', `username`='$username', `password`='$passwordmd5', `email`='$email', `regip`='hidden', `regdate`='" . $time . "', `salt`='$salt'";
                $r = self::RobotqueryExecute($sql);

                // $db->createCommand($sql1)->execute();

                $uid = static::getDb()->getLastInsertID("pre_ucenter_members");
                // if($uid<=0){
                //     return false;
                // }

                $sql = "REPLACE INTO `pre_ucenter_memberfields` SET uid='$uid'";
                $r = self::RobotqueryExecute($sql);
                // if($r === false){
                //     return false;
                // }

                $password = md5(self::Robotrandom(10));
                $sql = "REPLACE INTO `pre_common_member` SET `uid`=$uid,`username`='$username',`password` = '$password',`email`='$email',`adminid` = '0',`groupid` = '10',`regdate` = '" . $time . "',`emailstatus` = '0',`credits` = '".$extcredits1."',`timeoffset` = '9999' ";
                $r = self::RobotqueryExecute($sql);


                $sql = "REPLACE INTO `pre_common_member_status` SET `uid` = '$uid',`regip` = '$userip',`lastip` = '$userip',`lastvisit` = '" . $time . "',`lastactivity` = '" . $time . "',`lastpost` = '0',`lastsendmail` = '0'";
                $r = self::RobotqueryExecute($sql);


                $sql = "REPLACE INTO `pre_common_member_count` SET `uid` = '$uid',`extcredits1`='$extcredits1',`extcredits2`='$extcredits2'";
                $r = self::RobotqueryExecute($sql);


                $sql = "REPLACE INTO `pre_common_member_profile` SET `uid` = '$uid',`nickname` = '$username'";
                $r = self::RobotqueryExecute($sql);


                $sql = "REPLACE INTO `pre_common_member_field_forum` SET `uid` = '$uid'";
                $r = self::RobotqueryExecute($sql);


                $sql = "REPLACE INTO `pre_common_member_field_home` SET `uid` = '$uid'";
                $r = self::RobotqueryExecute($sql);


                $datetime = date("Ymd");
                $sql = "INSERT INTO `pre_common_statuser` SET `uid` = '$uid',`daytime` = '$datetime',`type` = 'login'";
                $r = self::RobotqueryExecute($sql);

                // $db->createCommand($sql2)->execute();


                $sql = "SELECT COUNT(*) FROM `pre_common_stat` WHERE `daytime` = '$datetime'";
                $r = self::RobotqueryExecute($sql);
                if ($r) {
                    self::RobotqueryExecute("UPDATE `pre_common_stat` SET `register` = `register`+1 WHERE `daytime` = '$datetime'");
                } else {
                    self::RobotqueryExecute("INSERT INTO `pre_common_stat` SET `daytime` = '$datetime',`register` = 1");
                }

                // 创建用户中心数据
                // dogucenter 数据库  nd_user数据表
                $user_id = $uid;
                $user_name = $username;
                $user_pass = $password;
                $user_nickname = $username;
                $user_server_id = 1;
                $user_cdate = $time;
                $user_email = $email;
                $dogname_temp = $username . "的狗狗";

                // 创建一个新狗狗
                $sql = "insert into dog_doginfo(dog_userid,dog_name,dog_species,dog_birth_y,dog_birth_m,dog_birth_d,dog_gender,dog_cdate) values ('" . $uid . "','" . $dogname_temp . "','".mt_rand(1,160)."','" . mt_rand(1980,2000) . "','" . mt_rand(1,12) . "','" . mt_rand(1,28) . "','".mt_rand(1,2)."',".$time.")";
                $r = self::RobotqueryExecute($sql);

                $user_dog_id = static::getDb()->getLastInsertID("dog_doginfo");
                $user_dog_name = $dogname_temp;
                $mem_dog_default = $user_dog_id;
                // $sql = "insert into nd_user(`user_id`,`user_name`,`user_pass`,`user_nickname`,`user_dog_id`,`user_dog_name`,`user_server_id`,`user_cdate`,`user_email`) values ('".$user_id."','".$user_name."','".$user_pass."','".$user_nickname."','".$user_server_id."','".$user_cdate."','".$user_email."','".$user_dog_id."','".$user_dog_name."')";
                $sql = "insert into nd_user(`user_id`,`user_name`,`user_pass`,`user_nickname`,`user_dog_id`,`user_dog_name`,`user_server_id`,`user_cdate`,`user_email`) values ('" . $user_id . "','" . $user_name . "','" . $user_pass . "','" . $user_nickname . "','" . $user_dog_id . "','" . $user_dog_name . "','" . $user_server_id . "','" . $user_cdate . "','" . $user_email . "')";
                $command = static::getUcenterdb()->createCommand($sql);
                $r = $command->execute();

                $sql = "INSERT IGNORE INTO supe_userspaces (
                uid, username, dateline, province, city )
                VALUES (
                '$uid', '$username', '$time', '未知', '')";
                $r = self::RobotqueryExecute($sql);

                $sql = "INSERT INTO dog_member (
                uid, mem_nickname,mem_dog_default,mem_gender,mem_birth_y,mem_birth_m,mem_birth_d )
                VALUES (
                '$uid', '$username','$mem_dog_default',".mt_rand(1,2).",".mt_rand(1980,2000).",".mt_rand(1,12).",".mt_rand(1,28)." )";
                $r = self::RobotqueryExecute($sql);

                $sql = "INSERT INTO dog_member_info (user_id) VALUES ('$uid')";
                $r = self::RobotqueryExecute($sql);

                // 创建一个新相册
                $sql = "INSERT INTO dog_album (
                abm_userid, abm_title, abm_cdate )
                VALUES (
                '$uid', '新相册', '$time' )";
                $r = self::RobotqueryExecute($sql);

                $transaction->commit();
                return [$uid,$mem_dog_default];//返回用户注册后的uid
            } catch (Exception $e) {
                $transaction->rollBack();
                return false;
            }
    }    

    public static function RobotqueryExecute($sql)
    {
        $command = static::getDb()->createCommand($sql);
        $r = $command->execute();
        return $r;
    }

    protected static function Robotrandom($length, $numeric = 0)
    {
        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        if ($numeric) {
            $hash = sprintf('%0' . $length . 'd', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
            $max = strlen($chars) - 1;
            for ($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
    }


    /**
     * @params username password
     * @return uid
     */
    public static function createNewUser($username, $password, $email, $ip = "", $phone="")
    {
        
        $salt = substr(uniqid(rand()), -6);
        $time = time();

        if (!empty($phone)) {
            $nickname = '手机('.substr_replace($phone,'****',3,4).')';
            $nickname = static::createAccountNickname($nickname);
        } else {
            $nickname = $username;
        }

        $db = static::getDb();
        $transaction = $db->beginTransaction();
        try {
            $user = new self;
            $user->username = $username;
            $user->password = md5(md5($password).$salt);
            $user->email = $email;
            $user->phone = $phone;
            $user->regip = $ip;
            $user->regdate = $time;
            $user->salt = $salt;
            if ($user->save()){
                $memberFields = new UcenterMemberFields;
                $memberFields->uid = $user->uid;
                $memberFields->save();

                $commonMember = new CommonMember;
                $commonMember->uid = $user->uid;
                $commonMember->username = $username;
                $commonMember->password = md5($password);
                $commonMember->email = $email;
                $commonMember->groupid = self::REGISTER_USER_GROUP_ID;
                $commonMember->regdate = $time;
                $commonMember->timeoffset = '9999';
                $commonMember->save(false);
                // print_r($commonMember);exit;

                $commonMemberStatus = new CommonMemberStatus;
                $commonMemberStatus->uid = $user->uid;
                $commonMemberStatus->regip = $ip;
                $commonMemberStatus->lastip = $ip;
                $commonMemberStatus->lastvisit = $time;
                $commonMemberStatus->lastactivity = $time;
                $commonMemberStatus->save();
                $commonMemberCount = new CommonMemberCount;
                $commonMemberCount->uid = $user->uid;
                $commonMemberCount->save();

                $commonMemberProfile = new CommonMemberProfile;
                $commonMemberProfile->uid = $user->uid;
                $commonMemberProfile->nickname = $username;
                $commonMemberProfile->save();

                $commonMemberFieldForum = new CommonMemeberFieldForum;
                $commonMemberFieldForum->uid = $user->uid;
                $commonMemberFieldForum->save();

                $commonMemberFieldHome = new CommonMemberFieldHome;
                $commonMemberFieldHome->uid = $user->uid;
                $commonMemberFieldHome->save();

                $commonStatuser = new CommonStatuser;
                $commonStatuser->uid = $user->uid;
                $commonStatuser->type = 'login';
                $commonStatuser->daytime = date("Ymd",$time);
                $commonStatuser->save();

                $commonStat = CommonStat::find()->where(["daytime"=>date("Ymd",$time)])->one();
                if ($commonStat) {
                    $commonStat->register = $commonStat->register + 1;
                } else {
                    $commonStat = new CommonStat;
                    $commonStat->register = 1;
                    $commonStat->daytime = date("Ymd",$time);
                }
                $commonStat->save();

                $doginfo = new Pets;
                $doginfo->dog_userid = $user->uid;
                $doginfo->dog_name = $nickname."的狗狗";
                $doginfo->dog_species = 60;
                $doginfo->dog_birth_y = date("Y");
                $doginfo->dog_birth_m = date("m");
                $doginfo->dog_birth_d = date("d");
                $doginfo->dog_gender = 2;
                $doginfo->dog_cdate = $time;
                $doginfo->save();

                $dogucenterdb = static::getUcenterdb();
                $dogucenter_transaction = $dogucenterdb->beginTransaction();
                try {
                    $nduser = new NdUser;
                    $nduser->user_id = $user->uid;
                    $nduser->user_name = $username;
                    $nduser->user_pass = md5($password);
                    $nduser->user_nickname = $username;
                    $nduser->user_dog_id = $doginfo->dog_id;
                    $nduser->user_dog_name = $nickname."的狗狗";
                    $nduser->user_server_id = 1;
                    $nduser->user_cdate = $time;
                    $nduser->user_email = $email;
                    $nduser->save();
                    $dogucenter_transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollBack();
                    $dogucenter_transaction->rollBack();
                    Yii::error("create user dog ucenter db insert fail with exception :".$e->getMessage(),"register");
                    return false;                   
                }

                $supeUserspaces = new SupeUserspaces;
                $supeUserspaces->uid = $user->uid;
                $supeUserspaces->username = $username;
                $supeUserspaces->dateline = $time;
                $supeUserspaces->province = '未知';
                $supeUserspaces->city = '';
                $supeUserspaces->save();

                $userinfo = new UserInfo;
                $userinfo->uid = $user->uid;
                $userinfo->mem_nickname = $nickname;
                $userinfo->mem_dog_default = $doginfo->dog_id;
                $userinfo->save();

                $commonMemberInfo = new CommonMemberInfo;
                $commonMemberInfo->user_id = $user->uid;
                $commonMemberInfo->save();

                $dogAlbum = new DogAlbum;
                $dogAlbum->abm_userid = $user->uid;
                $dogAlbum->abm_title = '新相册';
                $dogAlbum->abm_cdate = $time;
                $dogAlbum->save();
            }
            $transaction->commit();
            return $user->uid;//返回用户注册后的uid
        } catch (Exception $e) {
            Yii::warning("create user dog db insert fail with exception :".$e->getMessage(),"register");
            $transaction->rollBack();
            return false;
        }
    }

    public function getCommonMemberProfile()
    {
        return $this->hasOne(CommonMemberProfile::className(),["uid"=>"uid"]);
    }

    public function getUserTag()
    {
        return $this->hasMany(\backend\models\lingdang\UserTags::className(),["id"=>"tag_id"])->viaTable('usertag',["uid"=>"uid"]);
    }

    // public function getUsertags()
    // {
    //     return $this->hasMany(UserTag::className(),['uid'=>'uid']);
    // }

    public static function getUserByUsernameOrEmail($um){
        return  User::find()->where(["username"=>$um])->limit(1)->one();
    }

    public static function checkUserPassword($userpassword, $user)
    {
        if (md5(md5($userpassword).$user->salt) == $user->password) {
            return true;
        }
        return false;
    }

    public static function checkedUserName($username)
    {
        if (preg_match("/^[a-zA-Z]{1}([a-zA-Z0-9]|[._]){3,15}$/", $username)) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkUserEmail($email)
    {
        if (preg_match("/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/i", $email)) {
            return true;
        } else{
            return false;
        }
    }

    public static function checkUserEmailIsset($email)
    {
        return User::findOne(["email"=>$email]) !== null ? true : false;
    }

    public static function checkUserNameIsset($username)
    {
        return User::findOne(["username"=>$username]) !== null ? true : false;
    }

    public static function checkNickNameIsset($nickname)
    {
        return UserInfo::findOne(["mem_nickname"=>$nickname]) !== null ? true : false;
    }

    public static function checkUserPasswordLenght($password)
    {
        $lenght = strlen($password);
        if ($lenght > static::PASS_LENGTH_TOO_SHORT || $lenght < static::PASS_LENGTH_TOO_LONG ) {
            return true;
        }
        return false;
    }

    public function getUserPoints()
    {
        return $this->hasOne(UserPointsTotal::className(), ["uid"=>"uid"]);
    }

    public function getPoints()
    {
        if ($this->userPoints) {
            return $this->userPoints->points;
        }
        return 0;
    }

    public function getBio()
    {
        if ($this->commonMemberProfile) {
            return $this->commonMemberProfile->bio;
        }
        return "";
    }

    public function getUserLocation()
    {
        return $this->hasOne(UserLocation::className(), ["uid" => "uid"]);
    }

    public function getLocation()
    {
        if ($this->userLocation) {
            return $this->userLocation->province." ".$this->userLocation->city;
        }
        return "";
    }

    public static function getUserByUid($uid)
    {
        $user = static::findOne($uid);
        if ($user != null) {
            return $user;
        }
        return null;
    }

    public function getDogids()
    {
        return Pets::find()->select("dog_id")->where(["dog_userid"=>$this->uid])->column();
    }

    public function isFans($userid)
    {
        return Follow::getFollowStatus($userid, $this->uid);
    }

    public function isFollow($userid)
    {
        return Follow::getFollowStatus($this->uid, $userid);
    }

    public static function checkConnectPlat($plat)
    {   
        $source_id = $plat == "weibo" ? 3 : ($plat == "qq" ? 2 : ($plat == "wx" ? 4 : 0));
        return $source_id; 
    }

    public static function checkConnectPlatOpenidAndToken($openid, $token)
    {
        if (empty($openid) || empty($token)) {
            return false;
        }
        return true;
    }

    public static function getPlatUserInfo($plat, $openid, $token)
    {
        return Yii::$app->platform->receiveCallbackData($plat, $openid, $token);
    }

    public static function checkUserIsRegister($openid, $source)
    {
        $platuser = DogOtherMember::find()->where(['source_id' => $openid, 'source' => $source])
                                          ->andWhere("goumin_uid != 0")
                                          ->one();
        if ($platuser == null) {
            return null;
        }
        return $platuser;
    }

    public static function getPlatUserDataForRegister($platuser)
    {
        $username = static::createAccountUsername(trim($platuser['username']));
        $password = '';
        $email = rand(100, 99999) . 'abc@goumin.com';
        return [$username, $password, $email];
    }

    public static function createAccountUsername($username){
        // $json_code = json_encode($username);
        // $json_code = preg_replace("#(\\\u[d|e][0-9a-f]{3})#ie",'',$json_code);
        // $username = json_decode($json_code);
        
        $usernames = static::find()->where("username like '$username%'")->all();
        $nd_usernames = static::getUcenterUsername($username);
        if (empty($usernames) && empty($nd_usernames)) {
            return $username;
        }

        if(!empty($usernames)){
            $code = static::generateCode();
            $new_username = $username."_".$code;
        }
        $res = static::getUcenterUsername($new_username);
        if (!empty($res)) {
            $code = static::generateCode();
            $username = $username."_".$code;
        }else{
            $username = $new_username;
        }
        return $username;
    }

    public static function createAccountNickname($nickname)
    {
        $nicknames = UserInfo::find()->select(['mem_nickname'])->where("mem_nickname like '$nickname%'")->column();
        if (empty($nicknames)) {
            return $nickname;
        }

        $num = static::getUniqidNum($nicknames);
        if ($num) {
            return $nickname."($num)";
        }
        return $nickname."(1)";

    }

    public static function getUniqidNum($usernames)
    {
        $nums = [];
        foreach ($usernames as $username) {
            preg_match("/\((\d)\)/", $username, $match);
            if (!empty($match)) {
                $nums[] = $match['1'];
            }
        }

        if (empty($nums)) {
            return false;
        }
        return max($nums) + 1;
    }

    public static function generateCode($length=4) {
        $chars = "abcdefghijklmnpqrstuvwxyz023456789";
        $leng = strlen($chars);
        mt_srand((double)microtime(true)*1000000);
        $i = 0;
        $pass = '' ;

        while ($i < $length) {
            $num = mt_rand() % $leng;
            $tmp = substr($chars, $num, 1);
            $pass .= $tmp;
            $i++;
        }
        return $pass;
    }

    /*public static function updateInfo($uid, $nickname, $gender, $bio, $birthday, $tags, $email, $qq, $province, $city)*/
    public static function updateInfo($uid, $nickname, $gender, $bio, $birthday, $tags, $qq, $province, $city)
    {

        $db = static::getDb();
        $transaction = $db->beginTransaction();
        try {
            /*Static::updateAll(["email"=>$email],["uid"=>$uid]);*/
            UserInfo::updateAll(["mem_nickname" => $nickname, 
                                 "mem_gender" => $gender, 
                                 "mem_birth_y" => date("Y",$birthday), 
                                 "mem_birth_m" => date("m",$birthday), 
                                 "mem_birth_d" => date("d",$birthday),
                                 "contact_qq"  => $qq,
                                 "mem_province"=>$province,
                                 "mem_city"  => $city,
                                 ],["uid" => $uid]);
            if (!empty($tags) && is_array($tags)) {
                $tags = rtrim(implode($tags, ","),",");
            }
            CommonMemberProfile::updateAll(["bio" => $bio, 
                                            "nickname"=>$nickname, 
                                            "birthyear" => date("Y",$birthday),
                                            "birthmonth" => date("m",$birthday),
                                            "birthday"=> date("d",$birthday),
                                            "qq" => $qq,
                                            "tags"=>$tags],['uid' => $uid]);
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::error("create user dog ucenter db insert fail with exception :".$e->getMessage(),"register");
            return false; 
        }


    }

    public function getTags()
    {
        if (empty($this->commonMemberProfile->tags)) {
            return [];
        }
        return explode(",", $this->commonMemberProfile->tags);

    }

    public function getDaycount()
    {
        $created = EventLog::find()->select("created")->where(["uid"=>$this->uid])->limit(1)->scalar();
        if ($created) {
            return strtotime($created);
        }
        return 0;
    }

    public function getVideocount()
    {
        return EventLog::find()->where(["uid"=>$this->uid,"type"=>EventLog::EVENT_TYPE_VIDEO,"status"=>EventLog::EVENT_LOG_STATUS])->count();
    }

    public function getDiarycount()
    {
        return EventLog::find()->where(["uid"=>$this->uid,"type"=>EventLog::EVENT_TYPE_DIARY,"status"=>EventLog::EVENT_LOG_STATUS])->count();
    }

    public function getThreadcount()
    {
        return EventLog::find()->where(["uid"=>$this->uid,"type"=>EventLog::EVENT_TYPE_POST,"status"=>EventLog::EVENT_LOG_STATUS])->count();
    }

    public static function getByPhone($phone)
    {
        return static::find()->where(["phone"=>"$phone"])->limit(1)->one();
    }

    public static function bindPhone($phone, $user, $password = "")
    {
        if ($user instanceof static) {
            $user->phone = $phone;
            if (!empty($password)) {
                $user->password = md5(md5($password).$user->salt);
            }
            if ($user->update()){
                return true;
            }
        }
        return false;
    }

    public static function updatePassword($user,$password)
    {
        if ($user instanceof static) {
            $user->password = md5(md5($password).$user->salt);
            if ($user->save()){
                return true;
            }
        }
        return false;
    }

    public function renewLoginkey()
    {
        $this->login_key =  \Yii::$app->getSecurity()->generateRandomString();
        return $this->save(false);
    }

    /**
     * user reward
     * @param int uid , reward for this user
     * @param int type, user operation type
     * @param boolean islimit, whether to limit the number of awards
     *
     * return boolean
     */
    public static function getReward($uid,$type,$islimit=true)
    {
        $credit = true;
        $points = $integral = $goumin = 0;
        $creditrule = CreditRule::findOne($type);
        if(empty($creditrule)){
            $credit = false;
            return [$credit,$points=0,$integral=0,$goumin=0];
        }
        if ($islimit) {
            $credit = CreditLimitLog::limitAddNums($uid,$type,$creditrule->extcredits3);
        }
        if ($credit) {
            $points = $creditrule->points;
            $integral = $creditrule->extcredits1;
            $goumin = $creditrule->extcredits2;
        }
        return [$credit,$points,$integral,$goumin];
    }

    // User information is complete
    public static function isUserInfoCompleteByUid($uid)
    {
        // $userInfo  = [];
        $user_info = UserInfo::findOne(['uid'=>$uid]);
        $member_profile = CommonMemberProfile::findOne(['uid'=>$uid]);
        if ($user_info) {
            if (!empty($user_info->mem_nickname) && 
                !empty($user_info->mem_gender) && 
                !empty($user_info->mem_province) && 
                !empty($user_info->mem_city) &&
                !empty($member_profile->bio))
            {
                 return true;
            }
        }
        return false;
    }

    // Pet information is complete
    public static function isPetInfoCompleteByUid($uid)
    {
        $petInfo = [];
        $pet_info = Pets::find()->where(['dog_userid'=>$uid])->all();
        if ($pet_info) {
            foreach ($pet_info as $item) {
                if (!empty($item->dog_name) && 
                    !empty($item->dog_headid) && 
                    !empty($item->dog_birth_y) && 
                    !empty($item->dog_birth_m) &&
                    !empty($item->dog_gender) && 
                    !empty($item->dog_species))
                {
                    return true;
                }
            }
        }
        return false;
    }

    // User is bind phone
    public static function isBindPhoneByUid($uid)
    {
        $user= User::find()->select('phone')->where(['uid'=>$uid])->One();
        if ($user) {
            if (!empty($user->phone)) {
                return true;
            }
        }
        return false;
    }

    // Completion of user information
    public static function getUserComplete($uid)
    {
        $num = 0;
        $user_status = static::isUserInfoCompleteByUid($uid);
        $pet_status = static::isPetInfoCompleteByUid($uid);
        $phone_status = static::isBindPhoneByUid($uid);
        $user_avatar = static::getUserAvatarComplete($uid);

        //new rule
        if ($user_status) {
            $num += 25;
        }
        if ($pet_status) {
            $num += 25;
        }
        if ($phone_status) {
            $num += 25;
        }
        if ($user_avatar) {
            $num += 25;
        }
        return $num;
    }

    public static function isRewardByUid($uid,$schedule)
    {
        //If the user information is complete not reward
        if ($schedule==100) {
            return false;
        }

        $status = 0;
        $user_status = static::isUserInfoCompleteByUid($uid);
        $pet_status = static::isPetInfoCompleteByUid($uid);
        $phone_status = static::isBindPhoneByUid($uid);
        
        if ($user_status) {
            $status += 1;
        }
        if ($pet_status) {
            $status += 1;
        }
        if ($phone_status) {
            $status += 1;
        }

        //Whether the user has been rewarded
        $isReward = UserPointsTxn::find()->where(['entity_type'=>"user info perfect", 'uid'=>$uid])->count();
        if ($isReward==0) {
            $status += 1;
        }
        if ($status==4) {
            return true;
        }
        return false;
    }
    public static function sendUserReward($uid,$isReward)
    {   
        if ($isReward) {
            Yii::$app->deferer->defer(function() use ($uid){
                UserPoints::incomePoints($uid,User::USER_INFO_PERFECT_POINT,UserPoints::NONE_CATEGORY_ID,UserPoints::GOUMINBI_EXCHANGE_POINTS,"user info perfect","用户信息完善");
                CommonMemberCount::updateAllCounters(["extcredits2"=>0,"extcredits1"=>User::USER_INFO_PERFECT_INTEGRAL],["uid"=>$uid]);
                MobileStat::saveStat($uid,MobileStat::ACTION_REGISTER,1);
            });
        }
    }
    public static function getIsReward($uid){
        $num = static::getUserComplete($uid);
        $isReward = UserPointsTxn::find()->where(['entity_type'=>"user info perfect", 'uid'=>$uid])->count();
        if($isReward >= 4 && $num == 100){
            return [1,$num];
        }
        return [0,$num];
    }


    public function getRauthInfo()
    {
        return $this->hasOne(Rauthentication::className(),['uid'=>'uid'])->where(['status'=>1]);
    }

    public function getUserExtend()
    {
        $rauth_info = null;
        if ($this->rauthInfo) {
            $rauth_info = [
                'rid' => $this->rauthInfo->id,
                'real_name' => $this->rauthInfo->real_name,
                'type' => $this->rauthInfo->type,
                'company' => $this->rauthInfo->company,
                'positional' => $this->rauthInfo->positional,
                'service'=>$this->rauthInfo->service,
                'expert' => $this->rauthInfo->expertInfo,
            ];
        }
        return [
            'rauth_info' => $rauth_info,
        ];
    }
    public function getRole()
    {
        if(!class_exists('Rauthentication')){
           return null;
        }
        return $this->hasOne(Rauthentication::className(), ['uid' => 'uid'])->andWhere(["status" => 1]);
    }
/*
 * $param
 * - int :userid
 * - object :user object
 */
    public static function getBasicInfo($param)
    {
        $userInfo = [];

        $userObj = self::takeUserObj($param);
        if(!$userObj){
            return false;
        }

        $userInfo['id'] = $userObj->uid;
        $userInfo['user_id'] = $userObj->uid;
        $userInfo['nickname'] = $userObj->nickname;
        $userInfo['grouptitle'] = $userObj->grouptitle;
        $userInfo['user_extend'] = $userObj->userExtend;
        $userInfo['gender'] = $userObj->gender;
        $hp = $userObj->headPicture;
        if ($hp) {
            $userInfo['avatar'] = Yii::$app->params['diaryDomain'] . $hp->image;
        }else{
            $userInfo['avatar'] =  isset($userObj->headImage) ? $userObj->headImage->getUrl() : 'http://s.goumin.com/imgs/cover-s.jpg';
        }
        //$userInfo['avatar'] =  $userObj->headPicture? Yii::$app->params['diaryDomain'].$userObj->headPicture->image : '';

        return $userInfo;
    }
    /*
     * $param
     * - int :userid
     * - object :user object
     */
    public static function getDetailInfo($param)
    {
        $userInfo = [];

        $userObj = self::takeUserObj($param);
        if(!$userObj){
            return false;
        }

        $userInfo = self::getBasicInfo($userObj);

        $userInfo['phone'] = $userObj->phone;
        $userInfo['username'] = $userObj->username;
        $userInfo['email'] = $userObj->email;
        $userInfo['role_id'] = ($userObj->role)? $userObj->role->type : -1;

        return $userInfo;
    }
    /*
     * $param
     * - int :userid
     * - object :user object
     */
    public static function getExpandInfo($param)
    {
        $userInfo = [];
        
        $userObj = self::takeUserObj($param);
        if (!$userObj) {
            return false;
        }

        $userInfo = self::getBasicInfo($userObj);
        $userInfo['role_id'] = ($userObj->role)? $userObj->role->type : -1;

        $userInfo['grouptitle'] = $userObj->grouptitle;
        $userInfo['level'] = $userObj->grouplevel;

        $userInfo['extcredits1'] = $userObj->commonUserCount->extcredits1;
        $userInfo['extcredits2'] = $userObj->commonUserCount->extcredits2;

        $userInfo['points'] = $userObj->points;
        $userInfo['follownums'] = intval(Follow::getFollowCount($userObj->uid));
        $userInfo['fansnums'] = intval(Follow::getFansCount($userObj->uid));

        $userInfo['bio'] = $userObj->bio;
        $userInfo['location'] = $userObj->location;
        $userInfo['dogids'] = Pets::find()->select("dog_id")->where(["dog_userid" => $userObj->uid])->column();

        $userInfo['tags'] = $userObj->tags;
        $userInfo['birthday'] = !$userObj->birthday ? 0 : $userObj->birthday;

        $userInfo['qq'] = $userObj->qq;
        $userInfo['province'] = empty($userObj->province) ? "" : $userObj->province;
        $userInfo['city'] = empty($userObj->city) ? "" : $userObj->city;

        return $userInfo;

    }

    public static function getSingleCustomization($param, $keys)
    {
        $userInfo = [];
        $userObj = self::takeUserObj($param);
        if (!$userObj) {
            return false;
        }
        return \common\models\CommonUser::takeSingleCustomizationForBBSapiUser($userObj, $keys);
    }

    public static function takeUserObj($param){
        $userObj = $param;
        if(!is_object($param)){
            $userObj = self::findOne($param);
        }
        return $userObj;
    }

    public static function takeMultiUserObj($param){
        $userObj = $param;
        if(!is_object($param)){
            $userObj = self::findAll($param);
        }
        return $userObj;
    }

    public static function getMultiCustomization($uids, $keys)
    {
        return \common\models\CommonUser::takeMultiCustomizationForBBSapiUser($uids, $keys);
    }
    public static function getUserInfoByUid($user_id,$uid){
        $user = User::getUserByUid($user_id);
        if($user){
            $result = yii\helpers\ArrayHelper::toArray($user,[
                "bbsapi\models\User" => [
                    "userinfo" => function($data){
                        return User::getBasicInfo($data->uid);
                    },
                    "province" => "province",
                    "city" => "city",
                    "fans_num" => function ($data){
                        return intval(Follow::getFansCount($data->uid));
                    },
                    "is_follow" => function($data)use($uid){
                        return $status = empty($uid) ? 0 : intval(Follow::getFollowStatus($data->uid, $uid));
                    },
                    'welluser_id' => function($data){
                        return '';
                    },
                    'type' => function($data){
                        return null;
                    },
                    'desc' => function($data){
                        return '';
                    },
                    'alias' => function($data){
                        return '';
                    },
                ]
            ]); 
            return $result;
        }else{
            return null;
        }
    }
    public static function getPetUsers($species,$count,$uid){
        $users = self::find()
            ->joinWith(['userDogs' => function($query)use($species){
                $query->andWhere(['dog_species' => $species]);
            }])
            ->where(['<>','uid',$uid])
            ->limit($count)
            ->all();
        return $users;
    }

    public static function getUserAvatarComplete($uid)
    {
        $userInfo  = [];
        $user_info = UserInfo::findOne(['uid'=>$uid]);
        $user_avatar = Upload::findOne(['uid' => $uid, 'type' => 5]);
        if ($user_info) {
            if (!empty($user_avatar))
            {
                 return true;
            }
        }
        return false;
    }
    public static function getOtherIntegralTask($uid){
        $todayNum1 = $todayNum2 = $todayNum3 = $todayNum4 = 0;
        $is_finish1 = $is_finish2 = $is_finish3 = $is_finish4 = 0;

        $log1 = User::isBindPhoneByUid($uid);
        if($log1){
            $todayNum1 = 1;
            $is_finish1 = 1;
        }
        $log2 = User::getUserAvatarComplete($uid);
        if($log2){
            $todayNum2 = 1;
            $is_finish2 = 1;
        }
        $log3 = User::isPetInfoCompleteByUid($uid);
        if($log3){
            $todayNum3 = 1;
            $is_finish3 = 1;
        }
        $log4 = User::isUserInfoCompleteByUid($uid);
        if($log4){
            $todayNum4 = 1;
            $is_finish4 = 1;
        }
        $result = [
            [
                'type'=>20,
                'rulename'=>'绑定手机号',
                'integral'=>10,
                'num'=>1,
                'todayNum'=>$todayNum1,
                'is_finish'=>$is_finish1,
            ],
            [
                'type'=>21,
                'rulename'=>'上传头像',
                'integral'=>10,
                'num'=>1,
                'todayNum'=>$todayNum2,
                'is_finish'=>$is_finish2,
            ],
            [
                'type'=>22,
                'rulename'=>'维护宠物信息',
                'integral'=>10,
                'num'=>1,
                'todayNum'=>$todayNum3,
                'is_finish'=>$is_finish3,
            ],
            [
                'type'=>23,
                'rulename'=>'完善个人资料',
                'integral'=>10,
                'num'=>1,
                'todayNum'=>$todayNum4,
                'is_finish'=>$is_finish4,
            ],
        ];
        return $result;
    }
}
