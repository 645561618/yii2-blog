<?php
namespace common\extensions\Grid;

use Yii;
use yii\helpers\Html;
use yii\grid\ActionColumn;


class CustomerGridActionColumn extends ActionColumn
{
    protected function initDefaultButtons()
    {

        if (!isset($this->buttons['update-customer'])) {
            $this->buttons['update-customer'] = function ($url, $model) {
                return Html::a(
                    '<span class="glyphicon glyphicon-pencil"></span>', '/customer/update-customer?id=' . $model->id,
                    [
                        'title' => Yii::t('yii', '编辑'),
                    ]
                );
            };
        }

        if (!isset($this->buttons['delete-customer'])) {
            $this->buttons['delete-customer'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', '确定要删除么？?'),
                    'data-method' => 'post',
                ]);
            };
        }




	if (!isset($this->buttons['update-falsecustomer'])) {
            $this->buttons['update-falsecustomer'] = function ($url, $model) {
                return Html::a(
                    '<span class="glyphicon glyphicon-pencil"></span>', '/false-customer/update-falsecustomer?id=' . $model->id,
                    [
                        'title' => Yii::t('yii', '编辑'),
                    ]
                );
            };
        }

        if (!isset($this->buttons['delete-falsecustomer'])) {
            $this->buttons['delete-falsecustomer'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', '确定要删除么？?'),
                    'data-method' => 'post',
                ]);
            };
        }










        parent::initDefaultButtons();
    }
}
