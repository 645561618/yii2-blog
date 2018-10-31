<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use frontend\models\CommonMenu;
AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="pragma" content="no-cache">
    <meta HTTP-EQUIV="Cache-Control" CONTENT="no-cache"> 
    <meta HTTP-EQUIV="Expires" CONTENT="0"> 
    <meta name="360-site-verification" content="90514a1c3d460bd2c1b87a01a48cb697" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Author" content="黄信强">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body onselectstart="return true">
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    //导航
    if(isset($_GET['cid']) || Yii::$app->controller->action->id =="detail"){
    	$leftMenus[] = [
        	'label' => "首页", 'url' => ['/'],
        ];
    }else{
        $leftMenus[] = [
                'label' => "首页", 'url' => ['/'],
                'linkOptions' => ['style'=>'background:#080808;color:white;'],
	];
    }
    foreach(CommonMenu::getMenu() as $v){
	
	if(isset($_GET['cid']) && $v->id==$_GET['cid']){
		$leftMenus[] = [
			'label' => "$v->title", 'url' => Url::toRoute(['/home/index','cid'=>$v->id]),
			'linkOptions' => ['style'=>'background:#080808;color:white;'],
		];
	}else{
		$leftMenus[] = [
			'label' => "$v->title", 'url' => Url::toRoute(['/home/index','cid'=>$v->id]),
                ];
	}
    }
    //导航搜索
    $Search =	Html::beginForm(['/home/search'],'get',['class'=>'navbar-form visible-lg-inline-block'])
          	.'<div class="input-group">'
		.'<input type="text" name="kw" class="form-control" placeholder="全站搜索">'
		.'<span class="input-group-btn">'
		.Html::submitButton(
			'<span class="fa fa-search"></span>',	
			['class'=>'btn btn-default']
		)
		.'</span></div>'
		.Html::endForm();
    $session = Yii::$app->session;
    //if (Yii::$app->user->isGuest) {
    if ($session['UserLogin']['time']<time()) {
        /*$rightMenus[] = ['label' => Yii::t('yii','Signup'), 'url' => ['/site/signup']];*/
        #$rightMenus[] = ['label' => Yii::t('yii','登录'),'url' => ['/home/login'],'linkOptions'=>['data-toggle'=>'modal','data-target'=>'#myModal']];
        $rightMenus[] = ['label' => Yii::t('yii','登录'),'linkOptions'=>['class'=>'login','data-toggle'=>'modal','data-target'=>'#myModal']];
    } else {
        $rightMenus[] = [
            //'label' => '<img src="'.Yii::$app->params['avatar']['small'].'" alt="' . Yii::$app->user->identity->username . '")',
            //'items' =>[
            //    ['label'=> '<i class="fa fa-sign-out"></i> 退出', 'url'=>['/site/logout'], 'linkOptions'=>['data-method'=>'post']],
            //],
            //'linkOptions' => ['class' => 'avatar'],

	    'label' => '<img src="'.$session['UserLogin']['head_img'].'" alt="' . $session['UserLogin']['nickname'],
            'items' =>[
                ['label'=> '<i class="fa fa-user"></i> '.$session['UserLogin']['nickname']],
                ['label'=> '<i class="fa fa-sign-out"></i> 退出', 'url'=>['/home/logout']],
            ],

            'linkOptions' => ['class' => 'avatar'],

        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => $leftMenus,
    ]);
    echo $Search;

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' =>false,
        'items' => $rightMenus,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					&times;
				</button>
				<h4 class="modal-title" id="myModalLabel" style="text-align:center;">
					快捷登录	
				</h4>
			</div>
			<div class="modal-body" style="text-align:center;">
				<a href="/home/login"><img src="/images/login/qq.png"></a>&nbsp;&nbsp;
				<a href="javascript:;" class="wx_login"><img src="/images/login/weixin.png"></a>
				<a href="/home/github-login" class="wx_login"><img src="/images/login/github.jpg"></a>
			</div>
			<!--<div class="modal-footer" style="text-align:center;display:none;">
				<a href=""><img	src="<?= Url::to(['wx/qrcode'])?>"></a>
			</div>-->
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>


<footer class="footer">
    <div class="container">
	<p><a href="http://www.hxinq.com">hxinq.com</a> 版权所有 <a href="http://www.miibeian.gov.cn">赣ICP备15008740号-1</a></p>
	<hr>
        <p>本站主要用于学习记录和技术交流,如有疑问可以留言反馈,联系邮箱:hxinq@hxinq.com</p>
    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<!-- 百度页面自动提交 -->
<script>
(function(){
    var bp = document.createElement('script');
    var curProtocol = window.location.protocol.split(':')[0];
    if (curProtocol === 'https') {
        bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
    }
    else {
        bp.src = 'http://push.zhanzhang.baidu.com/push.js';
    }
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(bp, s);
})();


$('.login').click(function(){
	$('.modal-footer').hide();
})
$('.wx_login').click(function(){
	$('.modal-footer').show();
})
</script>
<!-- 百度统计-->
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?dc337e332cd1cd6079c7b5b0cb32cd18";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>

<script>(function(){
var src = (document.location.protocol == "http:") ? "http://js.passport.qihucdn.com/11.0.1.js?2c56b013635ac10ab420014a99822be7":"https://jspassport.ssl.qhimg.com/11.0.1.js?2c56b013635ac10ab420014a99822be7";
document.write('<script src="' + src + '" id="sozz"><\/script>');
})();
</script>
