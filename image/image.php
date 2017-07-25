<?php
/**
 * 图片操作
 * 1. 压缩图片
 * 2. 图片水印
 * 3. 文字水印
 * 
 */
Class Image {

	// 图片扩展名
	private $ext;
	// 图片信息
	private $info;
	// 保存在内存中的图片
	private $image;
	// 创建的压缩图片
	private $image_thumb;
	// 文件目录信息
	private $pathinfo;

	public function __construct($src)
	{
		$this->open_image($src);
	}

	/**
	 * 在内存中创建一张和图片类型相同的图片
	 * @return [type] [description]
	 */
	private function imagecreatefromXX($src)
	{
		$fun = "imagecreatefrom".$this->ext;
		return $fun($src);
	}

	/**
	 * 输出图片
	 * @param  [type] $src            [description]
	 * @param  string $new_image_name [description]
	 * @return [type]                 [description]
	 */
	private function imageXX($src, $new_image_name = '')
	{
		$func = "image".$this->ext;
		if (empty($new_image_name)) {
			$func($src);
		} else {
			$func($src, $new_image_name);
		}
		
	}

	/**
	 * 获取图片信息
	 * @param  [type] $src [description]
	 * @return [type]      [description]
	 */
	public function open_image($src)
	{
		$this->pathinfo = pathinfo($src);
		// 获取图片信息
		$this->info = getimagesize($src);
		// 通过图像的编号来获取图片的类型
		$this->ext = image_type_to_extension($this->info[2], false);
		// 把要操作的图片复制到内存中
		$this->image = $this->imagecreatefromXX($src);
	}

	/**
	 * 压缩图片
	 * @param  [type] $width  [压缩宽度]
	 * @param  [type] $height [压缩高度]
	 * @return [type]         [description]
	 */
	public function thumb($width, $height)
	{
		// 1. 在内存中创建一个真色彩图片
		$this->image_thumb = imagecreatetruecolor($width, $height);
		// 2. 将原图复制到新建的真色彩图片上，并且按照比例压缩
		imagecopyresampled($this->image_thumb, $this->image, 0, 0, 0, 0, $width, $height, $this->info[0], $this->info[1]);
		// 3. 销毁原始图片
		imagedestroy($this->image);
		$this->image = $this->image_thumb;
	}

	/**
	 * 文字水印
	 * @param  [type] $size     [字体尺寸]
	 * @param  [type] $angle    [角度制表示的角度]
	 * @param  [type] $x        [X坐标]
	 * @param  [type] $y        [Y坐标]
	 * @param  [type] $color    [字体颜色以及透明度]
	 * @param  [type] $fontfile [字体类型]
	 * @param  [type] $text     [文字内容]
	 * @return [type]           [description]
	 */
	public function fontMark($size, $angle, $x, $y, $color, $fontfile, $text)
	{
		// 填写字体颜色
		$col = imagecolorallocatealpha($this->image, $color[0], $color[1], $color[2], $color[3]);
		// 写入文字
		imagettftext($this->image, $size, $angle, $x, $y, $col, $fontfile, $text);
	}

	/**
	 * 图片水印
	 * @param  [type] $image_mark [水印图片]
	 * @param  [type] $dst_x      [description]
	 * @param  [type] $dst_y      [description]
	 * @param  [type] $src_x      [description]
	 * @param  [type] $src_y      [description]
	 * @param  [type] $pct        [透明度]
	 * @return [type]             [description]
	 */
	public function imageMark($image_mark, $dst_x, $dst_y, $src_x, $src_y, $pct)
	{

		// 获取水印图片的基本信息
		$info = getimagesize($image_mark);
		// 通过图片编号获取图片的类型
		$type = image_type_to_extension($info2[2], false);
		// 在内存中创建一个和我们水印图片一致的图片类型
		$func = "imagecreatefrom{$type2}";
		// 把水印图片复制到内存中
		$water = $func($image_mark);
		// 合并图片
		imagecopymerge($this->image, $water, $dst_x, $dst_y, $src_x, $src_y, $this->info[0], $this->info[1], $pct);
		// 销毁水印图片
		imagedestroy($water);

	}

	/**
	 * 浏览器输出图片
	 * @return [type] [description]
	 */
	public function show()
	{
		header("Content-type:".$this->info['mime']);
		$this->imageXX($this->image);
	}

	/**
	 * 保存图片
	 * @return [type] [description]
	 */
	public function save($new_image_name = '')
	{
		if (empty($new_image_name)) {
			$new_image_name = $this->pathinfo['filename'].'_'.date("YmdHis").'_'.rand(1000,9999);
		}

		$filename =  $new_image_name.'.'.$this->pathinfo['extension'];
		$this->imageXX($this->image, $filename);
		return $filename;
	}


	public function __destruct()
	{
		imagedestroy($this->image);
	}
}