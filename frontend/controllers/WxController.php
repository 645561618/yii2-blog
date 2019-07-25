<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\components\wechatCallbackapiTest;
use common\components\FaceAuth;
use dosamigos\qrcode\QrCode;

class WxController extends Controller
{
	public $enableCsrfValidation;

	public function actionIndex(){
		$wechatObj = new wechatCallbackapiTest();
        	if (!isset($_GET['echostr'])) {
	            $wechatObj->responseMsg();
        	}else{
	            $wechatObj->valid();
        	}
	}

	public function actionTest()
	{
		$url = "http://mmbiz.qpic.cn/mmbiz_jpg/nlh71dTqwDwHFSjEj0mqedIlHdfQAz5tLoNSc6pFCRoYbokZUHicSaGvqKorJ1lJs5AmwKibviamoukSvyhicGKzyw/0";
		$data = FaceAuth::getFaceValue($url);	
	}

	public function actionFace()
	{
		$image="/home/wwwroot/images/1.jpg";                //图片地址
		$content = "http://mmbiz.qpic.cn/mmbiz_jpg/nlh71dTqwDwHFSjEj0mqedIlHdfQAz5tLoNSc6pFCRoYbokZUHicSaGvqKorJ1lJs5AmwKibviamoukSvyhicGKzyw/0";
                //$fp = fopen($image, 'rb');
                //$content = fread($fp, filesize($image)); //二进制数据
    		$curl = curl_init();   
    		curl_setopt_array($curl, array(
       		CURLOPT_URL => "https://api-cn.faceplusplus.com/facepp/v3/detect",     //输入URL
       		CURLOPT_RETURNTRANSFER => true,
       		CURLOPT_ENCODING => "",
       		CURLOPT_MAXREDIRS => 10,
       		CURLOPT_TIMEOUT => 30,
      	 	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       		CURLOPT_CUSTOMREQUEST => "POST",       CURLOPT_POSTFIELDS => array('image_file";filename="image'=>"$content", 'api_key'=>"DG1vrVFXqGYntUQPjeIU0vaVpZHfqa9e",'api_secret'=>"C5b--zC8zJITArf6viOwOUuGOcR79Mim",'return_landmark'=>"1",'return_attributes'=>"gender,age,smiling,headpose,facequality,blur,eyestatus,emotion,ethnicity,beauty,mouthstatus,eyegaze,skinstatus"),   //输入api_key和api_secret
       		CURLOPT_HTTPHEADER => array("cache-control: no-cache",),
       		));    
    		$response = curl_exec($curl);
    		$err = curl_error($curl);   
    		curl_close($curl);   
    		if ($err) {
        		echo "cURL Error #:" . $err;
    		} else { 
        		echo $response;
    		}
	}


	public function actionWxLogin()
	{
		$url  = Yii::$app->platform->getPlatformWxLogin("wx");
		echo $url;exit;
	}	
	
	public function actionQrcode()
    	{
		$text = "http://www.hxinq.com/wx/wx-login";//表示生成二位的的信息文本
        	$outfile = false;              //表示是否输出二维码图片 文件,默认否
        	$level = 'L';                  //表示容错率，也就是有被覆盖的区域还能识别，分别是 L（QR_ECLEVEL_L，7%），M（QR_ECLEVEL_M，15%），Q（QR_ECLEVEL_Q，25%），H（QR_ECLEVEL_H，30%）；
        	$size = '3';                   //图片大小，默认是3
        	$margin = '4';                 //表示二维码周围边框空白区域间距值，默认是4
        	$saveAndPrint = false;         //表示是否保存二维码并显示
        	$res = Qrcode::png($text,false,'M','9','2',false);
		return $res;
    	}  	

	

}

