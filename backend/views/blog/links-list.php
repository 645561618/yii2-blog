<?php
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = '友链列表';
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu_name'] = 'blog';
$this->params['sidebar_name'] = '友链列表';
$this->params['three_menu'] = '友链管理';

?>

<div class="content">
    <h3>友链列表</h3>
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
                        'attribute'=>'sort','value'=>$model->showSort(),'format'=>'raw',
                ],
	    	'id',
                'title',
		'url',
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
                    'template'=>'{update-links}',
                    'header'=>'操作',
                ],
        ],
    ]); ?>

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
    
   <button class="btn btn-sm btn-primary" type="button" onclick="updateSort('/blog/links-sort');">排序</button>
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
                        //alert(msg);
                }
        });

}

</script> 






