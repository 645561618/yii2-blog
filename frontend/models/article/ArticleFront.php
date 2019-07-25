<?php
namespace frontend\models\article;

use Yii;
use common\models\Article;
use frontend\components\PageSubPages;
use common\models\Label;
use common\models\Category;
class ArticleFront extends Article
{

	public static function getCount($tid=0,$cid=0,$kw=""){
		if($tid==0 && $cid==0){
			if(empty($kw)){
				$data = static::find()->where(['status'=>2])->count();
			}else{
				$data = static::find()->where(['status'=>2])->andWhere("concat(`title`,`desc`) like :search_kwd")->addParams([':search_kwd'=>'%'.$kw.'%'])->count();
			}
		}elseif($tid!=0 && $cid==0){
			$data = static::find()->where(['status'=>2,'tid'=>$tid])->count();
		}elseif($tid==0 && $cid!=0){
			$data = static::find()->where(['status'=>2,'cid'=>$cid])->count();
		}elseif($tid !=0 && $cid !=0){
			$data = static::find()->where(['status'=>2,'tid'=>$tid,'cid'=>$cid])->count();
		}
		return $data;
	}
	
	public static function getArticleInfo($tid=0,$cid=0,$kw="",$count, $page)
	{
		$offset = ($page - 1) * $count;
		if($tid==0 && $cid==0){
			if(empty($kw)){
				$result = static::find()->where(['status'=>2])->orderBy('weight DESC,created DESC')->limit($count)->offset($offset)->all();	
			}else{
				$result = static::find()->where(['status'=>2])->andWhere("concat(`title`,`desc`) like :search_kwd")->addParams([':search_kwd'=>'%'.$kw.'%'])->limit($count)->offset($offset)->all();
			}
		}elseif($tid!=0 && $cid==0){
			$result = static::find()->where(['status'=>2,'tid'=>$tid])->orderBy('weight DESC,created DESC')->limit($count)->offset($offset)->all();	
		}elseif($tid==0 && $cid!=0){
			$result = static::find()->where(['status'=>2,'cid'=>$cid])->orderBy('weight DESC,created DESC')->limit($count)->offset($offset)->all();	
		}elseif($tid !=0 && $cid !=0){
			$result = static::find()->where(['status'=>2,'tid'=>$tid,'cid'=>$cid])->orderBy('weight DESC,created DESC')->limit($count)->offset($offset)->all();	
		}
        	return $result;
	}

	public static function getViewsArticle($recommend=0){
		if($recommend==1){
			return static::find()->where(['status'=>2,'is_recommend'=>1])->orderBy("weight DESC,created DESC")->limit(10)->all();
		}
		return static::find()->where(['status'=>2])->orderBy("views DESC,created DESC")->limit(10)->all();
	}

	public static function getLabelName($id){
		$label = Label::findOne($id);
		if($label){
			return $label->title;
		}
		return "";
	}

	public static function getCategory($id){
                $cate = Category::findOne($id);
                if($cate){
                        return $cate->title;
                }
                return "";
        }
	
	

}
