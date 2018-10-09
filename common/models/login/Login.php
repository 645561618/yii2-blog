<?php
namespace common\models\login;
use Yii;
use common\components\ManagerActiveRecord;
class Login extends ManagerActiveRecord
{
	public static function tablename()
	{
		return "login_user";
	}
}

?>
