<?php
session_start();
include("conn.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>班级模板</title>

</head>

<body>
<?php
if (isset($_SESSION['SuserType']) && $_SESSION['SuserType'] == 'g')//系统管理员登陆
{
	echo '<form id="form2" name="form2" method="post" action="saveClassMode.php">';
	echo '<table width="80%" border="1" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);font-size: 12px; border:solid 2px #dddddd">';
	echo '<tr>';
	echo '<th colspan="3" align="center"><h3>班级模板</h3></th>';
	echo '<th colspan="2" align="center"><a href="uploadingMenu.php">不做修改，直接返回</a></th>';
	echo '<th colspan="1" align="center"><input type="submit" name="submit" id="submit" value="提交修改" /></th>';
	echo '</tr>';
	echo '<tr>';
	echo '<th height="30"> 班级编号</th>';
	echo '<th>年 级</th>';
	echo '<th>专业编号</th>';
	echo '<th>班 级</th>';
	echo '<th>班主任</th>';
	echo '<th>人 数</th>';
	echo '</tr>';

    $class_tb = 'class_tb' . $_SESSION['SschoolID'];
	$sql_select = "SELECT * FROM $class_tb";
	$result = $mysqli->query($sql_select);
	while ($row = $result->fetch_array()) 
	{  
		echo '<tr>';
		echo '<td align="center">' .$row['classNum'] . '</td>';
		
		//传送级段
		$gradeNameid = 'gradeName' . $row['id'];
		$gradeName = $row['gradeName'];
		echo '<td align="center">' . '<input type="text" name="' . $gradeNameid . '" id="' . $gradeNameid . '" value="' . $gradeName . '" size="4"/>' . '</td>'; 
		
		//传送专业编号
		$majorNumid = 'majorNum' . $row['id'];
		$majorNum = $row['majorNum'];
		echo '<td align="center">' . '<input type="text" name="' . $majorNumid . '" id="' . $majorNumid . '" value="' . $majorNum . '" size="4"/>' . '</td>'; 
		
		//传送班级
		$classNameid = 'className' . $row['id'];
		$className = $row['className'];
		echo '<td align="center">' . '<input type="text" name="' . $classNameid . '" id="' . $classNameid . '" value="' . $className . '" size="5"/>' . '</td>';
		
		//传送班主任
		$leaderid = 'leader' . $row['id'];
		$leader = $row['leader'];
		echo '<td align="center">' . '<input type="text" name="' . $leaderid . '" id="' . $leaderid . '" value="' . $leader . '" size="4"/>' . '</td>';
		
		//传送人数
		$studentsNumid = 'studentsNum' . $row['id'];
		$studentsNum = $row['studentsNum'];
		echo '<td align="center">' . '<input type="text" name="' . $studentsNumid . '" id="' . $studentsNumid . '" value="' . $studentsNum . '" size="4"/>' . '</td>';
	
		echo '</tr>';
	}
	echo '</table>';
	echo '</form>';
}
else
{
	echo "<h1>管理员尚未登录！请登录管理员账号！</h1>";
}

$mysqli->close();
  ?>
  
</body>
</html>