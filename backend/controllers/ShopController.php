<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\OrderBack;
use backend\components\BackendController;
use backend\models\CustomerBack;
use common\models\Balance;
use backend\models\BalanceBack;
use backend\models\TotalBalanceBack;
class ShopController extends BackendController
{
	public function actionIndex(){
		$model = new OrderBack;
		$dataProvider = $model->search(Yii::$app->request->getQueryParams());		
		return $this->render('index',[
                	'model' => $model,
	                'dataProvider' => $dataProvider,
        	]);
	}


	public function actionStatus($id){
		 $models = OrderBack::findOne($id);
		 $total = TotalBalanceBack::find()->where(['uid'=>$models->shop_uid])->one();
		 if($models->commission>$total->total_account){
			Yii::$app->session->setFlash('error','余额不足,请尽快联系商家充值');
                        return $this->redirect("/shop/index");
		 }else if($models->price==0){
			Yii::$app->session->setFlash('error','请先设置订单金额');
			return $this->redirect("/shop/index");
		 }else if($models->url==""){
			Yii::$app->session->setFlash('error','确认商家是否上传合同图片证明');
                        return $this->redirect("/shop/index");
		 }else{
	         	if ($models->status==0) {
	        		$models->status = 1;
	        	    	if($models->save()){
					$customer = CustomerBack::find()->where(['id'=>$models->uid])->one();
					$customer->status=2;
					$customer->save();
		                        $result = BalanceBack::find()->where(['uid'=>$models->shop_uid])->orderBy(['created'=>SORT_DESC])->limit(1)->one();
                		        if($result){
                                		$total_money = $result->account;
		                        }
                		        $Balance = new BalanceBack;
		                        $Balance->uid = $models->shop_uid;
                		        $Balance->balance_des = "交易佣金扣除";
		                        $Balance->account = $total_money - $models->commission;
                		        $Balance->detail = "-".$models->commission."元";
		                        $Balance->save();
                		        //$total = TotalBalanceBack::find()->where(['uid'=>$models->shop_uid])->one();
		                        $total->total_account -= $models->commission;
                		        $total->save();

					$result = BalanceBack::find()->where(['uid'=>$models->shop_uid])->orderBy(['created'=>SORT_DESC])->limit(1)->one();
                                        if($result){
                                                $total_money = $result->account;
                                        }
                                        $Balance = new BalanceBack;
                                        $Balance->uid = $models->shop_uid;
                                        $Balance->balance_des = "签约成功,返还抢单扣费";
                                        $Balance->account = $total_money + 100;
                                        $Balance->detail = "+100元";
                                        $Balance->save();
                                        //$total = TotalBalanceBack::find()->where(['uid'=>$models->shop_uid])->one();
                                        $total->total_account += 100;
                                        $total->save();

					$resultData = OrderBack::find()->where(['uid'=>$models->uid,'status'=>0])->all();
					foreach($resultData as $k => $v){
						$model = new BalanceBack;
	                                        $result = BalanceBack::find()->where(['uid'=>$v->shop_uid])->orderBy(['created'=>SORT_DESC])->limit(1)->one();
        	                                $total_money = $result->account;
                	                        $model->uid = $v->shop_uid;
                        	                $model->balance_des = "交易失败返还";
                                	        $model->account = $total_money + 50;
                                        	$model->detail = "+50元";
	                                        $total = TotalBalanceBack::find()->where(['uid'=>$v->shop_uid])->one();
        	                                $total->total_account += 50;
						$OrderStatus = OrderBack::find()->where(['shop_uid'=>$v->shop_uid])->one();
						$OrderStatus->status=2;
						$OrderStatus->save();
                	                        $model->save();
                        	                $total->save();
					}
	         	        	return $this->redirect("/shop/index");
						
		            	}
			}
		}
	}
	
	
	
	public function actionPrice($id){
		$model = OrderBack::findOne($id);
		if($model->status==1){
			 Yii::$app->session->setFlash('error','已签约不能重复设置订单金额');
                         return $this->redirect("/shop/index");
		}else if($model->status==2){
			Yii::$app->session->setFlash('error','未签约不能设置订单金额');
                         return $this->redirect("/shop/index");
		}
		if (Yii::$app->request->isPost) {
            		$post = Yii::$app->request->post();
            		if($model->updatePrice($post)){
            			Yii::$app->session->setFlash('success','设置成功');
	            		return $this->redirect('/shop/index');
                	}
            	}
                return $this->render('price',[
                        'model' => $model,
                ]);
	}

}



?>
