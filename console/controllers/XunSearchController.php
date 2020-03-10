<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Common;

class XunSearchController extends Controller
{
    //添加文章索引
    public function actionRun()
    {
	$models = \common\models\Article::find()->all();
	foreach($models as $model){
		$search = \common\components\xunsearch\Search::findOne($model->id);
		if(!$search){
			$search = new \common\components\xunsearch\Search;
			$search->id = $model->id;
			$search->tid = $model->tid;
			$search->cid = $model->cid;
			$search->views = $model->views;
			$search->comment_nums = $model->comment_nums;
			$search->img = $model->img;
			$search->title = $model->title;
			$search->desc = Common::cutstr($model->desc,150);
			$search->status = $model->status;
			$search->weight = $model->weight;
			$search->created = $model->created;
			if($search->save(false)){
				echo $model->id."\n";
			}
		}

	}	
    }






}





