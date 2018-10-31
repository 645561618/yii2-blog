<?php
#***********************************************
#
#      Filename: HomeController.php
#
#        Author:hx.qiang@qq.com
#   Description: ---
#        Create: 2018-01-17 17:02:00
#***********************************************

namespace frontend\controllers;

use Yii;
use yii\base\ErrorException;
use yii\web\Controller;
use frontend\components\FrontendController;
use frontend\components\QQClient;
use common\models\Label;
use common\models\Category;
use common\models\Article;
use frontend\models\comment\UserCommentFront;
use common\models\UserReplyComment;
use common\models\UserCenter;
use common\models\Common;
use common\components\Email;
/**
 *  * Home controller
 */
class HomeController extends FrontendController
{



	public $enableCsrfValidation = false;
	public $defaultAction = 'index';

				
	public function actionIndex(){
		if(isset($_GET['plat']) && isset($_GET['code']) && isset($_GET['state'])){
			if(trim($_GET['state']) == Yii::$app->session['state']){
				$plat= base64_decode($_GET['plat']);
				$receive_params = $_GET;
        			unset($receive_params['plat']);
				$result = Yii::$app->platform->receiveCallback($plat, $receive_params);
        			if($result){
					$session = Yii::$app->session;			
					$session['UserLogin'] = $result;
			        	return $this->redirect("/");
        			}else{
			        	return $this->redirect("/");
				}
			}
		}
		return $this->render('index');
	}

	//github登录
	public function actionGithubLogin(){
		$url  = Yii::$app->platform->getPlatformLoginUrl("github");
                return $this->redirect($url);
	}

	//github回调
	public function actionCallback()
	{
		if(trim($_GET['state']) == Yii::$app->session['g_state']){
			$plat= base64_decode($_GET['plat']);
			$receive_params = $_GET;
			unset($receive_params['plat']);
			$result = Yii::$app->platform->receiveCallback($plat, $receive_params);
			if($result){
				$session = Yii::$app->session;
				$session['UserLogin'] = $result;
				return $this->redirect("/");
			}else{
				return $this->redirect("/");
			}
		}

	}
	
	//文章详情
	public function actionDetail($id){
		if($id){
			$model = Article::findOne($id);
			return $this->render('detail',['model'=>$model]);
		}
	}

	//搜索
	public function actionSearch(){
                return $this->render('search');
        }


	//QQ登录
	public function actionLogin()
	{
		$url  = Yii::$app->platform->getPlatformLoginUrl("qq");
        	return $this->redirect($url);
	}

	public function actionComment()
	{
		/*$aid = $_GET['id'];
		$result = UserCommentFront::getCommentData($aid);
		$comment_nums = UserCommentFront::getCommentNums($aid);
		return $this->render('test',['result'=>$result,'nums'=>$comment_nums]);
		*/
	}

	//评论
	public function actionComments()
	{
		$result=[];
		$session = Yii::$app->session;
		if(!empty($session['UserLogin'])){
			$post = Yii::$app->request->post();
			$content = $post['content'];
                        $content = strip_tags($content);//过滤掉输入、输出里面的恶意标签,如<script></script>
                        $content = trim(Common::replaceSpecialChar($content));
                        $content = htmlspecialchars($content);//转义
			if(isset($content) && !empty($content)){
				$ip = Yii::$app->request->userIP;
				$code = Yii::$app->PublicService->auth($ip,2,$content); 
				if($code){
					switch($code){
						case 10002:$msg = '您的IP已禁止评论';break;
						case 10003:$msg = '您的操作太频繁';break;
						case 10004:$msg = '您评论的文字过长';break;
						case 10005:$msg = '您评论的内容已被锁定';break;
						case 10006:$msg = '重复评论相同的内容';break;
					}
					$result['code']=300;
                                	$result['msg'] = $msg;
				}else{
					$model = new UserCommentFront();
					$model->content = $content;
					$model->comment_uid = $session['UserLogin']['uid'];
					$model->create_time = time();
					$model->aid = $post['aid'];
					if($model->save(false)){
						$article = Article::findOne($model->aid);
						if($article){
							$article->comment_nums +=1;
							$article->save(false);
						}	
						$User = UserCenter::find()->where(['id'=>$model->comment_uid])->one();
						$result['data']=[
							'id'=>$model->id,
							'c_uid'=>$model->comment_uid,
							'head_img' => $User->headimgurl,
							'nickname' => $User->nickname,
							'create_time' => date('Y-m-d H:i:s',$model->create_time),
							'content' => $model->content,
							'c_nums' => UserCommentFront::getCommentNums($model->aid),
						];
						$result['code']=200;
						$result['msg']="评论成功";
						$username = $session['UserLogin']['nickname'];
						$ip = Yii::$app->request->userIP;
						Email::SendCommentNotice($ip,$username,$model->aid,$article->title,$model->content,$model->create_time);

					}
				}
			}else{
				$result['code']=300;
				$result['msg']="评论内容不能为空";
			}
		}else{
			$result['code']=100;
                        $result['msg']="请登录之后再评论";

		}
		echo json_encode($result);
	}

