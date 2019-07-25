<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '添加自动回复';
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu_name'] = 'wechat';
$this->params['sidebar_name'] = '添加自动回复';
$this->params['three_menu'] = '';

?>
<div class="content">
    <h3>添加自动回复</h3>
<div class="container">
    <div class="row">
		<?php $form = ActiveForm::begin(['id'=>'create-form','options'=>['enctype'=>'multipart/form-data']])?>
		<?=$form->field($model,'type')->dropDownList($model->getDataType());?>
		<?=$form->field($model,'words');?>
		<?=$form->field($model,'reply');?>
		<?=$form->field($model,'sort');?>
            	<div class="form-group">
			<?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
            	</div>
		<?php ActiveForm::end();?>
    </div>
</div>

</div>
