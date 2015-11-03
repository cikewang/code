<?php
// https://kyfw.12306.cn/otn/leftTicket/log?leftTicketDTO.train_date=2015-11-01&leftTicketDTO.from_station=AYF&leftTicketDTO.to_station=BJP&purpose_codes=ADULT
// 
// 
// https://kyfw.12306.cn/otn/leftTicket/query?
// leftTicketDTO.train_date=2015-11-01
// &leftTicketDTO.from_station=AYF
// &leftTicketDTO.to_station=BJP
// &purpose_codes=ADULT
$data = array(
	'leftTicketDTO.train_date' => '2015-11-01',
	'leftTicketDTO.from_station' => 'AYF',
	'leftTicketDTO.to_station' => 'BJP',
	'purpose_codes' => 'ADULT'
	);


$data = http_build_query($data);

$opts = array(
	"ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
	'http' => array(
		'method' => 'GET',
		'header' => 'Content-Length:'.strlen($data)."\r\n".
		'Content-Type:application/json;charset=UTF-8'."\r\n".
		'host:kyfw.12306.cn'."\r\n".
		'Referer:https://kyfw.12306.cn/otn/leftTicket/init'."\r\n".
		'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.152 Safari/537.36'."\r\n".
		'Cookie:JSESSIONID=0A01D975FC9BCE803651E25D99AEB4BDBDB04CA4B6; __NRF=AA547B39D8D45899EFD4A70A15DC7C4A; BIGipServerotn=1977155850.64545.0000; _jc_save_fromStation=%u5B89%u9633%2CAYF; _jc_save_toStation=%u5317%u4EAC%2CBJP; _jc_save_fromDate=2015-11-01; _jc_save_toDate=2015-10-22; _jc_save_wfdc_flag=dc; current_captcha_type=Z'."\r\n",
		'content' => $data
		)
	);

$context = stream_context_create($opts);

$html = file_get_contents('https://kyfw.12306.cn/otn/leftTicket/query?', false, $context);
echo $html;