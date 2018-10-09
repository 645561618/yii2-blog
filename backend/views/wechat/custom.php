<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\grid\GridView;

$this->title = '二级菜单';
$this->params['menu_name']='wechat';
$this->params['sidebar_name']='二级菜单';
$this->params['three_menu']='';

?>

<div class="content">
        <h4>二级菜单</h4>
	<div class="container">
                <div class="row">

                        <?php $form = ActiveForm::begin(['id'=>'create-form','options'=>['enctype'=>'multipart/form-data']])?>
                        <div id="uploads" class="form-group ">
                                <?=$form->field($model,'type')->dropDownList($model->getDataType());?>
                                <?=$form->field($model,'name');?>
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
			'fid',
                        'name',
                        [
                                'attribute'=>'status','value'=>$model->showOpen(),'format'=>'raw',
                        ],
			'content',
                        [
                                'attribute'=>'type','value'=>$model->showType(),'format'=>'raw',
                        ],
                        'created',
                        [
                                'class'=>'\\common\\extensions\\Grid\\GridActionColumn',
                                'template'=>'{update-custom}',
                                'header'=>'操作',
                        ],

                ],
        ]);?>

 
</div>
