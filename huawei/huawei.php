<?php
header("Content-Type: text/html;charset=utf-8");

/**
 * [request_by_curl 模拟POST方式提交]
 * @param  [type] $remote_server [description]
 * @param  [type] $post_string   [description]
 * @return [type]                [description]
 */
function request_by_curl($remote_server, $post_string)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $remote_server);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'mypost=' . $post_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Jimmy's CURL Example beta");
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
} 

/**
 * [get_goods_list 获取列表西信息]
 * @param  [type] $url [description]
 * @return [type]      [description]
 */
function get_goods_list($url)
{
	$goods_info = array();

	$post_string = "prdAttrList=[]&sortField=default&sortType=desc";
	$info = request_by_curl($url, $post_string);

	// 获取到商品展示图片
	preg_match_all('#<div class=\"pro-panels\">.*?</div>#is', $info, $goods_list);
	foreach($goods_list[0] AS $key => $goods)
	{
			
		preg_match_all('#<p class=\"p-price\"><b>(.*?)</b></p>#is', $goods, $p);
		$price = $p[1][0];

		preg_match_all('#src=\"(.*?)\"#is', $goods, $img);
		$img_src = $img[1][0];


		preg_match_all('#href=\"(.*?)\"#is', $goods, $href);
		$url = $href[1][1];


		preg_match_all('#title=\"(.*?)\"#is', $goods, $title);
		$title = $title[1][0];

		$r_price = str_replace('', '¥',  $price);

		$n_price = substr($price, 5);

		$first = strrpos($url, '/')+1;
		$last = strrpos($url, '.') - strrpos($url, '/')-1;
		$gid = substr($url, $first, $last );

		echo "<pre>".$key."<bR>";
		echo 'price : '. $price .'-----'. $r_price .'-----'. $n_price .'<br> img_src : '. $img_src . '<br> url : '. $url. '<br> title : '. $title;
		
		// exit;
		

		$goods_info[$key]['gid'] = $gid;
		$goods_info[$key]['price'] = $n_price;
		$goods_info[$key]['url'] = $url;
		$goods_info[$key]['img_src'] = $img_src;
		$goods_info[$key]['title'] = $title;
		// echo "<br>";
		print_r($goods_info[$key]);
		echo "<hr>";
	}
// exit;
	return $goods_info;
}


// $sql = "INSERT INTO `huawei_goods` (`pid`, `name`, `price`, `photoPath`, `photoName` ) VALUES ({$v['id']}, '{$v['name']}', {$v['price']}, '{$v['photoPath']}', '{$v['photoName']}')";
		
/**
 * [_insert_goods_info 商品列插入数据库]
 * @param  [type] $goods [description]
 * @return [type]        [description]
 */
function _insert_goods_info($goods)
{
	$mysqli = mysqli_init();
	$mysqli->real_connect("localhost", "root", "root", 'shouji');
	foreach($goods as $v) 
	{
		$sql = "INSERT INTO `huawei_shouji` (`gid`, `price`, `url`, `img_src`, `title` ) VALUES ({$v['gid']}, {$v['price']}, '{$v['url']}', '{$v['img_src']}', '{$v['title']}')";

		$mysqli->query($sql);
		echo $mysqli->insert_id;
		echo "   <br>\r\n";
	}
}

/**
 * [getImage 下载图片函数]
 * @param  [type]  $url      [description]
 * @param  string  $save_dir [description]
 * @param  string  $filename [description]
 * @param  integer $type     [description]
 * @return [type]            [description]
 */
function getImage($url, $save_dir='',$filename='',$type=0){
    if(trim($url)==''){
		return array('file_name'=>'','save_path'=>'','error'=>1);
	}

	if(trim($save_dir)==''){
		$save_dir='./';
	}

    if(trim($filename)==''){//保存文件名
        $ext=strrchr($url,'.');
        if($ext!='.gif'&&$ext!='.jpg'){
			return array('file_name'=>'','save_path'=>'','error'=>3);
		}

        $filename=time().rand().$ext;
    }
	if(0!==strrpos($save_dir,'/')){
		$save_dir.='/';
	}
	//创建保存目录
	if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
		return array('file_name'=>'','save_path'=>'','error'=>5);
	}
    //获取远程文件所采用的方法 
    if($type){
		$ch=curl_init();
		$timeout=30;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$img=curl_exec($ch);
		curl_close($ch);
    }else{
	    ob_start(); 
	    readfile($url);
	    $img=ob_get_contents(); 
	    ob_end_clean(); 
    }
    $size=strlen($img);
    echo $size." <bR>\r\n";
    //文件大小 
    $fp2=@fopen($save_dir.$filename,'a');
    fwrite($fp2,$img);
    fclose($fp2);
	unset($img,$url);
    return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
}

/**
 * [get_goods_info 获取商品信息]
 * @return [type] [description]
 */
