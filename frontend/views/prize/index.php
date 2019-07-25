<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" />
<style>
	#w0{
		display:none;	
	}
	.wrap > .container{
		padding:20px 15px 20px;
	}
	.footer{
		display:none;
	}
	.hm-t-container{
		display:none;
	}
	.prv img{
		border-radius: 5px;
	}
	.giftout1{
	   position:fixed;
	   width:80%;
	   left:10%;
	   top:30%;
	   background:#FA4F47;
	   padding:10px;
	   z-index:11;
	   border-radius:10px;
	   padding-top:20px;
	   display:none;
	}
	.giftout1 .org{
	   color:#F9E607;
	}
	.giftout1 .pad-h{ width:70%; margin:0 auto; }
	.giftout1 a.btns{
	   color:#E64834;
	   background:#FFF;
	   display:block;
	   line-height:30px;
	   text-align:center;
	   border-radius:30px;
	   margin-top:15px;
	   width:40%;
	   margin:0 auto;
	}
	.giftout1 a.btns{
	    margin-left: 20px;
	    float: left;
	}

	#ljcj a.btn{
	    color: #E64834;
    	    background: #FFF;
    	    display: block;
            line-height: 30px;
            text-align: center;
            border-radius: 30px;
            margin-top: 15px;
            width: 40%;
            margin: 0 auto;
	}
</style>
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/prize/swiper.min.css" rel="stylesheet">
<link href="/css/prize/common.css" rel="stylesheet">
<link href="/css/prize/index.css" rel="stylesheet">
<script src="/js/jquery.min.js"></script>
<script src="/js/prize/swiper.min.js"></script>
<script src="/js/prize/jQueryRotate.2.2.js"></script>
<script src="/js/prize/jquery.easing.min.js"></script>
<script type="text/javascript">
var lottery={
	index:0,	//当前转动到哪个位置
	count:9,	//总共有多少个位置
	timer:0,	//setTimeout的ID，用clearTimeout清除
	speed:200,	//初始转动速度
	times:0,	//转动次数
	cycle:21,	//转动基本次数：即至少需要转动多少次再进入抽奖环节
	prize:4,	//中奖位置
	init:function(id){
		if ($("#"+id).find(".lottery-unit").length>0) {
			$lottery = $("#"+id);
			$units = $lottery.find(".lottery-unit");
			this.obj = $lottery;
			this.count = $units.length;
			$lottery.find(".lottery-unit-"+this.index).addClass("active");
		};
	},
	roll:function(){
		var index = this.index;
		var count = this.count;
		var lottery = this.obj;
		$(lottery).find(".lottery-unit-"+index).removeClass("active");
		index += 1;
		if (index>count-1){
			index = 0;
		};
		$(lottery).find(".lottery-unit-"+index).addClass("active");
		this.index=index;
		return false;
	},
	stop:function(index){
		this.prize=index;
		return false;
	}
};

