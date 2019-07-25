<?php
namespace common\models;
use Yii;
use common\components\BackendActiveRecord;
class WxUserInfo extends BackendActiveRecord
{
	        public static function tablename()
		{
			return "wx_user_info";

		}

}

