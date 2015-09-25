<?php
	//入口文件
	//项目密钥,避免非法请求
	define('ACCESS','movie');
	//项目初始化文件，类似shop里的init.php
	include_once 'core/Application.class.php';
	//定义初始化方法，初始化项目
	Application :: run();
	