<?php
session_start();
include("conn.php");

$sql_drop = "DROP TABLE IF EXISTS ".'class_tb'.$_GET['id']; //ɾ�����ݱ�
$mysqli->query($sql_drop);
$sql_drop = "DROP TABLE IF EXISTS ".'enroll_tb'.$_GET['id'];  //ɾ�����ݱ�
$mysqli->query($sql_drop);
$sql_drop = "DROP TABLE IF EXISTS ".'eventName_tb'.$_GET['id'];//ɾ�����ݱ�
$mysqli->query($sql_drop);
$sql_drop = "DROP TABLE IF EXISTS ".'user_tb'.$_GET['id'];//ɾ�����ݱ�
$mysqli->query($sql_drop);
$sql_drop = "DROP TABLE IF EXISTS ".'class_event_tb'.$_GET['id']; //ɾ�����ݱ�
$mysqli->query($sql_drop);

$sql_deleteSchool = "DELETE FROM school_tb WHERE id='$_GET[id]'";
$mysqli->query($sql_deleteSchool); //ɾ�����˶�����Ϣ���Ա����û�ʹ����ͬ����ע��

$mysqli->close();

$prePage = $_GET["prepage"]; 
echo "<script>window.location.href='$prePage';</script>";
?>