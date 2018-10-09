<?php
namespace frontend\widgets\discuss;
/**
 * 热门标签组件
 */
use Yii;
use yii\bootstrap\Widget;

class DiscussWidget extends Widget
{
    
    public function run()
    {
        return $this->render('index');
    }
  
    
}
