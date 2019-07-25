<?php 
namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\components\BackendActiveRecord;

class SafeRecord extends BackendActiveRecord{

    public static function collectionName(){
        return "safe_record";
    }

    public function rules(){
    	return [
    		[['ip','type','message'],'safe'],
    	];
    }
    public function attributelabels()
    {
        return[
            'ip' => '用户ip',
            'message' => '发布信息',
            'time' => '创建时间',
        ];
    }

    public function search($params)
    {
        $query = self::find()->orderBy(['time'=>SORT_DESC]);
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
        $query->andFilterWhere(['like','ip',$this->ip]);
        $query->andFilterWhere(['like','message',$this->message]);

        return $dataProvider;
    }

    public static function getType(){
        return function($data){
            if($data->type == 0){
                return "博客搜素";
            }
        };
    }

    public function getTime(){
        return function($data){
                return date('Y-m-d H:i:s',$data->time);
        };
    }



    

}





 ?>
