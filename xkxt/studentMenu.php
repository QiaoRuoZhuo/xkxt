<?php
session_start();
@header("content-type:text/html;charset=utf-8");
include("conn.php");

$courseLimitArr = array(0,0,0,0,0,0,0,0,0);
$user_tb = 'user_tb' . $_SESSION['SschoolID'];
$class_tb = 'class_tb' . $_SESSION['SschoolID'];
$class_event_tb = 'class_event_tb' . $_SESSION['SschoolID'];
$eventName_tb = 'eventName_tb' . $_SESSION['SschoolID'];
$enroll_tb = 'enroll_tb' . $_SESSION['SschoolID'];

$sql_select = "SELECT * FROM $user_tb WHERE username='$_SESSION[Susername]'"; 
$resultUser = $mysqli->query($sql_select);
if ($rowUser = $resultUser->fetch_array()) 
{ 
	if ($rowUser['permission'] != '1')
	{
		echo "<script>alert('选课系统尚未解锁！');</script>";
		echo "<script>window.location.href='index.php';</script>";
	}
	else
	{
		for ($i=1; $i<=8; $i++) //存储该生各课程选课限额
		{
			$courseNum = 'course' . $i;
			$courseLimitArr[$i] = $rowUser[$courseNum];
		}
	}
} 

$sexArray = array('m'=>'男', 'f'=>'女', 'b'=>'男女');
$stuRangeArray = array('b'=>'全班', 'x'=>'全校');
$courseEnrollArr = array(0,0,0,0,0,0,0,0,0); //已报名或可换选科目
$courseDoneArr = array(0,0,0,0,0,0,0,0,0); //在修科目

