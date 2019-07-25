<?php
namespace frontend\widgets\read;

use Yii;
use yii\base\Widget;
use frontend\models\article\ArticleFront;

class ReadWidget extends Widget
{

    public function run()
    {
        $result = ArticleFront::getViewsArticle();
        return $this->render('index', ['data' => $result]);
    }
}

