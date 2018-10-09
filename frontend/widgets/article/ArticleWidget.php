<?php

namespace frontend\widgets\article;

/**
 * 文章列表组件
 */

use Yii;
use yii\base\Widget;
use frontend\models\article\ArticleFront;
use common\components\SubPages;

class ArticleWidget extends Widget
{

    public function run()
    {
	$tid="";
	$cid="";
	if(isset($_GET['tid'])){
		$tid = intval($_GET['tid']);
	}
	if(isset($_GET['cid'])){
		$cid = intval($_GET['cid']);
	}
	$count = 10;
   	$sub_pages = 6;
	$pageCurrent = isset($_GET['p']) ? $_GET["p"] : 1;
	$num = ArticleFront::getCount($tid,$cid,"");
	$result = ArticleFront::getArticleInfo($tid,$cid,"",$count,$pageCurrent);
	$subPages = new SubPages($count, $num, $pageCurrent, $sub_pages, "?p=", 1);
	$p = $subPages->show_SubPages(2);
	return $this->render('index', ['data' => $result,'page'=>$p]);
    }
}
