<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;

class TestController extends Controller
{
	//字符串【key-value】类型
	public function actionSet()
	{
		$time = 5;//5秒过期
		Yii::$app->redis->setValue('name','小红',$time);
	}

	//字符串【key-value】类型
        public function actionGet()
        {
             echo  Yii::$app->redis->getValue('name');
        }


	//哈希类型--设置
	public function actionHset()
	{
		Yii::$app->redis->hset('user','name','xiaoqiang','sex','男','age',18,'weight','120');
	}
	
	//哈希类型--获取所有
	public function actionHgetAll()
	{
		$data = Yii::$app->redis->hgetAll('user');
		echo "<pre>";
		print_r($data);
	}
	
        //哈希类型--获取所有
        public function actionHget()
        {
                $data = Yii::$app->redis->hmget('user','name');
                echo "<pre>";
                print_r($data);
        }


	 //队列测试-左侧压入
        public function actionLpush()
        {
                Yii::$app->redis->lPush('iphone10','huangxinqiang-'.mt_rand(1,1000));
        }

        //队列测试-右侧弹出
        public function actionRpop()
        {
                echo Yii::$app->redis->rPop('iphone10');
        }

	//列表类型-范围显示
	public function actionLrange()
	{
		echo "<pre>";
		print_r(Yii::$app->redis->lRange('iphone10'));
	}
	//列表类型-显示个数
	public function actionLlen()
	{
		echo Yii::$app->redis->lLen('iphone10');
	}
	

	//xunsearch
	public function actionXunSearch()
	{
		
		//$model = new \common\components\xunsearch\Search;
		//$model = \common\components\xunsearch\Search::findOne(59);
		//$model->delete();
		//$model->deleteAll();
		//$model->id = 3;
		//$model->title = 'Redis安装和使用';
		//$model->desc = '黄信强';
		//$model->save();
		 
		//$model = Demo::findOne(321);
		//$model->message .= ' + updated';
		//$model->save();
		 
		 
		// 添加或更新索引还支持以方法添加索引词或文本
		// 这样做的目的是使得可以通过这些关键词检索到数据，但并非数据的字段值
		// 用法与 XSDocument::addTerm() 和 XSDocument::addIndex() 等同
		// 通常在 ActiveRecord::beforeSave() 中做这些操作
		//$model->addTerm('subject', 'hi');
		//$model->addIndex('subject', '你好，世界');	
	
	}

	public function actionSearch()
	{
		$condition="xunsearch";
		$count = \common\components\xunsearch\Search::find()->andWhere($condition)->count();
		$query = \common\components\xunsearch\Search::find()->andWhere($condition)->orderBy(['created'=>SORT_DESC])->limit($count)->asArray()->all();
		//$page=1;
		//$count=20;
		//$query = \common\components\xunsearch\Search::getArticleInfo($page,$count,$condition);
		//echo $count."\n";
		echo "<pre>";
		print_r($query);exit;
		
	}


}
