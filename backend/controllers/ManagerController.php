<?php
namespace backend\controllers;

use Yii;
use backend\models\LoginBack;
use backend\components\BackendController;

class ManagerController extends BackendController{

    //用户列表
    public function actionAdmin()
    {
        $model = new LoginBack;
        $data = $model->getAllData();
        return $this->render('admin',['data'=>$data]);
    }
    //创建用户
    public function actionCreate()
    {
        $model = new LoginBack;
	    //echo "<pre>";
	    //print_r($_POST);exit;
	if($_POST){
		$email = $_POST['LoginBack']['email'];
		$username = $_POST['LoginBack']['username'];
		$pwd = $_POST['LoginBack']['password'];
		$result = LoginBack::find()->where(["username"=>$username])->limit(1)->one();
		if($result){
			Yii::$app->session->setFlash('error','用户名不能相同');
		    	return $this->redirect("/manager/create");
		}
	        $model->setScenario('signup');
        	if($model->load($_POST) && $model->save()){
		    $mail= Yii::$app->mailer->compose('@app/views/manager/email',['email'=>$email,'username'=>$username,'pwd'=>$pwd]);   
		    $mail->setTo("{$email}");  
		    $mail->setSubject("后台管理账号发送通知");  
		    //$mail->setTextBody('邮件测试');   //发布纯文字文本
		    //$mail->setHtmlBody("<br>邮件测试,用户名：{$username}，密码：{$pwd}");    //发布可以带html标签的文本
		    if($mail->send()){
			Yii::$app->session->setFlash('success','邮件发送成功');
		    }else{
			Yii::$app->session->setFlash('error','邮件发送失败');
		    }
		    return $this->redirect("/manager/admin");
        	}
	}
        return $this->render('create',['model'=>$model,"isNew"=>true]);
    }

    
    


    //删除用户
    public function actionDeleteManager($id){
        $model = LoginBack::findOne($id);
        $model->deleteUser($id);
        return $this->redirect("/manager/admin");
    }

    //编辑用户
    public function actionUpdateManager($id){
        if($id){
            $model = LoginBack::findOne($id);
            if (!empty($_POST)) {
		$email = $_POST['LoginBack']['email'];
                $username = $_POST['LoginBack']['username'];
                $pwd = $_POST['LoginBack']['password'];
                if ($model->updateAttrs($_POST['LoginBack'])) {
		    $mail= Yii::$app->mailer->compose('@app/views/manager/edit-password-email',['email'=>$email,'username'=>$username,'pwd'=>$pwd]);   
                    $mail->setTo("{$email}");  
                    $mail->setSubject("后台管理账号密码修改通知");  
                    //$mail->setTextBody('邮件测试');   //发布纯文字文本
                    //$mail->setHtmlBody("<br>邮件测试,用户名：{$username}，密码：{$pwd}");    //发布可以带html标签的文本
                    if($mail->send()){
                        Yii::$app->session->setFlash('success','邮件发送成功');
                    }else{
                        Yii::$app->session->setFlash('error','邮件发送失败');
                    }
                    return $this->redirect("/");
                }
            }
        }
        return $this->render('create',['model'=>$model,"isNew"=>false]);
    }

}
