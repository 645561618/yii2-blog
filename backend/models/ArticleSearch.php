<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use backend\models\ArticleBack;
class ArticleSearch extends ArticleBack{


    public function rules()
    {
        return [
            [['is_recommend','tid','cid','title'], 'safe'],
        ];
    }



    public function search($params)
    {
        $query = static::find()->orderBy('weight DESC,created DESC');
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
        $query->andFilterWhere(['like','title',$this->title]);
        $query->andFilterWhere(['cid'=>$this->cid]);
        $query->andFilterWhere(['tid'=>$this->tid]);
        $query->andFilterWhere(['is_recommend'=>$this->is_recommend]);
        return $dataProvider;
    }



}

