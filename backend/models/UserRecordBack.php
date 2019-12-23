<?php
namespace backend\models;

use Yii;
use common\models\UserCenter;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class UserRecordBack extends UserCenter
{
        
    public function attributelabels()
    {
        return[
           'nickname' => '用户昵称',
           'gender'=>'性别',
           'province'=>'省份',
           'city'=>'城市',
        ];
    }
    
    public function search()
    {
        $query = static::find()->orderBy('time DESC');
        $dataProvider = new ActiveDataProvider([
            'query' =>$query,
            'sort'=>false,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
        
        return $dataProvider;
    }


    public function getTime(){
		return function($data){
			return date('Y-m-d H:i:s',$data->time);
		};
    }

    public function getHeadImg(){
        return function($data){
            return '<img src="'.$data->headimgurl.'" style="width:100px;height:100px;"/>';
        };
    }

    public function getType(){
        return function($data){
            if($data->type==0){
                return "QQ";
            }elseif ($data->type==1) {
                return "weibo";
            }elseif ($data->type==2) {
                return "github";
            }else{
                return "QQ";
            }
        };
    }
}

