<?php
namespace frontend\models\comment;

use Yii;
use common\models\UserComment;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use common\models\UserReplyComment;
use common\models\UserCenter;

class UserCommentFront extends UserComment{


	public static function getCommentNums($aid)
	{
		return static::find()->where(['status'=>0,'aid'=>$aid])->count();
	}
	
	public static function getCommentData($aid)
	{
		$data = [];
		$result = static::find()->where(['status'=>0,'aid'=>$aid])->orderBy(['create_time'=>SORT_DESC])->all();
		if($result){
			$res = yii\helpers\ArrayHelper::toArray($result,[
		        "frontend\models\comment\UserCommentFront"=>[
		            'id' =>"id",
		            'c_uid' => 'comment_uid',
			    'headimg'=>function($data){
				$Info = UserCenter::find()->where(['id'=>$data->comment_uid])->one();
				if($Info){
					return $Info->headimgurl;
				}
				return '';
			    },
			    'nickname'=>function($data){
				 $Info = UserCenter::find()->where(['id'=>$data->comment_uid])->one();
                                 if($Info->type==2){
                                        return $Info->login;
				 }else{
                                        return $Info->nickname;
				 }
			    },
			    'content'=>'content',
			    'create_time'=>function($data){
			     	return date('Y-m-d H:i:s',$data->create_time);
			    },
			    'reply_list'=>function($data){
				return self::getReplyData($data->id);
			    },
			    'c_nums'=>function($data){
				return UserReplyComment::find()->where(['c_id'=>$data->id,'status'=>0])->count();
			    },
		        ]]);
		    $data = $res;
		}
		return $data;
	}

	public static function getReplyData($c_id)
	{	
		$data = [];
		$result = UserReplyComment::find()->where(['c_id'=>$c_id,'status'=>0])->orderBy(['create_time'=>SORT_DESC])->all();
		if($result){
                        $res = yii\helpers\ArrayHelper::toArray($result,[
                        "common\models\UserReplyComment"=>[
                            'c_uid' => 'comment_uid',
                            'reply_uid' => 'reply_uid',
                            'headimg'=>function($data){
                                $Info = UserCenter::find()->where(['id'=>$data->reply_uid])->one();
                                if($Info){
                                        return $Info->headimgurl;
                                }
                                return '';
                            },  
			    'c_nickname'=>function($data){
                                 $Info = UserCenter::find()->where(['id'=>$data->comment_uid])->one();
                                 if($Info){
                                        return $Info->nickname;
                                 }
                                 return '';
                            },
			    'r_nickname'=>function($data){
                                 $Info = UserCenter::find()->where(['id'=>$data->reply_uid])->one();
                                 if($Info){
                                        return $Info->nickname;
                                 }
                                 return '';
                            },
                            'content'=>'content',
                            'create_time'=>function($data){
                             	return date('Y-m-d H:i:s',$data->create_time);
                            },
                        ]]);
                    $data = $res;
                }
                return $data;
	}

}
?>

