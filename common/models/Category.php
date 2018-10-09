<?php
namespace common\models;
use Yii;
use common\components\BackendActiveRecord;
class Category extends BackendActiveRecord
{
	        public static function tablename()
		{
			return "category";

		}

}

