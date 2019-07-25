<?php
use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = '自动回复列表';
$this->params['breadcrumbs'][] = $this->title;
$this->params['menu_name'] = 'wechat';
$this->params['sidebar_name'] = '自动回复列表';
$this->params['three_menu'] = '';
?>
<div class="content" style="margin-bottom:30px;">
    <h3>扫描二维码，关注微信公众号<img src="/images/weixin.jpg" /></h3>
<form id="create-form-package" method="post" action="/wechat/delete-more-reply">
	<?=GridView::widget([
		'dataProvider' => $dataProvider,
	        'hover'=>true,
        	'columns' => [
			['attribute'=>'sort','value'=>$model->showSort(),'format'=>'raw',],
                	['class' => 'kartik\grid\CheckboxColumn'],
	                'id',
        	        'words',
                	'reply',
                	[
                        	'attribute'=>'type',
	                        'value'=>$model->showType(),
        	                'format'=>'raw',
                	],
                	[
                        	'attribute'=>'created',
				'format'=>['date','Y-MM-dd'],
	                ],
        	        [
                	    	'class'=>'\\common\\extensions\\Grid\\GridActionColumn',
                    	    	'template'=>'{update-reply}{delete-reply}',
        	            	'header'=>'操作',
                	],
        	],
	
	]);?>
<button class="btn btn-sm btn-primary" type="button" onclick="updateSort('/wechat/update-sort');">排序</button>
<button class="btn btn-sm btn-danger" type="button" onclick="submitDelete();">删除</button>
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
    function submitDelete(){
        var length = 0;
        $("input[name='selection[]']:checked").each(function(){
               length +=1; 
        });
        if(length > 0){
            $("#create-form-package").submit();
        }else{
            alert("请选择要删除的某一项");
        }
    }

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


</div>

