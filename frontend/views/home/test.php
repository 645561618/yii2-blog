<!doctype html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title></title>
<style>
.comment {height:25rem;}
.head_img{width:40px;height:40px;float:left}
.com_form { width: 100%; position: relative ;float:left;}
.input { width: 100%; height: 120px; border: 1px solid #ccc;resize:none;}
.com_form p { height: 28px; line-height: 28px; position: relative; margin-top: 10px; }
.sub_btn { position: absolute; right: 0px; top: 0; display: inline-block; zoom: 1; /* zoom and *display = ie7 hack for display:inline-block */  *display: inline;
vertical-align: baseline; margin: 0 2px; outline: none; cursor: pointer; text-align: center; font: 14px/100% Arial, Helvetica, sans-serif; padding: .5em 2em .55em; text-shadow: 0 1px 1px rgba(0,0,0,.6); -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.2); -moz-box-shadow: 0 1px 2px rgba(0,0,0,.2); box-shadow: 0 1px 2px rgba(0,0,0,.2); color: #e8f0de; border: solid 1px #538312; background: #64991e; background: -webkit-gradient(linear, left top, left bottom, from(#7db72f), to(#4e7d0e)); background: -moz-linear-gradient(top, #7db72f, #4e7d0e);  filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#7db72f', endColorstr='#4e7d0e');
}
.sub_btn:hover { background: #538018; background: -webkit-gradient(linear, left top, left bottom, from(#6b9d28), to(#436b0c)); background: -moz-linear-gradient(top, #6b9d28, #436b0c);  filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#6b9d28', endColorstr='#436b0c');
}
.reply_btn{background:#007bff;color:#fff;border:none;border-radius:.2rem;}
.comment-top-title{border-bottom: 2px solid black;height: 2.2rem;}
.data-list{list-style: none;padding-top:2rem;border-bottom: rgba(0,0,0,0.1) solid 1px;padding-bottom: 1rem !important;}
.media-body{padding-left:1rem !important;}
.mediai,.pt-3{border-top: rgba(0,0,0,0.1) solid 1px;padding-top: 1rem !important;}
.media-header{margin-bottom: 0.5rem;}
.media-header a{color: #007bff;text-decoration: none;background-color: transparent;}
.float-right{color:"#6c757d";float: right !important;}
.media-content p{margin-top: 0;margin-bottom: 1rem;}
.media-content a{text-decoration: none;}
.hint{color: #939ba2;margin: .5rem 0;}
.hint em{color: #dc3545;font-weight: bold;font-style: normal;}
.media-footer{margin-top: 2.5rem;}
.reply-1{float:right;color: #494f54;}
.reply-2{float:right;color: #494f54;}
.reply-content{resize:none;width:100%;height:90px;border-radius:.5rem;}
</style>
</head>

<body>
<div class="row">
	<div class="comment col-lg-12 col-md-12 col-sx-12">
	  	<img class="head_img" src="/images/default.gif" alt="黄信强博客" title="黄信强博客">
		<form id="form1" action="/home/comments" method="post">
	  		<div class="com_form">
	    			<textarea class="input" id="saytext" name="content"></textarea>
	    			<p class="error_code" style="float:left;color:red;display:none;">错误</p><p><input type="button" class="sub_btn" value="评论"></p>
	  		</div>
		</form>
	</div>
	<div class="col-lg-12 col-md-12 col-sx-12">
		<div class="comment-top-title">
			<p style="float:left;margin-left: 12px;">最新评论</p>	
			<p style="float:right;margin-right:12px">总共<em class="c-nums" style="color: #dc3545;font-weight: bold;font-style: normal;"><?=$nums?></em>条评论</p>
		</div>
	</div>
	<ul class="col-lg-12 col-md-12 col-sx-12" id="c-list">
		<?php if($result){ ?>
		<?php foreach($result as $k=>$v){ ?>
		<li class="data-list" data-id="<?=$v['id']?>">
	  		<img class="head_img" src=<?php if($v['headimg']){?><?=$v['headimg']?><?php }else{ ?>"/images/default.gif"<?php }?> alt="黄信强博客" title="黄信强博客">
			<div class="media-body">
				<div class="media-header"><a href="javascript:void(0);" class="comment" data-name="<?=$v['c_uid']?>"><?=$v['nickname']?></a> 评论 <span style="color:#6c757d;" class="float-right"><?=$v['create_time']?></span></div>
				<div class="media-content"><p><?=$v['content']?><a href="javascript:void(0);" class="reply-1"><svg style="width: 1.2rem;margin-right: .5rem;" class="svg-inline--fa fa-reply fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="reply" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M8.309 189.836L184.313 37.851C199.719 24.546 224 35.347 224 56.015v80.053c160.629 1.839 288 34.032 288 186.258 0 61.441-39.581 122.309-83.333 154.132-13.653 9.931-33.111-2.533-28.077-18.631 45.344-145.012-21.507-183.51-176.59-185.742V360c0 20.7-24.3 31.453-39.687 18.164l-176.004-152c-11.071-9.562-11.086-26.753 0-36.328z"></path></svg>回复</a></p></div>
				<div class="comment-reply">

                        	</div>
				<p class="c-code" style="color:red;display:none;">错误</p>
				<div class="hint" style="<?php if($v['reply_list']){?>display:block;<?php }else{?>display:none<?php }?>">共<em><?=$v['c_nums']?></em>条回复</div>
				<div class="reply-list">
					<?php if($v['reply_list']){ ?>
					<?php foreach($v['reply_list'] as $kk=>$vv){ ?>
					<div class="media pt-3">
						<img class="head_img" src=<?php if($vv['headimg']){?><?=$vv['headimg']?><?php }else{ ?>"/images/default.gif"<?php }?> alt="黄信强博客" title="黄信强博客">
						<div class="media-body">
							<div class="media-header"><a href="javascript:void(0);" class="comment" data-name="<?=$vv['reply_uid']?>"><?=$vv['r_nickname']?></a> 回复 <a href="javascript:void(0);"><?=$vv['c_nickname']?></a><span style="color:#6c757d;" class="float-right"><?=$vv['create_time']?></span></div>
							<div class="media-content"><p><?=$vv['content']?><a href="javascript:void(0);" class="reply-2"><svg style="width: 1.2rem;margin-right: .5rem;" class="svg-inline--fa fa-reply fa-w-16" aria-hidden="true" data-prefix="fas" data-icon="reply" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M8.309 189.836L184.313 37.851C199.719 24.546 224 35.347 224 56.015v80.053c160.629 1.839 288 34.032 288 186.258 0 61.441-39.581 122.309-83.333 154.132-13.653 9.931-33.111-2.533-28.077-18.631 45.344-145.012-21.507-183.51-176.59-185.742V360c0 20.7-24.3 31.453-39.687 18.164l-176.004-152c-11.071-9.562-11.086-26.753 0-36.328z"></path></svg>回复</a></p></div>
							
							<div class="comment-reply">
								<!--<form id="reply-content" action="/home/replycomment" method="post">
									<div class="com_form">
										<textarea name="content" class="reply-content"></textarea>
										<p><input type="button" class="reply_btn" value="回复"></p>
									</div>
								</form>-->
							</div>
							<p class="c-code" style="color:red;display:none;">错误</p>
						</div>
					</div>
					<?php }?>
					<?php }?>
				</div>
			</div>	
		</li>
		<?php }?>
		<?php }?>

	</ul>
</div>
<input type="hidden" value="<?=$_GET['id']?>" id="aid">
</body>
<script src="/js/jquery.min.js"></script>
<script type="text/javascript">

$(function(){
	$('.sub_btn').click(function(){
	    var aid= $('#aid').val();
            var content = $('#saytext').val();
	    $.ajax({
		type: "POST",
		dataType: "json",
		url: "/home/comments",
		data: {'content':content,'aid':aid},
		success: function (result) {
		    console.log(result);
		    if (result.code == 200) {
			$('#c-list').prepend(""+
			  "<li class='data-list' data-id='"+result.data.id+"'>"+
			  "<img class='head_img' src='"+result.data.head_img+"' alt='黄信强博客' title='黄信强博客'>"+
			  "<div class='media-body'>"+
			  "<div class='media-header'><a href='javascript:void(0);' class='comment' data-name='"+result.data.c_uid+"'>"+result.data.nickname+"</a> 评论 <a href='javascript:void(0);'></a><span style='color:#6c757d;' class='float-right'>"+result.data.create_time+"</span></div>"+
			  "<div class='media-content'><p>"+result.data.content+"<a href='javascript:void(0);' class='reply-1'><svg style='width: 1.2rem;margin-right: .5rem;' class='svg-inline--fa fa-reply fa-w-16' aria-hidden='true' data-prefix='fas' data-icon='reply' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512' data-fa-i2svg=''><path fill='currentColor' d='M8.309 189.836L184.313 37.851C199.719 24.546 224 35.347 224 56.015v80.053c160.629 1.839 288 34.032 288 186.258 0 61.441-39.581 122.309-83.333 154.132-13.653 9.931-33.111-2.533-28.077-18.631 45.344-145.012-21.507-183.51-176.59-185.742V360c0 20.7-24.3 31.453-39.687 18.164l-176.004-152c-11.071-9.562-11.086-26.753 0-36.328z'></path></svg>回复</a></p></div>"+
			  "<div class='comment-reply'></div>"+
			  "<div class='hint' style='display:none'>共<em>0</em>条回复</div>"+
			  "<div class='reply-list'>"+
			  "</div>"+
			  "<p class='c-code' style='color:red;display:none;'>错误</p>"+
			  "</div>"+
			  "</li>");	
			$('.c-nums').html(result.data.c_nums);
			reply1();
		    }else if(result.code==100){
			$('.error_code').html(result.msg);
			$('.error_code').show();
		    }else if(result.code==300){
			$('.error_code').html(result.msg);
                        $('.error_code').show();
		    }
		    $('#c-list .comment-reply').hide();
		    $('#c-list .c-code').hide();
		},
		error : function() {
		    console.log("异常！");
		}
	    });
	})

	reply1();
	reply2();
	
	function reply1(){
		$('.reply-1').on("click",function(){
			var str ="<form id='reply-content' action='/home/replycomment' method='post'><div class='com_form'><textarea name='content' class='reply-content'></textarea><p><input type='button' class='reply_btn' value='回复'></p></div></form>";
			$(this).parents('.media-content').siblings('.comment-reply').html(str);
			$(this).parents('.media-content').siblings('.reply-list').find('.comment-reply').hide();
			$(this).parents('.data-list').siblings('.data-list').find('.comment-reply').hide();
			$(this).parents('.data-list').siblings('.data-list').find('.c-code').hide();
			$(this).parents('.media-content').siblings('.reply-list').find('.c-code').hide();
			$(this).parents('.media-content').siblings('.comment-reply').show();
			$('.error_code').hide();

			$(this).parents('.media-content').siblings('.comment-reply').find('.reply_btn').click(function(){
				var c_id = $(this).parents('.data-list').data('id');
				var content = $(this).parents('.comment-reply').find('.reply-content').val();
				var c_uid = $(this).parents('.comment-reply').siblings('.media-header').find('.comment').data('name');
				var mythis = $(this);
				$.ajax({
					type: "POST",
					dataType: "json",
					url: "/home/reply-comments",
					data: {'content':content,'c_id':c_id,'c_uid':c_uid},
					success: function (result) {
					    console.log(result);
					    if (result.code == 200) {
						mythis.parents('.comment-reply').siblings('.reply-list').prepend(
							"<div class='media pt-3'>"+
							"<img class='head_img' src='"+result.data.head_img+"' alt='黄信强博客' title='黄信强博客'>"+
							"<div class='media-body'>"+
							"<div class='media-header'><a href='javascript:void(0);' class='comment' data-name='"+result.data.r_uid+"'>"+result.data.r_nickname+"</a> 回复 <a href='javascript:void(0);'>"+result.data.c_nickname+"</a><span style='color:#6c757d;' class='float-right'>"+result.data.create_time+"</span></div>"+
							"<div class='media-content'><p>"+result.data.content+"<a href='javascript:void(0);' class='reply-2'><svg style='width: 1.2rem;margin-right: .5rem;' class='svg-inline--fa fa-reply fa-w-16' aria-hidden='true' data-prefix='fas' data-icon='reply' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512' data-fa-i2svg=''><path fill='currentColor' d='M8.309 189.836L184.313 37.851C199.719 24.546 224 35.347 224 56.015v80.053c160.629 1.839 288 34.032 288 186.258 0 61.441-39.581 122.309-83.333 154.132-13.653 9.931-33.111-2.533-28.077-18.631 45.344-145.012-21.507-183.51-176.59-185.742V360c0 20.7-24.3 31.453-39.687 18.164l-176.004-152c-11.071-9.562-11.086-26.753 0-36.328z'></path></svg>回复</a></p></div>"+
							"<div class='comment-reply'></div>"+
			  				"<p class='c-code' style='color:red;display:none;'>错误</p>"+
							"</div>"+
							"</div>");
						mythis.parents('.comment-reply').siblings('.hint').find('em').html(result.data.r_nums);
						mythis.parents('.comment-reply').siblings('.hint').show();
						reply2();
					    }else if(result.code==100){
                                                mythis.parents('.comment-reply').siblings('.c-code').html(result.msg);
                                                mythis.parents('.comment-reply').siblings('.c-code').show();
                                            }else if(result.code==300){
						mythis.parents('.comment-reply').siblings('.c-code').html(result.msg);
                                                mythis.parents('.comment-reply').siblings('.c-code').show();
                                            }else if(result.code==400){
						mythis.parents('.comment-reply').siblings('.c-code').html(result.msg);
                                                mythis.parents('.comment-reply').siblings('.c-code').show();
                                            }
					},
					error : function() {
					    console.log("异常！");
					}
				});
			
			})
		})
	}
	
	function reply2(){
		$('.reply-2').click(function(){
			var str ="<form id='reply-content' action='/home/replycomment' method='post'><div class='com_form'><textarea name='content' class='reply-content'></textarea><p><input type='button' class='reply_btn' value='回复'></p></div></form>";
			$(this).parents('.reply-list').siblings('.comment-reply').hide();
			$(this).parents('.media').siblings('.media').find('.comment-reply').hide();
			$(this).parents('.reply-list').siblings('.c-code').hide();
			$(this).parents('.media').siblings('.media').find('.c-code').hide();
			$('.error_code').hide();
			$(this).parents('.data-list').siblings('.data-list').find('.comment-reply').hide();
			$(this).parents('.data-list').siblings('.data-list').find('.c-code').hide();
			$(this).parents('.media-content').siblings('.comment-reply').html(str);
			$(this).parents('.media-content').siblings('.comment-reply').show();
			$(this).parents('.media-content').siblings('.comment-reply').find('.reply_btn').click(function(){
				var c_id = $(this).parents('.data-list').data('id');
				var content = $(this).parents('.comment-reply').find('.reply-content').val();
				var c_uid = $(this).parents('.comment-reply').siblings('.media-header').find('.comment').data('name');
				var mythis = $(this);
				$.ajax({
					type: "POST",
					dataType: "json",
					url: "/home/reply-comments",
					data: {'content':content,'c_id':c_id,'c_uid':c_uid},
					success: function (result) {
					    console.log(result);
					    if (result.code == 200) {
						mythis.parents('.reply-list').append(
                                                        "<div class='media pt-3'>"+
                                                        "<img class='head_img' src='"+result.data.head_img+"' alt='黄信强博客' title='黄信强博客'>"+
                                                        "<div class='media-body'>"+
                                                        "<div class='media-header'><a href='javascript:void(0);' class='comment' data-name='"+result.data.r_uid+"'>"+result.data.r_nickname+"</a> 回复 <a href='javascript:void(0);'>"+result.data.c_nickname+"</a><span style='color:#6c757d;' class='float-right'>"+result.data.create_time+"</span></div>"+
                                                        "<div class='media-content'><p>"+result.data.content+"<a href='javascript:void(0);' class='reply-2'><svg style='width: 1.2rem;margin-right: .5rem;' class='svg-inline--fa fa-reply fa-w-16' aria-hidden='true' data-prefix='fas' data-icon='reply' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512' data-fa-i2svg=''><path fill='currentColor' d='M8.309 189.836L184.313 37.851C199.719 24.546 224 35.347 224 56.015v80.053c160.629 1.839 288 34.032 288 186.258 0 61.441-39.581 122.309-83.333 154.132-13.653 9.931-33.111-2.533-28.077-18.631 45.344-145.012-21.507-183.51-176.59-185.742V360c0 20.7-24.3 31.453-39.687 18.164l-176.004-152c-11.071-9.562-11.086-26.753 0-36.328z'></path></svg>回复</a></p></div>"+
                                                        "<div class='comment-reply'></div>"+
			  				"<p class='c-code' style='color:red;display:none;'>错误</p>"+
                                                        "</div>"+
                                                        "</div>");
                                                mythis.parents('.reply-list').siblings('.hint').find('em').html(result.data.r_nums);
                                                mythis.parents('.reply-list').siblings('.hint').show();
                                                reply2();
					    }else if(result.code==100){
						mythis.parents('.comment-reply').siblings('.c-code').html(result.msg);
                                                mythis.parents('.comment-reply').siblings('.c-code').show();
					    }else if(result.code==300){
						mythis.parents('.comment-reply').siblings('.c-code').html(result.msg);
                                                mythis.parents('.comment-reply').siblings('.c-code').show();
					    }else if(result.code==400){
						mythis.parents('.comment-reply').siblings('.c-code').html(result.msg);
                                                mythis.parents('.comment-reply').siblings('.c-code').show();
					    }

					},
					error : function() {
					    console.log("异常！");
					}
				});
			
			})
			
		})
	}

	




});


</script>
</html>

