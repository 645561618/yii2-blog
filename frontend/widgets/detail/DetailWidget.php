<?php

namespace frontend\widgets\detail;

/**
 * 文章详情组件
 */

use Yii;
use yii\base\Widget;
use frontend\models\article\ArticleFront;
use common\components\SubPages;
use common\models\Label;

class DetailWidget extends Widget
{

    public function run()
    {
	$id = $_GET['id'];
	$cid =$_GET['cid'];
	$model = ArticleFront::find()->where(['id'=>$id,'cid'=>$cid])->one();
	if(!$model){
		Yii::$app->session->setFlash('error','不存在该文章');
		return false;
	}
	$model->views +=1;
	$model->save(false);
	$label = Label::findOne($model->tid);
	$name="";
	if($label){
		$name = $label->title;
	}
	return $this->render('index', ['model' => $model,'label'=>$name]);
    }
}
