<?php
namespace backend\controllers;

use Yii;
use backend\components\BackendController;
use backend\models\LabelBack;
use backend\models\CategoryBack;
use yii\data\ActiveDataProvider;
use backend\models\ArticleBack;
use backend\models\ArticleSearch;
use backend\models\Upload;
use backend\models\LinksBack;
use common\models\Common;
use common\components\Email;
use backend\models\NoticeBack;
use common\models\UserCenter;
use backend\models\UserRecordBack;

class BlogController extends BackendController{


	public $enableCsrfValidation;

	/*
	 *分类列表
	*/ 
	public function actionCategory(){
		$model = new CategoryBack;
		$dataProvider = $model->search();
		return $this->render('category-list',['model'=>$model,'dataProvider'=>$dataProvider]);	
	}

	/*
	 *添加分类
	 */
	public function actionAddCategory(){
		$model = new CategoryBack;
		if(Yii::$app->request->isPost){
			$post = Yii::$app->request->post();
			$model->load($post);
			if($model->validate()){
				$model->save();
				return $this->redirect('/blog/category');
			}
		}
		return $this->render('add-category',['model'=>$model,'isNew'=>true]);
	}

	/*
	 *修改分类
	 */
	public function actionUpdateCategory($id){
		if($id){
			$model = CategoryBack::findOne($id);
			if(Yii::$app->request->isPost){
				if($model->updatecate(Yii::$app->request->post())){
					Yii::$app->session->setFlash('success','修改分类成功！');
					return $this->redirect('/blog/category');
				}else{
					Yii::$app->session->setFlash('error','修改分类失败！');
				}
			}
		}
		return $this->render('add-category',['model'=>$model,'isNew'=>false]);
	}

	
	

	/*
         *标签列表
        */
        public function actionLabelList(){
                $model = new LabelBack;
                $dataProvider = $model->search();
                return $this->render('label-list',['model'=>$model,'dataProvider'=>$dataProvider]);
        }                       
                                
        /*                          
         *添加标签              
         */                             
        public function actionAddLabel(){
                $model = new LabelBack;
                if(Yii::$app->request->isPost){
                        $post = Yii::$app->request->post();
                        $model->load($post);
                        if($model->validate()){
                                $model->save();
                                return $this->redirect('/blog/label-list');
                                Yii::$app->session->setFlash('success','添加标签成功！');
                        }
                }       
                return $this->render('add-label',['model'=>$model,'isNew'=>true]);
        }       
                        
        /*      
         *修改标签
         */     
        public function actionUpdateLabel($id){
                if($id){
                        $model = LabelBack::findOne($id);
                        if(Yii::$app->request->isPost){
                                if($model->updatelabel(Yii::$app->request->post())){
                                        Yii::$app->session->setFlash('success','修改标签成功！');
                                        return $this->redirect('/blog/label-list');
                                }else{
                                        Yii::$app->session->setFlash('error','修改标签失败！');
                                }
                        }
                }
                return $this->render('add-label',['model'=>$model,'isNew'=>false]);
        }








	/*
	*文章列表
	*/
	public function actionArticleList(){
		$model = new ArticleSearch;
                $dataProvider = $model->search(Yii::$app->request->getQueryParams());	
		return $this->render('article-list',['model'=>$model,'dataProvider'=>$dataProvider]);
	}	


	


	/*
        *添加文章
        */
	public function actionAddArticle(){
		$model = new ArticleBack;
		$model->loadDefaultValues();
		try{
			if(Yii::$app->request->isPost){
                	        $post = Yii::$app->request->post();
				//echo "<pre>";
				//print_r($post);exit;
                        	$model->load($post);
	    			$first_id = $post['ArticleBack']['cid'];
				if($first_id==0){
	                            Yii::$app->session->setFlash('error','请选择一级分类');
	                        }elseif($model->validate()){
        	                        if($model->save(false)){
						//添加xunsearch索引
						$search = new \common\components\xunsearch\Search;
					        $search->id = $model->id;
						$search->tid = $model->tid;
						$search->cid = $model->cid;
						$search->views = $model->views;
						$search->comment_nums = $model->comment_nums;
					        $search->img = $model->img;                
					        $search->title = $model->title;                
						$search->desc = Common::cutstr($model->desc,150);
						$search->status = $model->status;
	                                        $search->weight = $model->weight;
					        $search->created = $model->created;
					        $search->save();
                	                	return $this->redirect('/blog/article-list');
					}
	                        }
			}
                }catch(\Exception $e){
    			Yii::$app->session->setFlash('error',$e->getMessage());
	    	}
	
		foreach(CategoryBack::findFirstCateAsArray() as $v){
	        	$FirstCate[$v['id']] = $v['title'];
        	}
		return $this->render('add-article',
    		[
    			'model' => $model,
			//'model1' => new LabelBack,
    			'FirstCate' => $FirstCate,
			'Tags' => LabelBack::findAllTags(),
			'isNew'=>true
    		]);
	
	}	

