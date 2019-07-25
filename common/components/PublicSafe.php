<?php 
namespace common\components;
use Yii;
use common\models\SafeRecord;
use backend\models\SafeConfigBack;

class PublicSafe{
	protected $channel = [];
	public function __construct(){
		$Typedata =[];
		$total = 2;
		for ($type=1; $type <=$total ; $type++) {
			$result = Yii::$app->cache->get("action_config".$type);
			if(!$result){
				$data = SafeConfigBack::find()->where(['type'=>$type])->one();
				if ($data) {
					$ip = $data->ip;
					$nums = $data->nums;
					$percent = $data->percent;
					$length = $data->length;
					$words = $data->words;
					$Typedata = [
						'ip' => $ip,
						'nums' => $nums,
						'percent' => $percent,
						'length' => $length,
						'words' => $words,
					];
					$this->channel[$type] = $Typedata;
					Yii::$app->cache->set("action_config".$type,$ip);
					Yii::$app->cache->set("action_nums".$type,$nums);
					Yii::$app->cache->set("action_percent".$type,$percent);
					Yii::$app->cache->set("action_length".$type,$length);
					Yii::$app->cache->set("action_words".$type,$words);
				}
			}else{
				$ip = Yii::$app->cache->get("action_config".$type);
				$nums = Yii::$app->cache->get("action_nums".$type);
				$percent = Yii::$app->cache->get("action_percent".$type);
				$length = Yii::$app->cache->get("action_length".$type);
				$words = Yii::$app->cache->get("action_words".$type);
				$Typedata = [
					'ip' => $ip,
					'nums' => $nums,
					'percent' => $percent,
					'length' => $length,
					'words' => $words,
				];
				$this->channel[$type] = $Typedata;
			}
		}
	}

	public function auth($userip,$type,$message=""){
        	$i =1;
	    	$j = 10;
        	$end_time = time() - $i * 60;
		//禁止ip
    		if(!empty($this->channel)){
    			if(Yii::$app->cache->get("action_config".$type)){
	    			$configdata = $this->channel[$type];
		    		$ip = $configdata['ip'];
			        $uid_data = explode(",",$ip);
			        foreach ($uid_data as $k => $v) {
			    		if($userip==$v){
			    			return 10002;
				    	}
			        }
    			}
    		}
		//一分钟次数限制
		$nums = SafeRecord::find()->where(['ip'=>$userip,'type'=>(int)$type])->andWhere("time >=$end_time")->count();
		$cnums = Yii::$app->cache->get("action_nums".$type);
		if(!$cnums){
			$limitresult = SafeConfigBack::find()->where(['type'=>$type])->one();
			if ($limitresult) {
				$cnums = $limitresult->nums;
				Yii::$app->cache->set("action_nums".$type,$cnums);
			}
		}
		if($cnums!==0 && !empty($cnums)){
	    		if($nums>=$cnums){
	    			return 10003;
	    		}
		}
		//文字长度限制以及十分钟文本相似度
    		$Time = time() - $j * 60;
	    	if($message!==""){
    			$length = strlen($message);
			//文字长度限制
	    		$clength = Yii::$app->cache->get("action_length".$type);
    			if(!$clength){
	    			$limitresult = SafeConfigBack::find()->where(['type'=>$type])->one();
	    			if($limitresult){
	    				$clength = $limitresult->length;
		    			Yii::$app->cache->set("action_length".$type,$clength);
		    		}
    			}
	    		if($clength!==0 && !empty($clength)){
		    		if($length>$clength){
	    				return 10004;
	    			}
    			}

			//关键词锁定
			$wordsData = SafeConfigBack::find()->where(['type'=>$type])->andWhere("words like :lock_words")->addParams([':lock_words'=>'%'.$message.'%'])->one();
			if($wordsData){
				return 10005;
			}

			//文本相似度
	    		$cpercent = Yii::$app->cache->get("action_percent".$type);
    			if(!$cpercent){
					$limitresult = SafeConfigBack::find()->where(['type'=>$type])->one();
					if ($limitresult) {
						$cpercent = $limitresult->percent;
						Yii::$app->cache->set("action_percent".$type,$cpercent);
					}
				}
	    		$data_message = SafeRecord::find()->where(['ip'=>$userip,'type'=>(int)$type])->andWhere("time>=$Time")->orderBy(['time'=>SORT_DESC])->one();
    			if($data_message){
		    		$next_message = $data_message->message;
			    	similar_text($message,$next_message,$percent);
			    	if($cpercent!==0 && !empty($cpercent)){
				    	if($percent>=$cpercent){
				    		return 10006;
				    	}
			    	}
    			}
    		}

		$model = new SafeRecord;
		$model->ip = $userip;
    		$model->type = (int)$type;
	    	$model->time = time();
    		$model->message = $message;
	    	$model->save();

	}
}

 ?>

