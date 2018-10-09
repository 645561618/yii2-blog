<?php
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = '文章列表';
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu_name'] = 'blog';
$this->params['sidebar_name'] = '文章列表';
$this->params['three_menu'] = '文章管理';

?>
<style>
select{ vertical-align:middle;background:none repeat scroll 0 0 #F9F9F9;border-color:#666666 #CCCCCC #CCCCCC #666666;border-style:solid;border-width:1px;color:#333;padding:2px;}
.search-form{ margin-bottom:10px}
textarea{display:none;}
</style>
<form id="create-form-package" method="post" action="/blog/update-more">
<div class="content">
    	<h3>文章列表</h3>
    	<hr class="darken">
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
	'filterModel'=>$model,
        'hover'=>true,//鼠标移动上去时，颜色变色，默认为false
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
		['class' => 'kartik\grid\CheckboxColumn'],
                ['class' => 'yii\grid\SerialColumn'],
		[
			'attribute'=>'weight','value'=>$model->showSort(),'format'=>'raw',
		],
                'id',
                'title',
                'views',
                'weight',
		[
                        'attribute'=>'cid',
                        'value'=>$model->getCategoryTitle(),
                        'filterType'=>constant('\\kartik\\grid\\GridView::FILTER_SELECT2'),
                        'filter'=>$model->getCateValues(),
                        'filterWidgetOptions'=>['pluginOptions'=>['allowClear'=>true]],
                        'filterInputOptions'=>['placeholder'=>'选择分类'],
                        'format'=>'html',
                ],
		[               
                        'attribute'=>'tid',
                        'value'=>$model->getLabelTitle(),
                        'filterType'=>constant('\\kartik\\grid\\GridView::FILTER_SELECT2'),
                        'filter'=>$model->getLabelValues(),
                        'filterWidgetOptions'=>['pluginOptions'=>['allowClear'=>true]],
                        'filterInputOptions'=>['placeholder'=>'选择标签'],
                        'format'=>'html',
                ],
                [
                        'attribute'=>'is_recommend',
                        'value'=>$model->getRecommend(),
                        'filterType'=>constant('\\kartik\\grid\\GridView::FILTER_SELECT2'),
                        'filter'=>$model->getRecommendValues(),
                        'filterWidgetOptions'=>['pluginOptions'=>['allowClear'=>true]],
                        'filterInputOptions'=>['placeholder'=>'是否推荐'],
                        'format'=>'html',
                ],
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
                        'attribute'=>'修改时间',
                        'value'=>$model->getModify(),
                ],
		/*[
    			'class' => '\kartik\grid\EditableColumn',
    			'attribute' => 'created',    
    			'hAlign' => 'center',
    			'vAlign' => 'middle',
    			'width' => '9%',
    			'format' => 'date',
    			'headerOptions' => ['class' => 'kv-sticky-column'],
    			'contentOptions' => ['class' => 'kv-sticky-column'],
    			'readonly' => function($model, $key, $index, $widget) {
        			return (!$model->status); // do not allow editing of inactive records
    			},
    			'editableOptions' => [
        			'header' => '创建时间', 
        			'size' => 'md',
        			'inputType' => \kartik\editable\Editable::INPUT_WIDGET,
        			'widgetClass' =>  'kartik\datecontrol\DateControl',
        			'options' => [
            				'type' => \kartik\datecontrol\DateControl::FORMAT_DATE,
            				'displayFormat' => 'yyyy-MM-dd',
            				'saveFormat' => 'php:Y-m-d',
           				'options' => [
                				'pluginOptions' => [
                    					'autoclose' => true
                				]
            				]
        			]
    			],
		],*/
                [
                    'class'=>'\\common\\extensions\\Grid\\GridActionColumn',
                    'template'=>'{update-article}',
                    'header'=>'操作',
                ],
        ],
    ]); ?>


	<button class="btn btn-sm btn-primary" type="button" onclick="updateSort('/blog/article-sort');">排序</button>
    	<select  name="status" id="status" style="height:29px;">
        	<option value="">选择操作</option>
        	<option value="1">审核不通过</option>
        	<option value="2">审核通过(上架)</option>
        	<option value="3">下架</option>
        </select>
   	<input type="button" class="btn btn-sm btn-danger" onclick="SubmitUpdate()" value="提交"/>

</div>
</form>

<div class="modal bootstrap-dialog type-warning fade size-normal in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
                <div class="modal-content">
                        <div class="modal-header">
                                <h4 class="modal-title bootstrap-dialog-title" id="myModalLabel">
                                        更新提示
                                </h4>
                        </div>
                        <div class="modal-body bootstrap-dialog-message">
                                排序更新成功
                        </div>
                        <div class="modal-footer">
                                <div class="bootstrap-dialog-footer-buttons">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">
                                        <span class="glyphicon glyphicon-ok"></span> 确定
                                </button>
                                </div>
                        </div>
                </div>
        </div>
</div>


<script type="text/javascript">
    function updateSort(url)
    {
        var pars = "";
        var nodes = jQuery.makeArray($('input[type=text]'));
        $.each(nodes,function(n,node){
           //alert(node.name.indexOf('sort'));
           //判断下标大于-1
           if(node.name.indexOf('sort') > -1)
           {
                tnode = node.name.split('_');
                pars+= tnode[0] + "_" + node.value + "-";
           }
        });


        $.post(url,{id:pars},function(msg){
		if(msg){
			$("#myModal").modal('show');  
			$("#myModal").on('hide.bs.modal',function(){
                		//window.location.reload();
				return false;
			});
			$("#myModal .btn").click(function(){
                                window.location.reload();
                        });
		}
        });

  }

  function SubmitUpdate()
  {
	var length = 0;
        $("input[name='selection[]']:checked").each(function(){
               length +=1; 
        });
        if(length > 0){
	    if($('#status').val()==""){
		alert('请选择要提交的状态');
		return false;
	    }
            $("#create-form-package").submit();
        }else{
            alert("请勾选一篇文章或多篇文章");
        }

  }
</script>




