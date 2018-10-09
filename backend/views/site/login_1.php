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
<div class="site-login" style="align:center;">
    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>


    <div class="row" style="margin:0 auto;width:30rem;">
        <div class="col-lg-5" style="width:30rem;">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>
		
		<?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-3">{image}</div></div><div class="row"><div class="col-lg-6">{input}</div></div>',
                ]) ?>		

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
