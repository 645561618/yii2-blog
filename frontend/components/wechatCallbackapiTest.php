<?php
namespace frontend\components;

use Yii;
use backend\models\KeywordsBack;
use backend\models\CustomClick;
use backend\models\CustomMenu;
use backend\models\WxUserInfo;
use common\models\Common;
use common\components\FaceAuth;
define("TOKEN", "sadtsgtixnkopd");
define("APIKey", "a57c29320b864d25b7b601c1cb6c6dc9");

class wechatCallbackapiTest 
{

    //验证签名
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if($tmpStr == $signature){
            return true;
        }else{
            return false;
        }
    }
    
    //响应消息
    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            switch ($RX_TYPE)
            {
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
		case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
		case "image":
                    $result = $this->receiveImage($postObj);
                    break;
		case "location":
                    $result = $this->receiveLocation($postObj);
                    break;
		case "voice":
                    $result = $this->receiveVoice($postObj);
                    break;
		case "video":
		case "shortvideo":
                    $result = $this->receiveVideo($postObj);
                    break;
		case "link":
                    $result = $this->receiveLink($postObj);
                    break;
            }
            echo $result;
        }else {
            echo "";
            exit;
        }
    }

    //接收文本消息
    private function receiveText($object)
    {   
        $keyword = trim($object->Content);
	$words = KeywordsBack::find()->where("words like '%{$keyword}%'")->one();
	$content = $this->tuling_robot($object->FromUserName, $object->Content);
	//if(is_array($content)){
		/*if(isset($content[0])){
			$result = $this->transmitNews($object, $content);
		}elseif(isset($content['MusicUrl'])){
			$result = $this->transmitMusic($object, $content);
		}*/
	//}else{
		if($words){
        		$content = $words->reply;
		}else{
			$str_trans = mb_substr($keyword,0,2,"UTF-8");
			$str_valid = mb_substr($keyword,0,-2,"UTF-8");
			if(strstr($keyword, "天气")){
        	        	$city = str_replace('天气', '', $keyword);
                		$content = $this->getWeatherInfo($city);
				$result = $this->transmitNews($object, $content);
			        return $result;
			}elseif($str_trans == '翻译' && !empty($str_valid)){
				$word = mb_substr($keyword,2,202,"UTF-8");
				//调用百度翻译API
				$content = $this->baiduDic($word);
		        }elseif(isset($content[0])){
				$result = $this->transmitNews($object, $content);	
			}else{
        			$content = "欢迎关注";
			}
		}
        	$result = $this->transmitText($object, $content);
	//}
        return $result;
    }

    //接收事件消息
    private function receiveEvent($object)
    {
        $contentStr = "";
        $rs = CustomMenu::find()->where(['type'=>'click','status'=>1])->andWhere("fid!=0")->asArray()->all();
        $str = "嘿，终于等到你！还好我们都没放弃，感谢您关注【强波子仔勒】\n微信号：huangxinqiang5833\n请回复序号：\n1. 天气查询\n2. 翻译查询\n3. 聊天\n输入【帮助】查看提示\n更多内容，敬请期待...";

        switch ($object->Event)
        {
	    case "subscribe":
		Common::UpdateUserInfo($object->FromUserName);
		$contentStr = $str;
		break;
            case "unsubscribe":
		Common::UpdateUserInfo($object->FromUserName);
		$contentStr = "取消关注";
                break;
            case "CLICK":
                if ($rs) {
                    foreach ($rs as $key => $value) {
                        if ($object->EventKey == trim($value['content'])) {
                            $replies = CustomClick::find()->where(['cid'=>$value['id'],'type'=>0])->all();
                            if ($replies) {
                                foreach ($replies as $k => $reply) {
                                    $contentStr[$k]['Title'] = $reply->title;
                                    $contentStr[$k]['Description'] = $reply->description;
                                    $contentStr[$k]['PicUrl'] = $reply->picurl;
                                    $contentStr[$k]['Url'] = $reply->url;
                                }
                            } else {
                                $contentStr[] = array("Title" =>"默认图文回复", 
                                    "Description" =>"您正在使用的是自定义菜单测试接口", 
                                    "PicUrl" =>"http://img.hxinq.com/apply/day_170905/20170905_4a60697.jpg", 
                                    "Url" =>"http://www.hxinq.com");
                            }
                        }
                    }
                } else {
                    $contentStr[] = array("Title" =>"默认图文回复", 
                        "Description" =>"您正在使用的是自定义菜单测试接口", 
                        "PicUrl" =>"http://img.hxinq.com/apply/day_170905/20170905_4a60697.jpg", 
                        "Url" =>"http://www.hxinq.com");
               }
                break;
	    case "VIEW":
                $contentStr = "跳转链接 ".$object->EventKey;
                break;
	    case "SCAN":
		$contentStr = "欢迎进来".$object->EventKey;
		break;	
	    case "scancode_push":
                $content = "扫码推事件";
                break;
            default:
                break;      

        }
        if (is_array($contentStr)){
            $resultStr = $this->transmitNews($object, $contentStr);
        }else{
            $resultStr = $this->transmitText($object, $contentStr);
        }
        return $resultStr;
    }

    //回复图文消息
    private function transmitNews($object, $arr_item, $funcFlag = 0)
    {
        if(!is_array($arr_item))
            return;

        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
        $item_str = "";
        foreach ($arr_item as $item)
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);

        $newsTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<Content><![CDATA[]]></Content>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
