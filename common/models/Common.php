<?php
namespace common\models;

use Yii;
use backend\models\WxUserInfoBack;

class Common{

	//根据ip获取城市
	public function taobaoIP($clientIP){
        	$taobaoIP = 'http://ip.taobao.com/service/getIpInfo.php?ip='.$clientIP;
	        $IPinfo = json_decode(file_get_contents($taobaoIP));
        	$province = $IPinfo->data->region;
	        $province = str_replace('省','',$province);
        	$city = $IPinfo->data->city;
	        $city = str_replace('市','',$city);
        	$county = $IPinfo->data->county;
	        $data = [
        	        'pro'=>$province,
                	'city'=>$city,
	                'county'=>$county,
        	];
	        return $data;
    	}

    //截取文本输出
    public static function cutstr($string,$length,$etc="..."){
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++){
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')){
                if ($length < 1.0){
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            }else{
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen){
            $result .= $etc;
        }
        return $result;
    }


   //特殊字符过滤
   public static function replaceSpecialChar($strParam)
   {
	$regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        return preg_replace($regex,"",$strParam);	
   }



    //https请求(支持GET和post)
    public static function https_request($url,$data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    //获取微信access_token
    public static function getAccessToken()
    {
        $appid = "wxac7d1cf75ef6e2d1";
        $appsecret = "4fd5e59494f004af02100ce63e9d638a";
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $jsoninfo = json_decode($output, true);
        if($jsoninfo){
            $access_token = $jsoninfo["access_token"];
        }else{
            $access_token = false;
        }
	Yii::$app->cache->set('access_token',$access_token,3600);
        return $access_token;
    }

  
   //获取粉丝列表openid
   public static function getData($access_token)
   {
        if($openid_Data = Yii::$app->cache->get('openid')){
                self::getOpenid($openid_Data,$access_token);
        }else{
                $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token;
                $result = json_decode(Common::https_request($url));
                $openid_Data = $result->data->openid;
                Yii::$app->cache->set('openid',$openid_Data,3600);
                self::getOpenid($openid_Data,$access_token);
        }
   }


   //循环获取用户信息
   public static function getOpenid($openid_Data,$access_token)
   {
         if($openid_Data){
                foreach($openid_Data as $v){
                        if($userData=Yii::$app->cache->get($v)){
                                self::SaveData($userData);//保存微信粉丝数据
                        }else{
                                $user_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$v."&lang=zh_CN";
                                $userData = json_decode(Common::https_request($user_url));
                                Yii::$app->cache->set($v,$userData,3600);
                                self::SaveData($userData);//保存微信粉丝数据
                        }
                }
         }

   }

   //微信公众号粉丝数据存储
   public static function SaveData($userData)
   {
        if($userData){
                $model = WxUserInfoBack::find()->where(['openid'=>$userData->openid])->one();
                if(!$model){
			$model =new WxUserInfoBack;
                        $model->subscribe = $userData->subscribe;
                        $model->openid = $userData->openid;
                        $model->nickname = $userData->nickname;
                        $model->sex = $userData->sex;
                        $model->language = $userData->language;
                        $model->city = $userData->city;
                        $model->province = $userData->province;
                        $model->country = $userData->country;
                        $model->headimgurl = $userData->headimgurl;
                        $model->subscribe_time = $userData->subscribe_time;
                        $model->remark = $userData->remark;
                        $model->groupid = $userData->groupid;
                        //$this->tagid_list = $userData->tagid_list;
                        if ($model->save()) {
				return true;
                        } else {
                                return false;
                        }
               }
       }

   }

   //关注微信公众号获取用户信息
   public static function getFollowUserInfo($openid)
   {
	$access_token = Yii::$app->cache->get('access_token');
        if(!$access_token)
        {
                $access_token = self::getAccessToken();
        }
	$user_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
        $userData = json_decode(Common::https_request($user_url));
        self::SaveData($userData);//保存微信粉丝数	
		
   }
   
   //重新关注和取消关注更新数据
   public static function UpdateUserInfo($openid)
   {
	$model = WxUserInfoBack::find()->where(['openid'=>$openid])->one();
        if($model){		
		if(intval($model->subscribe)==0){
			$model->subscribe=1;
			$model->subscribe_time = time();
		}elseif(intval($model->subscribe)==1){
			$model->subscribe=0;
			$model->subscribe_time = time();
		}
		$model->save(false);
	}

   }
 
   //发送模板消息
   public static function send_template_message($data)
   {
	$access_token = self::getAccessToken();
	$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;	
	$res = self::https_request($url,$data);
	return json_decode($res,true); 
   }





}
?>
