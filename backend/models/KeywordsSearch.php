<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use backend\models\KeywordsBack;
class KeywordsSearch extends KeywordsBack{


    public function rules()
    {
        return [
            [['words','reply','created'], 'safe'],
        ];
    }



    public function search($params)
    {
        $query = static::find()->orderBy('sort DESC,created DESC');
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
        $query->andFilterWhere(['like','words',$this->words]);
        $query->andFilterWhere(['like','reply',$this->reply]);
        $query->andFilterWhere(['like','created',$this->created]);
        return $dataProvider;
    }


    public function Count(){
        return static::find()->where(['is_del'=>0])->count();
    }


}

