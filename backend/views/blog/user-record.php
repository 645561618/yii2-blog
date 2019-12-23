<?php
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = '用户列表';
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu_name'] = 'blog';
$this->params['sidebar_name'] = '用户列表';
$this->params['three_menu'] = '用户管理';

?>

<div class="content">
    <h3>记录列表</h3>
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
	    		[
                    'attribute'=>'头像',
                    'value'=>$model->getHeadImg(),
                    'format'=>'html',
                ],
                'nickname',
                'gender',
                'province',
                'city',
				[
                    'attribute'=>'类型',
                    'value'=>$model->getType(),
                ],
                [
                        'attribute'=>'创建时间',
                        'value'=>$model->getTime(),
                        'format'=>'html',
                ],
        ],
    ]); ?>
</div>






