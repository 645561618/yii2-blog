<?php
namespace common\components\xunsearch;

use Yii;
use yii\data\ActiveDataProvider;
use hightman\xunsearch\ActiveRecord;

class Search extends ActiveRecord
{
    public static function projectName() {
        return 'search';  // 这将使用 @app/config/xunsearch/another_name.ini 作为项目名
    }

    public function getArticleInfo($page, $count, $content)
    {
	$offset = ($page - 1) * $count;
	return Search::find()->where(['status'=>2])->andWhere($content)->orderBy(['created'=>SORT_DESC])->limit($count)->offset($offset)->all();
    }


}
