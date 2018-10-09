
<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>


<style>
    
#main {
  background-color: #f3f2f7;
  position: relative;
  height: 100%;
}
.router-view{
    width: 100%;
}
#pc {
  position: absolute;
  top: 5rem;
  left: 50%;
  -webkit-transform: translate3d(-50%, 0, 0);
  transform: translate3d(-50%, 0, 0);
  text-align: center;
}
#pc img {
  max-width: 100%;
}
#pc p {
  margin-top: 6px;
  font-size: 18px;
}
#buttons-group {
  width: 100%;
  position: absolute;
  top: 40rem;
  left: 50%;
  -webkit-transform: translate3d(-50%, 0, 0);
  transform: translate3d(-50%, 0, 0);
}
.btn {
  border: 0;
  display: block;
  margin: 0 auto 20px;
  background: transparent;
  padding: 4px 80px;
  font-size: 16px;
}
.confirm-btn {
  border: 1px solid #63c355;
  color: #63c355;
  border-radius: 5px;
}
.cancel-btn {
  color: #818085;
}
#qcrode-tip {
  position: absolute;
  top: 50%;
  left: 0;
  -webkit-transform: translate3d(0, -50%, 0);
  transform: translate3d(0, -50%, 0);
  width: 100%;
  text-align: center;
  color: #ff3737;
}

</style>



<body style="background-color: rgb(243, 242, 247);">
<div id="app">
    <div id="main" class="router-view">
        <div id="pc">
            <img data-v-10d3eebe="" src="/images/login/c.png"> 
            <p data-v-10d3eebe="">电脑端登录确认</p>
        </div> 
        <div id="buttons-group">
            <button class="confirm-btn btn">登录</button>
        </div>
    </div> 
</div>

<script src="http://www.jq22.com/jquery/1.11.1/jquery.min.js"></script>
<script src="js/chosen.jquery.js"></script>
<script>
	$(function(){
        
    });
</script>
