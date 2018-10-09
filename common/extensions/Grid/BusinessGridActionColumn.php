<?php
namespace common\extensions\Grid;

use Yii;
use yii\helpers\Html;
use yii\grid\ActionColumn;


class BusinessGridActionColumn extends ActionColumn
{
    protected function initDefaultButtons()
    {

        if (!isset($this->buttons['update-business'])) {
            $this->buttons['update-business'] = function ($url, $model) {
                return Html::a(
                    '<span class="glyphicon glyphicon-pencil"></span>', '/business/update-business?id=' . $model->id,
                    [
                        'title' => Yii::t('yii', '编辑'),
                    ]
                );
            };
        }

        if (!isset($this->buttons['delete-business'])) {
            $this->buttons['delete-business'] = function ($url, $model) {
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

