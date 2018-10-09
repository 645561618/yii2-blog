<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use frontend\widgets\banner\BannerWidget;
use frontend\widgets\article\ArticleWidget;
use frontend\widgets\hot\HotWidget;
use frontend\widgets\tag\TagWidget;
use frontend\widgets\read\ReadWidget;
use frontend\widgets\detail\DetailWidget;
use frontend\widgets\links\LinksWidget;
use frontend\widgets\discuss\DiscussWidget;
use frontend\widgets\comment\CommentWidget;
use frontend\models\article\ArticleFront;
$model = ArticleFront::find()->where(['id'=>$_GET['id'],'cid'=>$_GET['cid']])->one();
if($model){
	$this->title = $model->title.'-黄信强博客';
	$this->metaTags[]="<meta name='keywords' content=".$model->title."-黄信强博客/>";
	$this->metaTags[]="<meta name='description' content=".mb_substr($model->desc,0,60,'utf-8').".../>";
	$cateTitle = ArticleFront::getCategory($model->cid);
	if($cateTitle){
		$this->params['breadcrumbs'][] = ['label' => $cateTitle, 'url' =>Url::toRoute(['/home/index','cid'=>$model->cid])];
       		$this->params['breadcrumbs'][] = $model->title;
	}

}else{
	$this->title = '-黄信强博客,hxinq博客,yii2博客,php博客,技术博客';
        $this->metaTags[]="<meta name='keywords' content=-黄信强博客,hxinq博客,yii2博客,php博客,技术博客/>";
        $this->metaTags[]="<meta name='description' content='黄信强博客,是一个基于yii2框架的技术博客网站'/>";
}
?>

<div class="row">
    <div class="col-lg-8">
	<?= DetailWidget::widget(); ?>		

	<div class="gave">
            <p><a href="javascript:;" id="gave">打赏</a></p>
	    <p>如果此文对你有所帮助，请随意打赏鼓励作者^_^</p>
            <div class="code" id="wechatCode" style="display: none;">
                <img src="http://img.hxinq.com/article/day_180510/20180510_ed6525f.jpg.thumb.jpg" alt="微信扫一扫支付" data-tag="bdshare">
            </div>
        </div>

	<?= CommentWidget::widget(); ?>		
	
	
    </div>

    <div class="col-lg-4">
	<!--交流学习-->
        <?= DiscussWidget::widget(); ?>

	<!--阅读排名-->
        <?= ReadWidget::widget(); ?>

	<!--标签云-->
	<?= TagWidget::widget();?>	

	<!-- 热门推荐 -->
        <?= HotWidget::widget(); ?>

	<!--友情链接-->
        <?= LinksWidget::widget();?>



    </div>

</div>
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script>
	$(function(){
		//鼠标的移入移出  
		$("#gave").mouseover(function (){  
		    $("#wechatCode").show();  
		}).mouseout(function (){  
		    $("#wechatCode").hide();  
		});  
	})
</script>
