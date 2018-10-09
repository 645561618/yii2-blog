<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\helpers\ArrayHelper;
use backend\components\BackendController;


class HomeController extends BackendController
{
    public function actionIndex()
    {
        return $this->render("index");
    }

}
