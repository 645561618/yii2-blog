<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use backend\models\OrderBack;
use backend\models\CustomerBack;
use backend\models\BalanceBack;
use backend\models\TotalBalanceBack;
class OrderController extends Controller
{
    public function actionRun(){
	$resultData = OrderBack::find()->where(['status'=>0])->all();
	if($resultData){
                foreach ($resultData as $k => $v) {
                        $id= $v['id'];
                        $uid = $v['uid'];
			$shop_uid = $v['shop_uid'];
                        $time = $v['created'];
                        $overtime = time()-strtotime($time);
                        if($overtime>10*24*3600){
                                $order = OrderBack::findOne($id);
                                $order->status = 2;
                                $order->save();
                                $customer = CustomerBack::find()->where(['id'=>$uid])->one();
                                if($customer){
                                        $customer->status = 0;
                                        $customer->save();
					$model = new BalanceBack;
					$result = BalanceBack::find()->where(['uid'=>$shop_uid])->orderBy(['created'=>SORT_DESC])->limit(1)->one();
                                	$total_money = $result->account;
		                        $model->uid = $shop_uid;
                		        $model->balance_des = "交易失败返还";
		                        $model->account = $total_money + 50;
                		        $model->detail = "+50元";
		                        $total = TotalBalanceBack::find()->where(['uid'=>$shop_uid])->one();
                		        $total->total_account += 50;
                		        $model->save(); 
					$total->save();
					
					

                               }
                        }
                }
	}

    }

    public function actionTest()
    {
	$num = 0;
	for ($i = 1; $i <= 100; $i++) {
    		$k = 0;
    		for ($j = 1; $j < $i; $j++) {
        		if ($i % $j == 0) {
            			$k++;
        		}
    		}
    		if ($k == 1) {
        		$num +=$i;
    		}
	}
	echo $num;
    }




}
