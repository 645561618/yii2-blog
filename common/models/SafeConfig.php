<?php
namespace common\models;
use Yii;
use common\components\BackendActiveRecord;
class SafeConfig extends BackendActiveRecord
{
                public static function tablename()
                {
                        return "safe_config";

                }

}

