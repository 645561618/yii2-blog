<?php
namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\components\BackendActiveRecord;

class CustomClick extends BackendActiveRecord
{

   public static function tablename()
   {
                return "custom_click";
   }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created'],
                ],
                'value'=>function(){return date("Y-m-d H:i:s");},
            ],
        ];
    }

    public function rules(){
        return [
            [['title','description','picurl','url'],'required'],
        ];
    }

    public function attributeLabels(){
        return [
            'cid'=>'分类名称',
            'title'=>'文章标题',
            'description'=>'文章描述',
            'picurl'=>'图片地址',
            'url'=>'文章链接',
            'created'=>'创建时间',
        ];
    }

    /**
     * 显示类型
     */
    public function showType()
    {
        return function($data){
            if($data->type == 'click'){
                return "<a href='/keywords/addtype?id=$data->id'>click</a>";
            }else{
                return "<font color='green'>view</font>";
            }
        };
    }



}
