<?php

namespace backend\components;
use backend\models\Log;
use Yii;

class Helper {

    public static function hello(){
        echo 'hello';
    }

    //历史访客数
    public static function getHistoryVisNum(){
        $res = Log::find()->count();
        return $res;
    }

    //最近一个月访问量
    public static function getMonthHistoryVisNum(){
        $LastMonth = strtotime("-1 month");
        $res = Log::find()->where(['>','created',$LastMonth])->count();
        return $res;
    }

}
