<?php
// 图片水印

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
// 1. 设置水印路径
$image_mark = "100.jpg";
// 2. 获取水印图片的基本信息
$info2 = getimagesize($image_mark);
// 3. 通过图片编号获取图片的类型
$type2 = image_type_to_extension($info2[2], false);
// 4. 在内存中创建一个和我们水印图片一致的图片类型
$fun2 = "imagecreatefrom{$type2}";
// 5. 把水印图片复制到内存中
$water = $fun2($image_mark);
// 6. 合并图片
imagecopymerge($image, $water, 20, 20, 0, 0, $info[0], $info[1], 50);
// 7. 销毁水印图片
imagedestroy($water);


/*第三步：输出图片*/
// 浏览器输出
header("Content-type:".$info['mime']);
$funs = "image{$type}";
$funs($image);
// 保存图片
$funs($image, 'image_mark.'.$type);


/*第四步：保存图片*/
imagedestroy($image);