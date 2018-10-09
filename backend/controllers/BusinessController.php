<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\OrderBack;
use backend\components\BackendController;
use common\models\register\LoginUser;
use backend\models\FrontUser;
use common\models\User;
use backend\models\BalanceBack;
use backend\models\BalanceSearchBack;
use backend\models\TotalBalanceBack;
class BusinessController extends BackendController
{
        public function actionList(){
                $model = new FrontUser;
                $dataProvider = $model->search();
                return $this->render('list',[
                        'model' => $model,
                        'dataProvider' => $dataProvider,
                ]);
        }

	public function actionAdd(){
		$model = new User;
		if(Yii::$app->request->isPost){
			$post = Yii::$app->request->post();
			//echo "<pre>";
			//print_r($post);exit;
			$data = $post['User'];
        		$model->username = $data['username'];
		        $model->phone = $data['phone'];
        		$model->setPassword($data['password']);
	        	$model->ip = Yii::$app->getRequest()->getUserIP();
	        	if($model->save()){
        	        	return $this->redirect("/business/list");
	        	};
		}
		return $this->render('add',['model'=>$model]);
	}

	public function actionDeleteBusiness($id){
     	   	$model = LoginUser::findOne($id);
		if($model->delete()){
			Yii::$app->session->setFlash('success', '删除成功');
        		return $this->redirect("/business/list");
		}
    	}

	public function actionUpdateBusiness($id){
		$model = User::findOne($id);
                if(Yii::$app->request->isPost){
                        $post = Yii::$app->request->post();
                        $data = $post['User'];
                        $model->username = $data['username'];
                        $model->phone = $data['phone'];
                        $model->setPassword($data['password']);
                        $model->ip = Yii::$app->getRequest()->getUserIP();
                        if($model->save(false)){
                                return $this->redirect("/business/list");;
                        };
                }
                return $this->render('add',['model'=>$model]);
	}


	public function actionBalance($uid){
		$model = new BalanceBack;
		if(Yii::$app->request->isPost){
			$result = BalanceBack::find()->where(['uid'=>$uid])->orderBy(['created'=>SORT_DESC])->limit(1)->one();
			$total_money = 0;
			if($result){
				$total_money = $result->account;
			}
                        $post = Yii::$app->request->post();
                        $model->uid = $uid;
                        $model->balance_des = "账户充值";
			$model->account = $total_money + $post['BalanceBack']['account'];
			$model->detail = "+".$post['BalanceBack']['account']."元";
			$total = TotalBalanceBack::find()->where(['uid'=>$uid])->one();
			if(!$total){
				$total = new TotalBalanceBack;
				$total->total_account = $post['BalanceBack']['account'];
                        	$total->uid = $uid;	
			}else{
				$total->total_account += $post['BalanceBack']['account'];
			}
                        if($model->save(false) && $total->save(false)){
				Yii::$app->session->setFlash('success','充值成功');
                                return $this->redirect("/business/list");;
                        };
                }
		return $this->render('balance',['model'=>$model]);
	}



      public function actionBalanceList($uid){
       	        $model = new BalanceSearchBack;
		$dataProvider = $model->search(Yii::$app->request->getQueryParams(),$uid);
                return $this->render('balance-list',[
                        'model' => $model,
                        'dataProvider' => $dataProvider,
                ]);

      }



}
?>

