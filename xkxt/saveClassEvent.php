<?php
session_start();
include("conn.php");

$user_tb = 'user_tb' . $_SESSION['SschoolID'];
$class_tb = 'class_tb' . $_SESSION['SschoolID'];
$class_event_tb = 'class_event_tb' . $_SESSION['SschoolID'];
$eventName_tb = 'eventName_tb' . $_SESSION['SschoolID'];
$enroll_tb = 'enroll_tb' . $_SESSION['SschoolID'];

$sql_truncate = "TRUNCATE TABLE $class_event_tb"; //清空数据表
$mysqli->query($sql_truncate);

$sql_selectEvent = "SELECT courseNum, maxNum, limitmajor1, limitmajor2, limitmajor3, limitmajor4, limitmajor5, limitmajor6 
					FROM $eventName_tb" . "  
					ORDER BY id";
$resultEvent = $mysqli->query($sql_selectEvent);
while ($rowEvent = $resultEvent->fetch_array()) 
{
	for ($i=1; $i<=6; $i++)
	{
		$eventID = $rowEvent['courseNum'];
		$limitmajor = 'limitmajor' . $i;
		
		$sql_selectClass = "SELECT classNum FROM $class_tb WHERE majorNum = '$i'";
		$resultClass = $mysqli->query($sql_selectClass);
		while ($rowClass = $resultClass->fetch_array()) 
		{
			$classID = $rowClass['classNum'];
			$maxNum = $rowEvent[$limitmajor]; 
			if ($rowEvent[$limitmajor] < $rowEvent['maxNum'])
			{
				$stuRange = 'b'; //面向全班
			}
			else //当班级报名限额等于学校报名限额时，默认为该课程向全校开放，全校学生一起抢
			{
				$stuRange = 'x'; //面向全校
			}
			
			$sql_insert = "INSERT INTO $class_event_tb(eventID, classID, maxNum, stuRange)
						   VALUES ('$eventID', '$classID', '$maxNum', '$stuRange')";  
			$mysqli->query($sql_insert);
		}
	}
}

$mysqli->close();

echo "<script>window.location.href='manageClassEvent.php';</script>"; 
?>