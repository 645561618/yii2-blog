<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '分配权限';
$this->params['menu_name']='managers';
$this->params['sidebar_name']='分配权限';
$this->params['three_menu']='';

?>

<div class="content">
    <h3>分配权限</h3>
    <?php if($status == 'success'){ ?>
        <div class="alert alert-success message">
            <button data-dismiss="alert" class="close" type="button">×</button>
            <strong>Add Ooerations to Role Success!</strong>
        </div>
    <?php }elseif($status == 'fail'){ ?>
        <div class="alert alert-error message">
            <button data-dismiss="alert" class="close" type="button">×</button>
            <strong>Add Ooerations to Role Fail!</strong>
        </div>
    <?php } ?>

		<?php $form=ActiveForm::begin(['id'=>'create-form','options'=>['enctype'=>'multipart/form-data']])?>
                <div class="panel panel-info">
		    <div class="panel-heading">
                    	<h3 class="panel-title">路由列表</h3>
                    </div>
		    <div class="panel-body">
			    <ul class="list-group" style="">
        	                <?php foreach($perms as $v){?>
                	        <li class="list-group-item">
                        	    	<input type="checkbox" value="<?= $v;?> " name="child[perm][]" <?php foreach($items as $item){ ?> <?php if($item == $v){ ?> checked<?php }?> <?php }?> ><?= $v;?>
	                        </li>
        	                <?php }?>
                	        <div class="help-block"></div>
	                    </ul>
		    </div>
                </div>
                <div class="form-group field-authitem-name required">
                        <div class="panel panel-primary">
			    <div class="panel-heading">
                                        <h3 class="panel-title">角色列表</h3>
                            </div>
			    <div class="panel-body">
	                            <ul class="list-group">
        	                    <?php foreach($roles as $v){?>
                	                <li class="list-group-item">
                        	        	<input type="checkbox" value="<?=$v?>" name="child[role][]" <?php foreach($items as $item){ ?><?php if($item == $v){ ?> checked<?php }?><?php }?>> <?=$v?>
					</li>
	                            <?php }?>
        	                    </ul>
                	            <div class="help-block"></div>
                        	</div>
                           </div>
                        <div class="form-group">
                            <input type='submit' value='提交' class='btn btn-large btn-primary' /> 
                        </div>
                    </div>
                </div>
               <?php ActiveForm::end();?> 
</div>
