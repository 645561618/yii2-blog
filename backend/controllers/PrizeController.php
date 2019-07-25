<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\helpers\ArrayHelper;
use backend\components\BackendController;
use backend\models\PrizeCodeBack;
use backend\models\PrizeCodeSearch;
use backend\models\WxCustomerBack;
use backend\models\Upload;

class PrizeController extends BackendController
{

    public $enableCsrfValidation;

    public function actionIndex()
    {

	if($_POST){
		$nums = trim($_POST['nums']);
		if(is_numeric($nums) == true){
			if($nums <= 10)	{
				for($i=1;$i<=$nums;$i++){
					//$str = self::getNonceStr(8);
					//$str .= time();
					//$code = str_shuffle($str);//打乱顺序	
					$code = self::MakeCode();
					$model = PrizeCodeBack::find()->where(['code'=>$code])->one();
					if(!$model){
						$model = new PrizeCodeBack();
						$model->code = $code;
						$model->created = time();
	                        		$model->save(false);
					}
				}
        	                return $this->redirect('/prize/index');
			}else{
				Yii::$app->session->setFlash('error','填写抽奖码数不能超过10');	
			}	
		}else{
			Yii::$app->session->setFlash('error','填写抽奖码数有误，请填写数字!');	
		}
	}
	$model = new PrizeCodeBack();
	$models = new PrizeCodeSearch();
        $dataProvider = $models->search(Yii::$app->request->getQueryParams());
        return $this->render('index',['model'=>$model,'models'=>$models,'dataProvider'=>$dataProvider]);
    }
	
    // 制作邀请码
    public function MakeCode() {
        $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand = $code[rand(0,25)]
            .strtoupper(dechex(date('m')))
            .date('d').substr(time(),-5)
            .substr(microtime(),2,5)
            .sprintf('%02d',rand(0,99));
        for(
            $a = md5( $rand, true ),
            $s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',
            $d = '',
            $f = 0;
            $f < 8;
            $g = ord( $a[ $f ] ),
            $d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
            $f++
        );
        return  $d;
    }

    //随机生成邀请码
    public function getNonceStr($length = 32) 
    {
    	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";  
    	$str ="";
    	for ( $i = 0; $i < $length; $i++ )  {  
        	$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
    	} 
    	return $str;
    }

    //微信二维码
    public function actionService()
    {
        $model = new WxCustomerBack();
	try{
        	if(Yii::$app->request->isPost){
			$count = WxCustomerBack::find()->count();
			if($count >=1){
				Yii::$app->session->setFlash('error','只能上传一张二维码图片');	
                               	return $this->redirect('/prize/service');
			}
                	$post = Yii::$app->request->post();
                        $model->load($post);
                        if($model->validate()){
                        	if($model->save(false)){
					Yii::$app->session->setFlash('success','上传成功');	
                                	return $this->redirect('/prize/service');
                                }
                        }
                }
        }catch(\Exception $e){
                Yii::$app->session->setFlash('error',$e->getMessage());
        }

        $dataProvider = $model->search();
        return $this->render('service',['model'=>$model,'dataProvider'=>$dataProvider,'isNew'=>true]);
    }

    //修改二维码
    public function actionUpdateWx($id)
    {
	if($id){
		$model = WxCustomerBack::findOne($id);
		if($model){
        		try{    
        		        if(Yii::$app->request->isPost){
        		                $post = Yii::$app->request->post();
        		                $model->load($post);
        		                if($model->updateWx($post)){
						Yii::$app->session->setFlash('success','修改成功');	
        		                	return $this->redirect('/prize/service');
        		                }
        		        }
        		}catch(\Exception $e){
        		        Yii::$app->session->setFlash('error',$e->getMessage());
        		}
        		
        		$dataProvider = $model->search();
        		return $this->render('service',['model'=>$model,'dataProvider'=>$dataProvider,'isNew'=>false,'staticUrl' => Yii::$app->params['targetDomain'],]);
		}else{
			Yii::$app->session->setFlash('error','不存在该id');	
        		return $this->redirect('/prize/service');
		}
		
	}	
    }

    //发放奖品
    public function actionGrant($id)
    {
        if($id){
                $model = PrizeCodeBack::findOne($id);
                if($model){
                        try{
				$model->status = 3;
                        	if($model->save(false)){
                                	Yii::$app->session->setFlash('success','发放奖品成功');
                        		return $this->redirect(Yii::$app->request->referrer);
                                }
                        }catch(\Exception $e){
                                Yii::$app->session->setFlash('error',$e->getMessage());
                        }
                }else{
                        Yii::$app->session->setFlash('error','不存在该id');
                        return $this->redirect('/prize/index');
                }

        }
    }

        //上传二维码
    public function actionUpload()
    {
    	$this->enableCsrfValidation = false;
        if ($_FILES) {
        	$model = new Upload;
                $result = $model->uploadWx($_FILES, true, 'wx');
		//echo "<pre>";
		//print_r($result);exit;
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
