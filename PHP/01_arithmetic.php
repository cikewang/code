<?php

$arr = array(
	array(10, 11, 12, 13),
	array(20, 21, 22, 23),
	array(30, 31, 32, 33),
	array(40, 41, 42, 43)
	);

function show_arr($arr)
{
	foreach ($arr as $key => $value) 
	{
		foreach ($value as $k => $v) 
		{
			echo $v.' ';
		}
		echo "<br>";
	}
}
show_arr($arr);

echo "<hr>";
echo "<h3>对角替换</h3>";
$arr_count = count($arr);
for($i = 0; $i < $arr_count ; $i++)
{	
	$arr2 = count($arr[$i]);
	for($j=0; $j < $arr2; $j++)
	{	
		if($j > $i){
			$tmp_v =  $arr[$i][$j];
			$arr[$i][$j] =  $arr[$j][$i];
			$arr[$j][$i] = $tmp_v;
		}
	}
}
show_arr($arr);

echo "<br>";

$arr_count = count($arr);
for($i = 0; $i < $arr_count ; $i++)
{
	$arr2 = count($arr[$i]);
	for($j=0; $j < $arr2; $j++)
	{	
		$tmp_v =  $arr[$i][$j];
		$arr[$i][$j] =  $arr[$j][$i];
		$arr[$j][$i] = $tmp_v;
	}
}
show_arr($arr);

echo "<hr>";
echo "<h3>右旋转 90度</h3>";
$arr2 = array();
for($n=0,$i=3; $i >= 0; $n++,$i--)
{
	for($j=0; $j <=3; $j++)
	{
		$arr2[$j][$n] = $arr[$i][$j];
	}
}
show_arr($arr2);


echo "<hr>";
echo "<h3>左旋转 90度</h3>";
$arr2 = array();
for($i=0; $i<=3; $i++)
{
	for($j=3, $n=0; $j >= 0; $j--, $n++)
	{
		// echo $n.' -- '.$i." --".$arr[$i][$j]." <br>";
		$arr2[$n][$i] = $arr[$i][$j];
	}
}

show_arr($arr2);