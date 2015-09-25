<?php
	//院线放映安排搜索功能控制器
	class SearchController extends Controller{
		public function submit(){
			//对用户提交进行判断
			//var_dump($_POST['city']);exit;
			$city = isset($_POST['city']) ? $_POST['city']: '成都';
			$city = urlencode($city);
			$value = isset($_POST['value']) ? $_POST['value']: '';
			$ak = 'ArSQzHaV5olyfzvf0536zGkh';
			$wd = isset($_POST['cinema']) ? $_POST['cinema']: '万达影城';
			//var_dump($city,$wd);exit;
			$wd = urlencode($wd);
			$url = "http://api.map.baidu.com/telematics/v3/movie?qt=search_cinema&wd={$wd}&location={$city}&ak={$ak}";
			@$res = file_get_contents($url);
			if(!$res){
				$this -> fail('请确保网络通畅后再试<br />系统即将跳转','index.php?c=index&a=show');
				exit;
			}
			@$xml = simplexml_load_string($res);
			//var_dump($xml);exit;
			$notice = '';
			if(!$xml){
				$notice = '度娘在玩自拍，请稍后再试';
			}
			//一共四个循环，需要求出4个数组的长度
			@$length_total = count($xml -> result -> item);
			if(@$xml -> error == 0){
				include_once VIEW_DIR.'/result.html';
			}else{
				$this -> fail('请填写正确的城市名和影院名<br />系统即将跳转','index.php?c=index&a=show');
				exit;
			}
		}
		public function show(){
			include_once VIEW_DIR.'/result.html';
		}
	}
