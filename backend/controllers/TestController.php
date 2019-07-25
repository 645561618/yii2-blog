<?php       
namespace backend\controllers;
        
use Yii;
use backend\components\BackendController;
        
class TestController extends BackendController
{



    //测试获取城市天气预报
    public function actionWeatherInfo()
    {
        $cityName = "北京";
        if ($cityName == "" || (strstr($cityName, "+"))){
                return "发送天气加城市，例如'天气深圳'";
        }

        //$ak = '00BiPSqlTDmNxeMzyY4eesbf0ZZ28hRX';
        $ak = 'xY6GLzbhwg3uA3WPMGI090v4brGRq5cj';

        $url = "http://api.map.baidu.com/telematics/v3/weather?location=$cityName&output=json&ak=$ak";
        $result = json_decode(@file_get_contents($url),true);
        if ($result["error"] != 0){
                return $result["status"];
        }
        $curHour = (int)date('H',time());
        $weather = $result["results"][0];
        $weatherArray[] = array("Title" =>$weather['currentCity']."天气预报", "Description" =>"", "PicUrl" =>"", "Url" =>"");
        for ($i = 0; $i < count($weather["weather_data"]); $i++) {
                $weatherArray[] = array("Title"=>
                    $weather["weather_data"][$i]["date"]."\n".
                    $weather["weather_data"][$i]["weather"]." ".
                    $weather["weather_data"][$i]["wind"]." ".
                    $weather["weather_data"][$i]["temperature"],
                "Description"=>"",
                "PicUrl"=>(($curHour >= 6) && ($curHour < 18))?$weather["weather_data"][$i]["dayPictureUrl"]:$weather["weather_data"][$i]["nightPictureUrl"], "Url"=>"");
        }
        echo "<pre>";
        print_r($weatherArray);exit;
        return $weatherArray;
   }


   //测试根据经纬度查询天气
   public  function actionWeather()
    {
        $lng = "121.573512";
        $lat = "29.873392";

        //$ak = '00BiPSqlTDmNxeMzyY4eesbf0ZZ28hRX';
        $ak = 'xY6GLzbhwg3uA3WPMGI090v4brGRq5cj';

        $laturl = "http://api.map.baidu.com/geocoder/v2/?output=json&pois=0&location=".$lat.','.$lng."&ak=".$ak;
        $resultData = json_decode(@file_get_contents($laturl),true);
        $city = trim($resultData['result']['addressComponent']['city']);
        $url = "http://api.map.baidu.com/telematics/v3/weather?location=$city&output=json&ak=$ak";
        $result = json_decode(@file_get_contents($url),true);
        if ($result["error"] != 0){
                return $result["status"];
        }
        $curHour = (int)date('H',time());
        $weather = $result["results"][0];
        $weatherArray[] = array("Title" =>$weather['currentCity']."天气预报", "Description" =>"", "PicUrl" =>"", "Url" =>"");
        for ($i = 0; $i < count($weather["weather_data"]); $i++) {
                $weatherArray[] = array("Title"=>
                    $weather["weather_data"][$i]["date"]."\n".
                    $weather["weather_data"][$i]["weather"]." ".
                    $weather["weather_data"][$i]["wind"]." ".
                    $weather["weather_data"][$i]["temperature"],
                "Description"=>"",
                "PicUrl"=>(($curHour >= 6) && ($curHour < 18))?$weather["weather_data"][$i]["dayPictureUrl"]:$weather["weather_data"][$i]["nightPictureUrl"], "Url"=>"");
        }
        echo "<pre>";
        print_r($weatherArray[1]['Title']);exit;
        //return $weatherArray;

    }

   //测试百度翻译
   public function actionFanyi(){
                $word = "中国";
                $from="auto";
                $to="auto";
                //$word = str_replace('翻译','',$word);
                //echo $word;exit;
                //首先对要翻译的文字进行 urlencode 处理
                $word_code=urlencode($word);

                //注册的API Key
                $appid="20170911000082039";
                $key="KToS1arLHYgu9_uKNu0F";
                $salt = rand(10000,99999);
                $sign = md5($appid.$word.$salt.$key);
                //生成翻译API的URL GET地址
                $baidu_url = "http://api.fanyi.baidu.com/api/trans/vip/translate?q=".$word_code."&from=".$from."&to=".$to."&appid=".$appid."&salt=".$salt."&sign=".$sign;
                //echo $baidu_url."</br>";
                $text=json_decode(@file_get_contents($baidu_url),true);

                if($text){
                        $text = $text['trans_result'];
                        if($text){
                                //echo "<pre>";
                                //print_r($text[0]['dst']);exit;
                                return $text[0]['dst']."【百度翻译】";
                        }
                }

   }

  //测试根据ip获取城市
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

   //测试memecache
   public function actionTest(){
        $key = "key";
        $value="test memcache";
        //Yii::$app->cache->delete($key);
        $data = Yii::$app->cache->get($key);
        if($data){
                echo $data;exit;
        }
        Yii::$app->cache->set($key,$value,5);

   }

     //测试缓存获取城市天气
    public function actionWea()
    {
        $ip = Yii::$app->request->userIP;
        $cityData = Yii::$app->cache->get($ip);
        if(!$cityData){
                $cityData = $this->taobaoIP($ip);
                Yii::$app->cache->set($ip,$cityData,24*60*60);
        }
        $cityName = $cityData['city'];
        if ($cityName == "" || (strstr($cityName, "+"))){
                return "";
        }
	//获取明天日期
        $tomo = date('Y-m-d',strtotime("+1 day"));
	//获取距离明天的时间差
        $seconds = strtotime($tomo) - time();

        $data = Yii::$app->cache->get($cityName);
        if($data){
                //$ak = '00BiPSqlTDmNxeMzyY4eesbf0ZZ28hRX';
                $ak = 'xY6GLzbhwg3uA3WPMGI090v4brGRq5cj';

                $url = "http://api.map.baidu.com/telematics/v3/weather?location=$cityName&output=json&ak=$ak";
                $result = json_decode(@file_get_contents($url),true);
                if ($result["error"] != 0){
                        return $result["status"];
                }
                $curHour = (int)date('H',time());
                $weather = $result["results"][0];
                $data = array(
                        "city"=>$weather['currentCity'],
                        "Title"=>mb_substr($weather["weather_data"][0]["date"],0,2,'UTF-8')." ".$weather["weather_data"][0]["weather"]." ".$weather["weather_data"][0]["temperature"],
                );
                Yii::$app->cache->set($cityName,$data,$seconds);
        }
	echo "<pre>";
	print_r($data);exit;
        //return $data;
   }












} 
