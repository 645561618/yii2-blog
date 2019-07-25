<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\grid\GridView;

$this->title = '添加菜单';
$this->params['menu_name']='wechat';
$this->params['sidebar_name']='添加菜单';
$this->params['three_menu']='';

?>


<div class="content">
        <h4>添加数据</h4>
	<div class="container">
                <div class="row">

                        <?php $form = ActiveForm::begin(['id'=>'create-form','options'=>['enctype'=>'multipart/form-data']])?>
                        <div id="uploads" class="form-group ">
                                <?=$form->field($model,'title');?>
                                <?=$form->field($model,'picurl');?>
                                <?=$form->field($model,'description')->textArea(['placeholder'=>'内容']);?>
                                <?=$form->field($model,'url');?>
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
			'cid',
                        'title',
                        'description',
                        'picurl',
                        'url',
                        'created',
                        [
                                'class'=>'\\common\\extensions\\Grid\\GridActionColumn',
                                'template'=>'{update-click}&nbsp;&nbsp;{delete-click}',
                                'header'=>'操作',
                        ],
                ],
        ]);?>


</div>
