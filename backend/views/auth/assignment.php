<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '分配角色';
$this->params['menu_name']='managers';
$this->params['sidebar_name']='分配角色';
$this->params['three_menu']='';

?>

<div class="content">
    <h3>分配权限</h3>
        <div class="container">

		<?php $form = ActiveForm::begin(['id'=>'create-form','options'=>['enctype'=>'multipart/form-data']])?>
                <div class="form-group field-authitem-name required">
			<ul>
                        <?php foreach($allRoles as $v){ ?>
                        	<li>
                            		<label><input type="checkbox" value="<?=$v ?>" name="child[role][]" <?php foreach($roles as $item){ ?><?php if($item == $v){ ?> checked<?php }?><?php }?>> <?=$v?> </label>
                        	</li>
                        <?php } ?>
			</ul>
                        <div class="help-block"></div>
                </div>
                <div class="form-group">
                	<input type='submit' value='提交' class='btn btn-large btn-primary' /> 
                </div>
		<?php ActiveForm::end();?>
        </div>
</div>
