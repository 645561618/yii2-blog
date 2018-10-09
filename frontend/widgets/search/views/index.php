<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use frontend\models\article\ArticleFront;
?>
<div class="panel" style="box-shadow: 2px 2px 2px 2px #E2E2E2;">
    <div class="panel-title box-title">
        <span>最新文章</span>
    </div>
    <div class="new-list">
	<?php if($data){?>
        <?php foreach ($data as $list){?>
	    <div class="blogs">
		<div class="row">
                    <div class="col-lg-4 col-md-4 col-xs-4 label-img-size">
			<a href="<?=Url::toRoute(['home/detail', 'id' =>$list['id'],'cid'=>$list['cid']]);?>" class="post-label">
	        		<img class="img-responsive" src="http://img.hxinq.com/<?=$list['img']?$list['img']:"article/day_171026/20171026_4a60697.jpg.thumb.thumb.jpg"?>">
			<a>
		    </div>
		    <div class="col-lg-8 col-md-8 col-xs-12">
	        	  <h3 style="margin-top:8px;"><a class="atitle" target="_blank" title="<?=$list['title']?>" href="<?=Url::toRoute(['home/detail', 'id' =>$list['id'],'cid'=>$list['cid']]);?>"><?=$list['title']?></a></h3>
		          <p class="adesc"><?=mb_substr($list['desc'],0,150,'utf-8');?>...</p>
			  <div class="row row-info">
				<span class="articel-label col-lg-3 col-md-2 col-xs-6"><a href="<?=Url::toRoute(['/home/index','tid'=>$list['tid']])?>"><?=ArticleFront::getLabelName($list['tid'])?></a></span>
				<span class="time col-lg-3 col-md-3 col-xs-6"><?=date('Y-m-d',$list['created'])?></span>
				<span class="views col-lg-3 col-md-2 col-xs-6">浏览（<a href="javascript:;"><?=isset($list['views'])?$list['views']:0?></a>）</span>
				<span class="comment f_r col-lg-3 col-md-5 col-xs-6">评论（<a href="javascript:;"><?=isset($list['comment_nums'])?$list['comment_nums']:0?></a>）</span>
			  </div>
		   </div>
	        </div>
            </div>
	<?php }?>
    	<div class="page"><?=$page;?></div>
	<?php }else{?>
		<div class="search_empty clearfix" style="margin: 5rem 0;text-align:center;">
                	<img src="/statics/images/search_nothing.png" alt="" style="width: 100%;"/>
                </div>	
	<?php }?>
    </div>
</div>
