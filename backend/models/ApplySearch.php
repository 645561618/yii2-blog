<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use backend\models\ApplyBack;
class ApplySearch extends ApplyBack{


    public function rules()
    {
        return [
            [['name','sex','status','email','number','bank_name','bank_code','phone'], 'safe'],
        ];
    }

 

    public function search($params)
    {
        $query = static::find()->where(['is_del'=>0])->orderBy('add DESC');
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
        $query->andFilterWhere(['like','name',$this->name]);
        $query->andFilterWhere(['like','phone',$this->phone]);
        $query->andFilterWhere(['like','email',$this->email]);
        $query->andFilterWhere(['like','bank_name',$this->bank_name]);
        $query->andFilterWhere(['like','bank_code',$this->bank_code]);
        $query->andFilterWhere(['like','add',$this->add]);
        $query->andFilterWhere(['like','sex',$this->sex]);
        $query->andFilterWhere(['like','status',$this->status]);
        return $dataProvider;
    }


    public function Count(){
	return static::find()->where(['is_del'=>0])->count();
    }


}
