<?php
namespace common\models;
use Yii;
use common\components\BackendActiveRecord;
class Order extends BackendActiveRecord
{
        public static function tablename()
        {
                return "order";
        }
}