<FuncFlag>%s</FuncFlag>
</xml>";

        $resultStr = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($arr_item), $funcFlag);
        return $resultStr;
    }    

    //回复文本消息
    private function transmitText($object, $content)
    {   
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
	
	Common::getFollowUserInfo($object->FromUserName);	
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }


    //回复图片消息
    private function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
        <MediaId><![CDATA[%s]]></MediaId>
    </Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[image]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }


    //回复语音消息
    private function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
        <MediaId><![CDATA[%s]]></MediaId>
    </Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);
        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[voice]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复视频消息
    private function transmitVideo($object, $videoArray)
    {
        $itemTpl = "<Video>
        <MediaId><![CDATA[%s]]></MediaId>
        <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
    </Video>";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[video]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }


    //回复音乐消息
        private function transmitMusic($object, $musicArray)
	{
        	if(!is_array($musicArray)){
			return "";
		}
		$itemTpl = "<Music>
			<Title><![CDATA[%s]]></Title>
			<Description><![CDATA[%s]]></Description>
			<MusicUrl><![CDATA[%s]]></MusicUrl>
			<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
			</Music>";
		$item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);
		$xmlTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[music]]></MsgType>
			$item_str
			</xml>";
		$result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
		return $result;
	}





    //接收图片消息
    private function receiveImage($object)
    {
        $content = array("MediaId"=>$object->MediaId);
//	$imgurl = strval($object->PicUrl);
  //      $content = FaceAuth::getFaceValue($imgurl);
	//$result = $this->transmitText($object, $content);
        $result = $this->transmitImage($object, $content);
        return $result;
    }


    //接收位置消息
    private function receiveLocation($object)
    {
	$content = $this->getWeather($object->Location_X,$object->Location_Y);
        //$content = "你发送的是位置，经度为：".$object->Location_Y."；纬度为：".$object->Location_X."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        //$result = $this->transmitText($object, $content);
	$result = $this->transmitNews($object, $content);
        return $result;
    }

    //接收语音消息
    private function receiveVoice($object)
    {
        if (isset($object->Recognition) && !empty($object->Recognition)){
            $content = $object->Recognition;
            $result = $this->transmitText($object, $content);
        }else{
            $content = array("MediaId"=>$object->MediaId);
            $result = $this->transmitVoice($object, $content);
        }
        return $result;
    }

    //接收视频消息
    private function receiveVideo($object)
    {
        $content = array("MediaId"=>$object->MediaId, "ThumbMediaId"=>$object->ThumbMediaId, "Title"=>"测试", "Description"=>"视频测试");
        $result = $this->transmitVideo($object, $content);
        return $result;
    }


    //接收链接消息
    private function receiveLink($object)
    {
        $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
        $result = $this->transmitText($object, $content);
        return $result;
    }


    //接收城市天气消息
    private function getWeatherInfo($cityName)
    {
	
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
        return $weatherArray;	

    }

   //根据经纬度查询天气
   //lng经度,lat纬度
   private function getWeather($lat,$lng)
    {
        //$lng = "121.573512";
        //$lat = "29.873392";
        
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
        return $weatherArray;

    }

     	//百度翻译
	public function baiduDic($word,$from="auto",$to="auto"){
		$appid="20170911000082039";
		$key="KToS1arLHYgu9_uKNu0F";
		$salt = rand(10000,99999);//随机数
		$sign = md5($appid.$word.$salt.$key);//签名

		//首先对要翻译的文字进行 urlencode 处理
		$word_code=urlencode($word);

		//生成翻译API的URL GET地址
		$baidu_url = "http://api.fanyi.baidu.com/api/trans/vip/translate?q=".$word_code."&from=".$from."&to=".$to."&appid=".$appid."&salt=".$salt."&sign=".$sign;
                $text=json_decode(@file_get_contents($baidu_url),true);
		$text = $text['trans_result'];
		//return $text[0]['dst']."【百度翻译】";
		return $text[0]['dst'];
		
	}

    //图灵机器人
    private function tuling_robot($userid, $info)
    {
	    $url = "http://www.tuling123.com/openapi/api?key=".APIKey."&info=$info&userid=$userid";
	    $ch = curl_init();
	    curl_setopt ($ch, CURLOPT_URL, $url);
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	    $output = curl_exec($ch);
	    curl_close($ch);
	    $result = json_decode($output, true);
	    switch ($result['code']) 
	    {
	    case 100000:    //文本类
		    $content = $result['text'];
		    break;
	    case 200000:    //链接类
		    $content = $result['text'].', <a href="'.$result['url'].'">点击进入</a>';
		    break;
	    case 302000:    //新闻类
		    $length = count($result['list']) > 8 ? 8 :count($result['list']);
		    for($i= 0; $i< $length; $i++){
			    $content[$i] = array (
				    'Title' => empty($result['list'][$i]['article'])?$result['list'][$i]['source']:$result['list'][$i]['article'],
				    'Description' => $result['list'][$i]['source'],
				    'PicUrl' => $result['list'][$i]['icon'],
				    'Url' => $result['list'][$i]['detailurl']
			    );
		    }
		    break;
	    case 308000:
		    $content[] = array (
			    'Title' => $result['list'][0]['name'],
			    'Description' => $result['list'][0]['info'],
			    'PicUrl' => $result['list'][0]['icon'],
			    'Url' => $result['list'][0]['detailurl']
		    );
		    break;
	    default:
		    $content = $result['text'] ;
		    break;
	    }
	    return $content;
    }




}
