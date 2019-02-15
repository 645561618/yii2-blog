<?php
#***********************************************
#
#      Filename: PrizeController.php
#
#        Author:hx.qiang@qq.com
#   Description: ---
#        Create: 2019-01-13 12:02:00
#***********************************************

namespace frontend\controllers;

use Yii;
use yii\base\ErrorException;
use yii\web\Controller;
use frontend\components\FrontendController;
use frontend\components\QQClient;
use common\models\Label;
use common\models\Category;
use common\models\Article;
use frontend\models\comment\UserCommentFront;
use common\models\UserReplyComment;
use common\models\UserCenter;
use common\models\Common;
use common\components\Email;
use backend\models\LinksBack;
use backend\models\PrizeCodeBack;
use backend\models\WxCustomerBack;
/**
 *  * Prize controller
 */
class PrizeController extends Controller
{

	public $enableCsrfValidation = false;
	public $defaultAction = 'index';

				
	public function actionIndex(){
		$model = WxCustomerBack::findOne(1);
		if($model){
			return $this->render('index',['model'=>$model]);
		}
	}
	//奖品数组
	public function PrizeArr()
	{
		return array( 
	    		'0' => array('id' => 1, 'prize' => '1元红包', 'v' => 0,'s'=>0), 
	    		'1' => array('id' => 2, 'prize' => '2元红包', 'v' => 100,'s'=>5), 
	    		'2' => array('id' => 5, 'prize' => '5元红包', 'v' => 0,'s'=>2), 
	    		'3' => array('id' => 10, 'prize' => '10元红包', 'v' => 0,'s'=>3), 
	    		'4' => array('id' => 50, 'prize' => '50元红包', 'v' => 0,'s'=>4), 
	    		'5' => array('id' => 100, 'prize' => '100元红包', 'v' => 0,'s'=>5), 
	    		'6' => array('id' => 500, 'prize' => '500元红包', 'v' => 0,'s'=>6), 
	    		'7' => array('id' => 888, 'prize' => '888元红包', 'v' => 0,'s'=>7), 
		);
	}	
	
	//抽奖
	public function actionGetPrize()
	{
		$arr = self::PrizeArr(); 
		$res = self::getRand($arr); //根据概率获取奖项id  
			 
		$data['prize_name'] = $res['prize']; 
		$data['prize_id'] = $res['id'];//前端奖项从-1开始 
		$data['stoped'] = $res['s'];//前端奖项从-1开始 
		if(!empty($_POST['code'])){	
			$code = trim($_POST['code']);
                        $model = PrizeCodeBack::findOne(['code'=>$code]);
                        if($model){
				$model->prize = $res['prize'];
				$model->prize_id = $res['id'];
				$model->status = 1;//已抽,待领取
				$model->save(false);	
			}
		}
		echo json_encode($data);	
	}

	//抽奖规则	
	public function getRand($proArr) {   
	    $result = array();
	    foreach ($proArr as $key => $val) { 
	        $arr[$key] = $val['v']; 
	    } 
	    // 概率数组的总概率  
	    $proSum = array_sum($arr);        
	    asort($arr);
	    // 概率数组循环   
	    foreach ($arr as $k => $v) {   
	        $randNum = mt_rand(1, $proSum);   
	        if ($randNum <= $v) {   
	            $result = $proArr[$k];   
	            break;   
	        } else {   
	            $proSum -= $v;   
	        }         
	    }     
	    return $result;   
	}   

	//填写抽奖码，获取抽奖资格
	public function actionCode()
	{
		if(!empty($_POST['code'])){
			$code = trim($_POST['code']);
			$code = strip_tags($code);
			$code = htmlspecialchars($code);
			$model = PrizeCodeBack::findOne(['code'=>$code]);
			if($model){
				if($model->status == 1){//已抽奖，待领取
					echo json_encode(['status'=>2,'msg'=>'您已抽过奖，待领取','code'=>$code,'prize'=>$model->prize,'prize_id'=>$model->prize_id]);exit;
				}elseif($model->status==2){
					echo json_encode(['status'=>3,'msg'=>'您已抽过奖，待发放,请您联系客服']);exit;
				}elseif($model->status==3){
					echo json_encode(['status'=>3,'msg'=>'您的奖品已发放']);exit;
				}else{
					echo json_encode(['status'=>1,'code'=>$code]);exit;
				}
			}
		}	
		echo json_encode(['status'=>3,'msg'=>'× 抽奖码不存在']);exit;
	}

	//填写信息，领取红包，待工作人员发放
	public function actionReceivePrize()
	{
		if($_POST){
			//echo "<pre>";
			//print_r($_POST);exit;
			$code = $_POST['code'];
			$username = htmlspecialchars(strip_tags(trim($_POST['username'])));
			$phone = htmlspecialchars(strip_tags(trim($_POST['phone'])));
			$model = PrizeCodeBack::findOne(['code'=>$code]);
			if($model){
				$model->username = $username;
				$model->phone = $phone;
				$model->status = 2;//已领取,待发放
				$model->modify = time();
				if($model->save(false)){
					echo json_encode(['status'=>1]);exit;
				}
			}
			
		}
	}



}
?>
