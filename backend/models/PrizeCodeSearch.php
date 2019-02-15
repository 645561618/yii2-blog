<?php 
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\PrizeCode;
use backend\models\PrizeCodeBack;
class PrizeCodeSearch extends PrizeCodeBack{



    public function rules()
    {
        return [
            [['code','username','phone','status'], 'safe'],
        ];
    }


    public function search($params)
    {
        $query = static::find()->orderBy('created DESC');
        $dataProvider = new ActiveDataProvider([
            'query' =>$query,
            'sort'=>false,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere(['code'=>$this->code]);
        $query->andFilterWhere(['like','username',$this->username]);
        $query->andFilterWhere(['like','phone',$this->phone]);
        $query->andFilterWhere(['status'=>$this->status]);
        return $dataProvider;
    }

   public function getTime(){
        return function($data){
                return date('Y-m-d H:i:s',$data->created);
        };
    }

        //状态
    public function getStatus(){
        return function($data){
                if($data->status==0){
                        return "未使用";
                }elseif($data->status == 1){
                        return "已抽奖,待领取";
                //}elseif($data->status == 2){
                //        return "已领取,待发放";
                }elseif($data->status == 3){
                        return "已发放";
                }
        };
    }

    public function getStatusValues(){
        return [
                '0'=>'未使用',
                '1'=>'已抽奖,待领取',
                //'2'=>'已领取,待发放',
                '3'=>'已发放',
        ];
    }

    public function getGrant()
    {
	return function($data){
		if($data->status==1 || $data->status==2){
			return "<a href='/prize/grant?id=$data->id' class='btn btn-danger btn-sm'>发放奖品</a>";
		}else{
			return "";
		}
	};

    }




}



 ?>
