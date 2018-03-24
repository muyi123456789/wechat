<?php
// 拼接发送字段 appid+secret+js_code+grant_type
function _str(){
	$js_code=isset($_GET['code'])?$_GET['code']:'false';
	if($js_code){
		$appid='wxf575c0415348c70f';
		$secret='5ebd03b826e87ff5d823b077285e0080';
		$url='https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$js_code.'&grant_type=authorization_code';
		return $url;
	}else{
		echo '获取用户code失败!';
	}
}
// php curl进行https连接  获取 session_key + openid等数据
function _url_https($url){
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);  // 从证书中检查SSL加密算法是否存在
    $tmpInfo = curl_exec($curl);     //返回api的json对象
    //关闭URL请求
    curl_close($curl);
    return $tmpInfo;    //返回json对象
}

// 数据库连接 *mysqli*
function _cnect($localhost,$user,$password,$database){
	$mysqli=new mysqli($localhost,$user,$password,$database);
	if($mysqli->connect_errno){
		echo "mysqli数据库连接失败...";
	}
	return $mysqli;
}
// 注册接口  将session_key openid存储在数据库
function sign($mysqli){
	$url=_str();
	$con=_url_https($url);
	$obj=json_decode($con);
	$sql="INSERT INTO con_1 (session_key, openid, expires_in)VALUES ('".$obj->session_key."','".$obj->openid."','".$obj->expires_in."')";
	if($mysqli->query($sql)){
		$sql_1="SELECT id FROM con_1 WHERE session_key='".$obj->session_key."' limit 1";
		$type_id=$mysqli->query($sql_1)->fetch_assoc();
		echo true;
	}else{
		echo false;
	}

}
// 验证接口  验证用户id是否与服务器上的session_key openid对的上
function validate($mysqli){
	$sql_2="SELECT id FROM con_1 WHERE id='".$_GET['id']."'";
	if($resource=$mysqli->query($sql_2)){
		$type_id=$resource->fetch_assoc();
		echo true;
	}else{
		echo fasle;
	}
}
// 主程序
function main(){
	$mysqli=_cnect('127.0.0.1','root','root','bais');
	if(isset($_GET['sign'])){
		sign($mysqli);
	}else if(isset($_GET['validate'])){
		validate($mysqli);
	}else{
		echo false;
	}
}


main();



