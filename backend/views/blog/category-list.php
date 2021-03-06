<?php
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title ="分类列表";
$this->params['menu_name']='blog';
$this->params['sidebar_name']='分类列表';
$this->params['three_menu'] = '分类管理';
?>

<div class="content">
    <h3>分类列表</h3>
    <hr class="darken">
    <?=GridView::widget([
	'dataProvider' => $dataProvider,
        'hover'=>true,
	'responsive' => true,//自适应,，默认为true
        'pjax' => true,//loading
        'panel' => [//头部显示数据
                'heading'=>false,
                'before'=>'<div style="margin-top:8px">{summary}</div>',
        ],
        'toolbar' =>  [
                ['content' =>
                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['/blog/article-list'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => '刷新'])
                ],
                '{toggleData}',
                '{export}',
        ],
	'columns'=>[
		'id',
                'title',
                'sort',
                [ 
                        'attribute'=>'状态',
                        'value'=>$model->getStatus(),
                ],
                [
                        'attribute'=>'创建时间',
                        'value'=>$model->getTime(),
                ],
                [
                    'class'=>'\\common\\extensions\\Grid\\GridActionColumn',
                    'template'=>'{update-category}',
                    'header'=>'操作',
                ],

	],
    ]);?>
</div>