function roll(){
	lottery.times += 1;
	lottery.roll();
	if (lottery.times > lottery.cycle+10 && lottery.prize==lottery.index) {
		$('#giftname').html(p);
		$('#giftimg').attr('src','/images/prize/'+n+'rmb.jpg');
   		$('#prize_code').val(thisCode);
		showgiftbox();
		//alert(lottery.prize+' / '+lottery.index);
		clearTimeout(lottery.timer);
		//lottery.prize=4;
		lottery.times=0;
		click=false;
	}else{
		if (lottery.times<lottery.cycle) {
			lottery.speed -= 10;
		}else if(lottery.times==lottery.cycle) {
			//var index = Math.random()*(lottery.count)|0;
			//lottery.prize = index;		
		}else{
			if (lottery.times > lottery.cycle+10 && ((lottery.prize==0 && lottery.index==7) || lottery.prize==lottery.index+1)) {
				lottery.speed += 110;
			}else{
				lottery.speed += 20;
			}
		}
		if (lottery.speed<40) {
			lottery.speed=40;
		};
		//console.log(lottery.times+'^^^^^^'+lottery.speed+'^^^^^^^'+lottery.prize);
		lottery.timer = setTimeout(roll,lottery.speed);
	}
	return false;
}
var click = false;
var n = 1;
var p = '1元红包';
$(function(){
	$('#start').click(function(){
	   $('.giftout1').show();
	   $('.error-msg').hide();	
	});	

	$('#choujiang').click(function(){
	   var prize_code = $('#code').val();
	   if(prize_code === ''){
		$('.error-msg').show();	
		return false;
	   }
	   $.ajax({
                  url : "/prize/code",
                  type : "POST",
		  data:{code:prize_code},
                  dataType: 'json',
                  success : function(data){
			if(data.status==1){
	   			$('.giftout1').hide();
				thisCode = data.code
	  	 		if(click){ return false; }
	  	 		$.ajax({
	  	 		       url : "/prize/get-prize",
	  	 		       type : "POST",
		  		       data:{code:thisCode},
	  	 		       dataType: 'json',
	  	 		       error : function(){
	  	 		          alert('出错了');
	  	 		       },
	  	 		       success : function(d){
	  	 		          var a = d.angle;
	  	 		     	 p = d.prize_name;
	  	 		     	 n = d.prize_id;
	  	 		     	 var s = d.stoped;
	  	 		     	 lottery.init('lottery');
	  	 		     	 lottery.speed=100;
	  	 		     	 lottery.prize=s;
	  	 		     	 click=true;
	  	 		     	 roll();
	  	 		       }   
	  	 		});
			}else if(data.status == 2){
				$('.giftout1').hide();
				$('.prize-msg').html('您已经抽中了'+data.prize+'，请您尽快领取！');
                		$('#giftimg').attr('src','/images/prize/'+data.prize_id+'rmb.jpg');
                		$('#prize_code').val(data.code);
				showgiftbox();
			}else if(data.status == 3){
				$('.error-msg').html(data.msg);	
				$('.error-msg').show();	
			}

	          }   
            });
	});	

	$('#ljlq').click(function(){
	   var username = $('input[name="username"]').val();
	   var phone = $('input[name="phone"]').val();
	   var code = $('#prize_code').val();
	   /*if(username === ''){
		return false;
	   }

	   if(phone === ''){
		return false;
	   }*/
           $.ajax({
                  url : "/prize/receive-prize",
                  type : "POST",
                  data:{code:code,username:username,phone:phone},
                  dataType: 'json',
                  success : function(data){
			if(data.status==1){
	      			hidegiftbox();
			}		   		
		  }
	   });
	});
	//userName('#username', ".errormsg1");
	//userTel('#phone', ".errormsg2");
	
	$('.getgift').click(function(){
	   if($(this).hasClass('notread')){
	      $(this).removeClass('notread');
		  $('.giftfm').removeClass('hidden');
	   }else{
	      //hidegiftbox();
	   }
	});
	//关闭
	$('.closegift').click(function(){
	   hidegiftbox();
	});
	$('.close1').click(function(){
           $('.giftout1').fadeOut(500);
        });
	//规则
	$('.showrolebox').click(function(){
	   showrolebox();
	});
	$('.closerolebox').click(function(){
	   hiderolebox();
	});
});
function showgiftbox(){
   $('.giftfm').addClass('hidden');
   $('.getgift').addClass('notread');
   $('.cover').fadeIn(500,function(){
      $('.giftout,.giftbg,.giftclose').show();
   });
}
function hidegiftbox(){
   $('.giftout,.giftbg,.giftclose').hide(function(){
      $('.cover').fadeOut();
   });
}
function showrolebox(){
   $('.cover').fadeIn();
   $('.rolebox').show();
}
function hiderolebox(){
   $('.rolebox').hide(function(){
      $('.cover').fadeOut();
   });
}

function isChinaName(name) {
    var pattern = /^[\u4E00-\u9FA5]{1,6}$/;
    return pattern.test(name);
}

