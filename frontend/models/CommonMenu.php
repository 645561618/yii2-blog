<?php
namespace frontend\models;

use Yii;
use common\models\Category;

class CommonMenu{
	
	public static function getMenu(){
		$key = "front_common_menu";
		$list = Yii::$app->cache->get($key);
		if(empty($list)){
			$list = Category::find()->where(['status'=>1])->orderBy('sort DESC')->all();
			Yii::$app->cache->set($key,$list,3600);
		}
		return $list;	
	}

}
