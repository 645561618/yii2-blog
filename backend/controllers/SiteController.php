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
use yii\data\Pagination;
use backend\components\Helper;
use common\components\Email;
use common\models\login\Login;
use backend\models\ArticleBack;
use backend\models\LinksBack;
use backend\models\WxUserInfoBack;
use backend\models\DataTotalBack;
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
                        'actions' => ['logout','index','webupload','error','get-data','get-chart'],
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


    /**
     * @Author       huangxinqiang
     * @DateTime     19-5-21 下午5:29
     * @NAME         actionGetdata
     * @description  [获取数据统计,UserNum：用户数;BlogNum:博客文章数,FansNum:微信粉丝数,LinkNum:友情链接数]
     *              
     */
    public function actionGetData() {
        $sitearr = array();
        $dateTime = date('Y-m');
        $dateString = $this->_getDate($dateTime);
        $UserNumArr = $this->getDataCount('UserNum', $dateTime);
        $BlogNumArr = $this->getDataCount('BlogNum', $dateTime);
        $FansNumArr = $this->getDataCount('FansNum', $dateTime);
        $LinkNumArr = $this->getDataCount('LinkNum', $dateTime);


        if (!empty($dateString)) {
            foreach ($dateString as $key => $date) {
                $sitearr[$date]['User'] = array(
                    'total' => $this->getAllDataCount('UserNum'),
                    'this' => isset($UserNumArr[$key]) ? $UserNumArr[$key] : 0,
                    'lastMonth' => date('n'),
                );

                $sitearr[$date]['Blog'] = array(
                    'total' => $this->getAllDataCount('BlogNum'),
                    'this' => isset($BlogNumArr[$key]) ? $BlogNumArr[$key] : 0,
                    'lastMonth' => date('n'),
                );

                $sitearr[$date]['Fans'] = array(
                    'total' => $this->getAllDataCount('FansNum'),
                    'this' => isset($FansNumArr[$key]) ? $FansNumArr[$key] : 0,
                    'lastMonth' => date('n'),
                );
                $sitearr[$date]['Link'] = array(
                    'total' => $this->getAllDataCount('LinkNum'),
                    'this' => isset($LinkNumArr[$key]) ? $LinkNumArr[$key] : 0,
                    'lastMonth' => date('n'),
                );
            }
            echo json_encode(['code' => 1, 'site' => array_reverse($sitearr)]);
            exit;
        }
        echo json_encode(['code' => 0]);
        exit;
    }

    /**
     * @Author       huangxinqiang
     * @DateTime     19-5-21 下午4:31
     * @NAME         actionGetChart
     * @description  [异步获取图表 type类别，User：用户;Blog:博客,Fans:微信粉丝,Link:友情链接]
     */
    public function actionGetChart() {
        $type = empty($_POST['type']) ? 'User' : $_POST['type'];
        $dateTime = empty($_POST['date']) ? date('Y-m') : $_POST['date'];
        if ($type == 'User') {
            $dataArr = $this->getDataCount('UserNum', $dateTime);
        } else if ($type == 'Blog') {
            $dataArr = $this->getDataCount('BlogNum', $dateTime);
        } else if($type == 'Fans') {
            $dataArr = $this->getDataCount('FansNum',$dateTime);
        } else if($type == 'Link') {
            $dataArr = $this->getDataCount('LinkNum',$dateTime);
        }
        $dateString = $this->_getDate($dateTime);
        echo json_encode(['code' => 1, 'dataArr' => $dataArr, 'dateString' => $dateString]);
        exit;
    }


    /**
     * @Author       huangxinqiang
     * @DateTime     19-5-21 下午5:30
     * @NAME         getDataCount
     * @description  [每月统计,UserNum：用户数;BlogNum:博客文章数,FansNum:微信粉丝数,LinkNum:友情链接数]
     * @param $type
     * @param $datetime
     * @param int $limit
     * @return array
     */
    public function getDataCount($type, $datetime, $limit = 12) {
        if (!empty($type)) {
            $typeString = [];
            $type_list = DataTotalBack::find()->where(['<=','datetime',$datetime])->orderBy(['datetime'=>SORT_DESC])->limit($limit)->All();
            if (!empty($type_list)) {
                $type_list = array_reverse($type_list);
                foreach ($type_list as $key => $value) {
                    $typeString[] = (int) $value->$type;
                }
            }
            return $typeString;
        }
    }


    /**
     * @Author       huangxinqiang
     * @DateTime     19-5-21 下午5:30
     * @NAME         getAllDataCount
     * @description  [总数统计,UserNum：用户数;BlogNum:博客文章数,FansNum:微信粉丝数,LinkNum:友情链接数]
     * @param $type
     * @return array
     */
    public function getAllDataCount($type) {
        if (!empty($type)) {
            return DataTotalBack::find()->sum("{$type}");
        }
        return 0;
    }



    /**
     * @Author       huangxinqiang
     * @DateTime     19-5-21 下午5:33
     * @NAME         _getDate
     * @description  [获取记录时间]
     * @param $cyd_id
     * @param $datetime
     * @param int $limit
     * @return array
     */
    public function _getDate( $datetime, $limit = 12) {
        $typeString = [];
        $type_list = DataTotalBack::find()->where(['<=', 'datetime', $datetime])->orderBy(['datetime'=>SORT_DESC])->limit($limit)->All();
        if (!empty($type_list)) {
            $type_list = array_reverse($type_list);
            foreach ($type_list as $key => $value) {
                $typeString[] = $value->datetime;
            }
        }
        return $typeString;
    } 



















}
