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
<link rel="stylesheet" type="text/css" href="/home.css" />
<style>
	#main{
	}
</style>

<?php if($id==1){ ?>
    	<div class="page-content" style="padding: 20px;">
    	    <div class="index_right_con">
    	        <div class="situation all_title">
    	            <div class="layui-row web_data box-shaodw">
    	                <div class="webdata_card">
    	                    <p class="evetwidtit layui-clear">数据统计 <span class="titmore"></span></p>
    	                    <section>
    	                        <ul>
    	
    	                        </ul>
    	                    </section>
    	                </div>
    	            </div>
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

