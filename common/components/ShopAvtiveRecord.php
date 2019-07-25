<?php
namespace common\components;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class ShopActiveRecord
 * @package common\components
 */
class ShopActiveRecord extends ActiveRecord
{

    public static function getDb()
    {
        return Yii::$app->get("shopdb");
    }

}

