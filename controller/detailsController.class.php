<?php
	//电影详情页控制器
	class DetailsController extends Controller{
		//定义id属性用来保存当前需要加载电影的id
		private $id;
		private $id1;
		public function __construct($id=''){
			//此处需要进行判断，只允许用户通过页面跳转访问
			if(empty($id)){
				$this -> fail('错误的请求，页面即将跳转到首页','index.php?c=index&a=show');
				exit;
			}
			$this -> id = $id;
		}
		public function details(){
			//用户请求页面，此时进行页面加载
			//获取用户当前请求电影的信息
			$details = new Details();
			//首先对请求的id进行判断是否存在
			//1，获取所有id,当前得到一个二维数组
			$arr_ids = $details -> getId('i_id');
			//2，对该数组进行遍历并赋值给一个1维数组
			foreach($arr_ids as $v){
				$arr_id[] = $v['i_id'];
			}
			if(!in_array($this -> id,$arr_id)){
				include_once VIEW_DIR.'/404.html';
				exit;
			}
			$arr_infos = $details -> getInfos($this -> id);
			$arr_reviews = $details -> getRows($this -> id,'review');
			//$arr_reviews = stripslashes($arr_reviews);
			//var_dump($arr_reviews);//exit;
			//echo '<pre>';
			//var_dump($arr_infos);exit;
			include_once VIEW_DIR.'/details.html';
		}
	}