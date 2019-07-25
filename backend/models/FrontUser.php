<?php
namespace backend\models;

use Yii;
use backend\models\CustomerBack;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\register\LoginUser;
class FrontUser extends LoginUser{

    public function rules()
    {
    	return[
    		[['username','phone','password'],'required'],
    	];
    }

    public function attributelabels()
    {
    	return[
    		'id' => '商家ID',
            	'username' => '商家名称',
		'password' => '密码',
		'phone' => '手机号',
	        'created' => '创建时间',
    	];
    }


    public function search()
    {
        $query = static::find();
        $dataProvider = new ActiveDataProvider([
            'query' =>$query,
            'sort'=>false,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
        return $dataProvider;
    }


    public function SetshopBalance(){
        return function($data){
            return "<a href='/business/balance?uid=".$data->id."'>账户充值</a>";
        };
   }

   public function getBalanceList(){
        return function($data){
            return "<a href='/business/balance-list?uid=".$data->id."'>商家余额明细</a>";
        };
   }


}

