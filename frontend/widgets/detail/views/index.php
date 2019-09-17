<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<style>
pre{
   	background: #282c34;
}
pre code{
	color:#abb2bf;
}
</style>
<div class="panel pannel-detail">
    <div class="detail-top">
	<h2><?=Html::encode($model->title)?></h2>
    	<div class="row row-info row-detail">
        	<span class="articel-label"><a href="<?=Url::toRoute(['/home/index','tid'=>$model->tid])?>"><?=Html::encode($label)?></a></span>
                <span class="time"><?=date('Y-m-d',$model->created)?></span>
                <span class="views">浏览（<a href="javascript:;"><?=isset($model->views)?$model->views:0?></a>）</span>
                <span class="comment f_r">评论（<a href="javascript:;"><?=isset($model->comment_nums)?$model->comment_nums:0?></a>）</span>
        </div>
    </div>
    <div class="article-detail" style="">
	<?=yii\helpers\Markdown::process($model->desc,'gfm');?>
    </div>
</div>

