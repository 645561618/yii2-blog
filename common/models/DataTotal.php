<?php
namespace common\models;
use Yii;
use common\components\BackendActiveRecord;
class DataTotal extends BackendActiveRecord
{
        public static function tablename()
        {
                return "data_total";
        }
}

