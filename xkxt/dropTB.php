<?php
session_start();
include("conn.php");

$sql_drop = "DROP TABLE IF EXISTS ".'class_tb'.$_GET['id']; //删除数据表
$mysqli->query($sql_drop);
$sql_drop = "DROP TABLE IF EXISTS ".'enroll_tb'.$_GET['id'];  //删除数据表
$mysqli->query($sql_drop);
$sql_drop = "DROP TABLE IF EXISTS ".'eventName_tb'.$_GET['id'];//删除数据表
$mysqli->query($sql_drop);
$sql_drop = "DROP TABLE IF EXISTS ".'user_tb'.$_GET['id'];//删除数据表
$mysqli->query($sql_drop);
$sql_drop = "DROP TABLE IF EXISTS ".'class_event_tb'.$_GET['id']; //删除数据表
$mysqli->query($sql_drop);

$sql_deleteSchool = "DELETE FROM school_tb WHERE id='$_GET[id]'";
$mysqli->query($sql_deleteSchool); //删除该运动会信息，以便新用户使用相同邮箱注册

$mysqli->close();

$prePage = $_GET["prepage"]; 
echo "<script>window.location.href='$prePage';</script>";
?>