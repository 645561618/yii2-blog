<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\components\FrontendController;
use common\models\customer\Customer;
use frontend\models\OrderFront;
use common\components\SubPages;
use common\models\Balance;
use backend\models\BalanceBack;
use backend\models\TotalBalanceBack;
use common\models\User;
/**
 * Order controller
 */
class OrderController extends FrontendController
{
	public $enableCsrfValidation;

	public function actionIndex(){
		$shop_uid = Yii::$app->user->identity->id;
		$count = 15;
	    	$sub_pages = 6;
	    	$pageCurrent = isset($_GET['p']) ? $_GET["p"] : 1;
	    	$num = OrderFront::find()->where(['shop_uid'=>$shop_uid])->count();
	    	$data = OrderFront::find()->where(['shop_uid'=>$shop_uid])->limit($count)->offset(($pageCurrent-1) * $count)->all();	
	    	$subPages = new SubPages($count, $num, $pageCurrent, $sub_pages, "/order/index.html?p=", 2);
      	    	$p = $subPages->show_SubPages(2);
		$totalData = TotalBalanceBack::find()->where(['uid'=>$shop_uid])->one();
		$total = 0;
		if($totalData){
			$total = ceil($totalData->total_account);
		}
		$user = User::findOne($shop_uid);
		$url = Yii::$app->params['targetDomain'].$user->url;
		return $this->render('index',
			[
				'url'=>$url,
				'user'=>$user,
				'data'=>$data,
				'username'=>Yii::$app->user->identity->username,
				'page'=>$p,
				'total'=>$total,
				'staticUrl' => Yii::$app->params['targetDomain'],	
			]);
	}


	public function actionDetail(){
		$shop_uid = Yii::$app->user->identity->id;
		$count = 15;
                $sub_pages = 6;
                $pageCurrent = isset($_GET['p']) ? $_GET["p"] : 1;
                $num = Balance::find()->where(['uid'=>$shop_uid])->count();
                $data = Balance::find()->where(['uid'=>$shop_uid])->limit($count)->offset(($pageCurrent-1) * $count)->orderBy(['created'=>SORT_DESC])->all();
                $subPages = new SubPages($count, $num, $pageCurrent, $sub_pages, "/order/detail.html?p=", 2);
                $p = $subPages->show_SubPages(2);
		$totalData = TotalBalanceBack::find()->where(['uid'=>$shop_uid])->one();
                $total = 0;
                if($totalData){
                        $total = ceil($totalData->total_account);
                }
		$user = User::findOne($shop_uid);
                $url = Yii::$app->params['targetDomain'].$user->url;
                return $this->render('detail',
			[
                                'username'=>Yii::$app->user->identity->username,
				'page'=>$p,
				'data'=>$data,
				'total'=>$total,
				'url' => $url,
				'user'=>$user,
                        ]);
        }
	
	public function actionRobOrder(){
		$data = Customer::find()->where(['status'=>[0,1]])->orderBy(['rand()' => SORT_DESC])->limit(2)->asArray()->all();
		header("Content-Type: application/json");
        	echo json_encode($data);	
	}
	
	public function actionClick($id){
		$data = [];
		$uid = Yii::$app->user->identity->id;
		$orderNums = OrderFront::find()->where(['shop_uid'=>$uid,'status'=>0])->andWhere(['>=','created',date("Y-m-d 00:00:00")])->andWhere(['<=','created',date("Y-m-d 23:59:59")])->count();
		$MoreNums = OrderFront::find()->where(['shop_uid'=>$uid,'status'=>0])->count();
		$result = Customer::find()->where(['id'=>$id])->asArray()->one();
		$total_account = 0;
		$total = TotalBalanceBack::find()->where(['uid'=>$uid])->asArray()->one();
		if($total){
			$total_account = ceil($total['total_account']);
		}
		$OrderCustomer = OrderFront::find()->where(['shop_uid'=>$uid,'uid'=>$id])->one();
		$data = [
			'orderNums'=>$orderNums,
			'result'=>$result,
			'total_account' => $total_account,
			'nums' => $result['nums'],
			'order'=>$OrderCustomer,
			'MoreNums' => $MoreNums,
		];
		header("Content-Type: application/json");
                echo json_encode($data);
	}

	public function actionOver(){
		$model = new OrderFront;
		$model->shop_uid = $_POST['shop_uid'];
		$model->uid = $_POST['uid'];
		$model->username = $_POST['username'];
		$model->phone = $_POST['phone'];
		if($model->save()){
			$customer = Customer::findOne(['id'=>$_POST['uid']]);
			$customer->status = 1;
			$customer->nums += 1;
			$customer->save();
			$result = BalanceBack::find()->where(['uid'=>$_POST['shop_uid']])->orderBy(['created'=>SORT_DESC])->limit(1)->one();
                        if($result){
                                $total_money = $result->account;
                        }
			$Balance = new BalanceBack;
                        $Balance->uid = $_POST['shop_uid'];
                        $Balance->balance_des = "抢单扣费";
                        $Balance->account = $total_money - 100;
                        $Balance->detail = "-100元";
                        $Balance->save();
                        $total = TotalBalanceBack::find()->where(['uid'=>$_POST['shop_uid']])->one();
                        $total->total_account -= 100;
			$total->save();
			
		};
		
	}



	public function actionRemark($id){
		$customer = Customer::findOne(['id'=>$id]);
		$remark = $customer->remark;
		header("Content-Type: application/json");
                echo json_encode($remark);
	}


}
