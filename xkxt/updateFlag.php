<?php
	session_start();
	@header("content-type:text/html;charset=utf-8");
	include("conn.php");
	
	if ("1" == $_GET["value"])
	{
		$sql_update = "UPDATE school_tb SET " . $_GET['flag'] . " = '0' WHERE id='$_GET[id]'";
		if (!$mysqli->query($sql_update))
		{
			echo "<script>alert('修改状态失败!');</script>";
		}
	}
	else
	{
		$sql_update = "UPDATE school_tb SET " . $_GET['flag'] . " = '1' WHERE id='$_GET[id]'";
		if (!$mysqli->query($sql_update))
		{
			echo "<script>alert('修改状态失败!');</script>";
		}
	}
	$mysqli->close();

	$prePage = $_GET["prepage"]; 
echo "<script>window.location.href='$prePage';</script>";
?>