<?php

namespace backend\components;

use Yii;
use yii\web\Controller;

class BackendController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \backend\components\filters\AccessControl::className(),
            ],
        ];
    }
}