if (isset($_GET["courseNum"]) && isset($_GET["classEventID"]) && isset($_GET["restOfTermNum"]) && isset($_GET["courseStyle"])) //报名该课程，插入新报名信息
{  
	$sql_select = "SELECT id FROM $enroll_tb WHERE userID='" . $_SESSION['Susername'] . "' AND eventID='" . $_GET["courseNum"] . "'";  
	$resultDone = $mysqli->query($sql_select);
	if ($resultDone->num_rows > 0)//该生已经选修过该课程（根据课程id判断）
	{
	    echo "<script>alert('你已经选修过该课程！');</script>";
	}
	else
	{
		$sql_select =  "SELECT E.id 
					   FROM $eventName_tb AS EN, 
					   $enroll_tb AS E 
					   WHERE E.userID='$_SESSION[Susername]' AND E.eventID=EN.courseNum AND EN.courseStyle='$_GET[courseStyle]'";
		$resultEnroll = $mysqli->query($sql_select); //查找该生的该类课程
		if ($courseLimitArr[$_GET["courseStyle"]] <= $resultEnroll->num_rows)
		{
			echo "<script>alert('第" . $_GET["courseStyle"] . "类课程你已经选满了！');</script>";
		}
		else
		{
			$sql_select = "SELECT maxNum, trueNum FROM $class_event_tb WHERE id='$_GET[classEventID]'"; 
			$resultClassEvent = $mysqli->query($sql_select);
			if ($rowClassEvent = $resultClassEvent->fetch_array()) 
			{
				if ($rowClassEvent['maxNum'] <= $rowClassEvent['trueNum']) //没有余额
				{
					echo "<script>alert('反应慢了点，被人抢了！');</script>";
				}
				else 
				{
					$upTime = date("Y-m-d H:i:s");
					$sql_insert = "INSERT INTO $enroll_tb" . 
								" (userID, classID, eventID, restOfTerm, upTime) 
								  VALUES ('$_SESSION[Susername]', '$_SESSION[SclassID]', '$_GET[courseNum]', '$_GET[restOfTermNum]', '$upTime')";  
					if ($mysqli->query($sql_insert))
					{
						$sql_updateClassEvent = "UPDATE $class_event_tb SET trueNum=trueNum+1 WHERE id='$_GET[classEventID]'";  					
						$sql_updateEvent = "UPDATE $eventName_tb SET trueNum=trueNum+1 WHERE courseNum='$_GET[courseNum]'";
						if ($mysqli->query($sql_updateClassEvent) && $mysqli->query($sql_updateEvent)) //课程的报名人数加一
						{
							echo "<script>alert('课程" . $_GET["courseNum"] . "报名成功!');</script>";
						}
						else
						{
							echo "<script>alert('更新报名人数失败！');</script>";
						}//else
					}
					else
					{
						echo "<script>alert('课程" . $_GET["courseNum"] . "报名失败!');</script>";
					}//else
				}//else
			}//if
		}//else
	}//else
	echo "<script>window.location.href='studentMenu.php';</script>"; 
} 
else if (isset($_GET["newCourseNum"]) && isset($_GET["classEventID"]) && isset($_GET["restOfTermNum"]) && isset($_GET["courseStyle"])) //该生改报同类型课程
{
	$sql_select =  "SELECT E.id, E.eventID    
		   FROM $eventName_tb AS EN, 
		   $enroll_tb AS E 
		   WHERE E.userID='$_SESSION[Susername]' AND E.eventID=EN.courseNum AND E.changeFlag='1' AND EN.courseStyle='$_GET[courseStyle]'";
	$resultEnroll = $mysqli->query($sql_select);
	if ($rowEnroll = $resultEnroll->fetch_array()) //查找该生的该类课程的可换选课程，并换选该课程
	{
		$sql_select = "SELECT maxNum, trueNum FROM $class_event_tb WHERE id='$_GET[classEventID]'";
		$resultClassEvent = $mysqli->query($sql_select);
		if ($rowClassEvent = $resultClassEvent->fetch_array()) //看看想换选的新课程满额没有
		{
			if ($rowClassEvent['maxNum'] <= $rowClassEvent['trueNum']) //没有余额
			{
				echo "<script>alert('反应慢了点，换课不成功！');</script>";
			}
			else //换选该课程
			{
				$upTime = date("Y-m-d H:i:s");
				$sql_update = "UPDATE $enroll_tb SET upTime='$upTime', changeFlag='0', eventID='$_GET[newCourseNum]', restOfTerm='$_GET[restOfTermNum]' WHERE id='$rowEnroll[id]'";  
				if ($mysqli->query($sql_update)) //更新该生的新选课程编号和剩余学期数
				{
					$sql_updateClassEvent = "UPDATE $class_event_tb SET trueNum=trueNum-1 WHERE classID='$_SESSION[SclassID]' AND eventID='$rowEnroll[eventID]'";  					
					$sql_updateEvent = "UPDATE $eventName_tb SET trueNum=trueNum-1 WHERE courseNum='$rowEnroll[eventID]'";
					if ($mysqli->query($sql_updateClassEvent) && $mysqli->query($sql_updateEvent)) //被换选课程的报名人数减一
					{
						$sql_updateClassEvent = "UPDATE $class_event_tb SET trueNum=trueNum+1 WHERE id='$_GET[classEventID]'";  					
						$sql_updateEvent = "UPDATE $eventName_tb SET trueNum=trueNum+1 WHERE courseNum='$_GET[newCourseNum]'";
						if ($mysqli->query($sql_updateClassEvent) && $mysqli->query($sql_updateEvent)) //换选课程的报名人数加一
						{
							echo "<script>alert('换课成功！');</script>";
						}
						else
						{
							echo "<script>alert('增加报名人数失败！');</script>";
						}//else
					}
					else
					{
						echo "<script>alert('减少报名人数失败！');</script>";
					}//else
				}
				else
				{
					echo "<script>alert('更新课程名称失败！');</script>";
				}//else
			}//else //换选该课程
		}//if 
	}
	else
	{
		echo "<script>alert('该类课程无可换选科目');</script>";
	}
	echo "<script>window.location.href='studentMenu.php';</script>"; 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>学生选课页面</title>
<style type="text/css">
<!--
.tishi {
	font-size: 12px;
	color: #F00;
}
-->
</style>
</head>

<body>
<table width="800" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
     <td><?php include("head.php"); ?></td>
    </tr>
</table>

<?php
if (isset($_SESSION['SuserType']) && $_SESSION['SuserType'] == 'x')
{
?>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);border:solid 10px #dddddd;font-size:14px">
  <tr>
     <th align="center" height="30"> <?php echo "热烈欢迎 " . $_SESSION['Snickname'] . "  祝您学习进步！"; ?> </th>
     <th align="center" width="100"><a href="updatePSW.php?prepage=studentMenu.php">修改密码</a></th>
     <th align="center">系统分配的原始密码不安全，请尽快修改密码！！</th>
    </tr>
     <tr>
        <th colspan="3" align="center" height="30">若要换选课程，可点击下表中对应课程状态，由“已报名”改成“可换选”</th>
    </tr>
     <tr>
        <th colspan="3" align="center">&nbsp;</th>
    </tr>
    <tr>
      <td colspan="3" height="90"><table width="100%" height="100%" border="1" cellspacing="0" cellpadding="0">
      <tr>
		<th> 班级</th>
        <th> 姓名</th>
        <th> 性别</th>
        <th> 课程名称</th>
        <th> 课程类别</th>
        <th> 任课教师</th>
        <th> 时间</th>
        <th> 地点</th>
        <th> 剩余学期</th>
        <th> 状态</th>
        <th> 操作</th>
        </tr>
    <?php
	$queryString = "SELECT C.gradeName, C.className, EN.eventName, EN.teacherName, EN.actTime, EN.address, EN.courseStyle, E.restOfTerm, E.id, E.changeFlag, EN.id AS kcid, EN.pic AS kcpic  
				   FROM $class_tb AS C,  
				   $eventName_tb AS EN, 
				   $enroll_tb AS E 
				   WHERE E.userID='$_SESSION[Susername]' AND E.eventID=EN.courseNum AND E.classID=C.classNum     
				   ORDER BY E.restOfTerm, E.upTime"; //查找该生在修科目

	$resultSearch = $mysqli->query($queryString);
	$total = $resultSearch->num_rows;
	
	if ($total==0)
	{
	   echo '<tr>';
	   echo '<td colspan="11" align="center">没有选课信息！</td>';
	   echo '</tr>';
	}
	else
	{
		$result = $mysqli->query($queryString);
		while($row = $result->fetch_array()) 
		{
			if ($row['changeFlag'] == '0' || $row['changeFlag'] == '1')//统计某课程类型的已选可换选或待换选课程数量
			{
				$courseEnrollArr[$row['courseStyle']]++;
			}
			else if ($row['changeFlag'] == '2')//统计某课程类型的在修课程数量
			{
				$courseDoneArr[$row['courseStyle']]++;
			}
			
			echo '<tr>';
			echo '<td align="center">' . $row['gradeName'] . '(' . $row['className'] . ')' . '</td>';
			echo '<td align="center">' .$_SESSION['Snickname'] . '</td>';
			echo '<td align="center">' .$sexArray[$_SESSION['Ssex']] . '</td>';
			if ($row['kcpic'])
			{
				echo '<td align="center">'.'<a href="showPicture.php?num=kc'.$row['kcid'].'" target="_blank">' .$row['eventName'] . '</a>' . '</td>';
			}
			else
			{
				echo '<td align="center">' . $row['eventName'] . '</td>';
			}
			
			echo '<td align="center">' .$row['courseStyle'] .  '</td>';
			
			$sql_js = "SELECT id, pic FROM $user_tb WHERE userType='j' AND nickname='$row[teacherName]'";
			$result_js = $mysqli->query($sql_js);
			if($row_js = $result_js->fetch_array()) 
			{
				if ($row_js['pic'])
				{
					echo '<td align="center">'.'<a href="showPicture.php?num=js'.$row_js['id'].'" target="_blank">' .$row['teacherName'] . '</a>' . '</td>';
				}
				else
				{
					echo '<td align="center">' . $row['teacherName'] . '</td>';
				}
			}
			else
			{
				echo '<td align="center">' . $row['teacherName'] . '</td>';
			}
			
			echo '<td align="center">' .$row['actTime'] . '</td>';
			echo '<td align="center">' .$row['address'] . '</td>';
			echo '<td align="center">' .$row['restOfTerm'] . '</td>';
			
			if ($row['changeFlag'] == '0')//该课程处于已报名状态，可点击将其换成可换选状态，同时同类型课程由可换选状态改为已报名状态
			{
				echo '<td align="center">' .'<a href="javascript:if(confirm(' . "'确定修改选课状态吗?'))location='updateEnrollChangeFlag.php?id=" . $row['id'] .'&prepage='.$_SERVER['PHP_SELF'].'&changeFlag='."0'" .'">已报名</a>' . '</td>';
			}
			else if ($row['changeFlag'] == '1')  //该课程处于可换选状态，可用同类型课程换选该课程
			{
				echo '<td align="center">' .'<a href="javascript:if(confirm(' . "'确定修改选课状态吗?'))location='updateEnrollChangeFlag.php?id=" . $row['id'] .'&prepage='.$_SERVER['PHP_SELF'].'&changeFlag='."1'" .'">可换选</a>' . '</td>';
			}
			else if ($row['changeFlag'] == '2') //该课程处于在修状态，不可换选
			{
				echo '<td align="center">' .'在修' . '</td>';
			}
			else if ($row['changeFlag'] == '3') //该课程处于已修状态，不可换选
			{
				echo '<td align="center">' .'已修' . '</td>';
			}
			
			if ($row['changeFlag'] == '0' || $row['changeFlag'] == '1') //允许删除该课程
			{
				echo '<td align="center">' .'<a href="javascript:if(confirm(' . "'确定删除文件吗?'))location='deleteItem.php?id=" . $row['id'] .'&prepage='.$_SERVER['PHP_SELF']."'" .'">删除</a>' . '</td>';
			}
			else
			{
				echo '<td align="center">&nbsp;</td>';
			}
			echo '</tr>';
		}  
	}//esle
    ?>
    </table></td>
    </tr>
</table>

<table width="800" border="1" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);border:solid 10px #dddddd;font-size:14px">
    <tr>
		<th colspan="11" align="center"> <h2>课程安排表</h2></th>
    </tr>
    <tr>
        <th> 编号</th>
		<th> 课程名称</th>
        <th> 课程类别</th>
        <th> 任课教师</th>
        <th> 时间</th>
        <th> 地点</th>
        <th> 学期</th>
        <th> 限额</th>
        <th> 余额</th>
        <th> 面向对象</th>
        <th> 操作</th>
    </tr>
    <?php  
    $queryString = "SELECT EN.courseNum, EN.eventName, EN.teacherName, EN.actTime, EN.address, EN.termNum, EN.sex, EN.courseStyle, CE.stuRange, CE.maxNum, CE.trueNum, CE.id, EN.maxNum AS SmaxNum, EN.trueNum AS StrueNum, EN.id AS kcid, EN.pic AS kcpic   
				   FROM $eventName_tb AS EN, 
				   $class_event_tb AS CE 
				   WHERE CE.eventID=EN.courseNum AND CE.classID='$_SESSION[SclassID]' AND CE.maxNum>'0' 
				   ORDER BY EN.courseStyle"; 
	$courseResult = $mysqli->query($queryString);
	while($courseRow = $courseResult->fetch_array()) 
	{
		$allowFlag = true; //允许报名标记
		if ($courseRow['sex'] != 'b' && $courseRow['sex'] != $_SESSION['Ssex'])
		{//该生性别不符合课程要求，不允许报名
			$allowFlag = false;
		}
		else //判断该生是否已经选过该课程
		{
			$sql_select = "SELECT id FROM $enroll_tb WHERE userID='" . $_SESSION['Susername'] . "' AND eventID='" . $courseRow['courseNum'] . "'";  
			$resultDone = $mysqli->query($sql_select);
			if ($resultDone->num_rows > 0)//该生已经选修过该课程（根据课程id判断）
			{
			   $allowFlag = false;
			}
		}
		
		if ($allowFlag == false || $courseLimitArr[$courseRow['courseStyle']] <= $courseDoneArr[$courseRow['courseStyle']]) //该课程不允许该生报名或者改报，或该类课程该生在修课程已满，则不显示该课程
		{
			continue;
		}
		
		$surplus = $courseRow['maxNum'] - $courseRow['trueNum']; //班级余额
		$surplusSchool = $courseRow['SmaxNum'] - $courseRow['StrueNum']; //学校余额
		if ($surplus > $surplusSchool)//以学校余额为准
		{
			$surplus = $surplusSchool;
		}
		
		echo '<tr>';
		echo '<td align="center">' . $courseRow['courseNum'] . '</td>';
		
		if ($courseRow['kcpic'])
		{
			echo '<td align="center">'.'<a href="showPicture.php?num=kc'.$courseRow['kcid'].'" target="_blank">' .$courseRow['eventName'] . '</a>' . '</td>';
		}
		else
		{
			echo '<td align="center">' . $courseRow['eventName'] . '</td>';
		}
		
		echo '<td align="center">' .$courseRow['courseStyle'] .  '</td>';
		
		$sql_js = "SELECT id, pic FROM $user_tb WHERE userType='j' AND nickname='$courseRow[teacherName]'";
		$result_js = $mysqli->query($sql_js);
		if($row_js = $result_js->fetch_array()) 
		{
			if ($row_js['pic'])
			{
				echo '<td align="center">'.'<a href="showPicture.php?num=js'.$row_js['id'].'" target="_blank">' .$courseRow['teacherName'] . '</a>' . '</td>';
			}
			else
			{
				echo '<td align="center">' . $courseRow['teacherName'] . '</td>';
			}
		}
		else
		{
			echo '<td align="center">' . $courseRow['teacherName'] . '</td>';
		}
			
		echo '<td align="center">' . $courseRow['actTime'] . '</td>';
		echo '<td align="center">' . $courseRow['address'] . '</td>';
		echo '<td align="center">' . $courseRow['termNum'] . '</td>';
		echo '<td align="center">' . $courseRow['maxNum'] . '</td>';//该课程允许该班级报名人数限额
		echo '<td align="center">' . $surplus . '</td>';//该课程允许该班级报名人数余额
		echo '<td align="center">' . $stuRangeArray[$courseRow['stuRange']] . $sexArray[$courseRow['sex']] . '</td>';
		
		if ($surplus <= 0) //余额为0
		{
			echo '<td align="center">满额' . '</td>';
		}
		else
		{
			$sql_select =  "SELECT E.changeFlag, EN.courseStyle  
						   FROM $eventName_tb AS EN, 
						   $enroll_tb AS E 
						   WHERE E.userID='$_SESSION[Susername]' AND E.eventID=EN.courseNum AND E.restOfTerm>'0' AND EN.actTime='$courseRow[actTime]'"; 
			$resultClash = $mysqli->query($sql_select); 
			if ($rowClash = $resultClash->fetch_array())  //查找该生已报课程里是否有时间冲突的
			{
				if ($courseLimitArr[$courseRow['courseStyle']] == $courseDoneArr[$courseRow['courseStyle']]+$courseEnrollArr[$courseRow['courseStyle']] && $rowClash['changeFlag']=='1' && $rowClash['courseStyle']==$courseRow['courseStyle'])//该生已报满该类课程，可选择改报同类型课程，则被换的课程时间与换选课程可以冲突，除此之外，时间不能冲突
				{
					echo '<td align="center">' .'<a href="studentMenu.php?newCourseNum=' . $courseRow['courseNum'] .'&classEventID=' . $courseRow['id'].'&restOfTermNum=' . $courseRow['termNum'].'&courseStyle=' . $courseRow['courseStyle'].'">改报</a>' . '</td>';
				}
				else
				{
					echo '<td align="center">时间冲突' . '</td>';
				}
			}
			else if ($courseLimitArr[$courseRow['courseStyle']] <= $courseDoneArr[$courseRow['courseStyle']]+$courseEnrollArr[$courseRow['courseStyle']])//该生已报满该类课程，可选择改报同类型课程
			{
				echo '<td align="center">' .'<a href="studentMenu.php?newCourseNum=' . $courseRow['courseNum'] .'&classEventID=' . $courseRow['id'].'&restOfTermNum=' . $courseRow['termNum'].'&courseStyle=' . $courseRow['courseStyle'].'">改报</a>' . '</td>';
			}
			else //该生可以报名本课程
			{
				echo '<td align="center">' .'<a href="studentMenu.php?courseNum='.$courseRow['courseNum'] .'&classEventID=' . $courseRow['id'].'&restOfTermNum=' . $courseRow['termNum'].'&courseStyle=' . $courseRow['courseStyle'].'">报名</a>' . '</td>';
			}
		}
		
		echo '</tr>';
	}
    ?>
  </table>
<?php
}
else
{
	echo "<h1>报名管理员尚未登录！请登录报名管理员账号！</h1>";
}
?>
</body>
</html>
<?php include("bottom.php"); ?>