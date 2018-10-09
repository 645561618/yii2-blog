<?php
namespace api\modules\v1\controllers;
use Yii;
use api\components\Controller;
use api\models\OrderFront;

class ShopController extends Controller
{

	public function actionIndex(){
		//$data = $this->_reqData['data'];
		list($code,$data) = OrderFront::HomeLIst($this->_reqData['data']);
		$this->code = $code;
		$this->data = $data;	
	}


}
