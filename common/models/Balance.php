<?php
namespace common\models;
use Yii;
use common\components\BackendActiveRecord;
class Balance extends BackendActiveRecord
{
        public static function tablename()
        {
                return "balance";
        }
}
