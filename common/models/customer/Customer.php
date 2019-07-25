<?php
namespace common\models\customer;
use Yii;
use common\components\BackendActiveRecord;
class Customer extends BackendActiveRecord
{
	public static function tablename()
	{
		return "customer";
	}
}

