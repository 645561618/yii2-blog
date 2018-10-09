<?php
namespace backend\models;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use common\components\BackendActiveRecord;


class AuthItem extends BackendActiveRecord{

    public static $TYPES = ['Role','Operation'];
    const TYPE_ROLE = 1;
    const TYPE_PERMISSION = 2;
    public  static function tableName(){
        return "auth_item";
    }

    public function rules(){
        return [
            [['name','type','description'],'required'],
            [['data','rule_name'],'safe'],
        ];
    }

   public function attributelabels()
   {
    	return[
        	'name' => '角色名',
            	'type' => '角色类型',
            	'description' => '角色描述',
    	];
   }


    public function check_auth($item_name){
        if(!empty($item_name)){
            $auth = \Yii::$app->authManager;
            if($auth->getRole($item_name) || $auth->getPermission($item_name)){
                return true;
            }
        }
        return false;
    }


    public function createItemInfo($params){
        $itemName = $params['name'];
        $type = $params['type'];
        $description = $params['description'];
        // $ruleName = $params['rule_name'];
        //$data = $params['data'];
        $auth = \Yii::$app->authManager;
        $status=$this->check_auth($itemName,$type);
        if($status){
            $msg = $this->showError($type);
            return [false,$msg];
        }
        if($type == static::TYPE_ROLE){  //add new role;
            $role = $auth->createRole($itemName);
            $role->description = $description;
            // $role->ruleName = $ruleName;
           // $role->data = $data;
            if($auth->add($role)){
                return [true,true];
            }
        }else{
            $perm = $auth->createPermission($itemName);
            $perm->description = $description;
            // $perm->ruleName = $ruleName;
            //$perm->data = $data;
            if($auth->add($perm)){
                return [true,true];
            }
        }
        return [false,"Create item fail!"];
    }

    //修改角色信息
    public function updateItemInfo($oldItemName,$type,$params){
        $itemName = $params['name'];
        $description = $params['description'];
        // $ruleName = $params['rule_name'];
//        $data = $params['data'];
        $auth = \Yii::$app->authManager;
        if($oldItemName != $itemName){
            $status=$this->check_auth($itemName,$type);
            if($status){
                $msg = $this->showError($type);
                return [false,$msg];
            }
        }
         if($type == static::TYPE_ROLE){
            $role = $auth->getRole($oldItemName);
            $role->name = $itemName;
            $role->description = $description;
  //          $role->data = $data;
            if($auth->update($oldItemName,$role)){
                return [true,true];
            }
        }else{
            $perm = $auth->getPermission($itemName);
            $perm->description = $description;
            // $perm->ruleName = $ruleName;
    //        $perm->data = $data;
            if($auth->update($oldItemName,$perm)){
                return [true,true];
            }
        }
        return [false,"Update item fail!"];
    }


    public function removeItemInfo($itemName,$type){
        $auth = \Yii::$app->authManager;
        if($type == static::TYPE_ROLE){
            $role = $auth->getRole($itemName);
            if($auth->remove($role)){
                return [true,true];
            }
        }else{
            $perm = $auth->getPermission($itemName);
            if($auth->remove($perm)){
                return [true,true];
            }
        }
        return [false,"Remove item fail!"];
    }

    public static function showError($type){
        return "Error! Possible there's already an item with the same name! ";//"已经存在相同的角色名称！";
    }
    

    public function getAllItems(){
        $provider = new ActiveDataProvider([
            'query' => static::find()
                    ->where(['type'=>1]),
            'sort' => [
                'attributes' => ['goods_id'],
            ],
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
        return $provider;
    }

    //获取权限数据
    public function getPermData(){
        $rs = [];
        $data = static::find()->where(['type'=>2])->all();
        if($data){
            foreach ($data as $key => $value) {
                $rs[$value['name']] = $value['name'];
            }
        }
        return $rs;
    }

    //获取所有的角色名称
    public function getAllRoles($itemName){
        $rs = [];
        $data = static::find()->where("type=1 and name!='{$itemName}'")->all();
        if($data){
            foreach ($data as $key => $value) {
                $qs = Assign::findOne(['child'=>$itemName,'parent'=>$value['name']]);
                if($qs){
                    continue;
                }
                $rs[$value['name']] = $value['name'];
            }
        }
        return $rs;
    }

    //获取已经被分配的角色名称
    public function assignedRoles($itemName){
        $assignedItems = Assign::find()->where(['parent'=>$itemName])->all();
        $rs = [];
        if($assignedItems){
            foreach ($assignedItems as $key => $value) {
                $rs[$value['child']] = $value['child'];
            }
        }
        return $rs;
    }

    //把权限分配给角色
    public function addPermToRole($itemName,$params){
        if(!empty($itemName) && !empty($params)){
            $auth = \Yii::$app->authManager;
            $success = 0;
            $roleObjec = $auth->getRole($itemName);
            $this->removeOldPerms($itemName);
            foreach ($params as $key=> $value) {
                foreach ($value as $v) {
                    $ifSet = $this->checkValue($itemName,$v);
                    if($ifSet){
                        continue;
                    }
                    $itemObject = '';
                    if($key === 'perm'){
                        $itemObject = $auth->getPermission($v);
                    }else{
                        $itemObject = $auth->getRole($v);
                    }
                    $success = $auth->addChild($roleObjec,$itemObject);
                }
            }
            return true;
        }
        return false;
    }

    //分配角色给用户
    public function assignRoleToUser($userId,$roles){
        AssignMent::deleteAll(['user_id'=>$userId]);
        if($userId  && $roles){
            $auth = \Yii::$app->authManager;
            foreach ($roles as $key => $v) {
                $roleName = $auth->getRole($v);
                $auth->assign($roleName, $userId);
            }
            return true;
        }
        return false;
    }

    // 获取当前用户已经被设置的角色数据！
    public function getUserRoles($userId){
        $qs = [];
        if($userId){
            $rs = AssignMent::find()->where(['user_id'=>$userId])->all();
            if($rs){
                foreach ($rs as $key => $v) {
                    $qs[$v['item_name']] = $v['item_name'];
                }
            }
        }
        return $qs;
    }


    //获取所以角色名称
    public function getRoles(){
        $rs = [];
        $qs = AuthItem::find()->where(['type'=>1])->all();
        if($qs){
            foreach ($qs as $key => $v) {
                $rs[$v['name']] = $v['name'];
            }
        }
        return $rs;
    }

    //修改角色权限之前先remover掉角色旧的权限
    public function removeOldPerms($itemName){
        Assign::deleteAll(['parent'=>$itemName]);
    }


    //检查同一角色是否存在相同权限值
    public function checkValue($itemName,$value){
        $status = Assign::findOne(['child'=>$value,'parent'=>$itemName]);
        return $status;
    }
    
    public function flushPerms(){
        $auth = \Yii::$app->authManager;
        // $auth->removeAllPermissions();
        // get all perms
        $perms = \Yii::$app->getUser()->getAllPerms();
        foreach ($perms as $perm) {
            if($auth->getPermission($perm)){
                continue;
            }
            $auth->add($auth->createPermission($perm));
        }
        $all_perms = AuthItem::find()->where(['type'=>2])->all();
        $rs = [];
        foreach ($all_perms as $key => $v) {
            $rs[$key] = $v['name'];
        }
        $rss = array_diff($rs,$perms);
        foreach ($rss as $v) {
            $auth->remove($auth->getPermission($v)); 
        }
    }
}
