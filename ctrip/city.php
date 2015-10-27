<?php
header("Content-type:text/html; charset=UTF-8");
/**
 * 获取携程城市列表信息
 * @var [type]
 */

$data = file_get_contents('http://hotels.ctrip.com/Domestic/Tool/AjaxGetCitySuggestion.aspx');

if(empty($data))
{
	echo "获取为空";exit;
}

$pos = strpos($data, ':');

$str = substr($data, $pos+1, -1);

// 将js 字符串中的字段名加上双引号
$str = str_replace(array('display', 'data', 'group'), array("\"display\"", "\"data\"","\"group\"",), $str);

$arr = explode('],', $str);

$arr_length = count($arr);

$mysqli = new mysqli("localhost", "my_user", "my_password", "ctrip");
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

$mysqli->set_charset('UTF8');

for($i=1; $i<$arr_length; $i++)
{
	if($i < 6)
	{
		$tmp_str = $arr[$i]."]";
	}
	else
	{
		$tmp_str = $arr[$i];
	}
	

	$pos = strpos($tmp_str, ':');
	$tmp_str = substr($tmp_str, $pos+1);

	$city_list = json_decode($tmp_str, true);
	// print_r($city_list);exit;
	// [0] => Array
 //        (
 //            [display] => 阿勒泰
 //            [data] => Aletai|阿勒泰|175
 //            [group] => A
 //        )
 	
 	foreach ($city_list as $key => $city) 
 	{
 		// 保存到数据库中
 		// print_r($city);exit;

 		$tmp_data = explode('|', $city['data']);

 		$city_name = $city['display'];
 		$pinyin = $tmp_data[0];
 		$city_id = $tmp_data[2];
 		$group = $city['group'];

 		$sql = "INSERT `xc_city`(`city_id_xc`,`city_name`, `pinyin`, `group`) VALUES ($city_id, '{$city_name}', '{$pinyin}', '{$group}')";
 	
 		$mysqli->query($sql);
 	}

}


