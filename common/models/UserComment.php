<?php
namespace common\models;
use Yii;
use common\components\BackendActiveRecord;
class UserComment extends BackendActiveRecord
{
        public static function tablename()
        {
                return "user_comment";
        }
}

