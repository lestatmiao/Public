<?php
	//仿smarty
	//该项目酌情使用
	class View{
		//定义一个属性用于存储所有的特殊标签与替换后的值
		private $data = array();
		/*
		 * 定义一个方法assign()用于分配php动态代码到模板页面
		 * @param1 string $name :获取要替换的标签名称
		 * @param2 string $value : 分配到模板横纵的php变量
		 * @param3
		 * @return 
		 * */
		public function assign($name,$value){
			$this -> data[$name] = $value;
			
		}
		/*
		 * 定义一个display方法
		 * @param1 string $file:模板文件名称
		 * 
		 * */
		public function display($file){
			$str = file_get_contents(VIEW_DIR."/$file");
			foreach($this -> data as $key => $value){
				$str = str_replace('{'.$key.'}',$value,$str);
			}
			echo $str;
		}
	}