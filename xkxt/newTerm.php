<?php
session_start();
include("conn.php");
@header("content-type:text/html;charset=utf-8");

$user_tb = 'user_tb' . $_SESSION['SschoolID'];
$class_tb = 'class_tb' . $_SESSION['SschoolID'];
$class_event_tb = 'class_event_tb' . $_SESSION['SschoolID'];
$eventName_tb = 'eventName_tb' . $_SESSION['SschoolID'];
$enroll_tb = 'enroll_tb' . $_SESSION['SschoolID'];

if (isset($_SESSION['SuserType']) && $_SESSION['SuserType'] == 'g')//系统管理员登陆
{
	//在数据库中删除已毕业学生选课信息
	$queryString = "DELETE FROM $enroll_tb 
					WHERE userID NOT IN (
					SELECT username FROM $user_tb)"; 				
	if (!$mysqli->query($queryString))
	{
		echo "<script>alert('删除已毕业学生选课信息失败！');</script>";
	}
	
	$sql_updateRestOfTerm = "UPDATE $enroll_tb SET restOfTerm=restOfTerm-1, changeFlag='2' WHERE restOfTerm > '0'";
	if (!$mysqli->query($sql_updateRestOfTerm)) //课程的剩余学期数减一，并将课程类型改为在修
	{
		echo "<script>alert('更新在修课程信息失败！');</script>";
	}
	
	$sql_updateChangeFlag = "UPDATE $enroll_tb SET changeFlag='3' WHERE restOfTerm = '0'";
	if ($mysqli->query($sql_updateChangeFlag)) //剩余学期数为0的课程类型改为已修
	{
		echo "<script>alert('更新课程信息成功！');</script>";
	}
	else
	{
		echo "<script>alert('更新已修课程状态失败！');</script>";
	}
				   
	echo "<script>window.location.href='adminMenu.php';</script>"; 
}
else
{
	echo "<h1>系统管理员尚未登录！请登录系统管理员账号！</h1>";
}
?>