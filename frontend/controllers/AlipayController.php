<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;

require(__DIR__.'/../../common/extensions/AliPay/AlipayPay.php');

class AlipayController extends Controller
{

	public function actionIndex(){
			$order_id='101001102';
                        $subject='这是测试支付接口';
                        $total_fee='0.01';
                        $body='订单#'.$order_id;
                	$show_url = '';	
			$alipay = new \AlipayPay();
			$html =$alipay->requestPay($order_id, $subject, $total_fee, $body, $show_url);
			echo $html;
			Yii::$app->end();
	}

	//同步通知(支付成功，页面跳转,只跳转一次)
	public function actionReturn(){
		$Alipay = new \AlipayPay();
		$result = $Alipay->verifyReturn();
		if($result){
			if($_GET['trade_status']=="TRADE_SUCCESS"){
				$this->redirect("http://www.hxinq.com");
			}
		}else{
			echo "支付失败";
		}
	}

	//异步通知(更改业务订单状态)
	public function actionNotify(){
		$Alipay = new \AlipayPay();
		$result = $Alipay->verifyNotify();
		if($result){
			if($_POST['trade_status']=="TRADE_SUCCESS" || $_POST['trade_status']=="TRADE_FINISHED"){
				//echo "支付成功";
                        }
                }else{
                        echo "支付失败";
                }

	
	}


}
