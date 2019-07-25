<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\components\FrontendController;
use frontend\models\OrderFront;
use backend\models\TotalBalanceBack;
use common\models\User;
/**
 * password controller
 */
class PasswordController extends FrontendController
{
        public $enableCsrfValidation;

        public function actionIndex(){
                $shop_uid = Yii::$app->user->identity->id;
                $totalData = TotalBalanceBack::find()->where(['uid'=>$shop_uid])->one();
                $total = 0;
                if($totalData){
                        $total = $totalData->total_account;
                }


		$model = User::findOne($shop_uid);
		if(Yii::$app->request->isPost){
                        $post = Yii::$app->request->post();
                        $model->setPassword($post['password']);
                        if($model->save(false)){
				Yii::$app->session->setFlash('success', '修改密码成功!');
                        };
                }


                return $this->render('index',
                        [
                                'username'=>Yii::$app->user->identity->username,
                                'total'=>$total,
				'staticUrl'=>Yii::$app->params['targetDomain'],
				'model'=>$model,
                        ]);
        }


}