	//编辑文章
	public function actionUpdateArticle($id){
                $model = ArticleBack::findOne($id);
                try{
                        if(Yii::$app->request->isPost){
                                $post = Yii::$app->request->post();
                                $model->load($post);
	    			$first_id = $post['ArticleBack']['cid'];
                                if($first_id==0){
                                    Yii::$app->session->setFlash('error','请选择一级分类');
                                }elseif($model->updateArticle($post)){
					//添加或修改xunsearch索引
					$search = \common\components\xunsearch\Search::findOne($model->id);
					if(!$search){
						$search = new \common\components\xunsearch\Search;	
					}
					$search->id = $model->id;
					$search->tid = $model->tid;
					$search->cid = $model->cid;
					$search->views = $model->views;
					$search->comment_nums = $model->comment_nums;
					$search->img = $model->img;                
					$search->title = $model->title;   
					$search->desc = Common::cutstr($model->desc,150);
					$search->status = $model->status;
					$search->weight = $model->weight;
					$search->created = $model->created;
					$search->save();
					Yii::$app->session->setFlash('success','文章编辑成功');
                                        return $this->redirect('/blog/article-list');
                                }
                        }
                }catch(\Exception $e){
                        Yii::$app->session->setFlash('error',$e->getMessage());
                }   
    
                foreach(CategoryBack::findFirstCateAsArray() as $v){
                        $FirstCate[$v['id']] = $v['title'];
                }  
                return $this->render('add-article',
                [   
                        'model' => $model,
                        'FirstCate' => $FirstCate,
			'Tags' => LabelBack::findAllTags(),
			'staticUrl' => Yii::$app->params['targetDomain'],
			'isNew'=>false
                ]); 		
	}


	//删除文章
	public function actionDeleteArticle($id){
		if($id){
			$model = ArticleBack::findOne($id);
			if($model->delete()){
				Yii::$app->session->setFlash('success','文章删除成功');
				return $this->redirect('/blog/article-list');
			}
		}
	}


	//更改文章状态
	public function actionCheckArticle($id,$status){
		if($id){
			$model = ArticleBack::findOne($id);
			switch($status){
				case 1:
					$model->status = $status;	
					$content = "审核不通过";
				break;
				case 2:
					$model->status = $status;
					$content = "审核通过";
				break;
			}
			if($model->save(false)){
				Yii::$app->session->setFlash('success',$content);
				//修改xunsearch索引状态
				$search = \common\components\xunsearch\Search::findOne($model->id);
				if($search){
					$search->status = $model->status;
					$search->save();
				}
				return $this->redirect('/blog/article-list');
			}
		}
	}

	//修改文章排序
        public function actionArticleSort()
        {
                $model = new ArticleBack;
                $id = $_REQUEST['id'];
                //去掉最后一个字符
                $id = substr($id,0,strlen($id)-1);
                $groups = split("-",$id);
                foreach($groups as $g){
                        if(!empty($g)){
                                list($id,$num) = split('_',$g);
                                $model->updateAll(['weight'=>$num],['id'=>$id]);
                        }
                }
		return 1;
                //echo "排序更新成功";
        }

	//多选更改文章状态
	public function actionUpdateMore()
	{
		$post = Yii::$app->request->post();
		$status = $post['status'];
		$ids = $post['selection'];
		foreach($ids as $id){
			ArticleBack::updateAll(['status'=>$status],['id'=>$id]);
			//修改xunsearch索引状态
			$search = \common\components\xunsearch\Search::findOne($id);
			if($search){
				$search->status = $status;
				$search->save(false);
			}

		}
		echo "<script> {window.alert('更新成功!');location.href='/blog/article-list'} </script>";
	}


	//封面图
	public function actionUploadLable()
    	{
        	$this->enableCsrfValidation = false;
	        if ($_FILES) {
        	    $model = new Upload;
	            $result = $model->uploadArticle($_FILES, true, 'article');
        	    if($result[0] == true){
			
echo <<<EOF
    <script>parent.stopSend("{$result[1]}","{$result[3]}");</script>
EOF;
            	    }else{
echo <<<EOF
    <script>alert("{$result[1]}");</script>
EOF;
            	    }
        	}
       }


	//编辑器上传图片
	public function actionUpload(){
       		$this->enableCsrfValidation = false;
	        //$fn=$_GET['CKEditorFuncNum'];

        	if($_FILES){
	            $model = new Upload;
        	    $result = $model->uploadArticle($_FILES, false, 'article');;
	            if($result[0] == true){
        	        $message = "上传成功";
                	$fileurl = $result[1];
	            }else{
        	        $fileurl = "";
                	$message = $result[1];
	            }
        	    $str='<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$fn.', \''.$fileurl.'\', \''.$message.'\');</script>';
	            echo $str;
        	}
    	}

