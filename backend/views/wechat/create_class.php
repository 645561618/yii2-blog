<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\grid\GridView;

$this->title = '自定义菜单';
$this->params['menu_name']='wechat';
$this->params['sidebar_name']='自定义菜单';
$this->params['three_menu']='';

?>
<div class="content">
        <h3>添加一级菜单&nbsp;&nbsp;<a href="/wechat/create-menu" target=_blank  class='btn btn-large btn-danger'>生成自定义菜单</a></h3>
	 <div class="container">
                <div class="row">

			<?php $form = ActiveForm::begin(['id'=>'create-form','options'=>['enctype'=>'multipart/form-data']])?>
		        <div id="uploads" class="form-group ">
				<?=$form->field($model,'name');?>
				<?=$form->field($model,'type')->dropDownList($model->getDataType());?>
				<?=$form->field($model,'content');?>
				<?=$form->field($model,'weight');?>
				<?=$form->field($model,'status');?>
		        </div>
		        <div class="form-group ">
				<?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
		        </div>
			<?php ActiveForm::end();?>
		</div>
	</div>

	<?=GridView::widget([
		'dataProvider'=>$dataProvider,
		'hover'=>true,
		'columns'=>[
			'id',		
			'name',		
			[
				'attribute'=>'status','value'=>$model->showOpen(),'format'=>'raw',
			],
			[
				'attribute'=>'type','value'=>$model->showType(),'format'=>'raw',			
			],
			'weight',
			'created',
			[
				'class'=>'\\common\\extensions\\Grid\\GridActionColumn',
		        	'template'=>'{update-class}{delete-class}&nbsp;&nbsp;{view-menu}',
                    		'header'=>'操作',				
			],
		],
	]);?>
</div>
