<?php

namespace frontend\widgets\search;

/**
 * 搜索组件
 */

use Yii;
use yii\base\Widget;
use frontend\models\article\ArticleFront;
use common\components\SubPages;
use common\models\Common;
use common\components\xunsearch\Search;

class SearchWidget extends Widget
{

    public function run()
    {
	try{
		if(Yii::$app->request->get()){
			$get = Yii::$app->request->get();
			$content = trim($get['kw']);
			$content = strip_tags($content);//过滤掉输入、输出里面的恶意标签,如<script></script>
			$content = trim(Common::replaceSpecialChar($content));
			$content = htmlspecialchars($content);//转义
			//echo $content;
			$ip = Yii::$app->request->userIP;
			if(isset($content) && !empty($content)){	
				$code = Yii::$app->PublicService->auth($ip,1,$content);	
				switch($code){
					case 10002:Yii::$app->session->setFlash('error','您的IP已禁止搜索');return;break;
					case 10003:Yii::$app->session->setFlash('error','您的操作太频繁');return;break;
					case 10004:Yii::$app->session->setFlash('error','您搜索的文字过长');return;break;
					case 10005:Yii::$app->session->setFlash('error','您搜索的关键词已被锁定');return;break;
					case 10006:if(!isset($_GET['p'])){Yii::$app->session->setFlash('error','重复搜索相同的关键词');return;}break;
				}
				$count = 10;
		   		$sub_pages = 6;
				$page = isset($_GET['p']) ? $_GET["p"] : 1;
				$page = intval($page);
				//mysql模糊查询
				//$num = ArticleFront::getCount(0,0,$content);
				//$result = ArticleFront::getArticleInfo(0,0,$content,$count,$pageCurrent);
				//xunsearch查询	
				$num = Search::find()->where(['status'=>2])->andWhere($content)->count();
				$result = Search::getArticleInfo($page,$count,$content);	
				$subPages = new SubPages($count, $num, $page, $sub_pages, "?kw=".$content."&p=", 1);
				$p = $subPages->show_SubPages(2);
				return $this->render('index', ['data' => $result,'page'=>$p]);
			}else{
				Yii::$app->session->setFlash('error','搜索不能为空');
			}
		}
	}catch(\Exception $e){
		Yii::$app->session->setFlash('error',$e->getMessage());
	}
    }
}
