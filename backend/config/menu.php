<?php
return [
	
	'managers'=>[
            'name' => '用户管理',
            'route' => 'manager/admin',
            'icon'=>'user',
            'style'=>'',
            'subs' => [
                [
                    'name' => '用户列表',
                    'route' => 'manager/admin',
                ],
                [
                    'name' => '添加用户',
                    'route' => 'manager/create',
                ],
		[
                    'name' => '角色列表',
                    'route' => 'auth/role',
                ],
		[   
                    'name' => '添加角色',
                    'route' => 'auth/add-role',
                ],
                [
                    'name' => '分配权限',
                    'route' => 'auth/assign',
                ],
                [
                    'name' => '权限添加',
                    'route' => 'auth/flushperms',
                ],
            ],
        ],
	
	/*'shop'=>[
            'name' => '订单管理',
            'route' => 'shop/index',
            'icon'=>'bell',
            'style'=>'',
            'subs' => [
		[
                    'name' => '订单列表',
                    'route' => 'shop/index',
                ],
            ],
        ],

        'customer'=>[
            'name' => '客户信息管理',
            'route' => 'customer/list',
            'icon'=>'star',
            'style'=>'',
            'subs' => [
		[
                    'name' => '客户列表',
                    'route' => 'customer/list',
                ],
                [
                    'name' => '添加客户信息',
                    'route' => 'customer/add',
                ],
		[
                    'name' => '添加',
                    'route' => 'false-customer/add',
                ],
            ],
        ],

	'business'=>[
            'name' => '商家管理',
            'route' => 'business/list',
            'icon'=>'star',
            'style'=>'',
            'subs' => [
                [
                    'name' => '商家列表',
                    'route' => 'business/list',
                ],
		[
                    'name' => '添加商家',
                    'route' => 'business/add',
                ],
            ],
    	],

	'apply'=>[
            'name' => '申请管理',
            'route' => 'apply/index',
            'icon'=>'star',
            'style'=>'',
            'subs' => [
                [
                    'name' => '列表',
                    'route' => 'apply/index',
                ],
            ],
        ],*/



	'wechat'=>[
            'name' => '微信管理',
            'route' => 'wechat/index',
            'icon'=>'weixin',
            'style'=>'',
            'subs' => [
		[
                    'name' => '自动回复列表',
                    'route' => 'wechat/index',
                ],
		[
                    'name' => '添加自动回复',
                    'route' => 'wechat/robot-reply',
                ],
                [
                    'name' => '自定义菜单',
                    'route' => 'wechat/addclass',
                ],

		[
		    'name' => '生成自定义菜单',
                    'route' => 'wechat/create-menu',
		],
		[
		    'name'=> '微信粉丝列表',
		    'route'=>'wechat/user-info',	
		],
            ],
        ],

	

	'blog'=>[
		'name' => '博客管理',
		'route' => 'blog/article-list',
		'icon'=>'list-alt',
		'style'=>'',
		'subs' => [
            [
                'name' => '通知管理',
                'route' => 'blog/notice',
                'subs_son'=>[
                        [
                                'name' => '通知列表',
                                'route' => 'blog/notice',
                        ],
                        [
                                'name' => '添加通知',
                                'route' => 'blog/add-notice',
                        ],
                ],
            ],
			[
                                'name' => '分类管理',
                                'route' => 'blog/category',
                                'subs_son'=>[
                                        [
                                                'name' => '分类列表',
                                                'route' => 'blog/category',
                                        ],
                                        [
                                                'name' => '添加分类',
                                                'route' => 'blog/add-category',
                                        ],
                                ],
                        ],
			[
				'name' => '标签管理',
				'route' => 'blog/label-list',
				'subs_son'=>[
					[
						'name' => '标签列表',
	                                        'route' => 'blog/label-list',
					],
					[
						'name' => '添加标签',
                                                'route' => 'blog/add-label',
					],
				],
			],
			[
                                'name' => '友链管理',
                                'route' => 'blog/links-list',
                                'subs_son'=>[
                                        [
                                                'name' => '友链列表',
                                                'route' => 'blog/links-list',
                                        ],
                                        [
                                                'name' => '添加友链',
                                                'route' => 'blog/add-links',
                                        ],
                                ],
                        ],
			[
				'name' => '文章管理',
				'route' => 'blog/article-list',
				'subs_son'=>[
		                        [
	        	                    'name' => '文章列表',
                	        	    'route' => 'blog/article-list',
                        		],
                	        	[
		                            'name' => '添加文章',
	        	                    'route' => 'blog/add-article',
                	        	],
                    		],

			],
		],

	],


	'safe'=>[
        	'name' => '安全配置',
            	'route' => 'safe/index',
            	'icon'=>'cog',
            	'style'=>'',
            	'subs' => [
               		[
                   		'name' => '安全配置列表',
                    		'route' => 'safe/index',
                	],
			[
				'name' => '添加安全配置',
                                'route' => 'safe/add',
			],
			[
                                'name' => '搜索记录',
                                'route' => 'safe/search-record',
                        ],
            	],
       	],
		
	'prize'=>[
                'name' => '抽奖中心',
                'route' => 'prize/index',
                'icon'=>'trophy',
                'style'=>'',
                'subs' => [
                        [
                                'name' => '抽奖码列表',
                                'route' => 'prize/index',
                        ],
                        [
                                'name' => '客服二维码',
                                'route' => 'prize/service',
                        ],
                ],
        ],







];
?>
