<?php
use kartik\grid\GridView;
$this->title = '用户管理列表';

$this->params['breadcrumbs'][] = $this->title;
$this->params['menu_name'] = 'managers';
$this->params['sidebar_name'] = '用户列表';
$this->params['three_menu'] = '';

?>

<div class="content">
    <h3>管理管理员</h3>
    <?= GridView::widget([
        'dataProvider' => $data,
        'hover'=>true,
        'columns' => [
                //'id',
                'username',
                'created',
                [
                    'class'=>'\\common\\extensions\\Grid\\GridActionColumn',
                    'template'=>'{update-manager}{delete-manager}{assignment}',
                    'header'=>'操作',
                ],
        ],
    ]); ?>

</div>
