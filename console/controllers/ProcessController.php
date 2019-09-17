<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use backend\models\OrderBack;
use backend\models\CustomerBack;
use backend\models\BalanceBack;
use backend\models\TotalBalanceBack;
use common\models\Article;
use common\components\xunsearch\Search;

class ProcessController extends Controller
{
    public function actionRun(){
	$filename = "/tmp/it.csv";
    	if (file_exists($filename)){
        	$myfile = fopen($filename, "r") or die("Unable to open file!");
	        while(!feof($myfile)) {
        	    $data = fgetcsv($myfile);
		    echo "<pre>";
		    print_r($data);
	            if ($data) {
        	        foreach ($data as $k => $v) {
        			 
                	}
            	    }
        	}
		exit;
        	fclose($myfile);
    	}	
    }

    //更改博客文章状态
    public function actionUpdateArticle()
    {
	$time = "2018-02-09 00:00:00";
	$start_time = strtotime($time);
	$day = intval(time()-$start_time);
	echo $day;
	$result = Article::find()->where(['status'=>0])->orderBy(['created'=>SORT_ASC])->one();
	if($result){
		$num = Article::find()->where(['status'=>0])->count(); 
		if($day >= 3*24*60*60 && $day < 6*24*60*60 && $num==5){
			self::SetArticle($result);
		}elseif($day >= 6*24*60*60 && $day < 9*24*60*60 && $num=4){
			self::SetArticle($result);
		}elseif($day >= 9*24*60*60 && $day < 12*24*60*60 && $num==3){
			self::SetArticle($result);
		}elseif($day >= 12*24*60*60 && $day < 15*24*60*60 && $num==2){
			self::SetArticle($result);
		}elseif($day >= 15*24*60*60 && $day < 18*24*60*60 && $num==1){
			self::SetArticle($result);
		}
	}
    }

    public function SetArticle($result)
    {
        $model = Article::findOne($result->id);
        if($model){
		$model->status = 2;
		$model->created = time();
		$model->modify = time();
		$model->save();
		$search = \common\components\xunsearch\Search::findOne($model->id);
		if(!$search){
			$search = new \common\components\xunsearch\Search;
		}
		$search->id = $model->id;
		$search->tid = $model->tid;
		$search->cid = $model->cid;
		$search->views = $model->views;
		$search->comment_nums = $model->comment_nums;
		$search->img = $model->img;
		$search->title = $model->title;
		$search->desc = Common::cutstr($model->desc,150);
		$search->status = $model->status;
		$search->weight = $model->weight;
		$search->created = $model->created;
		$search->save();
	}
	return false;

    }

    public function actionUpdateViews()
    {
	$model = Article::find()->where(['status'=>2])->all();
	if($model){
		foreach($model as $k => $v){
			$search = Search::findOne($v->id);
                        if($search){
        			$search->views = $v->views;
        			$search->save();
				echo "title=>".$v->title." , views=>".$v->views."\r\n";
                        }
		}	
	}
			
    }



} 
