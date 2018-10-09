<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginBack;
use common\models\LoginForm;
use common\models\Common;
use backend\model\LimitBack;
use backend\models\Log;
use yii\data\Pagination;
use backend\components\Helper;
use common\components\Email;
use common\models\login\Login;
use backend\models\ArticleBack;
use backend\models\LinksBack;
use backend\models\WxUserInfoBack;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public $sidebars=[];
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','captcha'],
			//'ips' => [ '183.135.153.30'],
                        'allow' => true,
			'roles' =>['?'],//游客用?
                    ],
                    [
                        'actions' => ['logout','index','webupload','error'],
                        'allow' => true,
                        'roles' => ['@'],//已认证登录用@
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    /*public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    */
    public function actions(){
        return [
	    'webupload' => 'yidashi\webuploader\Action',//markdown上传图片
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
		'offset'=>4,
		'minLength' => 4,
                'maxLength' => 4,
                'width'=>96,
                'height'=>38,
		'backColor'=>0xFFFFFF,
            ],
            'error'=>[
                'class'=>'yii\web\ErrorAction',
            ],
        ];
    }
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
	$id = Yii::$app->user->id;
	//后台用户数
	$userNums = Login::find()->where(['status'=>10])->count();	
	//博客文章数
	$BlogArticleNums = ArticleBack::find()->where(['status'=>2])->count();
	//微信公众号粉丝数
	$WxFollowNums = WxUserInfoBack::find()->where(['subscribe'=>1])->count();
	//友链数
	$LinksNums = LinksBack::find()->where(['status'=>1])->count();
        return $this->render('index',[
		'id' => $id,
		'userNums'=>$userNums,
		'BlogArticleNums'=>$BlogArticleNums,
		'WxFollowNums'=>$WxFollowNums,
		'LinksNums'=>$LinksNums,
	]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
	
	$this->layout = "login.php";	

	if (!\Yii::$app->user->isGuest) {
		$this->goHome();
	}

	$post = Yii::$app->request->post();
	$model = new LoginForm();
	if(!empty($post)){
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			$model->loginLog();

			$username = Yii::$app->user->identity->username;
			$ip = Yii::$app->request->userIP;
			Email::Send($ip,$username);
	
			Yii::$app->session->setFlash('success','登录成功');
		        return $this->goBack();
		}else{
	 	   	return $this->render('login', [
                		'model' => $model,
            		]);
		}
	}
	return $this->render('login', [
               'model' => $model,
        ]);


    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionError(){
	return "执行错误操作！";
    }
}
