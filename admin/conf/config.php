<?php
	
	//后台配置文件

	//配置项: 返回到文件包含处
	return array(
		//各种配置
		'mysql' => array(
			//以后如果服务器迁移: 只要修改此处
			'host' => '127.0.0.1',				//服务器电脑的ip地址
			'port' => '3306',					//服务器软件监听的端口
			'user' => 'root',					
			'pass' => '1',
			'dbname' => 'movie',
			'charset' => 'utf8',
			// 表前缀
			'prefix' => 'm_',		
		),
		//设置分页功能每页显示数量
	    'admin_goods_pagecount' => 5,
        //后台允许上传的图片类型
        'admin_goods_img_upoads' => 'image/png,image/jpg,image/jpeg,image/gif,image/pjpeg',
        //缩略图宽
        'admin_goods_thumb_width' => 60,
        //缩略图高
        'admin_goods_thumb_height' => 60
	);