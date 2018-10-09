<?php 
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\SafeConfig;
class SafeConfigBack extends SafeConfig{


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
                    return time();
                }
            ],
        ];
    }



    public function rules()
    {
        return [
            [['type','nums','percent','length','words'], 'required'],
            [['ip'], 'safe'],
        ];
    }

    public function attributelabels()
    {
    	return[
            'type' => '类型',
            'ip' => '被禁IP',
            'nums' => '次数限制',
            'percent' => '文本相似度',
            'length' => '字数限制',
            'words' => '关键字',
    	];
    }

    public function search()
    {
        $query = static::find();
        $dataProvider = new ActiveDataProvider([
            'query' =>$query,
            'sort'=>false,
        ]);
        return $dataProvider;
    }

    public function updateSafe($data)
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

    public function getType()
    {
	return function($data){
		switch($data->type){
			case 1:
				return "搜索";
			break;
			case 2:
				return "评论";
			break;
		}
	};

    }



}



 ?>
