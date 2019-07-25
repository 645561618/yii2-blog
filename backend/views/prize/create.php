<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '生成抽奖码';
$this->params['menu_name']='safe';
$this->params['sidebar_name']='生成抽奖码';
$this->params['three_menu']='';

?>
<div class="content">
    <h3>生成抽奖码</h3>
	<div class="container">
                <div class="row">
    			<?php $form = ActiveForm::begin(['id'=>'create-form','options'=>['enctype'=>'multipart/form-data']])?>
    			<div id="uploads" class="form-group ">
				<input type="text">
				<?=$form->field($model,'username');?>
		    	</div>
    			<div class="form-group ">
				<?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    			</div>
    			<?php ActiveForm::end();?>
		</div>
	</div>
</div>

