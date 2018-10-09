<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\WxUserInfo;
use common\models\Common;
class WxUserInfoBack extends WxUserInfo{



    public function rules()
    {
        return [
	    [['subscribe','openid','nickname','sex','language','province','city','country','headimgurl','unionid','remark','groupid','tagid_list','subscribe_time'],'safe'],	
        ];
    }

    public function attributelabels()
    {
        return[
		'subscribe'=>'是否关注',
		'openid' => 'openid',
                'nickname' => '昵称',
                'sex' => '性别',
                'province' => '省份',
                'city' => '城市',
                'country' => '国家',
                'headimgurl' => '头像',
        ];
    }

   public function search($params){
	$query = static::find()->orderBy('subscribe_time DESC');
        $dataProvider = new ActiveDataProvider([
            'query' =>$query,
            'sort'=>false,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like','openid',$this->openid]);
        $query->andFilterWhere(['like','nickname',$this->nickname]);
        $query->andFilterWhere(['sex'=>$this->sex]);
        $query->andFilterWhere(['province'=>$this->province]);
        $query->andFilterWhere(['city'=>$this->city]);
        $query->andFilterWhere(['country'=>$this->country]);
        return $dataProvider;
	
   }

   public function getTime()
   {
	return function($data){
		return date('Y-m-d H:i:s',$data->subscribe_time);
	};
   }

   public function getLook(){
	return function($data){
            if($data->subscribe == 0){
                return "取消关注";
            }elseif($data->subscribe==1){
                return "已关注";
	    }
        };

   }


   public function getSex(){
	return function($data){
            if($data->sex == 0){
                return "未知";
            }elseif($data->sex==1){
                return "男";
            }elseif($data->sex==2){
                return "女";
	    }
        };
   }    


  public function SexValues()
    {
        return [
            0 => '未知',
            1 => '男',
            2 => '女',
        ];
    }

   public function HeadImg(){
	return function($data){
		return "<img src='$data->headimgurl' style='width:50px;height:50px;'>";
	};
   }

   public function getUserInfo(){
	$access_token = Yii::$app->cache->get('access_token');
        if(!$access_token)
        {
		$access_token = Common::getAccessToken();
		if($access_token){
			Common::getData($access_token);
		}else{
			echo "access_token错误";
		}
	}else{
		Common::getData($access_token);
	}
	
   }

   /*public function getData($access_token)
   {
	
	if($openid_Data = Yii::$app->cache->get('openid')){
		$this->getOpenid($openid_Data,$access_token);
	}else{
		$url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$access_token;
	        $result = json_decode(Common::https_request($url));
        	$openid_Data = $result->data->openid;
	        Yii::$app->cache->set('openid',$openid_Data,3600);
		$this->getOpenid($openid_Data,$access_token);
	}
   }


   public function getOpenid($openid_Data,$access_token)
   {
	 if($openid_Data){
	 	foreach($openid_Data as $v){
			if($userData=Yii::$app->cache->get($v)){
				$this->SaveData($userData);
			}else{
        	        	$user_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$v."&lang=zh_CN";
	        	        $userData = json_decode(Common::https_request($user_url));
				Yii::$app->cache->set($v,$userData,3600);
				$this->SaveData($userData);
			}
       	 	}
       	 }

   }
 
   public function SaveData($userData)
   {
	if($userData){
        	$model = static::find()->where(['openid'=>$userData->openid])->one();
                if(!$model){
                        $this->subscribe = $userData->subscribe;
                        $this->openid = $userData->openid;
                        $this->nickname = $userData->nickname;
                        $this->sex = $userData->sex;
                        $this->language = $userData->language;
                        $this->city = $userData->city;
                        $this->province = $userData->province;
                        $this->country = $userData->country;
                        $this->headimgurl = $userData->headimgurl;
                        $this->subscribe_time = $userData->subscribe_time;
                        $this->remark = $userData->remark;
                        $this->groupid = $userData->groupid;
                        //$this->tagid_list = $userData->tagid_list;
                        if ($this->save()) {
                        	echo "成功\n";
                        } else {
                                echo "失败\n";
                        }
               }
       }
	
   }
  */ 


}
