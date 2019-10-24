<?php
namespace common\models;
use Yii;
use common\components\BackendActiveRecord;
class Notice extends BackendActiveRecord
{
        public static function tablename()
        {
                return "notice";
        }
}

