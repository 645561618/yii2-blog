<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\TotalBalance;
class TotalBalanceBack extends TotalBalance{

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['created'],
                ],
                'value'=>function(){
                    return date("Y-m-d H:i:s");
                }
            ],
        ];
    }

}

