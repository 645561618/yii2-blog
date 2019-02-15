<?php
return [
	'auth/add-role' => ['manager-auth-add-role','manager*'],
	'auth/role' => ['manager-auth-role','manager*'],
	'auth/update' => ['manager-auth-update','manager*'],
	'auth/delete' => ['manager-auth-delete','manager*'],
	'auth/assign' => ['manager-auth-assign','manager*'],
	'auth/assignment' => ['manager-auth-assignment','manager*'],
	'auth/flushperms' => ['manager-auth-flushperms','manager*'],
	'manager/admin' => ['manager-manager-admin','manager*'],
        'manager/update' => ['manager-manager-update','manager*'],
        'manager/delete' => ['manager-manager-delete','manager*'],
        'manager/create' => ['manager-manager-create','manager*'],
        'manager/update-manager' => ['manager-update-manager','manager*'],

	/*'apply/index' => ['apply-index','apply*'],
        'apply/delete-apply' => ['apply-delete-apply','apply*'],
        'apply/delete-all' => ['apply-delete-all','apply*'],
        'apply/delete-more' => ['apply-delete-more','apply*'],*/


	'shop/index' =>['shop-index' ,'shop*'],	
	'customer/list' =>['shop-customer-list' ,'shop*'],	
	'customer/update-customer' =>['shop-customer-update-customer' ,'shop*'],	
	'customer/delete-customer' =>['shop-customer-delete-customer' ,'shop*'],	
	'false-customer/add' =>['shop-false-customer-add' ,'shop*'],	
	'false-customer/update-customer' =>['shop-false-customer-update-customer' ,'shop*'],	
	'false-customer/delete-customer' =>['shop-false-customer-delete-customer' ,'shop*'],	
	'business/list' =>['shop-business-list' ,'shop*'],	
	'business/add' =>['shop-business-add' ,'shop*'],	
	'business/update-business' =>['shop-business-update-business' ,'shop*'],	
	'business/delete-business' =>['shop-business-delete-business' ,'shop*'],	
	'business/balance' =>['shop-business-balance' ,'shop*'],	
	'business/balance-list' =>['shop-business-balance-list' ,'shop*'],

	'keywords/index' =>['weixin-keywords-index' ,'weixin*'],	
	'keywords/update-reply' =>['weixin-keywords-update-reply' ,'weixin*'],	
	'keywords/delete-reply' =>['weixin-keywords-delete-reply' ,'weixin*'],	
	'keywords/robot-reply' =>['weixin-keywords-robot-reply' ,'weixin*'],	
	'keywords/addclass' =>['weixin-keywords-addclass' ,'weixin*'],	
	'keywords/update-class' =>['weixin-keywords-update-class' ,'weixin*'],	
	'keywords/create-menu' =>['weixin-keywords-create-menu' ,'weixin*'],	
	'keywords/view-menu' =>['weixin-keywords-view-menu' ,'weixin*'],	
	'keywords/addtype' =>['weixin-keywords-addtype' ,'weixin*'],	
	'keywords/update-click' =>['weixin-keywords-update-click' ,'weixin*'],	
	'keywords/delete-click' =>['weixin-keywords-delete-click' ,'weixin*'],	
	'keywords/update-custom' =>['weixin-keywords-update-custom' ,'weixin*'],	

	'blog/category' => ['blog-category','blog*'],
	'blog/add-category' => ['blog-add-category','blog*'],
	'blog/update-category' => ['blog-update-category','blog*'],
	'blog/label-list' => ['blog-label-list','blog*'],
	'blog/add-label' => ['blog-add-label','blog*'],
	'blog/update-label' => ['blog-update-label','blog*'],
	'blog/article-list' => ['blog-article-list','blog*'],
	'blog/add-article' => ['blog-add-article','blog*'],
	'blog/update-article' => ['blog-update-article','blog*'],
	'blog/delete-list' => ['blog-delete-article','blog*'],
	'blog/upload' => ['blog-upload','blog*'],
	
	'prize/index'  => ['prize-index','prize*'],
	'prize/grant'  => ['prize-grant','prize*'],
	'prize/service'  => ['prize-service','prize*'],
	'prize/update-wx'  => ['prize-update-wx','prize*'],
	'prize/upload'  => ['prize-upload','prize*'],


];

?>
