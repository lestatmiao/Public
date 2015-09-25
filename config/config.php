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
		//设置重定向延迟时间
		'redirect' => 2,
		//设置首页电影详情追加hot标签的判断标准
		'hot' => array(
			'score' => 8,
			'reviews' => 10,
			//定义标签文字和颜色
			'color' => 'red',
			'words' => '(最热!)'
		),
		'new' => array(
			//该参数是以秒为单位的间隔时间，用来通过当前时间减去信息的添加时间来自定义首页电影详情页面追加new标签的判断标准
			'interval' => 40400,
			//定义标签文字和颜色
			'color' => 'cyan',
			'words' => '(最新!)'
		)
);