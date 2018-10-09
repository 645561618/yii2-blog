<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>智囊团网邮箱验证</title>
  <style type="text/css">
  a:hover{ cursor: pointer;}
  </style>
</head>

<body>
<div style="width:700px; margin:auto; padding:auto; font-size: 12px; border: 1px solid #999999;">
	<div style="width:700px; height:52px; background-color: #e74303; color: #fff; font-size: 16px; line-height: 52px; font-weight: bolder;">
	<img style="float: left; margin-top: 2px; margin-left: 10px;" src="http://www.51znt.com/data/pic/email/qyyx_logo.gif" />&nbsp;&nbsp;&nbsp;<span> 让每个企业第一时间享受全球优质猎头服务</span>
	</div>
	<div style="width: 700px;">
		<div style="width:700px; height: 22px; line-height: 22px; margin-top: 35px; text-indent: 45px; margin-bottom: 20px;">尊敬的 <span style="text-decoration: underline; color: #cd3f08; font-weight: bolder; "><?php echo EnumMemberProperty::getText($member->property);?></span> 您好!</div>
		<div style="width: 700px;">
			<div style="width: 700px; height: 20px; line-height: 20px; color: #cd3f08; text-indent: 45px;"> 恭喜您已经成功注册智囊团网</div>
			<div style="width: 700px; height: 25px; line-height: 25px;  text-indent: 45px;">您的账号：<span style="color: #000; font-weight: bolder;"><?php echo $member->login_id;?></span></div>
			<div style="width: 700px; height: 25px; line-height: 25px;  text-indent: 45px;"><a href="<?php echo $url;?>"><span style="color: #000; font-weight: bolder;">点击这里完成您的邮箱验证。</span></a></div>
			<div style="width: 700px; height: 25px; line-height: 25px;  text-indent: 45px;">您也可以将以下链接复制到浏览器地址栏完成您的邮箱验证。</div>
			<div style="width: 700px; line-height: 25px;  text-indent: 45px;"><a href="<?php echo $url;?>"><span style="color: #000; font-weight: bolder;display:block;padding:0 45px; word-break: break-all;"><?php echo $url;?></span></a></div>

			<?php if($member->property==EnumMemberProperty::Headhunter || $member->property==EnumMemberProperty::HeadhunterP){ ?>
			<div style="width: 700px;height: 28px; line-height: 28px;">
			<span style="float: left; margin-left: 45px; ">
			您在智囊团网上可以：<br>1、免费开店；<br> 2、参与企业需求竞标； <br>3、接受企业需求委托。</span>
			</div>
			<?php }elseif($member->property==EnumMemberProperty::Company){ ?>
			<div style="width: 700px;height: 28px; line-height: 28px;">
			<span style="float: left; margin-left: 45px; ">
			您在智囊团网上可以：<br>1、  免费发布高级职位<br> 2、  委托猎头找人才<br></span>
			</div>
			<?php } ?>

		</div>
		<a style=" display:block; width: 593px; height: 33px; margin: auto; padding: auto; background-color: #ea5b24; line-height: 33px; text-align: center ;margin-top: 100px; font-size: 14px; font-weight: bolder; color: #fff;" href="<?php echo Yii::app()->request->hostInfo, Yii::app()->createUrl('/home/default/login');?>">
		立即登录智囊团网
		</a>
		<div style="text-align: center; width: 700px; height: 25px; line-height: 25px;">找好猎头，智囊团网更快！</div>
	</div>

	<div style="width: 700px; height: 137px; background-color: #f2f2f2; border-top: 1px solid #999999;">
		<pre style="margin-left: 45px; margin-top: 15px; line-height: 20px; color: #666666;">注：此邮件为系统邮件，请勿直接回复。
您收到这封邮件是因为您注册了智囊团网的账号或订阅了相关邮件；如果您不需要这份邮件的话，请忽略。
如有任何疑问，请拨打客服电话 4000-525-070
		</pre>
	</div>
</div>
</body>
</html>
