<?php
namespace api\models;

use Yii;
use common\models\Order;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;


class OrderFront extends Order
{

	const STATUS_SUCCESS = 10000;
	const NO_MORE_DATA = 11112;

	public static function HomeList($post){
		$data =[];
		$page = empty($post['page'])?1:$post['page'];
		$count = empty($post['count'])?20:$post['count'];
	        $offset = ($page-1)*$count;
		$results = Order::find()
			->orderBy(['created'=>SORT_DESC])
			->limit($count)
			->offset($offset)
			->all();
		if($results){
			$OrderData = yii\helpers\ArrayHelper::toArray($results,[
			        "common\models\Order"=>[
			            'order_id' =>"id",
			            'username' => 'username',
				    'phone' =>'phone',
				    'commission'=> 'commission',
				    'created' => 'created',
			]]);
		    $data = $OrderData;
		}
		if ($data) {
		    return [self::STATUS_SUCCESS, $data];
		}
		return [self::NO_MORE_DATA, []];

	}


}
?>

