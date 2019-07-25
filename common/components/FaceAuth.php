<?php
namespace common\components;

use Yii;

define ("FACEPP_DEBUG_MODE", false);

class FaceAuth
{

	public function getFaceValue($url)
	{
		$races = array('White'=>'白人','Asian'=> '黄种人','Black'=>'黑人');
		$genders = array('Male'=>'男性','Female'=> '女性');

		$values = "";
		$faceObj = new FacePPClient("DG1vrVFXqGYntUQPjeIU0vaVpZHfqa9e", "C5b--zC8zJITArf6viOwOUuGOcR79Mim");
		$result = $faceObj->face_detect($url);
		echo "<pre>";
		print_r($result);exit;
		if (is_null($result)){
			return "我太累了，先休息一下！！！";
		}
     		$numbers = count($result->face);
		if ($numbers == 0){
			$values = "是你在欺骗我还是我老眼昏花了？发点好认的图片过来行不！优先原则：大小适中、色彩分明、正面清晰、不戴眼镜！";
		}else if ($numbers == 1){
			$values = getSingleComment($result->face[0]->attribute->race->value, $result->face[0]->attribute->gender->value,
									$result->face[0]->attribute->age->value, $result->face[0]->attribute->gender->confidence);
		/*}else if ($numbers == 2){
			$result2 = $faceObj->recognition_compare($result->face[0]->face_id,$result->face[1]->face_id);
			// $values .= "眼相似度：".floor($result2->component_similarity->eye)."%\n".
				   // "嘴相似度：".floor($result2->component_similarity->mouth)."%\n".
				   // "鼻相似度：".floor($result2->component_similarity->nose)."%\n".
				   // "眉相似度：".floor($result2->component_similarity->eyebrow)."%\n".
				   // "近亲指数：".floor($result2->similarity)."%";
			$values = getDoubleComment($result2->component_similarity->eye,
						$result2->component_similarity->mouth,
						$result2->component_similarity->nose,
						$result2->component_similarity->eyebrow,
						$result2->similarity,
						$result->face[0]->attribute->age->value,
						$result->face[1]->attribute->age->value,
						$result->face[0]->attribute->gender->value,
						$result->face[1]->attribute->gender->value);*/
		}else{
			$values .= "面测到有".$numbers."人";
			for ($i = $numbers - 1; $i >= 0; $i--){
				if ($numbers != 1){$values .= "\n第".($numbers - $i)."个:";}
            			$values .= getMultiComment($result->face[$i]->attribute->gender->value, $result->face[$i]->attribute->age->value, $result->face[$i]->attribute->gender->confidence);
			}
        
		}
		return $values;
	}


