<?php
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = '角色列表';
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu_name'] = 'managers';
$this->params['sidebar_name'] = '角色列表';
$this->params['three_menu'] = '';

?>

<div class="content">
    <h3>角色列表</h3>
    <?=GridView::widget([
	'dataProvider'=>$dataProvider,
	'hover'=>true,
	'columns'=>[
		'name',
		[
			'attribute'=>'type',
			'value'=>function($model){
				if($model->type==1){return "Role";}else{return "Operation";}
			},
			'format'=>'html',
		],
		[	
                    'class'=>'\\common\\extensions\\Grid\\GridActionColumn',
                    'template'=>'{update-role}{delete-role}{assign}',
                    'header'=>'操作'
                ],

	],
    ]);?>
</div>


