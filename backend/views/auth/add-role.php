<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '添加角色';
$this->params['menu_name']='managers';
$this->params['sidebar_name']='添加角色';
$this->params['three_menu']='';
?>
<div class="content">
    <h3>添加角色</h3>
<div class="container">
	<div class="row">
		<?php $form = ActiveForm::begin(['id'=>'create-form','options'=>['enctype'=>'multipart/form-data']])?>
		<?=$form->field($model,'name');?>
		<?=$form->field($model,'type')->dropDownList(['1'=>'Role']);?>
		<?=$form->field($model,'description')->textArea(['row'=>2]);?>
            	<div class="form-group">
			<?= Html::submitButton('提交',['class'=>'btn,btn-large btn-primary'])?>
            	</div>
		<?php ActiveForm::end();?>
	</div>
</div>
</div>

