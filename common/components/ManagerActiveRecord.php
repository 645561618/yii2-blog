<?php
namespace common\components;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class BackendActiveRecord
 * @package common\components
 */
class ManagerActiveRecord extends ActiveRecord
{

    public static function getDb()
    {
        return Yii::$app->get("db");
    }

}


?>