	//回复
	public function actionReplyComments()
	{
		$result=[];
                $session = Yii::$app->session;
                if(!empty($session['UserLogin'])){
			$post = Yii::$app->request->post();
			$content = $post['content'];
                        $content = strip_tags($content);//过滤掉输入、输出里面的恶意标签,如<script></script>
                        $content = trim(Common::replaceSpecialChar($content));
                        $content = htmlspecialchars($content);//转义
                        if(isset($content) && !empty($content)){
                                $ip = Yii::$app->request->userIP;
                                $code = Yii::$app->PublicService->auth($ip,2,$content);
                                if($code){
                                        switch($code){
                                                case 10002:$msg = '您的IP已禁止评论';break;
                                                case 10003:$msg = '您的操作太频繁';break;
                                                case 10004:$msg = '您评论的文字过长';break;
                                                case 10005:$msg = '您评论的内容已被锁定';break;
                                                case 10006:$msg = '重复评论相同的内容';break;
                                        }
                                        $result['code']=300;
                                        $result['msg'] = $msg;
                                }else{  
					$session = Yii::$app->session;
					if($session['UserLogin']['uid']==$post['c_uid']){
						$result['code']=400;
						$result['msg']="不能回复自己";	
					}else{
						$content = $post['content'];
						$content = strip_tags($content);//过滤掉输入、输出里面的恶意标签,如<script></script>
						$content = trim(Common::replaceSpecialChar($content));
						$content = htmlspecialchars($content);//转义
						$model = new UserReplyComment();
						$model->content = $content;
						$model->c_id = $post['c_id'];
						$model->comment_uid = $post['c_uid'];
						$model->reply_uid = $session['UserLogin']['uid'];
						$model->create_time = time();
						if($model->save(false)){
							$userInfo = UserCenter::find()->where(['id'=>$model->reply_uid])->one();
							$User = UserCenter::find()->where(['id'=>$model->comment_uid])->one();
							$result['data']=[
								'c_uid'=>$model->comment_uid,
								'r_uid'=>$model->reply_uid,
								'head_img' => $userInfo->headimgurl,
								'r_nickname' => $userInfo->nickname,
								'c_nickname' => $User->nickname,
								'create_time' => date('Y-m-d H:i:s',$model->create_time),
								'content' => $model->content,
								'r_nums' => UserReplyComment::find()->where(['c_id'=>$post['c_id'],'status'=>0])->count(), 
							];
							$result['code']=200;
							$result['msg']="评论成功";
						}
					}
				}
			}else{
				$result['code']=300;
				$result['msg']="评论内容不能为空";
			}
		}else{
			$result['code']=100;
                        $result['msg']="请登录之后再评论";
		}
                echo json_encode($result);

	}



	public function actionLogout()
	{
		Yii::$app->session->remove('UserLogin');
		return $this->redirect("/");
	}

	//队列测试-左侧压入
	public function actionLpush()
	{
		Yii::$app->redis->lPush('iphone10','huangxinqiang-'.mt_rand(1,1000));
	}

	//队列测试-右侧弹出
	public function actionRpop()
	{
		echo Yii::$app->redis->rPop('iphone10');
	}
	
	


}
?>
