<?php
namespace common\models\customclick;
use Yii;
use common\components\BackendActiveRecord;
class CustomClick extends BackendActiveRecord
{
        public static function tablename()
        {
                return "custom_click";
        }
}

