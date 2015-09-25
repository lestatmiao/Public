<?php

	//文件上传工具类

	class Upload{
		//静态属性
		public static $error = '';
		
		/*
		 * 上传单个文件
		 * @param1 array $file,要上传的文件信息
		 * @param2 string $path,上传指定路径
		 * @param3 string $allow,允许上传的类型(MIME类似字符串,使用逗号分隔)
		 * @示例: image/png,image/jpg,image/gif
		 * @param4 int $maxsize = 1000000,默认允许最大1M
		 * @return string 新的文件名字
		*/
		public static function uploadSingle($file,$path,$allow,$maxsize = 1000000){
			//验证文件的合法性
			if(!is_array($file) || count($file) != 5){
				//不是一个合法的上传文件
				self::$error = '文件错误,不是一个合理的上传文件!';
				return false;
			}

			//判断系统认定结果
			switch($file['error']){
				case 1:
					self::$error = '上传文件超过服务器允许的大小,服务器允许的大小为: ' . ini_get('upload_max_filesize'); 
					return false;
				case 2:
					self::$error = '上传文件超过浏览器允许的大小!';
					return false;
				case 3:
					self::$error = '文件只上传了一部分!';
					return false;
				case 4:
					self::$error = '没有选中要上传的文件!';
					return false;
				case 6:
				case 7:
					self::$error = '服务器错误!';	//正确合理处理: 记录错误到系统日志
					return false;
			}

			//判断文件类型是否合理
			if(strpos($allow,$file['type']) === false){
				//文件类型不允许
				self::$error = '文件类型不允许,允许的类型有:' . $allow;
				return false;
			}

			//文件大小判断
			if($file['size'] > $maxsize){
				//文件超出当前允许大小
				self::$error = '文件超出当前允许的大小,当前允许的最大值是: ' . $maxsize/1000 . '千字节(KB)!';
				return false; 
			}

			//移动文件到指定目录
			//获取新的文件名
			$newname = self::getNewName($file['name']);
			if(move_uploaded_file($file['tmp_name'],$path . '/' . $newname)){
				//移动成功
				return $newname;
			}else{
				//移动失败
				self::$error = '文件移动失败!';
				return false;
			}
		}

		/*
		 * 生成新的文件名: YYYYMMDDHHIISS + 随机6位字符串(带后缀)
		 * @param1 string $filename,文件名字
		 * @return string 新的文件名字
		*/
		private static function getNewName($filename){
			//构造时间日期部分
			$newname = date('YmdHis');

			//随机6位字符串
			$str = 'abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
			for($i = 0 ;$i < 6;$i++){
				$newname .= $str[mt_rand(0,strlen($str) - 1)];
			}

			//拼凑后缀名
			return $newname . strrchr($filename,'.');
		}
	}