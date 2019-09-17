<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use backend\widgets\sidebar\SidebarWidget;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<header>
    <div class="headerpanel">
        <div class="logopanel">
            <h3><a href="/">后台管理系统</a></h3>
        </div><!-- logopanel -->

        <div class="headerbar">
            <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>

            <div class="header-right">
                <ul class="headermenu">
                    <li>
                        <div id="noticePanel" class="btn-group">
                            <!--<button class="btn btn-notice alert-notice" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-commenting"></i>
                            </button>-->
                            <div id="noticeDropdown" class="dropdown-menu dm-notice pull-right">
                                <div role="tabpanel">
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane" id="reminders" style="display:block">
					    <?php //$data=Yii::$app->user->WeatherInfo();?>
                                            <!--<h1 class="today-day"><?//=$data['Title']?></h1>-->
                                            <!--<h3 id="todayDate" class="today-date"></h3>-->
				    	    <!--<h4 class="panel-title">即将到期</h4>-->
                                            <ul class="list-group">
                                                <li class="list-group-item">
                                                    <div class="row">
                                                        <div class="col-xs-2">
                                                            <h4><?//=date('d')?></h4>
                                                            <p><?//=date('M')?></p>
                                                        </div>
                                                        <div class="col-xs-10">
                                                            <h5><a href="javascript:;"><?//=date('Y-m-d H:i:s',time())?></a></h5>
                                                            <small><?//=Yii::$app->user->getCity()?></small>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <div class="btn-group">
                            <button type="button" class="btn btn-logged" data-toggle="dropdown">
                                <img src="<?=Yii::$app->params['avatar']['small'] ?>" alt="头像">
                                <?=Yii::$app->user->identity->username?>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="/manager/update-manager?id=<?=Yii::$app->user->id?>"><i class="fa fa-user"></i> 修改密码</a></li>
                                <!--<li><a href="#"><i class="fa fa-cog"></i> 账户设置</a></li>-->
                                <li><a href="<?=Url::to(['site/logout'])?>" data-method="post" ><i class="fa fa-sign-out"></i> 退出</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div><!-- header-right -->
        </div><!-- headerbar -->
    </div><!-- header-->
</header>

<section>

    <div class="leftpanel">
        <div class="leftpanelinner">

            <!-- ################## LEFT PANEL PROFILE ################## -->

            <div class="media leftpanel-profile">
                <div class="media-left">
                    <a href="#">
                        <img src="<?=Yii::$app->params['avatar']['small']?>" alt="" class="media-object img-circle">
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading"><?=Yii::$app->user->identity->username?><a data-toggle="collapse" data-target="#loguserinfo" class="pull-right"><i class="fa fa-angle-down"></i></a></h4>
                    <span>管理员</span>
                </div>
            </div><!-- leftpanel-profile -->

            <div class="leftpanel-userinfo collapse" id="loguserinfo">
                <h5 class="sidebar-title">地址</h5>
                <address><?//=Yii::$app->user->getCity()?></address>
                <h5 class="sidebar-title">联系方式</h5>
                <ul class="list-group">
                    <li class="list-group-item">
                        <label class="pull-left">邮箱</label>
                        <span class="pull-right"><?=Yii::$app->user->getUserEmail()?></span>
                    </li>
                </ul>
            </div><!-- leftpanel-userinfo -->
            <div class="tab-content">

                <div class="tab-pane active" id="mainmenu">
                	<h5 class="sidebar-title">菜单</h5>
		    		<?php if(Yii::$app->user->id){?>
					<ul class="nav nav-pills nav-stacked nav-quirk">
				        <?php foreach(Yii::$app->user->getTopMenu() as $k=>$v){?>
					        <li class="nav-parent <?php if($this->params['menu_name']==$k){?>active<?php }?>">
			                		<a href="/<?=$v['route'] ?>"><i class="fa fa-<?=$v['icon']?>"></i><span><?=$v['name'] ?></span></a>
			                        	<ul class="children">
                        		        		<?php if(!empty($this->params['sidebar_name'])){ ?>
			                                        	<?php foreach(Yii::$app->user->getSubMenu($this->params['menu_name']) as $key=>$value){?>
                        			                        	<?php if(isset($value['subs_son'])){ ?>
                                                			        	<li <?php if($value['name']==$this->params['three_menu']){?>class="active"<?php }?> id="active-children">
			                                                                	<a href="/<?=$value['route']?>" ><?=$value['name']?></a>
	                        			                                        <ul class="children" id="children-<?=$key?>" <?php if($value['name']!=$this->params['three_menu']){?>style="display:none;"<?php }?>>
        	                                        			                        <?php foreach($value['subs_son'] as $kk=>$vv){ ?>
                	                                                        			        <li <?php if($vv['name']==$this->params['sidebar_name']){?>class="active"<?php }?>><a href="/<?=$vv['route']?>"><?=$vv['name']?></a></li>
				                                                                        <?php }?>
                        				                                        </ul>
                                        	        			        </li>
			                        	                        <?php }else{ ?>
                        			        	                        <li <?php if($value['name']==$this->params['sidebar_name']){?>class="active"<?php }?> onclick="showMenu('<?=$key?>');">
                                                				                <a href="/<?=$value['route']?>"><?=$value['name'] ?></a>
			                                                	        </li>
	                        			                        <?php }?>
				                                        <?php }?>
                	        			        <?php }?>
				                        </ul>
        					</li>
        					<?php } ?>
					</ul>
				<?php }?>
                </div>
            </div><!-- tab-content -->

        </div><!-- leftpanelinner -->
    </div><!-- leftpanel -->

    <div class="mainpanel">
        <div class="contentpanel">
            <?= Breadcrumbs::widget([
                'homeLink'=>[
                    'label' => '<i class="fa fa-home mr5"></i> '.Yii::t('yii', 'Home'),
                    'url' => '/',
                    'encode' => false,
                ],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'tag'=>'ol',
                'options' => ['class' => 'breadcrumb breadcrumb-quirk']
            ]) ?>
            <?= Alert::widget() ?>
            <?=$content?>
        </div>

    </div><!-- mainpanel -->

</section>

<?php Modal::begin([
    'id' => 'create-modal',
    'header' => '<h4 class="modal-title"></h4>',
]);
Modal::end();
?>
<?php $this->endBody() ?>
</body>
</html>
<script type="text/javascript">  
	function showMenu(num){
                $("#active-children").find('ul').hide();
        }
</script>;
<script src="/js/highcharts.js"></script>
<?php $this->endPage() ?>
