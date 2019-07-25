<?php
namespace common\models;
use Yii;
use common\components\BackendActiveRecord;
class Article extends BackendActiveRecord
{
	        public static function tablename()
		{
			return "article";

		}

}

