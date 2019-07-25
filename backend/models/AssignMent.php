<?php
namespace backend\models;

use common\components\BackendActiveRecord;

class AssignMent extends BackendActiveRecord{
	public static function tableName(){
		return "auth_assignment";
	}	
}
