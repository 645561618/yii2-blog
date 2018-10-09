<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '后台首页';
$this->params['menu_name'] = '';
$this->params['three_menu'] = '';
$this->params['sidebar_name'] = '';

?>
<?php if($id==1){ ?>
        <div class="col-sm-3" style="margin-bottom:20px;">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right">系统管理</span>
                    <h5>后台用户</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a href="/manager/admin"><?=$userNums?></a></h1>
                    <small>位</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3" style="margin-bottom:20px;">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-danger pull-right">文章管理</span>
                    <h5>博客文章</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a href="/blog/article-list"><?=$BlogArticleNums?></a></h1>
                    <small>篇</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3" style="margin-bottom:20px;">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right">微信粉丝</span>
                    <h5>关注粉丝</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a href="/wechat/user-info"><?=$WxFollowNums?></a></h1>
                    <small>位</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3" style="margin-bottom:20px;">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-danger pull-right">博客友链</span>
                    <h5>友情链接</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a href="/blog/links-list"><?=$LinksNums?></a></h1>
                    <small>个</small>
                </div>
            </div>
        </div>

<?php }else{?>

<div class="site-index">

    <div class="jumbotron">
        <h1>后台管理系统!</h1>



    </div>


</div>
<?php }?>
