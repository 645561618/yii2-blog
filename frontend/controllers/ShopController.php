<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\components\FrontendController;
use frontend\models\OrderFront;
use backend\models\TotalBalanceBack;
use common\models\User;
use frontend\models\Upload;
/**
 * shop controller
 */
class ShopController extends FrontendController
{
	public $enableCsrfValidation;

        public function actionInfo(){
                $shop_uid = Yii::$app->user->identity->id;
                $totalData = TotalBalanceBack::find()->where(['uid'=>$shop_uid])->one();
                $total = 0;
                if($totalData){
                        $total = $totalData->total_account;
                }


                $model = User::findOne($shop_uid);
                if(Yii::$app->request->isPost){
                        $post = Yii::$app->request->post();
			$model->phone = $post['User']['phone'];
			$model->url = $post['User']['url'];
                        if($model->save(false)){
                        	echo "<script> {window.alert('编辑信息成功');location.href='/site/index.html'} </script>";        
                        };
                }


                return $this->render('info',
                        [
				'model'=>$model,
                                'username'=>Yii::$app->user->identity->username,
                                'total'=>$total,
				'staticUrl'=>Yii::$app->params['targetDomain'],
                        ]);
        }


	public function actionUploadfile()
    	{
        $this->enableCsrfValidation = false;
        if ($_FILES) {
            $model = new Upload;
            $result = $model->uploadImage($_FILES, false, 'header');
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

