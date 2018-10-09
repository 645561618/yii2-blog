<?php
namespace common\components;

use Yii;
use common\models\Common;
use common\components\CityWeather;
//use common\models\
class Email{

	public static function Send($ip,$username){
                $cityData = CityWeather::taobaoIP($ip);
                $email = Yii::$app->params['email'];
                $time = date('Y-m-d H:i:s',time());
                $mail= Yii::$app->mailer->compose('/site/notice',['cityData'=>$cityData,'username'=>$username,'ip'=>$ip,'time'=>$time]);
                $mail->setTo("{$email}");
                $mail->setSubject("后台管理账号登录通知");
                $mail->send();
	}



	public static function SendCommentNotice($ip,$username,$aid,$title,$content,$time){
                $cityData = CityWeather::taobaoIP($ip);
                $email = Yii::$app->params['email'];
                $time = date('Y-m-d H:i:s',$time);
                $mail= Yii::$app->mailer->compose('/home/commentnotice',['cityData'=>$cityData,'username'=>$username,'ip'=>$ip,'time'=>$time,'aid'=>$aid,'title'=>$title,'content'=>$content]);
                $mail->setTo("{$email}");
                $mail->setSubject("文章评论通知");
                $mail->send();
        }




}

?>
