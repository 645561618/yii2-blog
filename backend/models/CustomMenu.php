<?php
namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\components\BackendActiveRecord;
use common\models\Common;
class CustomMenu extends BackendActiveRecord
{
    public static function tableName()
    {
        return "custom_menu";
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created'],
                ],
                'value'=>function(){return date("Y-m-d H:i:s");},
            ],
        ];
    }

    public function rules(){
        return [
            [['type','weight','name'],'required'],
            [['fid','content','status'],'safe'],
        ];
    }

    public function attributeLabels(){
        return [
            'fid'=>'父ID',
            'type'=>'类型',
            'name'=>'菜单名称',
            'content'=>'关键字或链接',
            'created'=>'创建时间',
            'status'=>'是否开启',
            'weight'=>'权重',
        ];
    }

    /**
     * 显示类型
     */
    public function showType()
    {
        return function($data){
            if($data->type == 'click'){
                return "<a href='/keywords/addtype?id=$data->id&type=0'>click</a>";
            }elseif($data->type=='view'){
                return "<font color='green'>view</font>";
            }elseif($data->type=='location_select'){
		return "<font color='red'>location_select</font>";
	    }
        };
    }


    public function showOpen()
    {               
        return function($data){
            if($data->status == 0){
                return "否";
            }else{
                return "是";
            }
        };
    }



    /*public function showName(){
	return function($data){
		$name = base64_decode($data->name);
		return $name;
	};
   }*/



    /**
     * type下拉
     * @return array
     */
    public function getDataType()
    {
        return [
            "click"=>"关键字",
            "view"=>"链接",
	    "location_select"=>"发送位置",
        ];
    }


    public function createMenu()
    {
        $access_token = Common::getAccessToken();
        $data = $this->getMenuData();
        //$data1 = $this->getData();
        if($access_token && !empty($data))
        {

            $jsonmenu = urldecode(json_encode(['button'=>$data]));
	    //echo "<pre>";
	    //print_r($jsonmenu);exit;
            $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
	    //$result = $this->https_request($url, $data1);
            $result = Common::https_request($url, $jsonmenu);
            return json_decode($result,true);
        }else{
            return [];
        }
    }


    public function getData(){

	return $data='{
		 "button":[
		 {
			   "name":"公共查询",
			   "sub_button":[
				{
				   "type":"click",
				   "name":"天气查询",
				   "key":"tianQi"
				}]
		  },
		  {
			   "name":"万年本地",
			   "sub_button":[
				{
				   "type":"view",
				   "name":"爱上万年",
				   "url":"http://f.hxinq.com"
				}]
		   },
		   {
			   "type":"view",
			   "name":"百度一下",
			   "url":"http://www.baidu.com"
		   }]
       }';

   }



    public function getMenuson()
    {
        return $this->hasMany(CustomMenu::className(),['fid'=>'id'])->onCondition(['status'=>1]);
    }

    public function getMenuData()
    {
        $data = self::find()->with("menuson")->where(['status'=>1,'fid'=>0])->orderBy(['weight'=>SORT_DESC])->asArray()->all();
        $rs = [];
        if($data){
            foreach($data as $k => $value)
            {
                $rs[$k]['name'] = urlencode($value['name']);
                if(!empty($value['menuson'])){
                    foreach($value['menuson'] as $v){
                        if($v['type'] == 'view'){
                            $sub_key = 'url';
                        }else{
                            $sub_key = 'key';
                        }
                        $rs[$k]['sub_button'][] = [
                            'type' => $v['type'],
                            'name' => urlencode($v['name']),
                            $sub_key => urlencode($v['content']),
                        ];
                    }
                }else{
                    $rs[$k]['type'] = $value['type'];
                    if($value['type'] == 'view'){
                        $key = 'url';
                    }else{
                        $key = 'key';
                    }
                    $rs[$k][$key] = urlencode($value['content']);
                }
            }
        }	
        return $rs;
    }


}
