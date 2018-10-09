<?php
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = '搜索记录';
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu_name'] = 'safe';
$this->params['sidebar_name'] = '搜索记录';
$this->params['three_menu'] = '';

?>

<div class="content">
    <h3>搜索记录</h3>
    <hr class="darken">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
	'filterModel'=>$model,	
	'hover'=>true,
        'columns' => [
            	['class' => 'yii\grid\SerialColumn'],
	    	'id',
                'ip',
                'message',
                [
                        'attribute'=>'创建时间',
                        'value'=>$model->getTime(),
                ],
        ],
    ]); ?>
</div>

