$(function(){
	$('.loginsubmit').click(function(){
                        var username = $('.username').val();
                        var password = $('.password').val();
                        if(username==''){
                                $('.login_messagewarm').html('用户名不能为空')
                                $('.login_message').show();
                        }else if(password==''){
                                $('.login_messagewarm').html('密码不能为空')
                                $('.login_message').show();

                        }else{
                                $("#login_go").submit();

                        }
                })




})
