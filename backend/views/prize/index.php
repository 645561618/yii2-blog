<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\ActiveForm;

$this->title = '抽奖中心';
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu_name'] = 'prize';
$this->params['sidebar_name'] = '抽奖码列表';
$this->params['three_menu'] = '';

?>
<div class="content">
    <!--<h3>生成抽奖码</h3>-->
    <div class="container">
         <div class="row">

         	<?php $form = ActiveForm::begin(['id'=>'create-form','options'=>['enctype'=>'multipart/form-data']])?>
                <div id="uploads" class="form-group ">
			<label class="control-label" style="font-weight:bold;">生成抽奖码数</label>
			<input type="text" class="form-control" name="nums"  style="width: 15%;">
                </div>
                <div class="form-group ">
                	<?= Html::submitButton('生成', ['class' => 'btn btn-primary']) ?>
                </div>
                 <?php ActiveForm::end();?>
           </div>
    </div>
    <hr class="darken">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
	'filterModel'=>$models,
	'hover'=>true,
        'columns' => [
            	['class' => 'yii\grid\SerialColumn'],
		'code',
		'prize',
		[
                        'attribute'=>'status',
                        'value'=>$models->getStatus(),
                        'filterType'=>constant('\\kartik\\grid\\GridView::FILTER_SELECT2'),
                        'filter'=>$models->getStatusValues(),
                        'filterWidgetOptions'=>['pluginOptions'=>['allowClear'=>true]],
                        'filterInputOptions'=>['placeholder'=>'抽奖状态'],
                        'format'=>'html',
                ],
                //'username',
		//'phone',
		[
                        'attribute'=>'是否发放奖品',
                        'value'=>$models->getGrant(),
                        'format'=>'html',
                ],
		[
                        'attribute'=>'创建时间',
                        'value'=>$models->getTime(),
                ],
        ],
    ]); ?>
</div>