	public function getSingleComment($races, $gender, $age, $confidence)
	{
		$raceArray = array('White'=>'','Asian'=> '','Black'=>'面如黑碳 ');
		$genderArray = array('Male'=>'男性','Female'=> '女性');
		$raceComment = $raceArray[$races];
		$ageComment = "";
		$confidenceComment = "";
		if ($gender == "Male"){
			$confidenceComment = "桃花运指数：".round($confidence)."(";
			if ($age < 3){$ageComment .= "襁褓婴儿";}
			else if ($age < 5) {$ageComment = "无忧孩提";}
			else if ($age < 10){$ageComment = "幼学龀童";}
			else if ($age < 15){$ageComment = "舞勺之年";}
			else if ($age < 20){$ageComment = "志学之年";}
			else if ($age < 30){$ageComment = "弱冠男儿";}
			else if ($age < 40){$ageComment = "而立之年";}
			else if ($age < 50){$ageComment = "不惑之年";}
			else if ($age < 60){$ageComment = "知命之年";}
			else if ($age < 70){$ageComment = "花甲之年";}
			else if ($age < 80){$ageComment = "古稀之年";}
			else{$ageComment = "年迫日索";}

			if ($confidence < 55){$confidenceComment .= "貌不符实";}
			else if ($confidence < 60){$confidenceComment .= "衣冠楚楚";}
			else if ($confidence < 65){$confidenceComment .= "文质彬彬";}
			else if ($confidence < 70){$confidenceComment .= "英俊潇洒";}
			else if ($confidence < 75){$confidenceComment .= "玉面郎君";}
			else if ($confidence < 80){$confidenceComment .= "城北徐公";}
			else if ($confidence < 85){$confidenceComment .= "潘安再世";}
			else  {$confidenceComment .= "震古烁今";}

		}else if($gender == "Female"){
			$confidenceComment = "桃花运指数：".round($confidence)."(";
			if ($age < 3){$ageComment = "襁褓婴儿";}
			else if ($age < 5) {$ageComment = "总角孩提";}
			else if ($age < 10){$ageComment = "小萝莉";}
			else if ($age < 15){$ageComment = "豆蔻年华";}
			else if ($age < 20){$ageComment = "碧玉年华";}
			else if ($age < 30){$ageComment = "花信年华";}
			else if ($age < 40){$ageComment = "风韵犹存";}
			else if ($age < 50){$ageComment = "半老徐娘";}
			else if ($age < 60){$ageComment = "年华垂暮";}
			else if ($age < 70){$ageComment = "老态龙钟";}
			else if ($age < 80){$ageComment = "雪鬓霜鬟";}
			else{$ageComment = "年迫日索";}

			if ($confidence < 55){$confidenceComment .= "貌不符实";}
			else if ($confidence < 60){$confidenceComment .= "落落大方";}
			else if ($confidence < 65){$confidenceComment .= "幽韵撩人";}
			else if ($confidence < 70){$confidenceComment .= "天生丽质";}
			else if ($confidence < 75){$confidenceComment .= "闭月羞花";}
			else if ($confidence < 80){$confidenceComment .= "风姿卓越";}
			else if ($confidence < 85){$confidenceComment .= "美艳绝伦";}
			else {$confidenceComment .= "倾国倾城";}
		}
		$result = "年龄：约".$age."岁(".$ageComment.")"."\n".$confidenceComment.")";
		return "本神察颜~观色~面相~摸骨~。嘿！有了：\n".$result;
	}



