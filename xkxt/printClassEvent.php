<?php
session_start();
include("conn.php");

$user_tb = 'user_tb' . $_SESSION['SschoolID'];
$class_tb = 'class_tb' . $_SESSION['SschoolID'];
$class_event_tb = 'class_event_tb' . $_SESSION['SschoolID'];
$eventName_tb = 'eventName_tb' . $_SESSION['SschoolID'];
$enroll_tb = 'enroll_tb' . $_SESSION['SschoolID'];
$sexArray = array('m'=>'男', 'f'=>'女', 'b'=>'男女');
$stuRangeArray = array('b'=>'全班', 'x'=>'全校');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>打印班级选课信息</title>
</head>

<body>

<?php
if (isset($_SESSION['SuserType']) && $_SESSION['SuserType'] == 'g')//系统管理员登陆
{
	echo '<table width="80%" border="1" align="center" cellpadding="0" cellspacing="0">'; 
	echo '<tr>';
	echo '<th align="center"> 序号</th>';
	echo '<th align="center"> 课程名称</th>';
	echo '<th align="center"> 课程类别</th>';
	echo '<th align="center"> 教师</th>';
	echo '<th align="center"> 地点</th>';
	echo '<th align="center"> 时间</th>';
	echo '<th align="center"> 性别</th>';
	echo '<th align="center"> 专业编号</th>';
	echo '<th align="center"> 年级</th>';
	echo '<th align="center"> 班级</th>';
	echo '<th align="center"> 学校限额</th>';
	echo '<th align="center"> 学校报名</th>';
	echo '<th align="center"> 班级限额</th>';
	echo '<th align="center"> 班级报名</th>';
	echo '<th align="center"> 抢课范围</th>';
	echo '</tr>';
   
	$queryString = "SELECT C.gradeName, C.className, C.majorNum, E.eventName, E.teacherName, E.actTime, E.address, E.sex, E.maxNum, E.trueNum, E.courseStyle, CE.stuRange, CE.maxNum AS maxNumClass, CE.trueNum AS trueNumClass, CE.eventID, CE.id  
				   FROM $class_tb AS C, 
				   $eventName_tb AS E, 
				   $class_event_tb AS CE 
				   WHERE CE.eventID=E.courseNum AND CE.classID=C.classNum AND CE.trueNum>'0' 
				   ORDER BY E.courseStyle, CE.eventID, C.classNum"; 
	
	$resultSearch = $mysqli->query($queryString);
	$total = $resultSearch->num_rows;
	
	$num = 1;
	if ($total==0)
	{
	   echo '<tr>';
	   echo '<th align="center" colspan="15" height="40">' . '对不起，暂无数据！' . '</th>';
	   echo '</tr>';
	   echo '</table>';
	}
	else
	{
		$result = $mysqli->query($queryString);
		while($row = $result->fetch_array()) 
		{
			echo '<tr>';
			echo '<td align="center">' . $num++ . '</td>';
			echo '<td align="center">' . $row['eventName'] . '</td>';
			echo '<td align="center">' . $row['courseStyle'] . '</td>';
			echo '<td align="center">' . $row['teacherName'] . '</td>';
			echo '<td align="center">' . $row['address'] . '</td>';
			echo '<td align="center">' . $row['actTime'] . '</td>';
			echo '<td align="center">' . $sexArray[$row['sex']] . '</td>';
			echo '<td align="center">' . $row['majorNum'] . '</td>';
			echo '<td align="center">' . $row['gradeName'] . '</td>';
			echo '<td align="center">' . $row['className'] . '</td>';
			echo '<td align="center">' . $row['maxNum'] . '</td>';
			echo '<td align="center">' . $row['trueNum'] . '</td>';
			echo '<td align="center">' . $row['maxNumClass'] . '</td>';
			echo '<td align="center">' . $row['trueNumClass'] . '</td>';
			echo '<td align="center">' . $stuRangeArray[$row['stuRange']] .  '</td>';
			echo '</tr>';
		}  //分页显示
		echo '</table>'; 
	}//esle
}
else
{
	echo "<h1>系统管理员尚未登录！请登录系统管理员账号！</h1>";
}
    $mysqli->close();
    ?>

</body>
</html>