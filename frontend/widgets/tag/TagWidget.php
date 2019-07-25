<?php
namespace frontend\widgets\tag;
/**
 * 热门标签组件
 */
use Yii;
use yii\bootstrap\Widget;
use frontend\models\tag\TagFront;

class TagWidget extends Widget
{
    
    public function run()
    {
    	$model = TagFront::getLabel();    
        return $this->render('index',['model'=>$model]);
    }
  
    
}
