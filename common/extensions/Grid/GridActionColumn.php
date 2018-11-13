<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\extensions\Grid;

use Yii;
use yii\helpers\Html;
use yii\grid\ActionColumn;

/**
 * ActionColumn is a column for the [[GridView]] widget that displays buttons for viewing and manipulating the items.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GridActionColumn extends ActionColumn
{
    /**
     * Initializes the default button rendering callbacks
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['label-list'])) {
            $this->buttons['label-list'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'check circle'),
                ]);
            };
        }
        if (!isset($this->buttons['tag'])) {
            $this->buttons['tag'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', "/act/tag?type=".$model->type."&id=".$model->id, [
                    'title' => Yii::t('yii', 'check circle'),
                ]);
            };
        }
        if (!isset($this->buttons['delete-tag'])) {
            $this->buttons['delete-tag'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>',"/act/deltag?type=".$model->type."&id=".$model->id, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this tag?'),
                    'data-method' => 'post',
                ]);
            };
        }
        if (!isset($this->buttons['check-circle'])) {
            $this->buttons['check-circle'] = function ($url, $model) {
                if($model->status == 1){
                    $check = "取消审核";
                }else{
                    $check = "审核";
                }
                return Html::a($check, $url, [
                    'title' => Yii::t('yii', 'check circle'),
                ]);
            };
        }
        if (!isset($this->buttons['tuijian-circle'])) {
            $this->buttons['tuijian-circle'] = function ($url, $model) {
                if($model->tuijian == 1){
                    $tuijian = "取消推荐";
                }else{
                    $tuijian = "推荐";
                }
                return Html::a($tuijian, $url, [
                    'title' => Yii::t('yii', 'tuijian circle'),
                ]);
            };
        }
        if (!isset($this->buttons['delete-video-comment'])) {
            $this->buttons['delete-video-comment'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this comment?'),
                    'data-method' => 'post',
                ]);
            };
        }
        if (!isset($this->buttons['delete-diary'])) {
            $this->buttons['delete-diary'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this diary?'),
                    'data-method' => 'post',
                ]);
            };
        }
         if (!isset($this->buttons['del-diary-comment'])) {
            $this->buttons['del-diary-comment'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this comment?'),
                    'data-method' => 'post',
                ]);
            };
        }
         if (!isset($this->buttons['delete-bell-gpush'])) {
            $this->buttons['delete-bell-gpush'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this push?'),
                    'data-method' => 'post',
                ]);
            };
        }
        if (!isset($this->buttons['update-bell-gpush'])) {
            $this->buttons['update-bell-gpush'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update version info'),
                ]);
            };
        }
        if (!isset($this->buttons['update-recommend'])) {
            $this->buttons['update-recommend'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update version info'),
                ]);
            };
        }
        if (!isset($this->buttons['update-discover'])) {
            $this->buttons['update-discover'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update version info'),
                ]);
            };
        }
        if (!isset($this->buttons['push-hot-diary'])) {
            $this->buttons['push-hot-diary'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'push hot diary subject info'),
                ]);
            };
        }
        if (!isset($this->buttons['delete-prize'])) {
            $this->buttons['delete-prize'] = function ($url, $model) {
                if ($model->status == 0) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        'title' => Yii::t('yii', 'Delete'),
                        'data-confirm' => Yii::t('yii', 'Are you sure to delete this subject?'),
                        'data-method' => 'post',
                    ]);
                } else {
                    return "";
                }
            };
        }
        if (!isset($this->buttons['delete-subject'])) {
            $this->buttons['delete-subject'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this subject?'),
                    'data-method' => 'post',
                ]);
            };
        }
        if (!isset($this->buttons['subject'])) {
            $this->buttons['subject'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update subject info'),
                ]);
            };
        }
        if (!isset($this->buttons['update-credit'])) {
            $this->buttons['update-credit'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update version info'),
                ]);
            };
        }
        if (!isset($this->buttons['update-version'])) {
            $this->buttons['update-version'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update version info'),
                ]);
            };
        }
        if (!isset($this->buttons['update-recommend'])) {
            $this->buttons['update-recommend'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update recommend info'),
                ]);
            };
        }
        if (!isset($this->buttons['update-treatment'])) {
            $this->buttons['update-treatment'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update treatment info'),
                ]);
            };
        }
        if (!isset($this->buttons['update-assay'])) {
            $this->buttons['update-assay'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update assay info'),
                ]);
            };
        }
        if (!isset($this->buttons['update-symptom'])) {
            $this->buttons['update-symptom'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update symptom info'),
                ]);
            };
        }
        if (!isset($this->buttons['update-disease'])) {
            $this->buttons['update-disease'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update disease info'),
                ]);
            };
        }
        if (!isset($this->buttons['update-relation'])) {
            $this->buttons['update-relation'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update friend link'),
                ]);
            };
        }
        if (!isset($this->buttons['delete-relation'])) {
            $this->buttons['delete-relation'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this relation?'),
                    'data-method' => 'post',
                ]);
            };
        }
        if (!isset($this->buttons['delete-recommend'])) {
            $this->buttons['delete-recommend'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this relation?'),
                    'data-method' => 'post',
                ]);
            };
        }
        if (!isset($this->buttons['updatekey'])) {
            $this->buttons['updatekey'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update friend link'),
                ]);
            };
        }
        if (!isset($this->buttons['deletekey'])) {
            $this->buttons['deletekey'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?'),
                    'data-method' => 'post',
                ]);
            };
        }
        if (!isset($this->buttons['delete-link'])) {
            $this->buttons['delete-link'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?'),
                    'data-method' => 'post',
                ]);
            };
        }
        if (!isset($this->buttons['update-link'])) {
            $this->buttons['update-link'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update friend link'),
                ]);
            };
        }
        if (!isset($this->buttons['addlink'])) {
            $this->buttons['addlink'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, [
                    'title' => Yii::t('yii', 'add goods attribute'),
                ]);
            };
        }
        if (!isset($this->buttons['updatelinkblock'])) {
            $this->buttons['updatelinkblock'] = function ($url, $model) {
                if (\Yii::$app->user->id != 1) {
                    return '';
                }

                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update link block name'),
                ]);
            };
        }
        if (!isset($this->buttons['assignment'])) {
            $this->buttons['assignment'] = function ($url, $model) {
                return Html::a('<button class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-cog"></i>&nbsp;分配角色</button>', "/auth/assignment?user_id=$model->id", [
                    'title' => Yii::t('yii', 'assignment roles to user'),
                ]);
            };
        }

        if (!isset($this->buttons['assign'])) {
            $this->buttons['assign'] = function ($url, $model) {
                return Html::a('<button class="btn btn-warning btn-sm"><i class="glyphicon glyphicon-cog"></i>&nbsp;分配权限</button>', $url, [
                    'title' => Yii::t('yii', 'assign user and roles permission'),
                ]);
            };
        }
        if (!isset($this->buttons['show'])) {
            $this->buttons['show'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-cog"></span>', $url, [
                    'title' => Yii::t('yii', 'show goods status'),
                ]);
            };
        }

        if (!isset($this->buttons['updatest'])) {
            $this->buttons['updatest'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update goods status'),
                ]);
            };
        }
        if (!isset($this->buttons['addmore'])) {
            $this->buttons['addmore'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, [
                    'title' => Yii::t('yii', 'add goods attribute'),
                ]);
            };
        }
        if (!isset($this->buttons['adetail'])) {
            $this->buttons['adetail'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, [
                    'title' => Yii::t('yii', 'add label ad details'),
                ]);
            };
        }
        if (!isset($this->buttons['updetail'])) {
            $this->buttons['updetail'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-edit"></span>', $url, [
                    'title' => Yii::t('yii', 'add label ad details'),
                ]);
            };
        }
        if (!isset($this->buttons['deldetail'])) {
            $this->buttons['deldetail'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?'),
                    'data-method' => 'post',
                ]);
            };
        }



        /* 犬种详情更新 */
        if (!isset($this->buttons['update-species'])) {
            $this->buttons['update-species'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update version info'),
                ]);
            };
        }
        /* 添加各种喜爱程度 */
        if (!isset($this->buttons['add-degree'])) {
            $this->buttons['add-degree'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-plus"></span>', '/petschool/add-degree?id=' . $model->spe_id, [
                    'title' => Yii::t('yii', 'add degree attribute'),
                ]);
            };
        }
        /* 添加详情介绍 */
        if (!isset($this->buttons['add-info'])) {
            $this->buttons['add-info'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-plus"></span>', '/petschool/add-info?id=' . $model->spe_id, [
                    'title' => Yii::t('yii', 'add info attribute'),
                ]);
            };
        }
        /* 犬种基本信息删除 */
        if (!isset($this->buttons['delete-species'])) {
            $this->buttons['delete-species'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', '/petschool/delete-species?id=' . $model->spe_id, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this relation?'),
                    'data-method' => 'post',
                ]);
            };
        }

        /* 犬种基本信息喜爱程度删除 */
        if (!isset($this->buttons['delete-degree'])) {
            $this->buttons['delete-degree'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', '/petschool/delete-degree?id=' . $model->spe_id, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this relation?'),
                    'data-method' => 'post',
                ]);
            };
        }
        /* 各种喜爱程度更新 */
        if (!isset($this->buttons['update-degree'])) {
            $this->buttons['update-degree'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update version info'),
                ]);
            };
        }
        /* 犬种基本信息详情程度删除 */
        if (!isset($this->buttons['delete-info'])) {
            $this->buttons['delete-info'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'petschool/delete-info?id=' . $model->spe_id, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this relation?'),
                    'data-method' => 'post',
                ]);
            };
        }
        /* 基本信息详情更新 */
        if (!isset($this->buttons['update-info'])) {
            $this->buttons['update-info'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update version info'),
                ]);
            };
        }

        /* 宠物学院首页焦点图删除 */
        if (!isset($this->buttons['delete-focus'])) {
            $this->buttons['delete-focus'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this relation?'),
                    'data-method' => 'post',
                ]);
            };
        }
        /* 宠物学院首页焦点图更新 */
        if (!isset($this->buttons['update-focus'])) {
            $this->buttons['update-focus'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update version info'),
                ]);
            };
        }
        /* 添加犬种基本信息介绍 */
        if (!isset($this->buttons['add-main'])) {
            $this->buttons['add-main'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, [
                    'title' => Yii::t('yii', 'add info attribute'),
                ]);
            };
        }

        /* 宠物友情链接删除 */
        if (!isset($this->buttons['delete-friendlink'])) {
            $this->buttons['delete-friendlink'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this relation?'),
                    'data-method' => 'post',
                ]);
            };
        }
        /* 宠物友情链接删除更新 */
        if (!isset($this->buttons['update-friendlink'])) {
            $this->buttons['update-friendlink'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update version info'),
                ]);
            };
        }

        /* 相关文章删除 */
        if (!isset($this->buttons['delete-article'])) {
            $this->buttons['delete-article'] = function ($url, $model) {
                return Html::a('<button class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i>&nbsp;删除</button>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', '您确定要删除此文章么?'),
                    'data-method' => 'post',
                ]);
            };
        }
        /* 相关文章更新 */
        if (!isset($this->buttons['update-article'])) {
            $this->buttons['update-article'] = function ($url, $model) {
                return Html::a('<button class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-pencil"></i>&nbsp;修改</button>', $url, [
                    'title' => Yii::t('yii', 'update version info'),
		    'style'=>'margin:0 10px 0 10px',
                ]);
            };
        }

        /* 宠物学院tdk删除 */
        if (!isset($this->buttons['delete-tdk'])) {
            $this->buttons['delete-tdk'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this relation?'),
                    'data-method' => 'post',
                ]);
            };
        }
        /* 宠物学院tdk更新 */
        if (!isset($this->buttons['update-tdk'])) {
            $this->buttons['update-tdk'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update version info'),
                ]);
            };
        }

        /* 宠物学院关联链接删除 */
        if (!isset($this->buttons['delete-relatedlink'])) {
            $this->buttons['delete-relatedlink'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this relation?'),
                    'data-method' => 'post',
                ]);
            };
        }
        /* 宠物学院关联链接更新 */
        if (!isset($this->buttons['update-relatedlink'])) {
            $this->buttons['update-relatedlink'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update version info'),
                ]);
            };
        }

        /* 圣诞抽奖奖品更新 */
        if (!isset($this->buttons['update-lottery'])) {
            $this->buttons['update-lottery'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update version info'),
                ]);
            };
        }

        /* 宠拖邦抽奖奖品更新 */
        if (!isset($this->buttons['update-prize'])) {
            $this->buttons['update-prize'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update version info'),
                ]);
            };
        }

        /**
         * 微信故事活动删除
         */
        /* 宠拖邦抽奖奖品更新 */
        if (!isset($this->buttons['delete-story'])) {
            $this->buttons['delete-story'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this relation?'),
                    'data-method' => 'post',
                ]);
            };
        }

        /**
         * 微信活动删除
         */
        if (!isset($this->buttons['delete-list'])) {
            $this->buttons['delete-list'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this relation?'),
                    'data-method' => 'post',
                ]);
            };
        }

        /* 公司其他成本更新 */
        if (!isset($this->buttons['update-cost'])) {
            $this->buttons['update-cost'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update cost info'),
                ]);
            };
        }

        /* 公司成本更新 */
        if (!isset($this->buttons['update-base'])) {
            $this->buttons['update-base'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update base info'),
                ]);
            };
        }

        if (!isset($this->buttons['add'])) {
        	$this->buttons['add'] = function ($url, $model, $key) {
        		$options = array_merge([
        				'title' => Yii::t('yii', 'Add'),
        				'aria-label' => Yii::t('yii', 'Add'),
        				'data-method' => 'post',
        				'data-pjax' => '0',
        		], $this->buttonOptions);
        		return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, $options);
        	};
        }

        if (!isset($this->buttons['update-config'])) {
            $this->buttons['update-config'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', 'update config info'),
                ]);
            };
        }

        if (!isset($this->buttons['del-config'])) {
            $this->buttons['del-config'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this config?'),
                    'data-method' => 'post',
                ]);
            };
        }

        if (!isset($this->buttons['del-post-comment'])) {
            $this->buttons['del-post-comment'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this comment?'),
                    'data-method' => 'post',
                ]);
            };
        }
        /* 达人墙 */
        if (!isset($this->buttons['update-welluser-category'])) {
            $this->buttons['update-welluser-category'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', '更新分类'),
                ]);
            };
        }
        if (!isset($this->buttons['del-category'])) {
            $this->buttons['del-category'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', '删除此分类，该分类下的达人将全部清除?'),
                    'data-method' => 'post',
                ]);
            };
        }
        if (!isset($this->buttons['add-welluser'])) {
            $this->buttons['add-welluser'] = function ($url, $model) {
                return Html::a('<span class="glyphicon">添加达人</span>', $url, [
                    'title' => Yii::t('yii', '添加达人'),
                ]);
            };
        }
        if (!isset($this->buttons['update-welluser'])) {
            $this->buttons['update-welluser'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', '更新达人'),
                ]);
            };
        }
        if (!isset($this->buttons['del-welluser'])) {
            $this->buttons['del-welluser'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', '删除达人?'),
                    'data-method' => 'post',
                ]);
            };
        }
        if (!isset($this->buttons['add-welluser-content'])) {
            $this->buttons['add-welluser-content'] = function ($url, $model) {
                return Html::a('<span class="glyphicon">添加内容</span>', $url, [
                    'title' => Yii::t('yii', '添加内容'),
                ]);
            };
        }
        if (!isset($this->buttons['update-welluser-hot'])) {
            $this->buttons['update-welluser-hot'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', '更新推荐'),
                ]);
            };
        }
        if (!isset($this->buttons['del-hot'])) {
            $this->buttons['del-hot'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', '删除推荐达人?'),
                    'data-method' => 'post',
                ]);
            };
        }

        if (!isset($this->buttons['del-image-settings'])) {
            $this->buttons['del-image-settings'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this comment?'),
                    'data-method' => 'post',
                ]);
            };
        }

        if (!isset($this->buttons['update-special'])) {
            $this->buttons['update-special'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', '更新专题'),
                ]);
            };
        }
        if (!isset($this->buttons['delete-special'])) {
            $this->buttons['delete-special'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this special?'),
                    'data-method' => 'post',
                ]);
            };
        }
        if (!isset($this->buttons['update-content'])) {
            $this->buttons['update-content'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', '更新专题内容'),
                ]);
            };
        }
        if (!isset($this->buttons['delete-content'])) {
            $this->buttons['delete-content'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this content?'),
                    'data-method' => 'post',
                ]);
            };
        }
        if (!isset($this->buttons['top-goods-update'])) {
            $this->buttons['top-goods-update'] = function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                    'title' => Yii::t('yii', '更新置顶商品'),
                ]);
            };
        }
        if (!isset($this->buttons['binding'])) {
            $this->buttons['binding'] = function ($url, $model) {
                return Html::a('<span class="glyphicon">绑定商品分类</span>', $url, [
                    'title' => Yii::t('yii', '绑定商品分类'),
                ]);
            };
        }
        if (!isset($this->buttons['focus'])) {
            $this->buttons['focus'] = function ($url, $model) {
                return Html::a(
                    '<span class="glyphicon">添加轮播图</span>',
                    '/cate-focus/create?id=' . $model->id,
                    ['title' => Yii::t('yii', '添加轮播图'),]
                );
            };
        }
        if (!isset($this->buttons['custom'])) {
            $this->buttons['custom'] = function ($url, $model) {
                return Html::a('<span class="glyphicon">自定义模块</span>', '/custom/create?id=' . $model->id, [
                    'title' => Yii::t('yii', '自定义模块'),
                ]);
            };
        }
        if (!isset($this->buttons['advert'])) {
            $this->buttons['advert'] = function ($url, $model) {
                return Html::a('<span class="glyphicon">添加广告</span>', '/advertising/create?id=' . $model->id, [
                    'title' => Yii::t('yii', '添加广告'),
                ]);
            };
        }
        if (!isset($this->buttons['scene'])) {
            $this->buttons['scene'] = function ($url, $model) {
                return Html::a('<span class="glyphicon">添加场景</span>', '/shop-scene/create?id=' . $model->id, [
                    'title' => Yii::t('yii', '添加场景'),
                ]);
            };
        }
        if (!isset($this->buttons['updatead'])) {
            $this->buttons['updatead'] = function ($url, $model) {
                return Html::a('<span class="glyphicon">修改</span>', $url, [
                    'title' => Yii::t('yii', '修改'),
                ]);
            };
        }
        if (!isset($this->buttons['addad'])) {
            $this->buttons['addad'] = function ($url, $model) {
                return Html::a('<span class="glyphicon">添加</span>','../custom-ad/create?id=' . $model->id, [
                    'title' => Yii::t('yii', '添加'),
                ]);
            };
        }
        if (!isset($this->buttons['updatecustomad'])) {
            $this->buttons['updatecustomad'] = function ($url, $model) {
                return Html::a('<span class="glyphicon">修改</span>',$url, [
                    'title' => Yii::t('yii', '修改'),
                ]);
            };
        }

        if (!isset($this->buttons['update-top'])) {
            $this->buttons['update-top'] = function ($url, $model) {
                return Html::a('<span class="glyphicon">编辑</span>',$url, [
                    'title' => Yii::t('yii', '编辑'),
                ]);
            };
        }

        if (!isset($this->buttons['update-scene'])) {
            $this->buttons['update-scene'] = function ($url, $model) {
                if($model->status == 1 && $model->end_date > date("Y-m-d H:i:s")){
                    return Html::a('<span class="glyphicon">编辑</span>',$url, [
                        'title' => Yii::t('yii', '编辑'),
                    ]);
                }
            };
        }

        if (!isset($this->buttons['add-goods'])) {
            $this->buttons['add-goods'] = function ($url, $model) {
                if($model->status == 1 && $model->end_date > date("Y-m-d H:i:s")){
                    return Html::a('<span class="glyphicon">添加商品</span>','/purchase/add-goods?id=' . $model->id, [
                        'title' => Yii::t('yii', '添加商品'),
                    ]);
                }
            };
        }

        if (!isset($this->buttons['update-goods'])) {
            $this->buttons['update-goods'] = function ($url, $model) {
                return Html::a('<span class="glyphicon">编辑</span>',$url, [
                    'title' => Yii::t('yii', '编辑'),
                ]);
            };
        }

        if (!isset($this->buttons['stop-scene'])) {
            $this->buttons['stop-scene'] = function ($url, $model) {
                if($model->status == 1 && $model->start_date <= date("Y-m-d H:i:s") && $model->end_date > date("Y-m-d H:i:s")){
                    return Html::a('<span class="glyphicon">终止</span>',$url, [
                        'title' => Yii::t('yii', '终止'),
                    ]);
                }
            };
        }

	if (!isset($this->buttons['update-reply'])) {
            $this->buttons['update-reply'] = function ($url, $model) {
                return Html::a('<button class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-pencil"></i>&nbsp;修改</button>', $url, [
                    'title' => Yii::t('yii', '修改'),
		    'style'=>'margin:0 10px 0 10px',
                ]);
            };
        }

	if (!isset($this->buttons['delete-reply'])) {
            $this->buttons['delete-reply'] = function ($url, $model) {
                return Html::a('<button class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i>&nbsp;删除</button>',"/wechat/delete-reply?id=".$model->id, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure to delete this wechat?'),
                    'data-method' => 'post',
                ]);
            };
        }


        if (!isset($this->buttons['delete-apply'])) {
            $this->buttons['delete-apply'] = function ($url, $model) {
                return Html::a('<button class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i>&nbsp;删除</button>',"/apply/delete-apply?id=".$model->id, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', '你确定要删除?'),
                    'data-method' => 'post',
                ]);
            };
        }


	if (!isset($this->buttons['delete-class'])) { 
            $this->buttons['delete-class'] = function ($url, $model) {
                return Html::a('<button class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i>&nbsp;删除</button>', "/wechat/delete-class?id=".$model->id, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', '你确定要删除?'),
                    'data-method' => 'post',
                ]);
            };
        }


        if (!isset($this->buttons['update-class'])) {
            $this->buttons['update-class'] = function ($url, $model) {
                return Html::a('<button class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-pencil"></i>&nbsp;修改</button>', "/wechat/update-class?id=".$model->id, [
                    'title' => Yii::t('yii', '修改'),
                    'style'=>'margin:0 10px 0 10px',
                ]);
            };
        }

	if (!isset($this->buttons['view-menu'])) {
            $this->buttons['view-menu'] = function ($url, $model) {
                return Html::a('<button class="btn btn-info btn-sm"><i class="glyphicon glyphicon-eye-open"></i>&nbsp;查看二级菜单</button>', "/wechat/view-menu?id=".$model->id, [
                    'title' => Yii::t('yii', '用户详情'),
                ]);
            };
        }


        if (!isset($this->buttons['update-click'])) {
            $this->buttons['update-click'] = function ($url, $model) {
                return Html::a('<button class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-pencil"></i>&nbsp;修改</button>', "/wechat/update-click?id=".$model->id."&cid=".$model->cid, [
                    'title' => Yii::t('yii', '修改'),
                    'style'=>'margin:0 10px 0 10px',
                ]);
            };
        }


        if (!isset($this->buttons['delete-click'])) {
            $this->buttons['delete-click'] = function ($url, $model) {
                return Html::a('<button class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i>&nbsp;删除</button>',"/wechat/delete-click?id=".$model->id, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', '你确定要删除?'),
                    'data-method' => 'post',
                ]);
            };
        }


        if (!isset($this->buttons['update-custom'])) {
            $this->buttons['update-custom'] = function ($url, $model) {
                return Html::a('<button class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-pencil"></i>&nbsp;修改</button>', "/wechat/update-custom?id=".$model->id."&fid=".$model->fid, [
                    'title' => Yii::t('yii', '修改'),
                    'style'=>'margin:0 10px 0 10px',
                ]);
            };
        }


        if (!isset($this->buttons['update-category'])) {
            $this->buttons['update-category'] = function ($url, $model) {
                return Html::a('<button class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-pencil"></i>&nbsp;修改</button>', "/blog/update-category?id=".$model->id, [
                    'title' => Yii::t('yii', '修改'),
                    'style'=>'margin:0 10px 0 10px',
                ]);
            };
        }

	if (!isset($this->buttons['update-label'])) {
            $this->buttons['update-label'] = function ($url, $model) {
                return Html::a('<button class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-pencil"></i>&nbsp;修改</button>', "/blog/update-label?id=".$model->id, [
                    'title' => Yii::t('yii', '修改'),
                    'style'=>'margin:0 10px 0 10px',
                ]);
            };
        }


	if (!isset($this->buttons['update-links'])) {
            $this->buttons['update-links'] = function ($url, $model) {
                return Html::a('<button class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-pencil"></i>&nbsp;修改</button>', "/blog/update-links?id=".$model->id, [
                    'title' => Yii::t('yii', '修改'),
                    'style'=>'margin:0 10px 0 10px',
                ]);
            };
        }

	if (!isset($this->buttons['delete-links'])) { 
            $this->buttons['delete-links'] = function ($url, $model) {
                return Html::a('<button class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i>&nbsp;删除</button>',"/blog/delete-links?id=".$model->id, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', '你确定要删除?'),
                    'data-method' => 'post',
                    'style'=>'margin:0 10px 0 10px',
                ]);
            };
        }




	 if (!isset($this->buttons['delete-manager'])) { 
            $this->buttons['delete-manager'] = function ($url, $model) {
                return Html::a('<button class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i>&nbsp;删除</button>',"/manager/delete-manager?id=".$model->id, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', '你确定要删除?'),
                    'data-method' => 'post',
                    'style'=>'margin:0 10px 0 10px',
                ]);
            };
        }


        if (!isset($this->buttons['update-manager'])) {
            $this->buttons['update-manager'] = function ($url, $model) {
                return Html::a('<button class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-pencil"></i>&nbsp;修改</button>', "/manager/update-manager?id=".$model->id, [
                    'title' => Yii::t('yii', '修改'),
                    'style'=>'margin:0 10px 0 10px',
                ]);
            };
        }


	if (!isset($this->buttons['delete-role'])) {
            $this->buttons['delete-role'] = function ($url, $model) {
                return Html::a('<button class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i>&nbsp;删除</button>',$url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', '你确定要删除?'),
                    'data-method' => 'post',
                    'style'=>'margin:0 10px 0 10px',
                ]);
            };
        }


        if (!isset($this->buttons['update-role'])) {
            $this->buttons['update-role'] = function ($url, $model) {
                return Html::a('<button class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-pencil"></i>&nbsp;修改</button>', $url, [
                    'title' => Yii::t('yii', '修改'),
                    'style'=>'margin:0 10px 0 10px',
                ]);
            };
        }

	if (!isset($this->buttons['update-safe'])) {
            $this->buttons['update-safe'] = function ($url, $model) {
                return Html::a('<button class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-pencil"></i>&nbsp;修改</button>', $url, [
                    'title' => Yii::t('yii', '修改'),
                    'style'=>'margin:0 10px 0 10px',
                ]);
            };
        }


	

        parent::initDefaultButtons();

    }
}

