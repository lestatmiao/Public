<?php
	//首页控制器
	class IndexController extends Controller{
		//定义一个id属性用于接收用户的诸如排序等其他请求
		private $id;
		public function __construct($id = ''){
			$this -> id = $id;
		}
		public function show(){
			//判断cookie['user']是否存在，如果存在则设置session
			if(isset($_COOKIE['user'])){
				$_SESSION['user'] = $_COOKIE['user'];
			}
			//判断id是否存在
			if(!$this -> id == ''){
				//echo $this -> id;exit;
				//通过index模型获取到电影图片文件的路径
				$index = new Index();
				$arr_image = $index -> getImage($this -> id);
			}else{
				//通过index模型获取到电影图片文件的路径
				$index = new Index();
				$arr_image = $index -> getImage();
			}
			//通过index模型获取新闻数据表中的内容
			$arr_news = $index -> getNews('news');
			//获取每部电影的总评论数用以显示
			$details = new Details();
			//$arr_reviews = $details -> getRows();
			//如果SESSION存在，则获取用户资料，用以显示头像等信息,此处可以调用个人中心模型
			if(isset($_SESSION['user'])){
				//调用个人中心模型
				$id = $_SESSION['user'];
				$user = new Manage();
				$arr_infos = $user -> getUserInfo($id);
			}
			include_once VIEW_DIR.'/index.html';
			
		}
		
	}