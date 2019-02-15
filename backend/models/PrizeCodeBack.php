<?php 
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\PrizeCode;
class PrizeCodeBack extends PrizeCode{


    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['modify'],
                ],
                'value'=>function(){
                    return time();
                }
            ],
        ];
    }



    public function rules()
    {
        return [
            [['code'], 'required'],
            [['username','phone','status'], 'safe'],
        ];
    }

    public function attributelabels()
    {
    	return[
            'code' => '抽奖码',
            'username' => '姓名',
            'phone' => '手机号',
            'created' => '创建时间',
	    'status'  => '抽奖状态',
	    'prize'   => '奖品',
    	];
    }



}



 ?>
