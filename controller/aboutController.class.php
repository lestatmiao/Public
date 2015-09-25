<?php
	//网站信息控制器
	class AboutController extends Controller{
		public function show(){
			//从对应数据表中获取数据加载到模板中
			$about = new About;
			$arr_infos = $about -> getOne();
			//var_dump($arr_infos);exit;
			include_once VIEW_DIR.'/about.html';
		}
	}