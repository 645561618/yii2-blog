<?php
namespace backend\controllers;

use Yii;
use backend\components\BackendController;
use backend\models\KeywordsBack;
use backend\models\KeywordsSearch;
use yii\data\ActiveDataProvider;
use backend\models\CustomMenu;
use backend\models\CustomClick;
use backend\models\WxUserInfoBack;

class WechatController extends BackendController
{

    public $enableCsrfValidation;
	
   //自动回复列表
   public function actionIndex(){
	$model = new KeywordsSearch;
	$dataProvider = $model->search(Yii::$app->request->getQueryParams());	
	return $this->render("index",['model'=>$model,'dataProvider'=>$dataProvider]);

   }


    //添加自动回复
    public function actionRobotReply()
    {
        $model = new KeywordsBack;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $model->load($post);
            if ($model->validate()) {
                $model->save();
                return $this->redirect('/wechat/index');
            }
        }
        return $this->render("robot-reply",['model'=>$model]);
    }
    //更新自动回复
    public function actionUpdateReply($id)
    {
        $model = KeywordsBack::findOne($id);
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $model->load($post);
            if ($model->validate()) {
                $model->save();
                return $this->redirect('/wechat/index');
            }
        }
        return $this->render("robot-reply",['model'=>$model]);
    }

    //回复列表排序
    public function actionUpdateSort()
    {
	$model = new KeywordsBack;
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


   //删除自动回复
   public function actionDeleteReply($id){
   	$model = KeywordsBack::findOne($id);
        if($model->delete()){
        	Yii::$app->session->setFlash('success', '删除成功');
                return $this->redirect("/wechat/index");
        }
   }

   //删除更多自动回复
    public function actionDeleteMoreReply()
    {
        if (Yii::$app->request->isPost) {
		//echo "<pre>";
		//print_r(Yii::$app->request->post());exit;
            $comments = Yii::$app->request->post("selection");
            $result = KeywordsBack::deleteAll(["id"=>$comments]);
            if ($result) {
                return $this->redirect(Yii::$app->request->getReferrer());
            }
        }
        return $this->redirect(Yii::$app->request->getReferrer());
    }


	//emoji表情
	public function bytes_to_emoji($cp)
	{
		if ($cp > 0x10000){       # 4 bytes
			return chr(0xF0 | (($cp & 0x1C0000) >> 18)).chr(0x80 | (($cp & 0x3F000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
		}else if ($cp > 0x800){   # 3 bytes
			return chr(0xE0 | (($cp & 0xF000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
		}else if ($cp > 0x80){    # 2 bytes
			return chr(0xC0 | (($cp & 0x7C0) >> 6)).chr(0x80 | ($cp & 0x3F));
		}else{                    # 1 byte
			return chr($cp);
		}
	}




   /**
     * 添加一级菜单
     */
    public function actionAddclass()
    {
        $model = new CustomMenu;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $model->load($post);
            if ($model->validate()) {
		$model->name = self::bytes_to_emoji(0xFE000).$post['CustomMenu']['name'];
                $model->save();
                return $this->redirect('/wechat/addclass');
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => CustomMenu::find()->where(['fid'=>0])->orderBy(['created'=>SORT_DESC]),
        ]);
        return $this->render("create_class",['model'=>$model,'dataProvider'=>$dataProvider]);
    }




    /**
     * 更新一级菜单
     * @param $id
     */
    public function actionUpdateClass($id){
        $model = CustomMenu::findOne($id);
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $model->load($post);
            if ($model->validate()) {
		//$model->name = self::bytes_to_emoji(0x1F436).$post['CustomMenu']['name'];
		$model->name = $post['CustomMenu']['name'];
                $model->save();
                return $this->redirect('/wechat/addclass');
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => CustomMenu::find()->where(['fid'=>0])->orderBy(['created'=>SORT_DESC]),
        ]);
        return $this->render('create_class', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    /*
    *删除一级菜单
    */
    public function actionDeleteClass($id)
    {
        $model = CustomMenu::findOne($id);
	if($model){
		if($model->delete()){
                	return $this->redirect('/wechat/addclass');
		}
	}
    }




    /**
     * 查看二级菜单
     */
    public function actionViewMenu($id)
    {
        $model = new CustomMenu;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $model->load($post);
            if ($model->validate()) {
                $model->fid = $id;
                $model->save();
                return $this->redirect('/wechat/view-menu?id='.$id);
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => CustomMenu::find()->where(['fid'=>$id])->orderBy(['created'=>SORT_DESC]),
        ]);
        return $this->render("custom",['model'=>$model,'dataProvider'=>$dataProvider]);
    }




    /**
     * 更新二级菜单
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionUpdateCustom($id){
        $fid = Yii::$app->request->get('fid');
        $model = CustomMenu::findOne($id);
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $model->load($post);
            if ($model->validate()) {
                $model->save();
                return $this->redirect('/wechat/view-menu?id='.$fid);
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => CustomMenu::find()->where(['fid'=>$fid])->orderBy(['created'=>SORT_DESC]),
        ]);
        return $this->render('custom', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }





    //生成菜单
    public function actionCreateMenu()
    {
        $model = new CustomMenu;
        $rs = $model->createMenu();
        if(!empty($rs)){
            return $rs['errmsg'];
        }else{
            return "fail";
        }
    }


   //click图文回复
   public function actionAddtype()
   {
        $model = new CustomClick;
        $cid = Yii::$app->request->get('id');
        $type = Yii::$app->request->get('type');
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $model->load($post);
            if ($model->validate()) {
                $model->cid = $cid;
                $model->type = $type;
                $model->save();
                return $this->redirect('/wechat/addtype?id='.$cid."&type=".$type);
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => CustomClick::find()->where(['cid'=>$cid,'type'=>$type])->orderBy(['created'=>SORT_DESC]),
        ]);
        return $this->render('custom_type',['model'=>$model,'dataProvider'=>$dataProvider]);

   }


    //编辑图文回复
    public function actionUpdateClick($id){
        $cid = Yii::$app->request->get('cid');
        $model = CustomClick::findOne($id);
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $model->load($post);
            if ($model->validate()) {
                $model->save();
                return $this->redirect('/wechat/addtype?id='.$cid."&type=0");
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => CustomClick::find()->where(['cid'=>$cid])->orderBy(['created'=>SORT_DESC]),
        ]);
        return $this->render('custom_type', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }



    /**
     * 删除
     */
    public function actionDeleteClick($id)
    {
        $post = CustomClick::findOne($id);
        if ($post->delete()) {
            return $this->redirect("/wechat/addclass");
        }
    }

    //微信粉丝列表
    public function actionUserInfo(){
	$model = new WxUserInfoBack;
	$dataProvider = $model->search(Yii::$app->request->getQueryParams());
        return $this->render("user_info",['model'=>$model,'dataProvider'=>$dataProvider]);
    }

   //获取微信关注粉丝
   public function actionGetUser()
   {
	$model = new WxUserInfoBack;
	$model->getUserInfo();
	echo "微信粉丝数据更新成功";
   }

   //微信公众号发送模板消息
   public function actionSendMessage()
   {
	$template =[ 
		"touser"=>"oZpmT0Q8w6ATFBtVFgzGerR4a8hs",
           	"template_id"=>"NTYcuCFg_nPFjWfSVN9MsrjIzUdoiDYo9T1wGu-Ap0Q",
           	"url"=>"http://www.hxinq.com",  
		"topcolor"=>"#FF0000",
          	"data"=>[
                   	"first"=>[
                       		"value"=>"微信公众号【强波子仔勒】",
                       		"color"=>"#173177",
                   	],
                   	"keyword1"=>[
                       		"value"=>"16",
                       		"color"=>"#173177",
                   	],
                   	"keyword2"=>[
                       		"value"=>"1",
                       		"color"=>"#173177",
                   	],
                   	"remark"=>[
                       		"value"=>"欢迎关注黄信强微信公众号！",
                       		"color"=>"#173177",
                   	],
           	]
	];
	$data  = \common\models\Common::send_template_message(json_encode($template));	
	echo "<pre>";
	print_r($data);exit;
   }
	

}
