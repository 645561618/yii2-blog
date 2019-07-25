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


       /**
     * @Author       huangxinqiang
     * @DateTime     19-5-21 下午4:31
     * @NAME         actionGetchart
     * @description  [异步获取图表 type类别，inquiry：询盘;productNum:产品,company:企业入驻数]
     */
    public function actionGetchart() {
        if (empty($this->cyd->id)) {
            echo json_encode(['code' => 0]);
            exit;
        }
        $type = empty($_POST['type']) ? 'inquiry' : $_POST['type'];
        $dateTime = empty($_POST['date']) ? date('Y-m') : $_POST['date'];
        if ($type == 'inquiry') {
            $dataArr = $this->_getDataCount($this->cyd->id, 'inquiryNum', $dateTime);
        } else if ($type == 'product') {
            $dataArr = $this->_getDataCount($this->cyd->id, 'productNum', $dateTime);
        } else if($type == 'company') {
            $dataArr = $this->_getDataCount($this->cyd->id, 'companyNum',$dateTime);
        }
        $dateString = $this->_getDate($this->cyd->id, $dateTime);
        echo json_encode(['code' => 1, 'dataArr' => $dataArr, 'dateString' => $dateString]);
        exit;
    }

    /**
     * @Author       huangxinqiang
     * @DateTime     19-5-21 下午5:29
     * @NAME         actionGetsitedata
     * @description  [获取数据统计,inquiryNum：询盘数;productNum:产品数,companyNum:企业入驻数
     *                  companyAllNum:询盘总数,productAllNum:产品总数,companyAllNum:入驻企业总数
     *              ]
     */
    public function actionGetsitedata() {
	echo 111;exit;
        $sitearr = array();
        $dateTime = date('Y-m');
        $dateString = $this->_getDate($dateTime);
        $inquiryArr = $this->_getDataCount($this->cyd->id, 'inquiryNum', $dateTime);
        $productArr = $this->_getDataCount($this->cyd->id, 'productNum', $dateTime);
        $companyArr = $this->_getDataCount($this->cyd->id, 'companyNum',$dateTime);

        if (!empty($dateString)) {
            foreach ($dateString as $key => $date) {
                $sitearr[$date]['inquiry'] = array(
                    'total' => $this->_getAllDataCount($this->cyd->id, 'inquiryAllNum'),
                    'this' => isset($inquiryArr[$key]) ? $inquiryArr[$key] : 0,
                    'lastMonth' => date('n',strtotime('-1 month')),
                );

                $sitearr[$date]['product'] = array(
                    'total' => $this->_getAllDataCount($this->cyd->id, 'productAllNum'),
                    'this' => isset($productArr[$key]) ? $productArr[$key] : 0,
                    'lastMonth' => date('n',strtotime('-1 month')),
                );

                $sitearr[$date]['company'] = array(
                    'total' => $this->_getAllDataCount($this->cyd->id, 'companyAllNum'),
                    'this' => isset($companyArr[$key]) ? $companyArr[$key] : 0,
                    'lastMonth' => date('n',strtotime('-1 month')),
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
     * @DateTime     19-5-21 下午5:30
     * @NAME         _getDataCount
     * @description  [该产业带下每月统计,inquiryNum：询盘数;productNum:产品数,companyNum:企业入驻数]
     * @param $cyd_id
     * @param $type
     * @param $datetime
     * @param int $limit
     * @return array
     */
    public function _getDataCount($cyd_id, $type, $datetime, $limit = 12) {
        if (!empty($cyd_id) && !empty($type)) {
            $typeString = [];
            $criteria = new CDbCriteria;
            $criteria->select = "$type";
            $criteria->condition = "cyd_id = '{$cyd_id}' AND dateTime <= '{$datetime}'";
            $criteria->order = 'dateTime DESC';
            $criteria->limit = $limit;
            $type_list = CydDataCount::model()->findAll($criteria);
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
     * @NAME         _getDataCount
     * @description  [该产业带下询盘总数统计，产品总数统计，入驻企业总数统计;inquiryAllNum:询盘总数,productAllNum:产品总数,companyAllNum:入驻企业总数]
     * @param $cyd_id
     * @param $type
     * @param $datetime
     * @param int $limit
     * @return array
     */
    public function _getAllDataCount($cyd_id, $type) {
        if (!empty($cyd_id) && !empty($type)) {
            $criteria = new CDbCriteria;
            $criteria->select = "$type";
            $criteria->condition = "cyd_id = '{$cyd_id}'";
            $res = CydAllTotal::model()->find($criteria);
            if($res){
                return $res->$type;
            }
            return 0;
        }
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
        if (!empty($cyd_id)) {
            $typeString = [];
            $type_list = ArticleBack::find()->where("created_time<=$datetime")->All();
		echo "<pre>";
print_r($type_list);exit;
            if (!empty($type_list)) {
                $type_list = array_reverse($type_list);
                foreach ($type_list as $key => $value) {
                    $typeString[] = $value->dateTime;
                }
            }
            return $typeString;
        }
    } 



















}
