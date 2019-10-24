<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '添加通知';
$this->params['menu_name']='blog';
$this->params['sidebar_name']='添加通知';
$this->params['three_menu']='通知管理';

?>
<div class="content">
<?php if ($isNew == true){ ?>
    <h3>添加通知</h3>
<?php }else{?>
    <h3>编辑通知</h3>
<?php } ?>
	<div class="container">
                <div class="row">
    			<?php $form = ActiveForm::begin(['id'=>'create-form','options'=>['enctype'=>'multipart/form-data']])?>
    			<div id="uploads" class="form-group ">
				<?=$form->field($model,'content');?>
				<?=$form->field($model,'status')->widget('\\kartik\\widgets\\SwitchInput');?>
    			</div>
    			<div class="form-group ">
				<?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    			</div>
    			<?php ActiveForm::end();?>
		</div>
	</div>
</div>

