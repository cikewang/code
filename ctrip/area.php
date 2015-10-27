<?php
/**
 * 获得区域信息
 */

header("Content-type:text/html; charset=UTF-8");

function fetch_data_file($url)
{	
	try{
		$info = @file_get_contents($url);
	}
	catch(Exception $e)
	{
		echo "catch \r\n";
	}
	
	if(empty($info))
	{
		echo "sleep 1 seconds \r\n";
		sleep(1);
		echo "go... \r\n";

		return @fetch_data($url);
	}
	return $info;
}


$mysqli = new mysqli("localhost", "my_user", "my_password", "ctrip");

if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

$mysqli->set_charset('UTF8');

$sql = "SELECT * FROM `xc_city` WHERE `status` = 0";

$result  = $mysqli->query($sql);

while ($city = $result ->fetch_assoc()) 
{
	$url = 'http://hotels.ctrip.com/Domestic/Tool/AjaxGetHotKeyword.aspx?cityid='.$city['city_id_xc'];
	$str = fetch_data_file($url);

	echo $url."\r\n";

	$str = strip_tags($str);
	$pos = strpos($str, 'suggestion');
	$str = substr($str, $pos+11);

	if(strcmp($str,"null") == 0)
	{
		echo "{$city['pinyin']}  {$city['city_name']}  {$city['city_id_xc']} is null \r\n";
		$sql = "UPDATE `xc_city` set `status` = 7 WHERE `city_id_xc` = ".$city['city_id_xc'];
		$mysqli->query($sql);
		continue;
	}

	$str = str_replace('\\\'', '\'', $str);
	$arr = json_decode($str, true);

	if(empty($arr))
	{
		echo "ERROR！ {$city['city_name']} {$city['city_id_xc']} area not fetch \r\n";

		exit;
	}
	else
	{
		echo "{$city['pinyin']}  {$city['city_name']}  {$city['city_id_xc']} area info \r\n";
	}
	
	foreach ($arr as $key => $value) 
	{
		
		$sql = "SELECT * FROM `xc_area_category` WHERE `area_cate_code` = '".$key."'";
		$cate_res = $mysqli->query($sql);
		$cate_info = $cate_res->fetch_assoc();
		if(empty($cate_info))
		{
			$sql = "INSERT `xc_area_category`(`area_cate_name`, `area_cate_code`) VALUES('{$value['cnname']}', '{$key}')";
			$mysqli->query($sql);
			$cate_info['area_cate_id'] = $mysqli->insert_id;
		}	

		if(empty($value['data']))
		{
			continue;
		}

		foreach ($value['data'] as $k => $v) 
		{
			$sql = "INSERT `xc_area`(`city_id_xc`, `area_cate_id`, `area_id_xc`, `area_name`, `area_type`) VALUES('{$city['city_id_xc']}', '{$cate_info['area_cate_id']}', '{$v['id']}', '{$v['name']}', '{$v['type']}')";
			$mysqli->query($sql);
		}
	}

	$sql = "UPDATE `xc_city` set `status` = 1 WHERE `city_id_xc` = ".$city['city_id_xc'];
	$mysqli->query($sql);

}
