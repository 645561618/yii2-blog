<?php

/* @var $this yii\web\View */
use frontend\widgets\banner\BannerWidget;
use frontend\widgets\article\ArticleWidget;
use frontend\widgets\hot\HotWidget;
use frontend\widgets\tag\TagWidget;
use frontend\widgets\read\ReadWidget;
use frontend\widgets\links\LinksWidget;
use frontend\widgets\discuss\DiscussWidget;
use frontend\widgets\search\SearchWidget;


if(isset($_GET['kw']) && !empty(trim($_GET['kw']))){
        $this->title = $_GET['kw'].'-黄信强博客';
        $this->metaTags[]="<meta name='keywords' content=".$_GET['kw']."-黄信强博客/>";
        $this->metaTags[]="<meta name='description' content=黄信强博客本次为您找到了关于".$_GET['kw']."的相关文章/>";
}else{
        $this->title = '黄信强博客,hxinq博客,yii2博客,php个人博客,技术博客';
        $this->metaTags[]="<meta name='keywords' content=-黄信强博客,hxinq博客,yii2博客,php个人博客,技术博客/>";
        $this->metaTags[]="<meta name='description' content='黄信强博客,是一个基于yii2框架的技术博客网站'/>";
}
?>

<div class="row">
    <div class="col-lg-8">
	<!-- 搜索的文章列表 -->
        <?= SearchWidget::widget() ?>

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
