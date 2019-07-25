<?php
namespace common\models;
use Yii;
use common\components\BackendActiveRecord;
class Label extends BackendActiveRecord
{
	        public static function tablename()
		{
			return "label";

		}

}

