<?php

// 文字水印

/*第一步：打开图片*/

// 1. 图片路径
$img = '100.jpg';
// 2. 获取图片信息
$info = getimagesize($img);
// 3. 通过图像的编号获取图像的类型
$type = image_type_to_extension($info[2], false);
// var_dump($type);
// 4. 在内存中创建一张和我们图像类型一样的图像
$fun = "imagecreatefrom{$type}";
// 5. 把图片复制到内存中
$image = $fun($img);


/*第二步：操作图片*/
// 1. 设置字体路径
$font = "msyh.ttc";
// 2. 填写字体颜色
$color = imagecolorallocatealpha($image, 255, 0, 0, 50);
// 3. 写入文字
imagettftext($image, 20, 0, 20, 30, $color, $font, "Easy");
imagettftext($image, 15, 0, 20, 70, $color, $font, "cikewang");


/*第三步：输出图片*/
// 浏览器输出
header("Content-type:".$info['mime']);
$func = "image{$type}";
$func($image);

// 保存图片
$func($image, 'newimage.'.$type);


/*第四步：保存图片*/
imagedestroy($image);

