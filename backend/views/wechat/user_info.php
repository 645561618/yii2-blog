<?php
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = '微信粉丝列表';
$this->params['breadcrumbs'][] = ['label' => '微信管理', 'url' => ['/wechat/index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu_name'] = 'wechat';
$this->params['sidebar_name'] = '微信粉丝列表';
$this->params['three_menu'] = '';
?>
<div class="content">
        <h3><a href="/wechat/get-user" target=_blank  class='btn btn-large btn-danger'>获取微信关注粉丝</a></h3>
        <hr class="darken">
	<?=GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'=>$model,
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
			[
                                'attribute'=>'头像',
                                'value'=>$model->HeadImg(),
                                'format'=>'raw',
                        ],
			[
				'attribute'=>'是否关注',
				'value'=>$model->getLook(),
				'format'=>'html',
			],
			'openid',
			'nickname',
			[
				'attribute'=>'sex',
	                        'value'=>$model->getSex(),
        	                'filterType'=>constant('\\kartik\\grid\\GridView::FILTER_SELECT2'),
                	        'filter'=>$model->SexValues(),
                        	'filterWidgetOptions'=>['pluginOptions'=>['allowClear'=>true]],
	                        'filterInputOptions'=>['placeholder'=>'选择性别'],
        	                'format'=>'html',
			],
			'country',
			'province',
			'city',
			[
				'attribute'=>'关注时间',
				'value'=>$model->getTime(),
				'format'=>'raw',
			],
        	],
	
	]);?>

</div>

