<?php

namespace backend\controllers;

use common\ToolBox\ToolExtend;
use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\imagine\Image;
use yii\web\Controller;
use backend\components\BackendController;
use yii\web\UploadedFile;
use frontend\models\OrderFront;
use backend\models\TotalBalanceBack;
use backend\models\Upload;
use common\models\User;
class UploadController extends BackendController
{
    public $enableCsrfValidation;//禁用Csrf验证  

    public function actionIndex($id)
    {
        $model = OrderFront::findOne($id);
        if($model->status==2){
		Yii::$app->session->setFlash('error', '未签约状态不能上传合同');
		return $this->redirect("/shop/index");
        }
        if(Yii::$app->request->isPost){
                $post = Yii::$app->request->post();
                $model->load($post);
                if($model->validate()){
                        $model->save();
			Yii::$app->session->setFlash('success', '上传合同成功');
	                return $this->redirect("/shop/index");			
                }
        }
        return $this->render('image',
                [
                        'model'=>$model,
                        'staticUrl' => Yii::$app->params['targetDomain'],
                ]);

    }

    public function actionUploadfile()
    {
        if ($_FILES) {
		echo "<pre>";
		print_r($_FILES);
            $model = new Upload;
            $result = $model->uploadImages($_FILES, false, 'order');
            if($result[0] == true){
echo <<<EOF
    <script>parent.stopSend("{$result[1]}","{$result[2]}");</script>
EOF;
            }else{
echo <<<EOF
    <script>alert("{$result[1]}");</script>
EOF;
            }
        }
    }




}

