<?php
namespace backend\models;

use Yii;
use common\models\customer\FalseCustomer;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class FalseCustomerBack extends FalseCustomer{

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
                [['username','phone','time'],'required'],
        ];
    }

    public function attributelabels()
    {
        return[
                'username' => '称谓',
                'phone' => '手机号',
                'time' => '婚期',
                'created' => '日期',
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
        $query->andFilterWhere(['like','phone',$this->phone]);
        return $dataProvider;
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

