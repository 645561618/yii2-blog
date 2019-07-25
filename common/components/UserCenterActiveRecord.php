<?php
namespace common\components;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class UserCenterActiveRecord
 * @package common\components
 */
class UserCenterActiveRecord extends ActiveRecord
{

    public static function getDb()
    {
        return Yii::$app->get("db");
    }

}


?>
