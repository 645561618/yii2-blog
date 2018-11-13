<?php
namespace common\components;

use Yii;
use common\models\Common;
use common\components\CityWeather;
//use common\models\
class Email{
	//后台登录
	public static function Send($ip,$username){
                $cityData = CityWeather::taobaoIP($ip);
                $email = Yii::$app->params['email'];
                $time = date('Y-m-d H:i:s',time());
                $mail= Yii::$app->mailer->compose('/site/notice',['cityData'=>$cityData,'username'=>$username,'ip'=>$ip,'time'=>$time]);
                $mail->setTo("{$email}");
                $mail->setSubject("后台管理账号登录通知");
                $mail->send();
	}


	//评论
	public static function SendCommentNotice($ip,$username,$aid,$title,$content,$time){
                $cityData = CityWeather::taobaoIP($ip);
                $email = Yii::$app->params['email'];
                $time = date('Y-m-d H:i:s',$time);
                $mail= Yii::$app->mailer->compose('/home/commentnotice',['cityData'=>$cityData,'username'=>$username,'ip'=>$ip,'time'=>$time,'aid'=>$aid,'title'=>$title,'content'=>$content]);
                $mail->setTo("{$email}");
                $mail->setSubject("文章评论通知");
                $mail->send();
        }
	
	//友情链接审核通知
	public static function SendLinksEmail($title,$time,$url,$email)
	{
                $mail= Yii::$app->mailer->compose('/blog/links-notice',['time'=>$time,'title'=>$title,'email'=>$email,'url'=>$url]);
                $mail->setTo("{$email}");
                $mail->setSubject("友情链接审核通知");
                if($mail->send()){
			return true;
		}
	}


	//友情链接提交通知
        public static function LinksApplyEmail($title,$time,$url)
        {
                $email = Yii::$app->params['email'];
		$ip = Yii::$app->request->userIP;
                $mail= Yii::$app->mailer->compose('/home/links-notice',['ip'=>$ip,'time'=>$time,'title'=>$title,'email'=>$email,'url'=>$url]);
                $mail->setTo("{$email}");
                $mail->setSubject("友情链接申请通知");
                if($mail->send()){
                        return true;
                }
        }





}

?>
