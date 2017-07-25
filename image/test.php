<?php
include_once 'image.php';

//压缩图片
$src = './100.jpg';
$image = new Image($src);
$image->thumb(50, 50);
$image->show();
$new_file_name = $image->save();
echo $new_file_name;


// 文字水印
// $fontfile = 'msyh.ttc';
// $image->fontMark(15, 0, 20, 30, [255, 0, 0, 50], $fontfile, "Hello1");
// $image->fontMark(15, 0, 20, 60, [255, 0, 0, 50], $fontfile, "Hello2");
// $image->fontMark(15, 0, 20, 90, [255, 0, 0, 50], $fontfile, "Hello3");
// $image->show();
// $image->save();


// 图片水印
// $image->imageMark($src, 20, 20, 0, 0, 60);
// $image->show();
// $image->save();