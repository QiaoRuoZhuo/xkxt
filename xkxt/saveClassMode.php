<?php
session_start();
@header("content-type:text/html;charset=utf-8");
include("conn.php");

if ($_POST['submit'])
{ 
    $class_tb = 'class_tb' . $_SESSION['SschoolID'];
	$sql_select = "SELECT id FROM $class_tb";
	$result = $mysqli->query($sql_select);
	while ($row = $result->fetch_array()) 
	{
		$gradeNameid = 'gradeName' . $row['id'];
		$majorNumid = 'majorNum' . $row['id'];
		$classNameid = 'className' . $row['id'];
		$leaderid = 'leader' . $row['id'];
		$studentsNumid = 'studentsNum' . $row['id'];
		
		$sql_update = "UPDATE $class_tb SET gradeName = '" . $_POST["$gradeNameid"] .
		           "', majorNum = '" . $_POST["$majorNumid"] .
				   "', className = '" . $_POST["$classNameid"] . 
				   "', leader = '" . $_POST["$leaderid"] . 
				   "', studentsNum = '" . $_POST["$studentsNumid"] . 
				    "' WHERE id = '" . $row['id'] ."'";
		if (!$mysqli->query($sql_update))
		{
			echo "<script>alert('更新班级模板失败！');</script>";
		}
	}
	
	$mysqli->close();
	echo "<script>alert('保存班级模板成功！');</script>";
	echo "<script>window.location.href='uploadingMenu.php';</script>"; 
}
?>