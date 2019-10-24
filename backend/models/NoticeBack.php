<?php
namespace backend\models;

use Yii;
use common\models\Notice;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class NoticeBack extends Notice
{
        
    public function behaviors()
    {   
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['modify'],
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
            [['id','content','status'], 'safe'],
        ];
    }

    public function attributelabels()
    {
    	return[
    		'id' => 'ID',
            'content' => '公告通知内容',
			'status'=>'状态', 
    	];
    }

    public function search()
    {
        $query = static::find()->orderBy('created DESC');
        $dataProvider = new ActiveDataProvider([
            'query' =>$query,
            'sort'=>false,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
        
        return $dataProvider;
    }

    //编辑
    public function updateNotice($data){
       if($this->load($data) && $this->save()) {
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
}