	public function getDoubleComment($eye, $mouth, $nose, $eyebrow, $similarity, $age1, $age2, $gender1, $gender2)
	{
		$organs = Array("眼睛"=>(string)$eye, "嘴巴"=>(string)$mouth, "鼻子"=>(string)$nose, "眉毛"=>(string)$eyebrow, "脸"=>(string)$similarity);
		$values = array_flip($organs);  			//反转
		krsort($values, SORT_NUMERIC);  			//排序

		$result_key = "";
		$result_value = "";
	
		$result = "";
		$title = "";
		$similarity_index = "";
		$similarity_comment = "";
		//取第一个
		foreach ($values as $key => $value) {
			$result_key = round($key);
			$result_value = $value;
			break;
		}

		//有小孩且年龄差距是10用最相似部分判断
		if (($age1 < 10 || $age2 < 10) && abs($age2 - $age1) > 15){
			$title = "亲子指数";
			$similarity_index = round($result_key);
			if ($result_key < 40){$similarity_comment .= "【".$result_key."】"."天壤之别";}
			else if ($result_key < 50){$similarity_comment = "【".$result_key."】"."迥然不同";}
			else if ($result_key < 60){$similarity_comment = "【".$result_key."】"."大同小异";}
			else if ($result_key < 70){$similarity_comment = "【".$result_key."】"."一脉相承";}
			else if ($result_key < 80){$similarity_comment = "【".$result_key."】"."骨肉情深";}
			else if ($result_key < 90){$similarity_comment = "【".$result_key."】"."血脉相连";}
			else{$similarity_comment = "一模一样 ";}
		}
    		//情侣 年龄均小于25
		else if (($gender1 != $gender2) && (abs($age2 - $age1) < 15) && ($age1 < 25 || $age2 < 25)){
			$title = "情侣相指数";
			$similarity_index = round($similarity);
			if ($similarity < 40){$similarity_comment .= "【".$similarity_index."】"."花好月圆";}
			else if ($similarity < 50){$similarity_comment = "【".$similarity_index."】"."相濡以沫";}
			else if ($similarity < 60){$similarity_comment = "【".$similarity_index."】"."情真意切";}
			else if ($similarity < 70){$similarity_comment = "【".$similarity_index."】"."郎才女貌";}
			else if ($similarity < 80){$similarity_comment = "【".$similarity_index."】"."心心相印";}
			else if ($similarity < 90){$similarity_comment = "【".$similarity_index."】"."浓情蜜意";}
			else{$similarity_comment = "海盟山誓";}
		}
    		//夫妻 年龄均大于25
		else if (($gender1 != $gender2) && ($age1 > 25 && $age2 > 25)){
			$title = "夫妻相指数：";
			$similarity_index = round($similarity);
			if ($similarity < 40){$similarity_comment .= "【".$similarity_index."】"."怜我怜卿";}
			else if ($similarity < 50){$similarity_comment = "【".$similarity_index."】"."举案齐眉";}
			else if ($similarity < 60){$similarity_comment = "【".$similarity_index."】"."相敬如宾";}
			else if ($similarity < 70){$similarity_comment = "【".$similarity_index."】"."相亲相爱";}
			else if ($similarity < 80){$similarity_comment = "【".$similarity_index."】"."比翼双飞";}
			else if ($similarity < 90){$similarity_comment = "【".$similarity_index."】"."白头偕老";}
			else{$similarity_comment = "生死与共";}
		}
    		//否则用脸部判断近亲
    		else{ 
			$title = "亲戚相指数";
			$similarity_index = round($result_key);
			if ($result_key < 40){$similarity_comment .= "天差地别";}
			else if ($result_key < 50){$similarity_comment = "大相径庭";}
			else if ($result_key < 60){$similarity_comment = "相差无几";}
			else if ($result_key < 70){$similarity_comment = "如出一辙";}
			else if ($result_key < 80){$similarity_comment = "履足差肩";}
			else if ($result_key < 90){$similarity_comment = "同根连脉";}
			else{$similarity_comment = "一模一样 ";}
		}

		$result = $title."(".$similarity_index.")"."\n".$similarity_comment.")";
    		return "本神察颜~观色~面相~摸骨~。嘿！有了：\n".$result;
	}

	public function getMultiComment($gender, $age, $confidence)
	{
    		if ($gender == "Male"){
			$confidenceComment = "男人味:".round($confidence + 5)."(";
			if ($confidence < 55){$confidenceComment .= "男女难分";}
			else if ($confidence < 60){$confidenceComment .= "有棱有角";}
			else if ($confidence < 65){$confidenceComment .= "浑厚内敛";}
			else if ($confidence < 70){$confidenceComment .= "豪爽勇敢";}
			else if ($confidence < 75){$confidenceComment .= "阳刚粗犷";}
			else if ($confidence < 80){$confidenceComment .= "刚毅深刻";}
			else if ($confidence < 85){$confidenceComment .= "气势凌人";}
			else {$confidenceComment .= "至情至圣";}
		        $confidenceComment .= ")";

		}else if($gender == "Female"){
			$confidenceComment = "女人味:".round($confidence + 5)."(";
			if ($confidence < 54){$confidenceComment .= "男女难分";}
			else if ($confidence < 60){$confidenceComment .= "蜂迷蝶猜";}
			else if ($confidence < 65){$confidenceComment .= "清水芙蓉";}
			else if ($confidence < 70){$confidenceComment .= "淡雅脱俗";}
			else if ($confidence < 75){$confidenceComment .= "妩媚优雅";}
			else if ($confidence < 80){$confidenceComment .= "动人心弦";}
			else if ($confidence < 85){$confidenceComment .= "千娇百媚";}
			else  {$confidenceComment .= "风情万种";}
        		$confidenceComment .= ")";
		}
    		return $confidenceComment;
	}

}



