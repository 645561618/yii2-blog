<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\FalseCustomerBack;
use backend\components\BackendController;
class FalseCustomerController extends BackendController
{
        public $enableCsrfValidation;

        //添加客户
        public function actionAdd()
        {
                $model = new FalseCustomerBack;
                try{
                        if(Yii::$app->request->isPost){
                                $post = Yii::$app->request->post();
                                $model->load($post);
                                if($model->validate()){
                                        $model->save();
                                        return $this->redirect('/false-customer/add');
                                }
                        }
                }catch(\Exception $e){
                        Yii::$app->session->setFlash('error',$e->getMessage());
                }
		$dataProvider = $model->search(Yii::$app->request->getQueryParams());
                return $this->render(
                        'add',
                [
                        'model'=>$model,
                        'isNew' => true,
			'dataProvider' => $dataProvider,
                ]);

        }

        public function actionUpdateFalsecustomer($id){
                if($id){
                        $model = FalseCustomerBack::findOne($id);
                        try{
                                if(Yii::$app->request->isPost){
                                        if($model->updateCustomer(Yii::$app->request->post())){
                                                Yii::$app->session->setFlash('success', '编辑成功!');
                                                return $this->redirect('/false-customer/add');
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
        public function actionDeleteFalsecustomer($id)
        {
                try {
                        $model = FalseCustomerBack::findOne($id);
                        if($model->delete()){
                                Yii::$app->session->setFlash('success', '删除成功');
                                return $this->redirect('/false-customer/add');
                        }
                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
        }

}
