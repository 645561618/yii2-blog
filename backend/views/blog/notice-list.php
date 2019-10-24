<?php
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = '通知列表';
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu_name'] = 'blog';
$this->params['sidebar_name'] = '通知列表';
$this->params['three_menu'] = '通知管理';

?>

<div class="content">
    <h3>通知列表</h3>
    <hr class="darken">
    <?= GridView::widget([
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
        'columns' => [
	    	'id',
                'content',
                [
                        'attribute'=>'创建时间',
                        'value'=>$model->getTime(),
                ],
                [
                    'class'=>'\\common\\extensions\\Grid\\GridActionColumn',
                    'template'=>'{update-notice}{delete-notice}',
                    'header'=>'操作',
                ],
        ],
    ]); ?>
</div>






