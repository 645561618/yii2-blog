<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use frontend\models\article\ArticleFront;
use common\models\Common;
?>
<div class="panel" style="box-shadow: 2px 2px 2px 2px #E2E2E2;">
    <div class="panel-title box-title">
        <span>热门文章</span>
    </div>
    <div class="new-list">
	<?php if($data){?>
        <?php foreach ($data as $list){?>
	    <div class="blogs">
		<div class="row">
                    <div class="col-lg-4 col-md-4 col-xs-4 label-img-size">
			<a href="<?=Url::toRoute(['home/detail', 'id' =>$list['id'],'cid'=>$list['cid']]);?>" class="post-label">
	        		<img class="img-responsive" src="<?php echo Yii::$app->params['domain']?><?=$list['img']?$list['img']:"article/day_171026/20171026_4a60697.jpg.thumb.thumb.jpg"?>" alt="黄信强博客" title="黄信强博客">
			<a>
		    </div>
		    <div class="col-lg-8 col-md-8 col-xs-12">
	        	  <h3 style="margin-top:8px;"><a class="atitle" target="_blank" title="<?=$list['title']?>" href="<?=Url::toRoute(['home/detail', 'id' =>$list['id'],'cid'=>$list['cid']]);?>"><?=Html::encode($list['title'])?></a></h3>
		          <!--<p class="adesc"><?=mb_substr($list['desc'],0,150,'utf-8');?>...</p>-->
		          <p class="adesc"><?=Html::encode(Common::cutstr($list['desc'],150));?></p>
			  <div class="row row-info">
				<span class="articel-label col-lg-2 col-md-2 col-xs-6"><a href="<?=Url::toRoute(['/home/index','tid'=>$list['tid']])?>"><?=Html::encode(ArticleFront::getLabelName($list['tid']))?></a></span>
				<span class="views col-lg-7 col-md-2 col-xs-6">浏览（<a href="javascript:;"><?=isset($list['views'])?$list['views']:0?></a>）</span>
				<span class="time col-lg-3 col-md-3 col-xs-6"><?=Html::encode(date('Y-m-d',$list['created']))?></span>
			  </div>
		   </div>
	        </div>
            </div>
	<?php }?>
    	<div class="page"><?=$page;?></div>
	<?php }else{?>
		<div class="search_empty clearfix" style="margin: 5rem 0;text-align:center;">
                	<img src="/statics/images/nothing.png" alt="" style="margin-bottom: 3rem;"/>
                        <p>没有更多文章数据......<span></span></p>
                </div>	
	<?php }?>
    </div>
</div>

<script id="cy_cmt_num" src="https://changyan.sohu.com/upload/plugins/plugins.list.count.js?clientId=cytq8LGDe">
</script>
