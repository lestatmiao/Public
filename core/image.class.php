<?php

	//图片处理类

	class Image{
		//静态属性
		public static $thumb_error = '';
		/*
		 * 制作缩略图
		 * @param1 string $file,原图资源
		 * @param2 string $path,保存路径
		 * @param3 int $width,缩略图宽
		 * @param4 int $height,缩略图高
		*/
		public static function makeThumb($file,$path,$width,$height){
			//判断原图
			if(!is_file($file)){
				//不是合理文件
				self::$thumb_error = '原图文件不存在!';
				return false;
			}
			//获取原图资源
			//定义数组: MIME类型对应的函数名字
			$funcs = array(
				'image/gif' => 'gif',	
				'image/png' => 'png',	
				'image/jpg' => 'jpeg',	
				'image/jpeg' => 'jpeg',	
				'image/pjpeg' => 'jpeg'	
			);
			
			//获取文件信息
			$info = getimagesize($file);

			//确定函数名
			$create = 'imagecreatefrom' . $funcs[$info['mime']];	//字符串
			$save   = 'image' . $funcs[$info['mime']];
			//	imagecreatefrom . jpeg ===> imagecreatefromjpeg

			//利用可变函数获取资源
			$src = $create($file);

			//获取缩略图资源
			$dst = imagecreatetruecolor($width,$height);

			//补白
			$bg = imagecolorallocate($dst,255,255,255);
			imagefill($dst,0,0,$bg);

			//计算缩略图实际宽高
			$src_cmp = $info[0]/$info[1];
			$dst_cmp = $width / $height;

			//比较比例
			if($src_cmp > $dst_cmp){
				$dst_width = $width;
				$dst_height = floor($dst_width / $src_cmp);
			}else{
				$dst_height = $height;
				$dst_width = floor($dst_height * $src_cmp);
			}

			//计算缩略图起始位置
			$dst_x = floor(($width - $dst_width) / 2);
			$dst_y = floor(($height - $dst_height) / 2);

			//采样复制
			if(!imagecopyresampled($dst,$src,$dst_x,$dst_y,0,0,$dst_width,$dst_height,$info[0],$info[1])){
				//采样复制失败
				self::$thumb_error = '采样复制失败!';
				return false;
			}

			//保存文件: 在原图名字前面加上前缀: thumb_
			//拼凑名字
			$thumb_name = 'thumb_' . basename($file);
			if($save($dst,$path . '/' . $thumb_name)){
				//成功
				return $thumb_name;
			}else{
				//失败
				self::$thumb_error = '缩略图保存失败!';
				return false;
			}
		}
	}