// 验证手机号
function isPhoneNo(phone) {
    var pattern = /^1[34578]\d{9}$/;
    return pattern.test(phone);
}
/*用户名判断*/
function userName(inputid, spanid) {
    $(inputid).blur(function() {
        if ($.trim($(inputid).val()).length == 0) {
            $(spanid).html("× 名称没有输入");
	    $(spanid).show();
	    return false;
        } else {
            if (isChinaName($.trim($(inputid).val())) == false) {
                $(spanid).html("× 名称不合法");
	    	$(spanid).show();
	    	return false;
            }
        }
    });
    $(inputid).focus(function() {
        $(spanid).html("");
	$(spanid).hide();
    });

};
/*手机号判断*/
function userTel(inputid, spanid) {
    $(inputid).blur(function() {
        if ($.trim($(inputid).val()).length == 0) {
            $(spanid).html("× 手机号没有输入");
	    $(spanid).show();
	    return false;
        } else {
            if (isPhoneNo($.trim($(inputid).val())) == false) {
                $(spanid).html("× 手机号码不正确");
	    	$(spanid).show();
	    	return false;
            }
        }
        $(inputid).focus(function() {
            $(spanid).html("");
        });
	$(spanid).hide();
    });
};
</script>

<div class="kljgg"><img src="/images/prize/logo-1.png" class="img-responsive"></div>
<div class="jggbox">
   <div class="clearfix"><img src="/images/prize/jgg.png" class="img-responsive"></div>
   <div class="cont" id="lottery">
      <table width="100%" height="100%">
         <tr>
             <td height="33%" class="lottery-unit lottery-unit-0 active"><div class="prv"><img src="/images/prize/888rmb.jpg" class="img-responsive"><div class="toum"></div></div></td>
             <td class="lottery-unit lottery-unit-1"><div class="prv"><img src="/images/prize/50rmb.jpg" class="img-responsive"><div class="toum"></div></div></td>
             <td class="lottery-unit lottery-unit-2"><div class="prv"><img src="/images/prize/500rmb.jpg" class="img-responsive"><div class="toum"></div></div></td>
         </tr>
         <tr>
             <td height="33%" class="lottery-unit lottery-unit-7"><div class="prv"><img src="/images/prize/50rmb.jpg" class="img-responsive"><div class="toum"></div></div></td>
             <td><a href="javascript:void(0);" id="start"><img src="/images/prize/btn.jpg" class="img-responsive"></a></td>
             <td class="lottery-unit lottery-unit-3"><div class="prv"><img src="/images/prize/50rmb.jpg" class="img-responsive"><div class="toum"></div></div></td>
         </tr>
         <tr>
             <td height="33%" class="lottery-unit lottery-unit-6"><div class="prv"><img src="/images/prize/100rmb.jpg" class="img-responsive"><div class="toum"></div></div></td>
             <td class="lottery-unit lottery-unit-5"><div class="prv"><img src="/images/prize/2rmb.jpg" class="img-responsive"><div class="toum"></div></div></td>
             <td class="lottery-unit lottery-unit-4"><div class="prv"><img src="/images/prize/100rmb.jpg" class="img-responsive"><div class="toum"></div></div></td>
         </tr>
      </table>
   </div>
</div>
<div class="clearfix text-center wihte f-sm">
<p class="mar-v-xs"><a href="javascript:void(0);" class="rolebtn showrolebox" style="color: #FFF;padding: 4px 10px;    border: 1px #FFF solid;">活动规则</a></p>
<!--<p class="mar-v-xs">开始时间：2019/01/12 14:59</p>
<p class="mar-v-xs">结束时间：2019/01/12 14:59</p>-->
</div>
<div class="cover"></div>
<div class="rolebox" style="z-index: 999;">
   <div class="title" style="background:red">活动规则<a href="javascript:void(0);" class="icon closerolebox icon-close" style="background-image: url('<?php echo Yii::$app->params['domain']?>/prize/icon-close.png');width: 20px;height: 20px;"></a></div>
   <div class="cont">
      <p>1、每人每周有一次机会，红包金额随机（1-888元）红包金额大小看你人品。</p>
      <p>2、需要抽奖码才能进行抽奖，抽奖码获取请扫描微信二维码进行添加。</p>
      <p>3、获奖后截图给客服给予红包金额。客服工作时间为早上九点至晚上十一点。</p>
      <a href="javascript:void(0);" class="btns closerolebox">我知道了</a>
   </div>
