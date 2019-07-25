<?php
namespace frontend\widgets\hot;

use Yii;
use yii\base\Widget;
use frontend\models\article\ArticleFront;
use common\components\SubPages;

class HotWidget extends Widget
{
    public $title = '';
    public $limit = 6;

    public function run()
    {
        $result = ArticleFront::getViewsArticle(1);
        return $this->render('index', ['data' => $result]);
    }
}

