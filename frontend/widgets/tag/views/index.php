<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>

    <div class="hot-label">
      <h4>热门标签</h4>
      <ul>
	<?php if($model){?>
	<?php foreach($model as $v){?>
        	<li><a href="<?=Url::toRoute(['/home/index','tid'=>$v->id])?>"><?=Html::encode($v->title)?></a></li>
	<?php }?>
	<?php }?>
      </ul>
    </div>
