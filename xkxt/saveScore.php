<?php
session_start();
@header("content-type:text/html;charset=utf-8");
include("conn.php");

$user_tb = 'user_tb' . $_SESSION['SschoolID'];
$class_tb = 'class_tb' . $_SESSION['SschoolID'];
$class_event_tb = 'class_event_tb' . $_SESSION['SschoolID'];
$eventName_tb = 'eventName_tb' . $_SESSION['SschoolID'];
$enroll_tb = 'enroll_tb' . $_SESSION['SschoolID'];

$sql_select = "SELECT permission FROM $user_tb WHERE username='$_SESSION[Susername]'"; 
$resultUser = $mysqli->query($sql_select);
if ($rowUser = $resultUser->fetch_array()) 
{ 
	if ($rowUser['permission'] != '1')
	{
		echo "<script>alert('选课系统尚未解锁！');</script>";
		echo "<script>window.location.href='index.php';</script>";
	}
}

$maxID = 0; //enroll_tb中的最大id数
$sql_select = "SELECT MAX(id) AS maxID FROM $enroll_tb"; 
$resultEnroll = $mysqli->query($sql_select);
if ($rowEnroll = $resultEnroll->fetch_array()) 
{ 
	$maxID = $rowEnroll['maxID'];
}

if (isset($_POST['submitScore']))
{ 
	for ($i=1; $i<=$maxID; $i++)
	{
		$enrollID = 'enroll' . $i; 
		$crunkNumID = 'crunkNum' . $i; 
		$scoreID = 'score' . $i; 
		$levelID = 'level' . $i; 
		$creditID = 'credit' . $i; 
		
		if (isset($_POST["$enrollID"]))
		{
			$sql_update = "UPDATE $enroll_tb SET crunkNum='$_POST[$crunkNumID]', score='$_POST[$scoreID]', level='$_POST[$levelID]', credit='$_POST[$creditID]' WHERE id='$_POST[$enrollID]'";
			if (!$mysqli->query($sql_update)) //更新该生的成绩
			{
				echo "<script>alert('登分失败！');</script>";
			}
		}
	}
}

$mysqli->close();
echo "<script>alert('登分成功！');</script>"; 
echo "<script>window.location.href='teacherMenu.php';</script>";
?>