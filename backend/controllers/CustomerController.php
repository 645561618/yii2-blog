<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\CustomerBack;
use backend\models\CustomerSearchBack;
use backend\components\BackendController;
class CustomerController extends BackendController
{
	public $enableCsrfValidation;
	
	//添加客户
	public function actionAdd()
	{
		$model = new CustomerBack;
		try{
			if(Yii::$app->request->isPost){
				$post = Yii::$app->request->post();
				$model->load($post);
				if($model->validate()){
					$model->save();
					return $this->redirect('/customer/list');
				}
			}
		}catch(\Exception $e){
			Yii::$app->session->setFlash('error',$e->getMessage());
		}
		return $this->render(
			'add',
		[
			'model'=>$model,
			'isNew' => true,
		]);		
	
	}
	
	public function actionUpdateCustomer($id){
		if($id){
			$model = CustomerBack::findOne($id);
			try{
				if(Yii::$app->request->isPost){
					if($model->updateCustomer(Yii::$app->request->post())){
						Yii::$app->session->setFlash('success', '编辑成功!');
						return $this->redirect('/customer/list');
					}
				}
			}catch(\Exception $e){
				Yii::$app->session->setFlash('error',$e->getMessage());
			}
		}
		return $this->render(
                        'add',
                [
                        'model'=>$model,
			'isNew'=> false,
                ]);  
	}
    
    	//删除
    	public function actionDeleteCustomer($id)
    	{
    		try {
    			$model = CustomerBack::findOne($id);
    			if($model->delete()){
    				Yii::$app->session->setFlash('success', '删除成功');
    				return $this->redirect('/customer/list');
	    		}
	        } catch (\Exception $e) {
        	    Yii::$app->session->setFlash('error', $e->getMessage());
	        }
    	}
	
	//客户列表
	public function actionList(){
		$model = new CustomerSearchBack;
		$dataProvider = $model->search(Yii::$app->request->getQueryParams());
		return $this->render('customer-list',
        	[
                	'model' => $model,
	                'dataProvider' => $dataProvider,
        	]);
	
	}







}
