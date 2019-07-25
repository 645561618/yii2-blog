<?php
namespace common\extensions\Grid;

use Yii;
use yii\helpers\Html;
use yii\grid\ActionColumn;

class ManagerGridActionColumn extends ActionColumn
{
    protected function initDefaultButtons()
    {

        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model) {
                return Html::a(
                    '<span class="glyphicon glyphicon-pencil"></span>', '/manager/update?id=' . $model->id,
                    [
                        'title' => Yii::t('yii', '编辑'),
                    ]
                );
            };
        }

        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model) {
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

