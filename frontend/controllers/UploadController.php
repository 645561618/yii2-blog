<?php  
  
namespace frontend\controllers;  
  
use common\ToolBox\ToolExtend;  
use Yii;  
use yii\helpers\FileHelper;  
use yii\helpers\Html;  
use yii\helpers\Url;  
use yii\imagine\Image;  
use yii\web\Controller;  
use yii\web\UploadedFile;  
use frontend\models\OrderFront;
use backend\models\TotalBalanceBack;
use frontend\models\Upload;
use common\models\User;
class UploadController extends Controller  
{  
    public $enableCsrfValidation;//禁用Csrf验证  

    public function actionImage($id)  
    {
	$model = OrderFront::findOne($id); 
	if($model->status==2){
		echo "<script> {window.alert('未签约状态不能上传合同');location.href='/order/index.html'} </script>";  
	}
	if(Yii::$app->request->isPost){
		$post = Yii::$app->request->post();
		$model->load($post);
		if($model->validate()){
			$model->save();
			echo "<script> {window.alert('上传合同成功,请等待审核');location.href='/order/index.html'} </script>";
		}
	}
	$shop_uid = Yii::$app->user->identity->id;
	$totalData = TotalBalanceBack::find()->where(['uid'=>$shop_uid])->one();
                $total = 0;
                if($totalData){
                        $total = $totalData->total_account;
                }
	$user = User::findOne($shop_uid);
	$url = Yii::$app->params['targetDomain'].$user->url;
	return $this->render('image',
		[
			'model'=>$model,
			'username'=>Yii::$app->user->identity->username,	
			'total'=>$total,
			'staticUrl' => Yii::$app->params['targetDomain'],
			'url'=>$url,
			'user'=>$user,
		]);
  
    }

    public function actionUploadfile()
    {
        $this->enableCsrfValidation = false;
        if ($_FILES) {
            $model = new Upload;
            $result = $model->uploadImage($_FILES, false, 'order');
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
