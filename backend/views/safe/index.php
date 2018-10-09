<?php
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = '安全配置列表';
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu_name'] = 'safe';
$this->params['sidebar_name'] = '安全配置列表';
$this->params['three_menu'] = '';

?>

<div class="content">
    <h3>安全配置列表</h3>
    <hr class="darken">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
	'hover'=>true,
        'columns' => [
            	['class' => 'yii\grid\SerialColumn'],
	    	'id',
		[
			'attribute'=>'类型',
                        'value'=>$model->getType(),
		],
                'nums',
		'percent',
		'length',
		'words',
                'ip',
                [
                        'attribute'=>'创建时间',
                        'value'=>$model->getTime(),
                ],
                [
                    'class'=>'\\common\\extensions\\Grid\\GridActionColumn',
                    'template'=>'{update-safe}',
                    'header'=>'操作',
                ],
        ],
    ]); ?>
</div>

