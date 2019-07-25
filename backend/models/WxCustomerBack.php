<?php 
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\WxCustomer;
class WxCustomerBack extends WxCustomer{


    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['modify'],
                ],
                'value'=>function(){
                    return time();
                }
            ],
        ];
    }



    public function rules()
    {
        return [
            [['wx_img'], 'required'],
        ];
    }

    public function attributelabels()
    {
    	return[
            'wx_img' => '微信二维码',
    	];
    }

    public function search()
    {
        $query = static::find();
        $dataProvider = new ActiveDataProvider([
            'query' =>$query,
            'sort'=>false,
            'pagination' => [
                'pageSize' => 1,
            ],
        ]);
        return $dataProvider;
    }

    public function updateWx($data)
    {   
        $this->load($data);
        if ($this->save()) {
            return true;
        } else {
            return false;
        }
    }


    public function getTime(){
        return function($data){
                return date('Y-m-d H:i:s',$data->created);
        };
    }

    public function WxImg(){
        return function($data){
                return "<a href='http://images.hxinq.com/$data->wx_img' target='_blank'><img src='http://images.hxinq.com/$data->wx_img' style='width:100px;height:100px;'></a>";
        };
   }

    //状态
    public function getStatus(){
        return function($data){
                if($data->status==0){
                        return "未使用";
                }elseif($model->status == 1){
                        return "已抽奖,待领取";
                }elseif($model->status == 2){
                        return "已领取,待发放";
                }elseif($model->status == 3){
                        return "已发放";
                }
        };
    }

    public function setStatusValues(){
        return [
                '0'=>'未使用',
                '1'=>'已抽奖,待领取',
                '2'=>'已领取,待发放',
                '3'=>'已发放',
        ];
    }



}



 ?>
