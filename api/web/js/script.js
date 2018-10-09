$(document).ready(function(){
    $('.loginmenu').focus(function(){
        var texts = $(this).next().text();
        $('.wrong em').text(texts);
    });

    $('.loginmenu').focus(function(){
        $(this).addClass('on');
    });

    $('.loginmenu').blur(function(){
        $(this).removeClass('on').addClass('sure');
    });



    $('.bg_f input').focus(function(){
        var texts = $(this).next().text();
        $('.prompt').text(texts);
    });

    //DOM加载完后执行
        nameflag = false;
        passwordflag = false;
        repasswordflag = false;
        emailflag = false;
        varifyflag = false;
        //绑定所有类名为ipt焦点事件
        $('.ipt').focus(function() {
            $(this).addClass('on');
        });
        //绑定所有类名为ipt失去焦点事件
        $('.ipt').blur(function() {
            $(this).removeClass('on');
        });
        //用户名验证事件绑定       
        $('#username').blur(function() {
            //此处做用户名验证判断，以下代码仅做演示,实际运用中根据ajax返回数据做判断。
            var that = $(this);
            var value = $(this).val();
            if(value==''){                
                $('.prompt').html('用户名不能为空，请输入！');
                $(this).parent().removeClass('yes').addClass('no');
            }else{
                var regex=/^[a-zA-Z][a-zA-Z0-9]{4,15}$/;
                if(regex.exec(value)){
                    $.ajax({
                         type: "POST",
                         url: "/opt/Ajax/checkUserNameExist",
                         data: {username: value},
                         success: function(msg){
                             if(msg==1){
                                 $('.prompt').html('此用户名已被注册，换个试试？');
                                 that.parent().removeClass('yes').addClass('no');
                                 that.addClass('sure');
                             }else{
                                 $('.prompt').html('恭喜，用户名可用，请继续。');
                                 that.parent().removeClass('no').addClass('yes');
                                 that.addClass('sure');
                                 nameflag = true;
                             }
                         }
                    });

                }else{
                    $('.prompt').html('以字母开头，由4-16位字母、数字组成！');
                    that.parent().removeClass('yes').addClass('no');
                }
            }
        });
        //密码验证事件绑定,
        $('#password').blur(function() {
            var value = $(this).val();
            var regex = /^.{6,16}$/
            if(value==''){
                $('.prompt').html('密码不能为空，请输入！');
                $(this).parent().addClass('no');
            }
            else if(value==$('#username').val())
            {
                $('.prompt').html('密码不能和用户名一致');
                $(this).parent().addClass('no');
                $(this).addClass('sure');
            }
            else if(regex.exec(value))
            {
                passwordflag = true;
                $('.prompt').html('密码输入正确。');
                $(this).parent().removeClass('no').addClass('yes');
                $(this).addClass('sure');
            }
            else{
                $('.prompt').html('密码长度介于 6 和 16 之间');
                $(this).parent().addClass('no');
            }
        });
        //密码验证事件绑定
        $('#password_confirm').blur(function() {
            var value = $(this).val();
            if(value==''){
                $('.prompt').html('密码不能为空，请输入！');
            }else if(value!=$('#password').val()){
                $('.prompt').html('两次密码不一致，请核对！');
                $(this).parent().addClass('no');
                
            }else{
                //此处需做格式判断，可使用正式表达式判断，暂略
                repasswordflag = true;
                $('.prompt').html('密码匹配一致。');
                $(this).parent().removeClass('no').addClass('yes');
                $(this).addClass('sure');
            }
        });
        //邮箱地址验证事件绑定
        $('#email').blur(function() {
            //此处做用户名验证判断，以下代码仅做演示,实际运用中根据ajax返回数据做判断。
            var that = $(this);
            var value = $(this).val();
            if(value==''){
                $('.prompt').html('邮箱地址不能为空，请输入！');
                $(this).parent().removeClass('yes').addClass('no'); 
            }else{
                var regex=/^(([0-9a-zA-Z]+)|([0-9a-zA-Z]+[_.0-9a-zA-Z-]*[0-9a-zA-Z]+))@([a-zA-Z0-9-]+[.])+([a-zA-Z]{2}|net|com|gov|mil|org|edu|int)$/;
                if(regex.exec(value)){
                    $.ajax({
                         type: "POST",
                         url: "/opt/Ajax/checkUserEmailExist",
                         data: {email: value},
                         success: function(msg){
                             if(msg==1){
                                 $('.prompt').html('邮箱已经被注册');
                                 that.parent().removeClass('yes').addClass('no');
                                 that.addClass('sure');
                             }else{
                                 $('.prompt').html('输入正确。');
                                 that.parent().removeClass('no').addClass('yes');
                                 that.addClass('sure');
                                 emailflag = true;
                             }
                         }
                    });


                                  
                }else{
                    $('.prompt').html('请输入正确格式的电子邮件');
                    that.parent().removeClass('yes').addClass('no');
                }
            }
        });
        //验证码验证事件绑定
        $('#verifycode').blur(function() {
            //此处做用户名验证判断，以下代码仅做演示,实际运用中根据ajax返回数据做判断。
            var value = $(this).val();
            if(value==''){
                $('.prompt').html('验证码是必填的！');
                $(this).parent().addClass('no');          
            }else{
                //var regex=/^[0-9]{4}$/
                //if(regex.exec(value))
                if(1)
                {
                    $.ajax({
                        type: "POST",
                        url: "/opt/Ajax/checkVerificationExist",
                        data: {verifycode: value},
                        success: function(msg){
                            if(msg==1)
                            {
                                varifyflag = true;
                                $('.prompt').html('正确');
                            }
                            else
                                $('.prompt').html('验证码错误');
                        }
                    });                   
                }else{
                    $('.prompt').html('验证码错误');
                }
            }
        });
});
function checkForm()
{        
    if(nameflag && passwordflag && repasswordflag && emailflag && varifyflag)
    {        
        $("#reg_form").submit();
    }else if(!nameflag){
        $('#username_tip').html('用户名不正确或已注册，请输入！'); 
    }else if(!passwordflag){
        $('#password_tip').html('密码不能为空或输入不正确，请输入！'); 
    }else if(!repasswordflag){
        $('#password_confirm_tip').html('重复密码不能为空或输入不正确，请输入！'); 
    }else if(!emailflag){
        $('#email_tip').html('邮箱地址不正确或已被注册，请输入！'); 
    }else if(!varifyflag){
        $('#verify-tip').html('验证码错误，请输入！'); 
    }
}

function refreshVerify() {
    $('#verify_img').attr('src', '/seccode.php?c=' + Math.random());
}

$(".phone_numbtn").on("click",function(){
    $(".con2").hide(200);
    $(".reg_cont").show(400);
})
