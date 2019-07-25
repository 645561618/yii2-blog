<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '添加安全配置';
$this->params['menu_name']='safe';
$this->params['sidebar_name']='添加安全配置';
$this->params['three_menu']='';

?>
<div class="content">
<?php if ($isNew == true){ ?>
    <h3>添加安全配置</h3>
<?php }else{?>
    <h3>编辑安全配置</h3>
<?php } ?>
	<div class="container">
                <div class="row">
    			<?php $form = ActiveForm::begin(['id'=>'create-form','options'=>['enctype'=>'multipart/form-data']])?>
    			<div id="uploads" class="form-group ">
				<?=$form->field($model,'type')->widget('\\kartik\\select2\\Select2',['data'=>[''=>'请选择类型','1'=>'搜索','2'=>'评论'],'options'=>['placeholder'=>'请选择类型'],'pluginOptions'=>['allowClear'=>true]])?>
				<?=$form->field($model,'nums');?>
				<?=$form->field($model,'percent');?>
				<?=$form->field($model,'length');?>
				<?=$form->field($model,'words',['inputOptions'=>['placeholder'=>'多个关键词,用逗号分隔']])->textArea();?>
				<?=$form->field($model,'ip',['inputOptions'=>['placeholder'=>'多个ip,用逗号分隔']])->textArea();?>
		    	</div>
    			<div class="form-group ">
				<?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    			</div>
    			<?php ActiveForm::end();?>
		</div>
	</div>
</div>

