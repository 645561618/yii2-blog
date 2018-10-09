<?php
namespace common\models\keyword;
use Yii;
use common\components\BackendActiveRecord;
class Keywords extends BackendActiveRecord
{
        public static function tablename()
        {
                return "keywords";
        }
}

