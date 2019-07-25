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

<!--百度分享-->
<div class="baidu-share">
<div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more">分享到：</a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博">新浪微博</a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信">微信</a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间">QQ空间</a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博">腾讯微博</a><a href="#" class="bds_tieba" data-cmd="tieba" title="分享到百度贴吧">百度贴吧</a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网">人人网</a><a href="#" class="bds_mshare" data-cmd="mshare" title="分享到一键分享">一键分享</a></div>
</div>
<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"16"},"share":{"bdSize":16},"image":{"viewList":["tsina","weixin","qzone","tqq","tieba","renren","mshare"],"viewText":"分享到：","viewSize":"16"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["tsina","weixin","qzone","tqq","tieba","renren","mshare"]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>


<script type="text/javascript">  
    var setMyBlog = {
        setCodeRow: function(){
            // 代码行号显示
	    var pre = $(".article-detail pre code"); //选中需要更改的部分
            if(pre && pre.length){
                pre.each(function() {
                    var item = $(this);
                    var lang = item.attr("class").split(" ")[1]; //判断高亮的语言
                    item.html(item.html().replace(/<[^>]+>/g,"")); //将<pre>标签中的html标签去掉
                    item.removeClass().addClass("brush: " + lang +";"); //根据语言添加笔刷
                    SyntaxHighlighter.all();
                })
            }
        },
        runAll: function() {
            /* 运行所有方法
             * setAtarget() 博客园内标签新窗口打开
             * setContent() 设置目录
             * setCopyright() 设置版权信息
             * setCodeRow() 代码行号显示
             */ 
            this.setCodeRow();
        }
    }
    setMyBlog.runAll();
</script>