class FacePPClient
{
	private $api_server_url;
	private $auth_params;

	public function __construct($api_key, $api_secret)
	{
		$this->api_server_url = "https://api-cn.faceplusplus.com/facepp/v3/";
    		$this->auth_params = array();
   		$this->auth_params['api_key'] = $api_key;
   		$this->auth_params['api_secret'] = $api_secret;
	}

	public function person_create($person_name)
	{
		return $this->call("person/create", array("person_name"=>$person_name));
	}
	public function person_add_face($face_id, $person_name)
	{
		return $this->call("person/add_face", array("person_name"=>$person_name, "face_id"=>$face_id));
	}
	public function recognition_train($group_name)
	{
		return $this->call("recognition/train", array("group_name"=>$group_name, "type"=>"recognize"));
	}
	//相似脸搜索
	public function recognition_search($group_name, $key_face_id)
	{
		return $this->call("recognition/search", array("group_name"=>"weixin_group", "count"=>1, "key_face_id"=>$key_face_id));
	}
	public function face_detect($urls = null)
	{
		//echo "<pre>";
		//echo $urls;
		//print_r($this->auth_params);exit;
		return $this->call("detect", array("url"=>$urls));
	}
	public function face_detect_via_img($img = null)
	{
		return $this->call("detection/detect", array("img"=>$img));
	}
	public function recognition_recognize($url, $group_name)
	{
		return $this->call("recognition/recognize", array("url"=>$url, "group_name"=>$group_name));
	}
	//人脸对比相似性 人脸比对
	public function recognition_compare($face_id1, $face_id2)
	{
		return $this->call("recognition/compare", array("face_id1"=>$face_id1, "face_id2"=>$face_id2));
	}
	public function group_create($group_name)
	{
		return $this->call("group/create", array("group_name"=>$group_name));
	}
	public function group_add_person($person_name, $group_name)
	{
		return $this->call("group/add_person", array("person_name"=>$person_name, "group_name"=>$group_name));
	}
    public function info_get_session($session_id)
    {
		return $this->call("info/get_session", array("session_id"=>$session_id));
    }
	//根据face id获得相似照片
    public function info_get_face($face_id)
    {
		return $this->call("info/get_face", array("face_id"=>$face_id));
    }
    protected function call($method, $params = array())
    {

		$headers = array(
			"User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:14.0) Gecko/20100101 Firefox/14.0.1",
			"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
			"Accept-Language: en-us,en;q=0.5",
			//"Accept-Encoding: gzip, deflate",
			"Referer: http://mmsns.qpic.cn/"
		);

		//二进制方式提交img
		if (array_key_exists("img", $params)){
			//$url = $this->api_server_url."$method?".http_build_query($this->auth_params)."&img=";
			$url = $this->api_server_url."$method?".http_build_query(array_merge($this->auth_params, $params));
			if (FACEPP_DEBUG_MODE){echo "REQUEST: $url" . "\n";}
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params["img"]);
		//url方式提交img
		}else{
			$url = $this->api_server_url."$method?".http_build_query(array_merge($this->auth_params, $params));
			if (FACEPP_DEBUG_MODE){echo "REQUEST: $url" . "\n";}
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		}
		//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     	$data = curl_exec($ch);
    	curl_close($ch);
		$result = null;
		if (!empty($data)){
			if (FACEPP_DEBUG_MODE){
				echo "RETURN: " . $data . "\n";
			}
			$result = json_decode($data);
		}
        else{
            echo "cURL Error:". curl_error($ch);
        }
		return $result;
    }
}


