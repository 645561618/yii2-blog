<?php                   
namespace backend\controllers;
                
use Yii;
use backend\components\BackendController;
use yii\data\ActiveDataProvider;
use backend\models\SafeConfigBack;
use common\models\SafeRecord;
                                
class SafeController extends BackendController{
                                        
                                
        public $enableCsrfValidation;


	public function actionIndex()
	{
		$model = new SafeConfigBack();
		$dataProvider = $model->search();
		return $this->render('index',['model'=>$model,'dataProvider'=>$dataProvider]);
	}

	public function actionAdd()
	{
		$model = new SafeConfigBack();
		try{
			if(Yii::$app->request->isPost){
				$post = Yii::$app->request->post();
				$model->load($post);
				if($model->validate()){
					$model->save();
					return $this->redirect('/safe/index');
				}
			}
		}catch(\Exception $e){
			Yii::$app->session->setFlash('error',$e->getMessage());
		}
		return $this->render('add-config',['model'=>$model,'isNew'=>true]);	
		
	}

        public function actionUpdateSafe($id)
        {                       
                $model = SafeConfigBack::findOne($id);
                try{            
                        if(Yii::$app->request->isPost){
                                $post = Yii::$app->request->post();
                                $model->load($post);
                                if($model->updateSafe($post)){
					Yii::$app->cache->set("action_config".$model->type,$model->ip);
					Yii::$app->cache->set("action_nums".$model->type,$model->nums);
					Yii::$app->cache->set("action_percent".$model->type,$model->percent);
					Yii::$app->cache->set("action_length".$model->type,$model->length);
					Yii::$app->cache->set("action_words".$model->type,$model->words);
                                        return $this->redirect('/safe/index');
                                }
                        }
                }catch(\Exception $e){
                        Yii::$app->session->setFlash('error',$e->getMessage());
                }
                return $this->render('add-config',['model'=>$model,'isNew'=>false]);
                                        
        }    

	public function actionSearchRecord()
	{
		$model = new SafeRecord;
                $dataProvider = $model->search(Yii::$app->request->getQueryParams());
                return $this->render('search-record',['model'=>$model,'dataProvider'=>$dataProvider]);				
	}



}
