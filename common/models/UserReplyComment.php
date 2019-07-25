<?php
namespace common\models;
use Yii;
use common\components\BackendActiveRecord;
class UserReplyComment extends BackendActiveRecord
{
        public static function tablename()
        {
                return "user_reply_comment";
        }
}

