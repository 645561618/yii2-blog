<?php
namespace backend\models;
            
use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\Category;
class CategoryBack extends Category{
        
                
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
            [['url','pid','sort','status','created'],'safe'],
        ];
    }           
        
    public function attributelabels()
    {
        return[
                'title' => '分类名',
                'pid' => '父级分类ID',
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
			return "关闭";
		}elseif($data->status==1){
			return "开启";
		}
	};
    }

    public function updatecate($data){
	if($this->load($data) && $this->save()){
		return true;
	}else{
		return false;
	}
    }

    public function getSonCate(){
	return function($data){
		return "<a href='/blog/add-soncate?id=".$data->id."'>二级菜单</a>";
	};
    }

    public static function findFirstCateAsArray()
    {
        return static::find()->where(['pid'=>0,'status'=>1])->orderBy("sort DESC")->all();
    }


    public function getTwoCate($id){
        $descendants = static::find()->select('id,title')->where(['pid'=>$id])->asArray()->all();
        return $descendants;
    }


}
