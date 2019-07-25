<?php

 use yii\helpers\Url;
 use yii\helpers\Html;

 ?>
 <?php if(!empty($data)):?>
 		<div class="companyNews">
                    <h4 class="infoCenter_title">热门推荐</h4>
                    <div class="companyNews_list_wrap">
                        <!--<span class="left_line"></span>-->
                        <ul class="companyNews_list">
			    <?php foreach($data as $v){?>
                            	<li><span></span><a href="<?=Url::toRoute(['/home/detail','id'=>$v->id,'cid'=>$v->cid])?>"><?=Html::encode($v->title)?></a></li>
			    <?php }?>
                        </ul>
                    </div>
                </div>

 <?php endif;?>
