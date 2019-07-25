<?php
namespace common\models\customer;
use Yii;
use common\components\BackendActiveRecord;
class FalseCustomer extends BackendActiveRecord
{
        public static function tablename()
        {
                return "false_customer";
        }
}

