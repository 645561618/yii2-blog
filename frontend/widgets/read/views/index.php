<?php

 use yii\helpers\Url;
 use yii\helpers\Html;

 ?>
 <?php if(!empty($data)):?>
	<div class="infoCenter_hot">
                <h4 class="infoCenter_title">阅读排名</h4>
                <ul class="infoCenter_hot_list">
			<?php
                                $i = 1;
                                foreach ($data as $v) {
                                ?>
                                 <li class="clearfix">
                                    <?php if($i<=3){ ?>
                                        <span class="hot_active"><?php echo $i; ?></span>
                                    <?php }else{ ?>
                                        <span><?php echo $i; ?></span>
                                    <?php } ?>
                                    <a href="<?php echo Url::toRoute(['home/detail', 'id'=>$v->id,'cid'=>$v->cid]); ?>"><?=Html::encode($v->title)?></a>
                                </li>

                                <?php
                                ++$i;
                                }
                        ?>
                </ul>
        </div>

 <?php endif;?>
