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
<html lang="<?= Yii::$app->language ?>" xmlns:wb="http://open.weibo.com/wb">
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
    <script src="https://tjs.sjs.sinajs.cn/open/api/js/wb.js" type="text/javascript" charset="utf-8"></script>
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
				<a href="/home/login"><img alt="黄信强博客" src="/images/login/qq.png"></a>&nbsp;&nbsp;
				<a href="javascript:;" class="wx_login"><img alt="黄信强博客" src="/images/login/weixin.png"></a>
				<a href="/home/github-login" class="wx_login"><img alt="黄信强博客"  src="/images/login/github.jpg"></a>
			</div>
			<!--<div class="modal-footer" style="text-align:center;display:none;">
				<a href=""><img	alt="黄信强博客" src="<?= Url::to(['wx/qrcode'])?>"></a>
			</div>-->
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>


<div class="modal fade in" id="modal-links" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true" style="display: none; padding-right: 15px;">
        <div class="modal-dialog">
            <div class="modal-content row">
                <div class="col-xs-12 col-md-12 col-lg-12" style="width:100%;text-align: center;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title b-ta-center" id="myModalLabel">申请友链 <span class="b-hint">(请确保已添加本站的友情链接)</span></h4>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12 b-submit-site" style="width:100%;margin-top:10px;" >
                    <form class="form-horizontal" role="form" action="" method="post">
                        <input type="hidden" name="_token" value="sJfLqMwD4dmA0thXFTEFyuTrw63LJ9KZibjYtwL4">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">名称</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" placeholder="请输入网站名,例如:黄信强博客">
                            </div>
                        </div>
			<div id="link_name" style="display:none;">
			    <p class="col-sm-2"></p>
			    <p class="col-sm-10" style="margin-top: -10px;"><em style="color:red;">网站名不能为空</em></p>
			</div>
                        <div class="form-group">
                            <label for="url" class="col-sm-2 control-label">链接</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="url" placeholder="请输入URL,例如:http://www.hxinq.com">
                            </div>
                        </div>
			<div id="link_url" style="display:none;">
                            <p class="col-sm-2"></p>
                            <p class="col-sm-10" style="margin-top: -10px;"><em style="color:red;">链接地址不能为空</em></p>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-sm-2 control-label">邮箱</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="email" placeholder="请输入邮箱用于接收通知">
                            </div>
                        </div>
			<div id="link_email" style="display:none;">
                            <p class="col-sm-2"></p>
                            <p class="col-sm-10" style="margin-top: -10px;"><em style="color:red;">邮箱不能为空</em></p>
                        </div>
                    </form>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-12" style="width:100%">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary center-block b-s-submit">提交</button>
                    </div>
                </div>
            </div>
        </div>
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

$(function(){
	var _name = $.trim($('input[name="name"]').val());
	var _url = $.trim($('input[name="url"]').val());
	var _email = $.trim($('input[name="email"]').val());
	$('input[name="name"]').focus(function(){
		if(!_name){
			$('#link_name').show();	
			return false;
		}		
  	});
	$('input[name="name"]').blur(function(){
		var name = $.trim($('input[name="name"]').val());
		if(name){
			$('#link_name').hide();
		}
  	});
	$('input[name="url"]').focus(function(){
		if(!_url){
			$('#link_url').show();
			return false;
		}
  	});
	$('input[name="url"]').blur(function(){
		var url = $.trim($('input[name="url"]').val());
		if(url){
			$('#link_url').hide();
		}
  	});
	
	$('input[name="email"]').focus(function(){
		if(!_email){
			$('#link_email').show();
			return false;
		}	
  	});
	$('input[name="email"]').blur(function(){
		var email = $.trim($('input[name="email"]').val());
		if(email){
			$('#link_email').hide();
		}
  	});

})

$('.b-s-submit').click(function(){
	var _name = $.trim($('input[name="name"]').val());
	var _url = $.trim($('input[name="url"]').val());
	var _email = $.trim($('input[name="email"]').val());
	if(!_name){
		$('#link_name').show();	
		return false;
	}		
	if(!_url){
		$('#link_url').show();
		return false;
	}
	if(!_email){
		$('#link_email').show();
		return false;
	}	
	$.ajax({
		type:'post',
		url:"/home/links",
		data:{name:_name,url:_url,email:_email},
		dataType:'json',
		success:function(data){
			if(data.status==1){
				alert(data.msg);
				window.location.reload();
			}
		}
	})


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
