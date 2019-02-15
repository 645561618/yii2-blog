<?php
namespace common\models;
use Yii;
use common\components\BackendActiveRecord;
class WxCustomer extends BackendActiveRecord
{
                public static function tablename()
                {
                        return "wx_customer";

                }

}

