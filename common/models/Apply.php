<?php
namespace common\models;
use Yii;
use common\components\BackendActiveRecord;
class Apply extends BackendActiveRecord
{
        public static function tablename()
        {
                return "apply";
        }
}

