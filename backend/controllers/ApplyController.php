<?php
namespace backend\controllers;

use Yii;
use backend\components\BackendController;
use backend\models\ApplyBack;
use backend\models\ApplySearch;
use yii\data\ActiveDataProvider;


class ApplyController extends BackendController
{

   public $enableCsrfValidation;


   public function actionIndex(){
        $model = new ApplySearch;
	$dataProvider = $model->search(Yii::$app->request->getQueryParams());
	$count = $model->Count(Yii::$app->request->getQueryParams());
        return $this->render("index",['model'=>$model,'dataProvider'=>$dataProvider]);

   }



    public function actionDeleteApply($id)
    {
        try {
                $model = ApplyBack::findOne($id);
                if ($model->is_del==0) {
                    $model->is_del = 1;
                }
                if($model->save()){
                    return $this->redirect("/apply/index");
                }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    public function actionDeleteAll()
    {
        try {
                $model = new ApplyBack();
		$model->updateAll(['is_del'=>1]);
                return $this->redirect("/apply/index");
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }

    public function actionDeleteMore()
    {
        if (Yii::$app->request->isPost) {

            $comments = Yii::$app->request->post("selection");
            $result = ApplyBack::updateAll(["is_del"=>1],["id"=>$comments]);
            if ($result) {
                return $this->redirect(Yii::$app->request->getReferrer());
            }
        }
        return $this->redirect(Yii::$app->request->getReferrer());
    }



}
