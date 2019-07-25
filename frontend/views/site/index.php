<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>

<div class="row">
    <div class="col-lg-9">
        <!-- 图片轮播 -->
	<div class="panel">
            <div id="carousel-example" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                	<li data-target="#carousel-example" data-slide-to="0" class="active"></li>
                	<li data-target="#carousel-example" data-slide-to="1" class="active"></li>
                	<li data-target="#carousel-example" data-slide-to="2" class="active"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                        <div class="item active">
                            <a href="">
                                <img src="/statics/images/banner/b_0.jpg" alt="">
                            </a>
                            <div class="carousel-caption">
                            </div>
                        </div>
                </div>

                <!-- Controls -->
                <a class="left carousel-control" href="#carousel-example" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span></a>
                <a class="right carousel-control" href="#carousel-example" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span></a>
            </div>
        </div>
    </div>

    <div class="col-lg-3">

    </div>

</div>
