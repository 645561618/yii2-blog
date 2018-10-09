<?php
namespace frontend\models;

use Yii;
use common\models\Order;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class OrderFront extends Order{

	public function behaviors()
	{
        	return [
	            'timestamp' => [
        	        'class' => 'yii\behaviors\TimestampBehavior',
                	'attributes' => [
	                    ActiveRecord::EVENT_BEFORE_INSERT => ['created'],
        	            ActiveRecord::EVENT_BEFORE_UPDATE => ['created'],
	                ],
        	        'value'=>function(){
                	    return date("Y-m-d H:i:s");
	                }
        	    ],
	        ];
       }

    	public function rules()
	{
        	return[
                	[['username','phone','uid','price','commission','url','status'],'safe'],

        	];	
    	}


	public static function HomeList(){
		

	}




}
?>

