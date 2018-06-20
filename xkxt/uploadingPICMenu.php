<?php
session_start();
include("conn.php");
$user_tb = 'user_tb' . $_SESSION['SschoolID'];
$class_tb = 'class_tb' . $_SESSION['SschoolID'];
$class_event_tb = 'class_event_tb' . $_SESSION['SschoolID'];
$eventName_tb = 'eventName_tb' . $_SESSION['SschoolID'];
$enroll_tb = 'enroll_tb' . $_SESSION['SschoolID'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>教师上传资料</title>
</head>

<body>
<?php
	echo '<table width="80%" border="1" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);font-size: 18px; border:solid 12px #dddddd">'; 
	echo '<tr>';
	echo '<th align="center" colspan="5" height="40"> 上传教师个人资料（请把相关资料做在一张照片上）</th>';
	echo '</tr>';
	echo '<tr>';
	echo '<th align="center" height="30"> 用户类型</th>';
	echo '<th align="center"> 性别</th>';
	echo '<th align="center"> 用户名</th>';
	echo '<th align="center"> 姓名</th>';
	echo '<th align="center"> 操作</th>';
	echo '</tr>';

	$sexArray = array('m'=>'男', 'f'=>'女');
	$queryString = "SELECT id, username, nickname, sex, pic FROM $user_tb WHERE username='$_SESSION[Susername]'";
	$result = $mysqli->query($queryString);
	if ($row = $result->fetch_array()) 
	{
		echo '<tr>';
		echo '<td align="center" height="30">' . '教师' . '</td>';
		echo '<td align="center">' . $sexArray[$row['sex']] . '</td>';
		echo '<td align="center">' . $row['username'] . '</td>';
		if ($row['pic'])
		{
			echo '<td align="center">'.'<a href="showPicture.php?num=js'.$row['id'].'" target="_blank">' .$row['nickname'] . '</a>' . '</td>';
			echo '<td align="center">'.'<a href="uploadingPIC.php?num=js'.$row['id'].'&username='.$row['username'].'&nickname='.$row['nickname'].'" target="_blank">' . '重新上传' . '</a>' . '</td>';
		}
		else
		{
			echo '<td align="center">' . $row['nickname'] . '</td>';
			echo '<td align="center">'.'<a href="uploadingPIC.php?num=js'.$row['id'].'&username='.$row['username'].'&nickname='.$row['nickname'].'" target="_blank">' . '上传图片' . '</a>' . '</td>';
		}
		echo '</tr>';
	}  
	else
	{
		echo '<tr>';
		echo "<div align=center>对不起，暂无数据！</div>";
		echo '</tr>';
	}
	echo '</table>'; 
?>
<?php
	echo '<table width="80%" border="1" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);font-size: 18px; border:solid 12px #dddddd">'; 
	echo '<tr>';
	echo '<th align="center" colspan="9" height="40"> 上传任教课程资料（请把相关资料做在一张照片上）</th>';
	echo '</tr>';
	echo '<tr>';
	echo '<th align="center" height="30"> 序号</th>';
	echo '<th align="center" height="30"> 课程类别</th>';
	echo '<th align="center"> 教师</th>';
	echo '<th align="center"> 地点</th>';
	echo '<th align="center"> 时间</th>';
	echo '<th align="center"> 性别</th>';
	echo '<th align="center"> 课程编号</th>';
	echo '<th align="center"> 课程名称</th>';
	echo '<th align="center"> 操作</th>';
	echo '</tr>';

	$sexArray = array('m'=>'男', 'f'=>'女', 'b'=>'男女');
	$queryString = "SELECT id, eventName, teacherName, actTime, address, courseNum, courseStyle, sex, pic FROM $eventName_tb WHERE teacherID='$_SESSION[Susername]'";
	$resultSearch = $mysqli->query($queryString);
	$total = $resultSearch->num_rows;
	
	$num = 1;
	if ($total==0)
	{
	   echo '<tr>';
	   echo "<div align=center>对不起，暂无数据！</div>";
	   echo '</tr>';
	}
	else
	{
		$result = $mysqli->query($queryString);
		while($row = $result->fetch_array()) 
		{
			echo '<tr>';
			echo '<td align="center" height="30">' . $num++ . '</td>';
			echo '<td align="center">' . $row['courseStyle'] . '</td>';
			echo '<td align="center">' . $row['teacherName'] . '</td>';
			echo '<td align="center">' . $row['address'] . '</td>';
			echo '<td align="center">' . $row['actTime'] . '</td>';
			echo '<td align="center">' . $sexArray[$row['sex']] . '</td>';
			echo '<td align="center">' . $row['courseNum'] . '</td>';
			if ($row['pic'])
			{
				echo '<td align="center">'.'<a href="showPicture.php?num=kc'.$row['id'].'" target="_blank">' .$row['eventName'] . '</a>' . '</td>';
				echo '<td align="center">'.'<a href="uploadingPIC.php?num=kc'.$row['id'].'&username='.$row['courseNum'].'&nickname='.$row['eventName'].'" target="_blank">' . '重新上传' . '</a>' . '</td>';
			}
			else
			{
				echo '<td align="center">' . $row['eventName'] . '</td>';
				echo '<td align="center">'.'<a href="uploadingPIC.php?num=kc'.$row['id'].'&username='.$row['courseNum'].'&nickname='.$row['eventName'].'" target="_blank">' . '上传图片' . '</a>' . '</td>';
			}
			echo '</tr>';
		}  //分页显示
	}  
	echo '</table>'; 
	$mysqli->close();
?>
</body>
</html>