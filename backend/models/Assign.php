<?php
namespace backend\models;

use yii\db\ActiveRecord;
use common\components\BackendActiveRecord;
class Assign extends BackendActiveRecord{
	public static function tableName(){
		return "auth_item_child";
	}
}
