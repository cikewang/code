<?php
// 压缩图片

/*第一步：打开图片*/
// 1. 配置图片路径
$src = '100.jpg';
// 2. 获取图片信息
$info = getimagesize($src);
// 3. 通过图像的编号来获取图片的类型
$type = image_type_to_extension($info[2], false);
// 4. 在内存中创建一个和我们图像类型相同的图片
$fun = "imagecreatefrom{$type}";
// 5. 把要操作的图片复制到内存中
$image = $fun($src);


/*第二步：操作图片*/
// 1. 在内存中创建一个真色彩图片
$image_thumb = imagecreatetruecolor(150, 150);
// 2. 将原图复制到新建的真色彩图片上，并且按照比例压缩
imagecopyresampled($image_thumb, $image, 0, 0, 0, 0, 150, 150, $info[0], $info[1]);
// 3. 销毁原始图片
imagedestroy($image);

/*第三步：输出图片*/
// 浏览器输出
header("Content-type:".$info['mime']);
$funs = "image{$type}";
$funs($image_thumb);
// 保存图片
$funs($image_thumb, 'image_thumb.'.$type);


/*第四步：保存图片*/
imagedestroy($image_thumb);