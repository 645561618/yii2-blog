<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\DataTotal;
class DataTotalBack extends DataTotal{


    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['created_time'],
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
	       [['UserANum','BlogANum','FansANum','LinkNum'],'safe'],	
        ];
    }





}
