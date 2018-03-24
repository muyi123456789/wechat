<?php
// $str=$_POST['name'].",".$_POST['sex'].",".$_POST['type'].",".$_POST['money'].",".$_POST['iphone'].",".$_POST['adr'].",".$_POST['time'].",".$_POST['more'];
// echo $str;

// mysqli数据库连接
function _cnect($localhost,$user,$password,$database){
	$mysqli=new mysqli($localhost,$user,$password,$database);
	if($mysqli->connect_errno){
		echo "mysqli数据库连接失败...";
	}
	return $mysqli;
}
// 进行存储
function M_S($mysqli,$x){
			$str=$_POST['name']."','".$_POST['sex']."','".$_POST['type']."','".$_POST['money']."','".$_POST['iphone']."','".$_POST['adr']."','".$_POST['time']."','".$_POST['more'];
	switch ($x) {
		case 'save':
			$sql="INSERT INTO bill (name,sex,type,money,iphone,adr,time,more)VALUES('".$str."');";
			if($mysqli->query($sql)){
				header('Content-Type:application/json');
				$arr=array('state'=>'true');
				$obj=json_encode($arr);
				echo $obj;
			}else{
				header('Content-Type:application/json');
				$arr=array('state'=>'false');
				$obj=json_encode($arr);
				echo $obj;
			}
			# code...
			break;
		
		// case 'delete':
		// 	$sql="INSERT INTO form ('name','sex','type','money','iphone','adr','time','more')VALUE('".$a,$b,$c,$d,$e,$f,$g,$h."'');";
		// 	# code...
		// 	break;

		// case 'update':
		// 	$sql="INSERT INTO form ('name','sex','type','money','iphone','adr','time','more')VALUE(".$a,$b,$c,$d,$e,$f,$g,$h.");";
		// 	# code...
		// 	break;

		// case 'get':
		// 	$sql="INSERT INTO form ('name','sex','type','money','iphone','adr','time','more')VALUE(".$a,$b,$c,$d,$e,$f,$g,$h.");";
		// 	# code...
		// 	break;

		// default:
		// 	# code...
		// 	break;
	}
}
function main(){
	$mysqli=_cnect('127.0.0.1','root','root','bais');
	M_S($mysqli,$_POST['x']);
}
main();