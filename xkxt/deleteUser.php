<?php
session_start();
include("conn.php");

$id = $_GET["id"];
$prePage = $_GET["prepage"]; 

$user_tb = 'user_tb' . $_SESSION['SschoolID'];
$sql_deleteUser = "DELETE FROM $user_tb WHERE id='$id'"; 
$mysqli->query($sql_deleteUser); //删除该运动员所有报名信息
$mysqli->close();

echo "<script>window.location.href='$prePage';</script>";
?>