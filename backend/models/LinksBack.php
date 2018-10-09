<?php
namespace backend\models;
            
use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\Links;
class LinksBack extends Links{
        
                
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
            [['title','url','sort','status'], 'required'],
            [['created','modify'],'safe'],
        ];
    }           
        
    public function attributelabels()
    {
        return[
                'title' => '链接名称',
                'url' => '链接',
                'sort'=>'排序',
                'status'=>'是否开启',
		'created' => '创建时间',		
		'modify' => '修改时间',

        ];
    }

    public function search()
    {
        $query = static::find()->orderBy('sort ASC');
        $dataProvider = new ActiveDataProvider([
            'query' =>$query,
            'sort'=>false,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        return $dataProvider;
    }


    public function getTime(){
	return function($data){
		return date('Y-m-d H:i:s',$data->created);
	};
    }

    public function getStatus(){
	return function($data){
		if($data->status==0){
			return "关闭<i class='glyphicon glyphicon-remove'></i>";
		}elseif($data->status==1){
                        return "开启<i class='glyphicon glyphicon-ok'></i>";
		}
	};
    }

    public function updatelinks($data){
	if($this->load($data) && $this->save()){
		return true;
	}else{
		return false;
	}
    }


    //排序
    public function showSort()
    {
        return function($data){
                return "<input name='".$data->id."_sort' class='input-text' id='".$data->id."_sort' type='text' size='3' value='$data->sort'/>";
        };
    }





}
