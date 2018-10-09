<?php
namespace backend\models;

use Yii;
use common\models\customer\Customer;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class CustomerBack extends Customer{

	public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['created'],
                ],
                'value'=>function(){
                    return date("Y-m-d H:i:s");
                }
            ],
        ];
    }

    public function rules()
    {
    	return[
    		[['username','phone','time','money','style','remark'],'required'],
    	];
    }

    public function attributelabels()
    {
    	return[
    		'username' => '称谓',
            	'phone' => '手机号',
            	'time' => '婚期',
    		'money' => '结婚预算',
		'style' => '风格',
		'remark' => '备注',
		'created' => '日期',
    	];
    }



    //编辑
    public function updateCustomer($data){
       if($this->load($data) && $this->save()) {
            return true;
        } else {
            return false;
        } 
    }

}

