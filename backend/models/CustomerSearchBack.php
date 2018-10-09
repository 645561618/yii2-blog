<?php
namespace backend\models;

use Yii;
use backend\models\CustomerBack;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class CustomerSearchBack extends CustomerBack{

    public function rules()
    {
        return [
            ['phone', 'safe'],
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

    public function getStatus(){
	return function($data){
            if($data->status == 0){
                return "正常";
            }else{
                return "<span style='color:red;'>被抢</span>";
            }
            return "";
        };
    }



}
