<?php
namespace common\components;

use Yii;

class CityWeather{

    //根据城市获取天气
    public static function WeatherInfo($ip)
    {
        $cityData = Yii::$app->cache->get($ip);
        if(!$cityData){
                $cityData = self::taobaoIP($ip);
                Yii::$app->cache->set($ip,$cityData,24*60*60);
        }
        //if(empty($cityData['county'])){
                $cityName = $cityData['city'];
        //}else{
        //        $cityName = $cityData['county'];
       // }
        if ($cityName == "" || (strstr($cityName, "+"))){
                return "";
        }

        $tomo = date('Y-m-d',strtotime("+1 day"));
        $seconds = strtotime($tomo) - time();

        $data = Yii::$app->cache->get($cityName);
        if(!$data){
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
        return $data;

   }



  //根据ip获取城市
  public static function getCity($ip){
        $cityData = Yii::$app->cache->get($ip);
        if($cityData){
                $cityName = $cityData['city'];
                $county = $cityData['county'];
                if(empty($county)){
                        return $cityData['pro']." ".$cityName;
                }else{
                        return $cityData['pro']." ".$cityName." ".$county;
                }
        }else{
                $cityData = self::taobaoIP($ip);
                Yii::$app->cache->set($ip,$cityData,24*60*60);
                $cityName = $cityData['city'];
                $county = $cityData['county'];
                if(empty($county)){
                        return $cityData['pro']." ".$cityName;
                }else{
                        return $cityData['pro']." ".$cityName." ".$county;
                }

        }


  }



  public static function taobaoIP($clientIP){
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




}
