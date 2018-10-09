<?php
namespace backend\models;

use Yii;
use backend\models\BalanceBack;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class BalanceSearchBack extends BalanceBack{

    public function rules()
    {
        return [
            ['created', 'safe'],
            ['balance_des', 'safe'],
        ];
    }

    public function attributelabels()
    {
        return[
                'balance_des' => '明细名称',
                'detail' => '收入/支出',
                'account' => '余额',
                'created' => '时间',
        ];
    }


    public function search($params,$uid)
    {
        $query = static::find()->where(['uid'=>$uid])->orderBy('created DESC');
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
        $query->andFilterWhere(['like','created',$this->created]);
        $query->andFilterWhere(['like','balance_des',$this->balance_des]);
        return $dataProvider;
    }

}

