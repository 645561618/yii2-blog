<?php

/* @var $this yii\web\View */
use frontend\widgets\banner\BannerWidget;
use frontend\widgets\article\ArticleWidget;
use frontend\widgets\hot\HotWidget;
use frontend\widgets\tag\TagWidget;
use frontend\widgets\read\ReadWidget;
use frontend\widgets\links\LinksWidget;
use frontend\widgets\discuss\DiscussWidget;
use frontend\models\article\ArticleFront;

if($_GET){
	if((isset($_GET['cid']) && $name = ArticleFront::getCategory($_GET['cid'])) || (isset($_GET['tid']) && $name = ArticleFront::getLabelName($_GET['tid']))){
		$this->title = $name.'-黄信强博客';
		$this->metaTags[]="<meta name='keywords' content='".$name."-黄信强博客'/>";
		$this->metaTags[]="<meta name='description' content='黄信强博客,是一个基于yii2框架的技术博客网站'/>";
	
	}else{
		$this->title = '黄信强博客,hxinq博客,yii2博客,php个人博客,技术博客';
	        $this->metaTags[]="<meta name='keywords' content='黄信强博客,hxinq博客,yii2博客,php个人博客,技术博客'/>";
        	$this->metaTags[]="<meta name='description' content='黄信强博客,是一个基于yii2框架的技术博客网站'/>";
	}
}else{
	$this->title = '黄信强博客,hxinq博客,yii2博客,php个人博客,技术博客';
	$this->metaTags[]="<meta name='keywords' content='黄信强博客,hxinq博客,yii2博客,php个人博客,技术博客'/>";
	$this->metaTags[]="<meta name='description' content='黄信强博客,是一个基于yii2框架的技术博客网站'/>";
}
?>

<div class="row">
    <div class="col-lg-8">
	<!-- 文章列表 -->
        <?= ArticleWidget::widget() ?>
    </div>


    <div class="col-lg-4">
	<!--交流学习-->
        <?= DiscussWidget::widget(); ?>

	<!--点击排行 -->
        <?= ReadWidget::widget(); ?>
	
	<!--热门标签-->
	<?= TagWidget::widget();?>	

	<!--热门推荐-->
        <?= HotWidget::widget(); ?>
	
	
	<!--友情链接-->
	<?= LinksWidget::widget();?>

    </div>

</div>
