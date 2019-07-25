<?php
namespace backend\models;
            
use Yii;
use yii\helpers\Html;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\Article;
class ArticleBack extends Article{
        
                
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
            [['title','cid','tid','desc'], 'required'],
            [['weight','img','is_recommend'],'safe'],
        ];
    }           
        
    public function attributelabels()
    {
        return[
                'cid' => '分类',
                'title' => '文章标题',
                'desc' => '文章描述',
                'img' => '封面图',
                'tid' => '标签',
                'weight'=>'排序',
                'views'=>'阅读量',
                'is_recommend'=>'是否推荐',
		'created' => '创建时间',
		'modify' => '修改时间',
        ];
    }




    public function getTime(){
	return function($data){
		return date('Y-m-d H:i:s',$data->created);
	};
    }

    public function getModify()
    {
	return function($data){
		return $data->modify==0?"":date('Y-m-d H:i:s',$data->modify);
	};
    }


    public function getStatus(){
	return function($data){
		if($data->status==0){
			return "<a class='btn btn-info btn-sm' href='/blog/check-article?id=".$data->id."&status=2'>审核通过</a>&nbsp;&nbsp;<a class='btn btn-warning btn-sm' href='/blog/check-article?id=".$data->id."&status=1'>审核不通过</a>";
		}elseif($data->status==1){
			return "审核未通过<i class='glyphicon glyphicon-remove'></i>";
		}elseif($data->status==2){
			return "审核通过<i class='glyphicon glyphicon-ok'></i>";
		}elseif($data->status==3){
			return "下架<i class='glyphicon glyphicon-arrow-down'></i>";
		}
	};
    }

    //推荐
    public function getRecommend(){
	return function($data){
		if($data->is_recommend==0){
			return "<i class='glyphicon glyphicon-remove-circle'></i>";
		}else{
			return "<i class='glyphicon glyphicon-ok-circle'></i>";
		}
	};
    }

    public function getRecommendValues(){
	return [
		'0'=>'不推荐',
		'1'=>'推荐',
	];
    }


    public function updateArticle($data){
	$this->load($data);
	if($this->save()){
		return true;
	}else{
		return false;
	}
    }



    //排序
    public function showSort()
    {
        return function($data){
                return "<input name='".$data->id."_sort' class='input-text' id='".$data->id."_sort' type='text' size='3' value='$data->weight'/>";
        };
    }

    //分类
    public function getCategoryTitle(){
        return function ($data) {
		$result = CategoryBack::find()->where(['status'=>1,'id'=>$data->cid])->one();
		if($result){
                    return "<a href='javascript:;'>".$result->title."</a>";
		}
        };
    }


    public function getCateValues()
    {
	$res =[];
	$result = CategoryBack::find()->where(['status'=>1])->orderBy("sort DESC,created DESC")->all();		
	foreach($result as $k=>$v){
		$res[$v->id]=$v->title;
	}
        return $res; 
    }

  //标签
   public function getLabelTitle(){
        return function ($data) {
                $result = LabelBack::find()->where(['status'=>1,'id'=>$data->tid])->one();
                if($result){
                    return "<a href='javascript:;'>".$result->title."</a>";
                }
        };
    }


    public function getLabelValues()
    {
        $res =[];
        $result = LabelBack::find()->where(['status'=>1])->orderBy("sort DESC,created DESC")->all();
        foreach($result as $k=>$v){
                $res[$v->id]=$v->title;
        }
        return $res;
    }







}
