<?php
namespace common\models;
use Yii;
use common\components\BackendActiveRecord;
class TotalBalance extends BackendActiveRecord
{
        public static function tablename()
        {
                return "total_balance";
        }
}

