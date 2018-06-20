<?php
session_start();
include("conn.php"); 
set_time_limit(0);

$user_tb = 'user_tb' . $_SESSION['SschoolID'];
$class_tb = 'class_tb' . $_SESSION['SschoolID'];
$class_event_tb = 'class_event_tb' . $_SESSION['SschoolID'];
$eventName_tb = 'eventName_tb' . $_SESSION['SschoolID'];
$enroll_tb = 'enroll_tb' . $_SESSION['SschoolID'];

$sexArray = array('m'=>'男', 'f'=>'女'); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>打印报名信息</title>
</head>

<body>
<?php  
if (isset($_SESSION['SuserType']) && $_SESSION['SuserType'] == 'g')//系统管理员登陆
{
	echo '<table width="80%" border="1" align="center" cellpadding="0" cellspacing="0">'; 
	echo '<tr>';
	echo '<th> 专业编号</th>';
	echo '<th> 班级</th>';
	echo '<th> 准考证号</th>';
	echo '<th> 姓名</th>';
	echo '<th> 性别</th>';
	echo '<th> 课程</th>';
	echo '<th> 任课教师</th>';
	echo '<th> 时间</th>';
	echo '<th> 地点</th>';
	echo '<th> 剩余学期</th>';
	echo '<th> 报名时间</th>';
	echo '<th> 旷课数</th>';
    echo '<th> 成绩</th>';
    echo '<th> 等级</th>';
    echo '<th> 学分</th>';
	echo '</tr>';
	
	if ($_GET['changeFlag'] == '2' || $_GET['changeFlag'] == '3')
	{
		$queryString = "SELECT EN.courseNum, EN.eventName, EN.teacherName, EN.actTime, EN.address, E.restOfTerm, E.upTime, C.majorNum, C.className, C.gradeName, U.username, U.nickname, U.sex, E.id, E.score, E.level, E.credit, E.crunkNum     
					   FROM $eventName_tb AS EN, 
					   $user_tb AS U, 
					   $enroll_tb AS E, 
					   $class_tb AS C
					   WHERE E.eventID=EN.courseNum AND E.classID=C.classNum AND E.userID=U.username AND E.changeFlag='$_GET[changeFlag]'                       
					   ORDER BY U.userType, C.id, U.id"; 
	}
	else
	{
		$queryString = "SELECT EN.courseNum, EN.eventName, EN.teacherName, EN.actTime, EN.address, E.restOfTerm, E.upTime, C.majorNum, C.className, C.gradeName, U.username, U.nickname, U.sex, E.id, E.score, E.level, E.credit, E.crunkNum     
					   FROM $eventName_tb AS EN, 
					   $user_tb AS U, 
					   $enroll_tb AS E, 
					   $class_tb AS C
					   WHERE E.eventID=EN.courseNum AND E.classID=C.classNum AND E.userID=U.username AND (E.changeFlag='0' OR E.changeFlag='1')                        
					   ORDER BY U.userType, C.id, U.id"; 
	}
	
	$resultSearch = $mysqli->query($queryString);
	$total = $resultSearch->num_rows;
	
	$num = 1;
	if ($total==0)
	{
	   echo '<tr>';
	   echo '<th align="center" colspan="16" height="40">' . '对不起，暂无数据！' . '</th>';
	   echo '</tr>';
	   echo '</table>';
	}
	else
	{
		$result = $mysqli->query($queryString);
		while($row = $result->fetch_array()) 
		{
			echo '<tr>';
			echo '<td align="center">' . $row['majorNum'] . '</td>';
			echo '<td align="center">' . $row['gradeName'] . '(' . $row['className'] . ')' . '</td>';
			echo '<td align="center">' . $row['username'] . '</td>';
			echo '<td align="center">' . $row['nickname'] . '</td>';
			echo '<td align="center">' . $sexArray[$row['sex']] . '</td>';
			echo '<td align="center">' . $row['eventName'] . '(' . $row['courseNum'] . ')' . '</td>';
			echo '<td align="center">' .$row['teacherName'] . '</td>';
			echo '<td align="center">' .$row['actTime'] . '</td>';
			echo '<td align="center">' .$row['address'] . '</td>';
			echo '<td align="center">' .$row['restOfTerm'] . '</td>';
			echo '<td align="center">' . substr($row['upTime'], 0, 10) . '</td>'; 
			echo '<td align="center">' .$row['crunkNum'] . '</td>'; 
			echo '<td align="center">' .$row['score'] . '</td>'; 
			echo '<td align="center">' .$row['level'] . '</td>'; 
			echo '<td align="center">' .$row['credit'] . '</td>'; 
			
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