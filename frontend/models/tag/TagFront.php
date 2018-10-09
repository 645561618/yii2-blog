<?php 
namespace frontend\models\tag;

use Yii;
use common\models\Label;

class TagFront extends Label
{
	public static function getLabel(){
		return Label::find()->where(['status'=>1])->all();
		
	}
}
