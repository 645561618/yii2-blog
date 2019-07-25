<?php
namespace frontend\widgets\links;
/**
 * 友情链接组件
 */
use Yii;
use yii\bootstrap\Widget;
use common\models\Links;
class LinksWidget extends Widget
{
    
    public function run()
    {
    	$model = Links::find()->where(['status'=>1])->orderBy("sort DESC,created asc")->all();    
        return $this->render('index',['model'=>$model]);
    }
  
    
}
