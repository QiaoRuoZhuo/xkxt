<?php
session_start();
include("conn.php");

$queryString = "SELECT id, adminName, adminPWD FROM school_tb WHERE id='$_GET[id]'";
$result = $mysqli->query($queryString);
if ($row = $result->fetch_array()) 
{
	$sql_truncate = "TRUNCATE TABLE ".'class_tb'.$row['id']; //清空数据表
	$mysqli->query($sql_truncate); 
	$sql_truncate = "TRUNCATE TABLE ".'enroll_tb'.$row['id'];  //清空数据表
	$mysqli->query($sql_truncate); 
	$sql_truncate = "TRUNCATE TABLE ".'eventName_tb'.$row['id'];//清空数据表
	$mysqli->query($sql_truncate); 
	$sql_truncate = "TRUNCATE TABLE ".'user_tb'.$row['id'];//清空数据表
	$mysqli->query($sql_truncate); 
	$sql_truncate = "TRUNCATE TABLE ".'class_event_tb'.$row['id']; //清空数据表
	$mysqli->query($sql_truncate); 
	
	$user_tb = 'user_tb' . $row['id']; 
	$sql_insert = "INSERT INTO $user_tb(nickname, username, psw, userType) VALUES ('管理员','$row[adminName]', '$row[adminPWD]', 'g')";
	$mysqli->query($sql_insert);
}

$mysqli->close();

$prePage = $_GET["prepage"]; 
echo "<script>window.location.href='$prePage';</script>";
?>