	//获取二级标签
	public function actionSonCate($id){
        	$model = new CategoryBack;
	        $sonLable = $model->getTwoLable($id);
        	header("Content-Type: application/json");
	        echo json_encode($sonLable);
	}



	/*      
         *友情链接列表
        */
        public function actionLinksList(){
                $model = new LinksBack;
                $dataProvider = $model->search();
                return $this->render('links-list',['model'=>$model,'dataProvider'=>$dataProvider]);
        }               
                        
        /*          
         *添加友情链接      
         */             
        public function actionAddLinks(){
                $model = new LinksBack;
                if(Yii::$app->request->isPost){
                        $post = Yii::$app->request->post();
                        $model->load($post);
                        if($model->validate()){
                                $model->save();
                                return $this->redirect('/blog/links-list');
                        }
                }
                return $this->render('add-links',['model'=>$model,'isNew'=>true]);
        }       
        
        /*
         *修改友情链接
         */
        public function actionUpdateLinks($id){
                if($id){
                        $model = LinksBack::findOne($id);
                        if(Yii::$app->request->isPost){
                                if($model->updatelinks(Yii::$app->request->post())){
                                        Yii::$app->session->setFlash('success','修改友链成功！');
                                	return $this->redirect('/blog/links-list');
                                }else{
                                        Yii::$app->session->setFlash('error','修改友链失败！');
                                }
                        }
                }
                return $this->render('add-links',['model'=>$model,'isNew'=>false]);
        }



	//修改友链排序
	public function actionLinksSort()
    	{
        	$model = new LinksBack;
	        $id = $_REQUEST['id'];
        	//去掉最后一个字符
	        $id = substr($id,0,strlen($id)-1);
        	$groups = split("-",$id);
	        //echo $id;
        	//echo "<pre>";
	        //print_r($groups);exit;
        	foreach($groups as $g){
                	if(!empty($g)){
                        	list($id,$num) = split('_',$g);
	                        $model->updateAll(['sort'=>$num],['id'=>$id]);
        	        }
        	}
        	echo "排序更新成功";
    	}
	
	//删除友情链接
	public function actionDeleteLinks($id)
	{
		if($id){
			$model = LinksBack::findOne($id);
			if($model->delete()){
				Yii::$app->session->setFlash('success','链接删除成功');
				return $this->redirect('/blog/links-list');
			}
		}
		
	}

	//发送邮件
	public function actionSendEmail($id)
	{
		if($id){
			$model = LinksBack::findOne($id);
			if($model){
				$title = $model->title;
				$time = $model->created;
				$url = $model->url;
				$email = $model->email;
        	                if(Email::SendLinksEmail($title,$time,$url,$email)){
					Yii::$app->session->setFlash('success','邮件发送成功');
					$model->is_send=1;
					if($model->save(false)){
	                                	return $this->redirect('/blog/links-list');				
					}
				}
				
			}
		}
	}


	/*
	 *通知列表
	*/ 
	public function actionNotice(){
		$model = new NoticeBack;
		$dataProvider = $model->search();
		return $this->render('notice-list',['model'=>$model,'dataProvider'=>$dataProvider]);	
	}

	/*
	 *添加通知
	 */
	public function actionAddNotice(){
		$model = new NoticeBack;
		if(Yii::$app->request->isPost){
			$post = Yii::$app->request->post();
			$model->load($post);
			if($model->validate()){
				$model->save();
				return $this->redirect('/blog/notice');
			}
		}
		return $this->render('add-notice',['model'=>$model,'isNew'=>true]);
	}

	/*
	 *修改通知
	 */
	public function actionUpdateNotice($id){
		if($id){
			$model = NoticeBack::findOne($id);
			if(Yii::$app->request->isPost){
				if($model->updateNotice(Yii::$app->request->post())){
					Yii::$app->session->setFlash('success','修改通知成功！');
					return $this->redirect('/blog/notice');
				}else{
					Yii::$app->session->setFlash('error','修改通知失败！');
				}
			}
		}
		return $this->render('add-notice',['model'=>$model,'isNew'=>false]);
	}

	//删除通知
	public function actionDeleteNotice($id){
		if($id){
			$model = NoticeBack::findOne($id);
			if($model->delete()){
				Yii::$app->session->setFlash('success','文章通知成功');
				return $this->redirect('/blog/notice');
			}
		}
	}


	/*
	*socket
	*/
	public function pushSocketInfo($content)
	{
		$push_api_url = "http://127.0.0.1:2121/";
		$post_data = array(
		   "type" => "publish",
		   "content" => $content,
		);
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
		curl_setopt ($ch, CURLOPT_HTTPHEADER, array("Expect:"));
		curl_exec ( $ch );
		curl_close ( $ch );
	}


	public function actionLoginRecord()
	{
		$model = new UserRecordBack;
		$dataProvider = $model->search();
		return $this->render('user-record',['model'=>$model,'dataProvider'=>$dataProvider]);	
	}



}
