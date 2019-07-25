<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>后台管理账号登录通知</title>
  <style type="text/css">
  a:hover{ cursor: pointer;}
  </style>
</head>

<body>
<div style="width:700px; margin:auto; padding:auto; font-size: 12px; border: 1px solid #999999;">
        <div style="width:700px; height:52px; background-color: #e74303; color: #fff; font-size: 16px; line-height: 52px; font-weight: bolder;text-align: center;">让每个人第一时间享受全球优质服务</div>
        <div style="width: 700px;">
                <div style="width:700px; height: 22px; line-height: 22px; margin-top: 35px; text-indent: 45px; margin-bottom: 20px;"><span style="text-decoration: underline; color: #cd3f08; font-weight: bolder; ">BOSS</span> 您好!</div>
                <div style="width: 700px;height:180px">
                        <div style="width: 700px; height: 30px; line-height: 30px; color: #cd3f08; text-indent: 66px;"> 您的管理用户<?=$username?>在<?=$time?>登录了管理后台！</div>
                        <div style="width: 700px; height: 30px; line-height: 30px;  text-indent: 66px;">登录账号：<span style="color: #000; font-weight: bolder;"><?php echo $username;?></span></div>
                        <div style="width: 700px; height: 30px; line-height: 30px;  text-indent: 66px;">登录IP：<span style="color: #000; font-weight: bolder;"><?php echo $ip;?></span></div>
                        <!--<div style="width: 700px; height: 30px; line-height: 30px;  text-indent: 66px;">登录所在的城市：<span style="color: #000; font-weight: bolder;"><?php // echo $cityData['pro'];?>&nbsp;&nbsp;<?php // echo $cityData['city']; ?>&nbsp;&nbsp;<?php // echo $cityData['county']; ?></span></div>-->
                        <div style="width: 700px; height: 30px; line-height: 30px;  text-indent: 66px;"><a href="http://b.hxinq.com"><span style="color: #000; font-weight: bolder;">点击这里，登录后台管理。</span></a>&nbsp;&nbsp;&nbsp;</div>
                </div>
        </div>

        <div style="width: 700px; height: 137px; background-color: #f2f2f2; border-top: 1px solid #999999;">
                <pre style="margin-left: 45px; margin-top: 15px; line-height: 20px; color: #666666;">注：此邮件为系统邮件，请勿直接回复。
您收到这封邮件是因为您已经拥有了后台管理员的账号或订阅了相关邮件；如果您不需要这份邮件的话，请忽略。
                </pre>
        </div>
</div>
</body>
</html>

