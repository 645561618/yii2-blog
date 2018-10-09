<?php
namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\models\keyword\Keywords;

class KeywordsBack extends Keywords
{

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created','modified'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['modified'],
                ],
                'value'=>function(){return date("Y-m-d H:i:s");},
            ],
        ];
    }

    public function rules(){
        return [
            [['type','words','reply'],'required'],
	    [['sort'], 'safe'],
        ];
    }

    public function attributeLabels(){
        return [
            'sort'=>'排序',
            'words'=>'关键字',
            'type'=>'类型',
            'reply'=>'回复内容',
            'created'=>'创建时间',
        ];
    }

    /**
     * 显示类型
     */
    public function showType()
    {
        return function($data){
            if($data->type == '1'){
                return "<font color='green'>文本</font>";
            }else{
                return "<a href='/foster/addtype?id=$data->id&type=1'>图文</a>";
            }
        };
    }


    //排序
    public function showSort()
    {
        return function($data){
                return "<input name='".$data->id."_sort' class='input-text' id='".$data->id."_sort' type='text' size='3' value='$data->sort'/>";
        };
    }



    /**
     * type下拉
     * @return array
     */
    public function getDataType()
    {
        return [
            "1"=>"文本",
            "2"=>"图文",
            // "view"=>"链接",
        ];
    }


}
