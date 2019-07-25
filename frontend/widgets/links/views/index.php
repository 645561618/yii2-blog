<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>

<div class="links">
      <h3><span>[<a href="javascript:void(0);" data-toggle="modal" data-target="#modal-links">申请友情链接</a>]</span>友情链接</h3>
      <?php if($model){?>
      <ul>
	<?php foreach($model as $v){?>
        <li><a href="<?=$v->url?>" target=_blank><?=Html::encode($v->title)?></a></li>
	<?php }?>
      </ul>
      <?php }?>
    </div>
