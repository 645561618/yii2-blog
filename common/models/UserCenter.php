<?php
namespace common\models;
use Yii;
use common\components\UserCenterActiveRecord;
class UserCenter extends UserCenterActiveRecord
{
        public static function tablename()
        {
                return "user_center";
        }
}

