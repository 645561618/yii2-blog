<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = '内容管理';
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu_name'] = 'blog';
$this->params['sidebar_name'] = '标签列表';

?>

<div class="content">
    <h3>标签列表</h3>
    <hr class="darken">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
	'hover'=>true,
        'columns' => [
            	['class' => 'yii\grid\SerialColumn'],
	    	'id',
                'title',
                'sort',
                [
                        'attribute'=>'状态',
                        'value'=>$model->getStatus(),
                        'format'=>'html',
                ],
                [
                        'attribute'=>'创建时间',
                        'value'=>$model->getTime(),
                ],
                [
                    'class'=>'\\common\\extensions\\Grid\\GridActionColumn',
                    'template'=>'{update-label}',
                    'header'=>'操作',
                ],
        ],
    ]); ?>
</div>

