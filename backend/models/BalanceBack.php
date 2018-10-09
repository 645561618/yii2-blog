<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\Balance;
class BalanceBack extends Balance{

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
                [['account'],'required'],
        ];
    }

    public function attributelabels()
    {
        return[
                'account' => '充值',
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