</div>
<div class="giftbg"><img src="/images/prize/giftbg.png" class="img-responsive"></div>
<div class="giftout">
   <div class="text-center org pad-v f-md prize-msg">恭喜您，抽中<span id="giftname"></span></div>
   <div class="clearfix">
      <div class="pad-h"><img src="<?php echo Yii::$app->params['domain']?>/prize/1rmb.jpg" id="giftimg" class="img-responsive"></div>
   </div>
   <div class="clearfix text-center mar-v-sm f-sm wihte pad-h-sm">记得截图并发给客服，我们会尽快和您确认并把奖品发给您噢</div>
   <!--<div class="clearfix giftfm hidden">
      <div class="clearfix text-center mar-v-sm f-sm wihte pad-h-sm">记得留下您的联系方式，我们会尽快和您确认把奖品发给您噢</div>
      <div class="clearfix">
         <ul>
            <li style="margin-bottom: 10px;"><input type="hidden" class="form-control" name="prize_code" class="prize_code" id="prize_code"></li>
            <li>
		<input type="text" class="form-control" name="username" id="username" placeholder="姓名">
	    	<p style="text-align: left;color:white;font-size:12px;display:none;margin-top: 5px;" class="errormsg1"></p>
	    </li>
            <li class="mar-v-sm">
		<input type="text" class="form-control" name="phone" id="phone" placeholder="手机">
	    	<p style="text-align:left;color:white;font-size:12px;display:none;margin-top: 5px;" class="errormsg2"></p>
	    </li>
         </ul>
      </div>
   </div>-->
   <!--<div class="clearfix mar-v"><a href="javascript:void(0);" class="btns getgift notread" id="ljlq">立即领取</a></div>-->
</div>
<div class="giftclose"><a href="javascript:void(0);" class="icon closegift icon-close" style="background-image: url('/images/prize/icon-close.png');width: 20px;height: 20px;"></a></div>

<div class="giftout1">
   <div class="close1" style="position: relative;width: 20px;height: 20px;z-index: 13;left: 90%;top: 7%"><a href="javascript:void(0);" class="icon closegift icon-close" style="background-image: url('../../images/prize/icon-close.png');width: 20px;height: 20px;"></a></div>
   <div style="display:block;">
      <div class="clearfix text-center mar-v-sm f-sm wihte pad-h-sm">请输入抽奖码，如果没有抽奖码，请您跟客服联系获取</div>
      <div class="clearfix">
         <ul>
            <li style="margin-bottom: 10px;"><input type="text" id="code" class="form-control" placeholder="添加客服领取抽奖码"></li>
	    <p style="text-align: center;color:white;font-size:12px;display:none;" class="error-msg">× 抽奖码不能为空</p>
         </ul>
      </div>
   </div>
   <div class="clearfix mar-v">
	<a href="javascript:void(0);" class="btns getgift notread" data-toggle="modal" data-target="#wxImg">添加客服</a>
	<a href="javascript:void(0);" class="btns getgift notread" id="choujiang">立即抽奖</a>
   </div>
</div>

<!-- 模态框（Modal） -->
<div class="modal fade" id="wxImg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-body" style="margin: 0 auto;text-align: center;">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute;right: 22px;font-size: 25px;">
			&times;
		</button>
		<?php if($model){?>
			<img src="http://images.hxinq.com<?php echo $model->wx_img?>" style="width:100%;"/>
		<?php }?>
	</div>
			
</div>
