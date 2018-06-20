<?php
//存储学校比赛信息
function DownloadSchoolDataLib($schoolID)
{	
	if(!isset($_SESSION))
	{
		session_start();
	}
	include("conn.php"); 
	
	$user_tb = 'user_tb' . $schoolID;
	$class_tb = 'class_tb' . $schoolID;
	$class_event_tb = 'class_event_tb' . $schoolID;
	$eventName_tb = 'eventName_tb' . $schoolID;
	$enroll_tb = 'enroll_tb' . $schoolID;
	
	$sexArray = array('m'=>'男', 'f'=>'女'); 
	
	$sql_select = "SELECT schoolName FROM school_tb WHERE id = '$schoolID'";
	$result = $mysqli->query($sql_select);
	if($row = $result->fetch_array()) 
	{
		$schoolName = $row['schoolName'];
		$fileName = $schoolName . "选修课统计表.xls"; //文件的存储路径和名称

		echo '<table width="80%" border="1" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);font-size: 12px; border:solid 10px #dddddd">'; 
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
	
		$queryString = "SELECT EN.courseNum, EN.eventName, EN.teacherName, EN.actTime, EN.address, E.restOfTerm, E.upTime, C.majorNum, C.className, C.gradeName, U.username, U.nickname, U.sex, E.id, E.score, E.level, E.credit, E.crunkNum, EN.id AS kcid, EN.pic AS kcpic 
					   FROM $eventName_tb AS EN, 
					   $user_tb AS U, 
					   $enroll_tb AS E, 
					   $class_tb AS C
					   WHERE E.eventID=EN.courseNum AND E.classID=C.classNum AND E.userID=U.username";  
		$resultSearch = $mysqli->query($queryString);
		$total = $resultSearch->num_rows;
		if ($total > 0)
		{
			ob_start();
			header("Content-type:application/vnd.ms-excel"); 
			header("Content-Disposition:filename=$fileName"); 
			$queryString .= " ORDER BY U.userType, C.id, U.id";  
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
				echo '<td align="center">' . $row['teacherName'] . '</td>';
				echo '<td align="center">' .$row['actTime'] . '</td>';
				echo '<td align="center">' .$row['address'] . '</td>';
				echo '<td align="center">' .$row['restOfTerm'] . '</td>';
				echo '<td align="center">' . substr($row['upTime'], 0, 10) . '</td>'; 
				echo '<td align="center">' .$row['crunkNum'] . '</td>'; 
				echo '<td align="center">' .$row['score'] . '</td>'; 
				echo '<td align="center">' .$row['level'] . '</td>'; 
				echo '<td align="center">' .$row['credit'] . '</td>'; 
				echo '</tr>';
			}  
			echo '</table>'; 
			ob_end_flush();//输出全部内容到浏览器
		} //if ($total > 0)
	}//查找学校名称
}
?>


