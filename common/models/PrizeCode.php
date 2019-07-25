<?php
namespace common\models;
use Yii;
use common\components\BackendActiveRecord;
class PrizeCode extends BackendActiveRecord
{
                public static function tablename()
                {
                        return "prize_code";

                }

}

