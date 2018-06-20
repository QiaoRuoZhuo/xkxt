<?php
session_start();
include("conn.php");

if (isset($_SESSION['SuserType']) && $_SESSION['SuserType'] == 'g')//系统管理员登陆
{
	$id = $_GET["class_eventID"];
	$prePage = $_GET["prepage"]; 
	$maxNum = $_GET["maxNum"]; 
	$stuRange = $_GET["stuRange"]; 
	$class_event_tb = 'class_event_tb' . $_SESSION['SschoolID'];
	
	if ($stuRange == 'b')
	{
		$sql_update = "UPDATE $class_event_tb SET stuRange='x', maxNum='$maxNum' WHERE id='$id'";
		$mysqli->query($sql_update);
	}
	else
	{
		$sql_update = "UPDATE $class_event_tb SET stuRange='b' WHERE id='$id'";
		$mysqli->query($sql_update);
	}
	
	echo "<script>window.location.href='$prePage';</script>"; 
}
else
{
	echo "<h1>系统管理员尚未登录！请登录系统管理员账号！</h1>";
}
?>