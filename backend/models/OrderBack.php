<?php
namespace backend\models;

use Yii;
use common\models\Order;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\register\LoginUser;
class OrderBack extends Order{
	
    public function rules()
    {
        return [
            [['username','phone','id','created','status'], 'safe'],
        ];
    }

    public function attributelabels()
    {
    	return[
    		'id' => '订单ID',
                'username' => '客户名称',
                'phone' => '手机号',
    		'created' => '订单时间',
		'price' => '订单金额',
		'commission' => '佣金',
		'status'=>'订单状态', 
    	];
    }

    public function search($params)
    {
        $query = static::find()->orderBy('created DESC');
        $dataProvider = new ActiveDataProvider([
            'query' =>$query,
            'sort'=>false,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like','username',$this->username]);
        $query->andFilterWhere(['like','phone',$this->phone]);
        $query->andFilterWhere(['like','id',$this->id]);
        $query->andFilterWhere(['like','created',$this->created]);
        $query->andFilterWhere(['like','status',$this->status]);
        return $dataProvider;
    }
    
    public function getcheckStatus(){
        return function($data){
            if($data->status == 0){
                return "<a href='/shop/status?id=".$data->id."'>洽谈中</a>";
            }else if($data->status ==1){
                return "<a href='javascript:void(0);' style='color:green'>已签约</a>";
            }else if($data->status ==2){
		return "<a href='javascript:void(0);' style='color:red'>未签约</a>";
	    }
            return "";
        };
    }

    public function getShopUser(){
	return function($data){
		$result  = LoginUser::find()->where(['id'=>$data->shop_uid])->one();
		if($result){
			return $result->username;
		}
		return "";
	};	
    } 

    public function setOrderPrice(){
        return function($data){
                return "<a href='/shop/price?id=".$data->id."'>设置订单金额</a>";
        };
    }

    public function updatePrice($data)
    {
	$this->price = $data['OrderBack']['price'];
	$this->commission = ceil(($data['OrderBack']['price'])*0.05);
    	if ($this->load($data) && $this->save()) {
    	    return true;
    	} else {
    	    return false;
    	}
    }


   public function getUrl(){
        return function($data){
		if($data->url){	
                	return "<a href='".Yii::$app->params['targetDomain'].$data->url."' target='_blank'><img src='".Yii::$app->params['targetDomain'].$data->url."' style='height:100px;width:100px;' /></a>";
		}
                return "";
        };
    }

   public function getBalance(){
        return function($data){
		$result = TotalBalanceBack::find()->where(['uid'=>$data->shop_uid])->one();
               return $result->total_account;
        };
   }

  public function getOrderStatus(){
	return function ($data) {
            switch ($data->status) {
                case 0:
                    return "<a href='/shop/status?id=".$data->id."'>洽谈中</a>";
                case 1:
                    return "<a href='javascript:void(0);' style='color:green'>已签约</a>";
                case 2:
                    return "<a href='javascript:void(0);' style='color:red'>未签约</a>";
            }
        };
    }


  public function orderValues()
    {
        return [
            0 => '洽谈中',
            1 => '已签约',
            2 => '未签约',
        ];
    }

   public function getUpload(){
        return function($data){
               return "<a href='/upload/index?id=".$data->id."' >上传</a>";
        };
    }

}

