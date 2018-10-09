<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
//use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
$this->title = '后台登录';
//$this->params['breadcrumbs'][] = $this->title;
?>
<!--必要样式-->
<link rel="stylesheet" type="text/css" href="/statics/css/component.css" />
		<div class="container">
			<div class="content">
				<div id="large-header" style="position: fixed;top: 0px;left: 0px;" class="large-header">
					<canvas id="demo-canvas"></canvas>
					<div class="logo_box">
						<h3>后台管理系统欢迎您</h3>
						<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
							<?= $form->field($model, 'username',[
					                    'inputOptions'=>[
					                            'placeholder'=>'请输入账户',
					                    ],
					                    'inputTemplate'=>
					                            '<div class="input-group">
					                                <span class="input-group-addon"><i class="fa fa-user"></i></span>{input}
					                            </div>',
					               ])->label(false) ?>

					               <?= $form->field($model, 'password',[
						                'inputOptions'=>[
					        	                'placeholder'=>'请输入密码',
						                ],
						                'inputTemplate'=>
						                    '<div class="input-group">
					                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>{input}
					                            </div>',
					            	])->passwordInput()->label(false) ?>

							<?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
						                'template' => '<div class="row"><div class="col-xs-8">{input}</div><div class="col-xs-2">{image}</div></div>',
                					'imageOptions'=>['alt'=>'点击换图','title'=>'点击换图', 'style'=>'cursor:pointer']])->label(false); ?>
							<div class="mb2">
								<?= Html::submitButton('登录', ['class' => 'btn btn-primary btn-success btn-quirk btn-block', 'name' => 'login-button']) ?>
							</div>
						<?php ActiveForm::end(); ?>	
					</div>
				</div>
			</div>
		</div>


		<script src="/js/login/TweenLite.min.js"></script>
		<script src="/js/login/EasePack.min.js"></script>
		<script src="/js/login/rAF.js"></script>
		<script src="/js/login/demo-1.js"></script>
