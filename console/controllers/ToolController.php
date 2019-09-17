<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Common;
use backend\models\ArticleBack;
use backend\models\LinksBack;
use backend\models\WxUserInfoBack;
use common\models\UserCenter;
use backend\models\DataTotalBack;

class ToolController extends Controller
{
    public function actionRun()
    {
	    for ($i=0; $i <=22; $i++) { 
            $start_month = date('Y-m',strtotime("-{$i} month"));
            $start = strtotime($start_month);
            $end_month = date("Y-m",strtotime("$start_month +1 month"));
            $end = strtotime($end_month);
            //user
            $UserNum = UserCenter::find()->where(['>=','time',$start])->andwhere(['<','time',$end])->count();
            //blog
            $BlogNum = ArticleBack::find()->where(['status'=>'2'])->andwhere(['>=','created',$start])->andwhere(['<','created',$end])->count();
            //fans  
            $FansNum = WxUserInfoBack::find()->where(['>=','subscribe_time',$start])->andwhere(['<','subscribe_time',$end])->count();
            //Link
            $LinkNum = LinksBack::find()->where(['>=','created',$start])->andwhere(['<','created',$end])->count();
            $model = DataTotalBack::find()->where(['=','datetime',$start_month])->one();
            if(!$model){
                $model = new DataTotalBack;
            }
            $model->UserNum = $UserNum;
            $model->BlogNum = $BlogNum;
            $model->FansNum = $FansNum;
            $model->LinkNum = $LinkNum;
            $model->datetime = $start_month;
            $model->save(false);
            echo $start_month."=>".$UserNum.'-->--'.$BlogNum."-->--".$FansNum."-->--".$LinkNum."\r\n";
        }
        
    }


    



}





