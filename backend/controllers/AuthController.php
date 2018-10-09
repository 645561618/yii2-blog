<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\components\BackendController;
use backend\models\AuthItem;
use backend\models\Assign;
use backend\models\AssignMent;

class AuthController extends BackendController{

    public $enableCsrfValidation=false;

    public function actionPermlist(){
        $model = new AuthItem;
        return $this->render("permlist",['model'=>$model]);

    }
    //show all roles
    public function actionRoleList(){
        $model = new AuthItem;
        $msg = '';
        if(!empty($_POST)){
            list($status,$msg) = $model->createItemInfo($_POST['AuthItem']);
            if($status){
                return $this->redirect(Yii::$app->request->getReferrer());
            }
        }
	$dataProvider = $model->getAllItems();      
        return $this->render("role-list",['model'=>$model,'dataProvider'=>$dataProvider,'isNew'=>true,'show_error'=>$msg]);
    }


   public function actionRole(){

	$model = new AuthItem;
	$dataProvider = $model->getAllItems();
	return $this->render("role-list",['model'=>$model,'dataProvider'=>$dataProvider]);

   }


   public function actionAddRole(){

	$model = new AuthItem;
	$msg = '';
	if(!empty($_POST)){
	    if(empty($_POST['AuthItem']['name'])){
		Yii::$app->session->setFlash('error','name不能为空');
		return $this->redirect('/auth/add-role');		
	    }elseif(empty($_POST['AuthItem']['description'])){
		Yii::$app->session->setFlash('error','description不能为空');
		return $this->redirect('/auth/add-role');
	    }
            list($status,$msg) = $model->createItemInfo($_POST['AuthItem']);
	    //echo $msg;exit;
            if($status){
		return $this->redirect('/auth/role');		
                //return $this->redirect(Yii::$app->request->getReferrer());
            }else{
		Yii::$app->session->setFlash('error',"{$msg}");
	    }
        }
        return $this->render("add-role",['model'=>$model,'show_error'=>$msg]);

  }



    // get permissions.php's perm value and insert into db
    public function actionFlushperms(){
        $model = new AuthItem;
        $model->flushPerms();
        // $auth = Yii::$app->authManager;
        // $auth->removeAllPermissions();
        // // get all perms
        // $perms = Yii::$app->getUser()->getAllPerms();
        // foreach($perms as $perm) {
        //     if($auth->getPermission($perm)){
        //         continue;
        //     }    
        //     $auth->add($auth->createPermission($perm));
        // }
        return "权限添加执行成功！";
    }

    //assign permissions to roles
    public function actionAssign(){
        $model = new AuthItem;
        $itemName = @$_GET['id']?:"";
        $status = '';
        if($itemName && !empty($_POST['child'])){
            $status = $model->addPermToRole($itemName,$_POST['child']);
            if($status){
                return $this->redirect(Yii::$app->request->getReferrer());
            }
        }
        $roles = $model->getAllRoles($itemName);
        $perms = $model->getPermData();
        $items = $model->assignedRoles($itemName);
        return $this->render("assign",['model'=>$model,'roles'=>$roles,'perms'=>$perms,'items'=>$items,'status'=>$status]);
    }

    //assign roles to users 
    public function actionAssignment(){
        $model = new AuthItem;
        $userId = @$_GET['user_id']?:"";
        if($userId && !empty($_POST['child']['role'])){
            if($model->assignRoleToUser($userId,$_POST['child']['role'])){
		return $this->redirect('/manager/admin');		
                //return $this->redirect(Yii::$app->request->getReferrer());
            }
        }
        $roles = $model->getUserRoles($userId);
        $allRoles = $model->getRoles();
        return $this->render("assignment",['userId'=>$userId,'roles'=>$roles,'allRoles'=>$allRoles]);
    }


    public function actionUpdateRole(){
        $itemName = $_GET['id'];
        $model = AuthItem::findOne($itemName);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        $msg = '';
        if(!empty($_POST)){
            list($status,$msg) = $model->updateItemInfo($itemName,$model->type,$_POST['AuthItem']);
            if($status){
                return $this->redirect("/auth/role-list");
            }
        }
        return $this->render("add-role",['model'=>$model]);
    }


    public function actionDeleteRole(){
        $itemName = $_GET['id'];
        $model = AuthItem::findOne($itemName);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        $msg = '';
        if(!empty($_POST)){
            list($status,$msg) = $model->removeItemInfo($itemName,$model->type);
            if($status){
                return $this->redirect("/auth/role");
            }
        }
        return $this->redirect("/auth/role");
    }


   public function actionCreate()
    {
        $model = new AuthItemChild();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'parent' => $model->parent, 'child' => $model->child]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }



}

