<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\ApplyBack;
use frontend\models\Upload;


class ApplyController extends Controller
{
        public $enableCsrfValidation;

        /*public function actionIndex(){
		$model = new ApplyBack();
		try{
			if(Yii::$app->request->isPost){
				$post = Yii::$app->request->post();
			//	echo "<pre>";
			//	print_r($post);exit;
				$model->load($post);
				if(empty($post['ApplyBack']['number_front'])){
					echo "<script> {window.alert('请您上传本人身份证正面照');location.href='/apply/index.html'} </script>";
				}elseif(empty($post['ApplyBack']['number_back'])){
					echo "<script> {window.alert('请您上传本人身份证反面照');location.href='/apply/index.html'} </script>";
				}elseif(empty($post['ApplyBack']['bank_img'])){
					echo "<script> {window.alert('请您上传本人四大行之一的银行卡正面照');location.href='/apply/index.html'} </script>";
				}
				if($model->validate()){
					if($model->save(false)){;
						echo "<script> {window.alert('提交成功');location.href='/apply/index.html'} </script>";
					}
				}
			}
		}catch(\Exception $e){
			Yii::$app->session->setFlash('error',$e->getMessage());
		}			
		return $this->render('index',['model'=>$model]);
	}*/
    
    public function actionUploadfiles()
    {
        $this->enableCsrfValidation = false;
        $num = Yii::$app->request->post('num');
        if ($_FILES) {
            $model = new Upload;
            $result = $model->uploadImage($_FILES, false, 'apply');
            if ($result[0] == true) {
                echo <<<EOF
    <script>parent.stopSend("{$num}","{$result[1]}","{$result[2]}");</script>
EOF;
            } else {
                echo <<<EOF
    <script>alert("{$result[1]}");</script>
EOF;
            }
        }
    }


	
}

?>
