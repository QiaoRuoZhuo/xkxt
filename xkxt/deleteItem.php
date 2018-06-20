<?php
session_start();
include("conn.php");

$id = $_GET["id"];
$prePage = $_GET["prepage"]; 
$user_tb = 'user_tb' . $_SESSION['SschoolID'];
$class_tb = 'class_tb' . $_SESSION['SschoolID'];
$class_event_tb = 'class_event_tb' . $_SESSION['SschoolID'];
$eventName_tb = 'eventName_tb' . $_SESSION['SschoolID'];
$enroll_tb = 'enroll_tb' . $_SESSION['SschoolID'];

$sql_select = "SELECT eventID, classID FROM $enroll_tb WHERE id='$id'"; 
$result = $mysqli->query($sql_select);
if ($rowEnroll = $result->fetch_array()) //找到要删除的课程
{
	$sql_Delete = "DELETE FROM $enroll_tb WHERE id='$id'";
	if ($mysqli->query($sql_Delete))
	{
		$sql_updateClassEvent = "UPDATE $class_event_tb SET trueNum=trueNum-1 WHERE classID='$rowEnroll[classID]' AND eventID='$rowEnroll[eventID]'";  
		
		$sql_updateEvent = "UPDATE $eventName_tb SET trueNum=trueNum-1 WHERE courseNum='$rowEnroll[eventID]'";
		if ($mysqli->query($sql_updateClassEvent) && $mysqli->query($sql_updateEvent)) //被删除课程的报名人数减一
		{
			echo "<script>alert('OK');</script>";
		}
		else
		{
			echo "<script>alert('FALSE');</script>";
		}//else
	}
	else
	{
		echo "<script>alert('FALSE');</script>";
	}//else
}
echo "<script>window.location.href='$prePage';</script>";
?>