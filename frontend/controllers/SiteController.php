<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\User;
use common\models\register\LoginUser;
use common\models\FrontendForm;
use frontend\components\FrontendController;
use common\models\customer\Customer;
use backend\models\TotalBalanceBack;
use backend\models\FalseCustomerBack;
/**
 * Site controller
 */
class SiteController extends FrontendController
{

    public $enableCsrfValidation = false;
	

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
		'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        //'actions' => ['login','signup', 'error'],
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index','error'],
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
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
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
	

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    //抢单系统
    public function actionIndex()
    {
	$data=[];
	$result = FalseCustomerBack::find()->all();
	$nums = Customer::find()->count();
	$num = ceil($nums/4);
	$uid = Yii::$app->user->identity->id;
	$totalData = TotalBalanceBack::find()->where(['uid'=>$uid])->one();
	$total = 0;
	if($totalData){
		$total = $totalData->total_account;
	}
	$username = Yii::$app->user->identity->username;
	$model = User::findOne($uid);
	$url = Yii::$app->params['targetDomain'].$model->url;
	if(!empty($result)){
		foreach($result as $k=>$v){
			$data[] = [
				'username'=>$v->username,
				'phone'   =>substr_replace($v->phone,'******',3,6),
				'created' =>date("Y-m-d",strtotime($v->created)),
			];
		}
	}	
        return $this->render('index',['data'=>$data,'num'=>$num,'uid'=>$uid,'username'=>$username,'total'=>$total,'url'=>$url,'model'=>$model]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
	$post = Yii::$app->request->post();
        $model = new FrontendForm;
        if(!empty($post)){
                if ($model->login($post)) {
                       return $this->goBack();
                }else{
			echo "<script> {window.alert('账号或密码错误');location.href='/site/login.html'} </script>";
                }
        }else{
            return $this->render('login', [
                'model' => $model,
            ]);

        }

    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
	$post = Yii::$app->request->post();
        $model = new SignupForm();
	if(!empty($post)){
        	    if ($user = $model->signup($post)) {
                    	return $this->redirect('/site/login.html');;
            	    }
	}

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionError(){
	return "执行错误操作！";
   }




}
