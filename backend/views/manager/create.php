<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '添加用户';
$this->params['menu_name']='managers';
$this->params['sidebar_name']='添加用户';
$this->params['three_menu']='';

?>

<div class="content">

    <h3>创建管理员</h3>
<div class="container">

    <div class="row">
        <div class="form">
            
		<?php $form = ActiveForm::begin(['id'=>'create-form','options'=>['enctype'=>'multipart/form-data']])?>

	    <?php if($isNew==false){?>
	            <?=$form->field($model,'username')->textInput(['readonly'=>true]);?>
	    <? }else{ ?>
            	<?=$form->field($model,'username');?>
	    <?php } ?>
            <?=$form->field($model,'password')->passwordInput();?>
            <?=$form->field($model,'email');?>
            <div class="form-group">
			<?= Html::submitButton('提交', ['class' => 'btn btn-large btn-primary']) ?>
            </div>
	    <?php ActiveForm::end();?>
        </div>
    </div>
</div>
</div>