function get_goods_info(){
	$mysqli = mysqli_init();
	$mysqli->real_connect("localhost", "root", "root", 'shouji');
	$mysqli->set_charset('utf8');
	$sql = "select * from `huawei_shouji`";
	$result = $mysqli->query($sql);

	while($row = $result->fetch_assoc()) {
		
	 	if( $row['download'] == 1 ){
	 		echo $row['id']." Have downloaded  <br>\r\n";
	 		continue;
	 	}
	 	echo $row['id']."  <br>\r\n";

	 	// /*根据商品ID，获取商品的展示图片和描述图片*/
	 	$goods_url = "http://www.vmall.com/product/{$row['gid']}.html";
	 	echo $goods_url."  <br>\r\n";
	 	$goods = file_get_contents($goods_url);
	 $name = iconv('utf-8', 'gbk', $row['title']);
	 //	$name =  $row['title'];
	 	
		$pro = '';
	 	if($row['id'] < 10){
	 		$pro = '0'.$row['id'];
	 	}else{
	 		$pro = $row['id'];
	 	}


	 	$name = $pro.'_'.str_replace('/', '_', $name);
	 	$dir0 = './'.$name.'/0/';
	 	$dir1 = './'.$name.'/1/';

	 	if( mkdir($name, 0777) ){
	 		echo "ok";
	 	 	mkdir($dir0, 0777);
	 	 	mkdir($dir1, 0777);
	 	}

	 	// 获取到商品展示图片
	 	preg_match_all('#<div class=\"pro-gallery-thumbs\">.*?</div>#is', $goods, $cont);
	 	preg_match_all('#src=\"(.*?)\"#is', $cont[0][0], $img_list);

	 	// 获取商品描述的图片
	 	preg_match_all('#<div id="pro-tab-feature-content" class="pro-detail-tab-area pro-feature-area">.*?</div>#is', $goods, $des);
	 	preg_match_all('#src=\"(.*?)\"#is', $des[0][0], $des_img_list);

	 	// 保存商品商品展示图片
	 	if( isset($img_list[1]) && !empty($img_list[1])) 
	 	{
	 		foreach ($img_list[1] as $key => $value) {
	 			$img_url = $value;
	 			$pos = strrpos( $value, '_');
	 			$img_name = substr($value, $pos+1);

	 			$first = strrpos($row['img_src'], '//')+1;
	 			$last = strrpos($row['img_src'], '/') - $first+1;
	 			$photoPath = substr($row['img_src'], $first, $last) ;

	 			$sql = "INSERT INTO `huawei_img`(`gid`, `img_url`, `img_name`, `photoPath`) VALUES ({$row['gid']}, '{$value}', '{$img_name}', '{$photoPath}')";
	 			// echo $sql;
	 			$mysqli->query($sql);

	 			echo $img_name."  <br>\r\n";
	 			// 下载图片	

	 			// http://res.vmall.com/pimages//product/100101089/02//group/800_800_1393048329639.jpg
	 			$img_url_800 = '';
	 			if($key === 0){
					$img_url_800 = "http://res.vmall.com/pimages".$photoPath."800_800_".$img_name;
	 			}
	 			else
	 			{
	 				$img_url_800 = "http://res.vmall.com/pimages".$photoPath."group/800_800_".$img_name;
	 			}

	 			if( !empty($img_url_800)){
					getImage($img_url_800, $dir0,'', 1);
	 			}
	 			else
	 			{
	 				echo " img url error <br>\r\n";
	 			}
	 			
	 		}
	 	} 
	 	else
	 	{
	 		echo "img not exist   <br>\r\n";
	 	}

	 	// 保存商品描述图片
	 	if(isset($des_img_list[1]) && ! empty($des_img_list[1]))
	 	{
	 		foreach ($des_img_list[1] as $key => $value) {

	 			$sql = "INSERT INTO `huawei_desc_img`(`gid`, `desc_img_url`) VALUES ({$row['gid']}, '{$value}')";
	 			$mysqli->query($sql);
	 			echo $value."  <br>\r\n";
	 			// 下载图片
	 			getImage($value, $dir1);
	 		}
	 	} 
	 	else
	 	{
	 		echo "desc img not exist   <br>\r\n";
	 	}

	 	$sql = "UPDATE `huawei_shouji` SET `download` = '1' WHERE `id` =".$row['id'];
	 	$mysqli->query($sql);
	}

}



/**
*华为商城 http://www.vmall.com/list-1#2
* 获得华为商城手机信息
* 图片 URL http://res.vmall.com/pimages/product/100101147/01/142_142_11.jpg
* 例图: http://res.vmall.com/pimages//product/100101159/01/142_142_B-ZM.jpg
*/
$html1 = "http://www.vmall.com/list-data-36-1";
$html2 = "http://www.vmall.com/list-data-36-2";
$html3 = "http://www.vmall.com/list-data-36-3";

// 获取商品列表
// $goods = get_goods_list($html3);
// 插入商品
// _insert_goods_info($goods);

// 获取单个商品信息
get_goods_info();


?>
