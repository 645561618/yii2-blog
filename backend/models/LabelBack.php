<?php
namespace backend\models;
            
use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\Label;
class LabelBack extends Label{
        
                
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
            [['title'], 'required'],
            [['aid','sort','status','created'],'safe'],
        ];
    }           
        
    public function attributelabels()
    {
        return[
                'title' => '标签名',
                'aid' => '文章ID',
                'sort'=>'排序',
                'status'=>'是否开启',
		'created' => '创建时间',
        ];
    }

    public function search()
    {
        $query = static::find()->orderBy('sort DESC,created DESC');
        $dataProvider = new ActiveDataProvider([
            'query' =>$query,
            'sort'=>false,
            'pagination' => [
                'pageSize' => 10,
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

    public function updatelabel($data){
	if($this->load($data) && $this->save()){
		return true;
	}else{
		return false;
	}
    }

    public function getSonLable(){
	return function($data){
		return "<a href='/blog/add-sonlable?id=".$data->id."'>二级标签</a>";
	};
    }

    public static function findFirstLableAsArray()
    {
	return static::find()->where(['status'=>1])->orderBy("sort DESC")->all();
    }


    public function getTwoLable($id){
        $descendants = static::find()->select('id,title')->where(['pid'=>$id])->asArray()->all();
        return $descendants;
    }

   public static function findAllTags(){
        $result = static::find()->select('id,title')->asArray()->all();
	foreach($result as $k=>$v){
		$data[$v['id']] =  $v['title'];
	}
	return $data;
    } 




}
