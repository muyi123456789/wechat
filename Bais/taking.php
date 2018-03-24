<?php
// mysqli数据库连接
function _cnect($localhost,$user,$password,$database){
	$mysqli=new mysqli($localhost,$user,$password,$database);
	if($mysqli->connect_errno){
		echo "mysqli数据库连接失败...";
	}
	return $mysqli;
}
// 数据库是否有新纪录，若有=>返回数据 若没有=>则返回一个标志 【可以只将新变化的数据发送前端，这样可以减少小程序端的数据传输与接收，提高程序效率，减少流量,减轻服务器压力】
function is_refresh(){
	if(isset($POST['refresh'])){
		

	}else{
		echo $_POST['refresh'];
	}
}
function get_all($mysqli){
	$sql="SELECT * FROM bill LIMIT 20 WHERE id=";
	$arr=array();
	$i=0;
	if($res=$mysqli->query($sql)){
		while($a=$res->fetch_assoc()){
			if($i==20){

			}
			$arr[$i]=$a;
			$i++;
		}
	}

}
function main(){
	$mysqli=_cnect('127.0.0.1','root','root','bais');
	get_all($mysqli);
}
main();