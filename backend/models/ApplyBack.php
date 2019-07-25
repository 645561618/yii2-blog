<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\Apply;
class ApplyBack extends Apply{


    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['add'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['add'],
                ],
                'value'=>function(){
                    return date("Y-m-d H:i:s");
                }
            ],
        ];
    }


    public function rules()
    {
        return [
            [['name','email','phone','number','bank_name','bank_code','number_front','number_back','bank_img'], 'required'],
	    ['phone','string','max'=>11],
	    ['email', 'email'],	
	    [['sex','status','read'],'safe'],	
        ];
    }

    public function attributelabels()
    {
        return[
                'name' => '姓名',
                'phone' => '手机号',
                'email' => '邮箱',
                'number' => '身份证号',
                'bank_name' => '开户银行',
                'bank_code'=>'开户代码',
                'sex'=>'性别',
                'status'=>'是否有过期经历',
        ];
    }



    public function getSex(){
	return function($data){
            if($data->sex == 0){
                return "女";
              }else{
                return "男";
            }
        };
   }    


  public function SexValues()
    {
        return [
            0 => '女',
            1 => '男',
        ];
    }


     public function getStatus(){
        return function($data){
            if($data->status == 0){
                return "否";
              }else{
                return "是";
            }
        };
   }


  public function StatusValues(){
        return [
		0 => '否',
		1 => '是',
        ];
   }



   public function getRead(){
        return function($data){
            if($data->read == 0){
                return "不同意";
              }else{
                return "阅读并同意";
            }
        };
   }

   public function getFrontImg(){
        return function($data){
		return "<a href='".Yii::$app->params['targetDomain'].$data->number_front."' target= _blank><img width='50px' height='50px' src='".Yii::$app->params['targetDomain'].$data->number_front."' /></a>";
        };
   }


   public function getBackImg(){
        return function($data){
		return "<a href='".Yii::$app->params['targetDomain'].$data->number_back."' target= _blank><img width='50px' height='50px' src='".Yii::$app->params['targetDomain'].$data->number_back."' /></a>";
        };
   }


   public function getBankImg(){
        return function($data){
		return "<a href='".Yii::$app->params['targetDomain'].$data->bank_img."' target= _blank><img width='50px' height='50px' src='".Yii::$app->params['targetDomain'].$data->bank_img."' /></a>";
        };
   }





    


}
