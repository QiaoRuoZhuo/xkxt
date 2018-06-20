<?php
session_start();
include("conn.php");

$user_tb = 'user_tb' . $_SESSION['SschoolID'];
$class_tb = 'class_tb' . $_SESSION['SschoolID'];
$class_event_tb = 'class_event_tb' . $_SESSION['SschoolID'];
$eventName_tb = 'eventName_tb' . $_SESSION['SschoolID'];
$enroll_tb = 'enroll_tb' . $_SESSION['SschoolID'];

$gradeArray = array();  //记录年级
$sql = "SELECT DISTINCT gradeName 
		FROM $class_tb" . "  
		WHERE gradeName IS NOT NULL" . "  
		ORDER BY id";
$result = $mysqli->query($sql);
while ($row = $result->fetch_array()) 
{
	$gradeArray[] = $row['gradeName'];
}

$classArray = array();  //记录班级
$sql = "SELECT DISTINCT className 
		FROM $class_tb" . "  
		WHERE className IS NOT NULL" . "  
		ORDER BY id";
$result = $mysqli->query($sql);
while ($row = $result->fetch_array()) 
{
	$classArray[] = $row['className'];
}

$majorArray = array();  //记录专业编号
$sql = "SELECT DISTINCT majorNum 
		FROM $class_tb" . "  
		WHERE majorNum<>'0'" . "  
		ORDER BY id";
$result = $mysqli->query($sql);
while ($row = $result->fetch_array()) 
{
	$majorArray[] = $row['majorNum'];
}

$eventArray = array();  //记录课程名称
$sql = "SELECT DISTINCT eventName
		FROM $eventName_tb" . "  
		ORDER BY id";
$result = $mysqli->query($sql);
while ($row = $result->fetch_array()) 
{
	$eventArray[] = $row['eventName'];
}

$courseStyleArray = array();  //记录课程类别
$sql = "SELECT DISTINCT courseStyle 
		FROM $eventName_tb" . "  
		ORDER BY id";
$result = $mysqli->query($sql);
while ($row = $result->fetch_array()) 
{
	$courseStyleArray[] = $row['courseStyle'];
}

$addressArray = array();  //记录上课地点
$sql = "SELECT DISTINCT address 
		FROM $eventName_tb" . "  
		ORDER BY id";
$result = $mysqli->query($sql);
while ($row = $result->fetch_array()) 
{
	$addressArray[] = $row['address'];
}

$actTimeArray = array();  //记录上课时间
$sql = "SELECT DISTINCT actTime 
		FROM $eventName_tb" . "  
		ORDER BY id";
$result = $mysqli->query($sql);
while ($row = $result->fetch_array()) 
{
	$actTimeArray[] = $row['actTime'];
}

$sexArray = array('m'=>'男', 'f'=>'女', 'b'=>'男女');
$stuRangeArray = array('b'=>'全班', 'x'=>'全校');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>班级选课信息</title>
</head>

<body>

<table width="80%" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
     <td><?php include("head.php"); ?></td>
    </tr>
</table>

<form id="form1" name="form1" method="post" action="">
  <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);font-size: 14px; border:solid 2px #dddddd">
    <tr>
    <th align="center" colspan="7" height="40">只需输入需要限制的条件，无输入则输出全部名单</th>
    <th align="center" colspan="2" height="40"><input type="submit" name="btn" id="btn" value="查询" /></th>
    </tr>
    <tr>
      <td height="40" align="center">课程类别
        <select name="courseStyle" id="courseStyle">
          <option value="">选择</option>
          <?php
		  	foreach($courseStyleArray as $courseStyle)
			{
				echo '<option value="'. $courseStyle . '">' . $courseStyle . '</option>';
			}
		  ?>
      </select></td>
      <td height="40" align="center">课程名称
        <select name="eventName" id="eventName">
          <option value="">选择</option>
          <?php
		  	foreach($eventArray as $eventName)
			{
				echo '<option value="'. $eventName . '">' . $eventName . '</option>';
			}
		  ?>
      </select></td>
       <td height="40" align="center">时间
        <select name="actTime" id="actTime">
          <option value="">选择</option>
          <?php
		  	foreach($actTimeArray as $actTime)
			{
				echo '<option value="'. $actTime . '">' . $actTime . '</option>';
			}
		  ?>
      </select></td>
       <td height="40" align="center">地点
        <select name="address" id="address">
          <option value="">选择</option>
          <?php
		  	foreach($addressArray as $address)
			{
				echo '<option value="'. $address . '">' . $address . '</option>';
			}
		  ?>
      </select></td>
      <td align="center">性别
        <select name="sex" id="sex">
          <option value="">选择</option>
          <option value="b">男女</option>
          <option value="m">男</option>
          <option value="f">女</option>
      </select></td>
      <td align="center">年级
        <select name="gradeName" id="gradeName">
          <option value="">选择</option>
          <?php
		  	foreach($gradeArray as $gradeName)
			{
				echo '<option value="'. $gradeName . '">' . $gradeName . '</option>';
			}
		  ?>
      </select></td>
      <td align="center">班级
        <select name="className" id="className">
          <option value="">选择</option>
          <?php
		  	foreach($classArray as $className)
			{
				echo '<option value="'. $className . '">' . $className . '</option>';
			}
		  ?>
      </select></td>
      <td align="center">专业编号
        <select name="majorNum" id="majorNum">
          <option value="">选择</option>
          <?php
		  	foreach($majorArray as $majorNum)
			{
				echo '<option value="'. $majorNum . '">' . $majorNum . '</option>';
			}
		  ?>
      </select></td>
      <td align="center">教师
      <input name="teacherName" type="text" id="teacherName" size="10" /></td>
    </tr>
  </table>
</form>

<?php
if (isset($_SESSION['SuserType']) && $_SESSION['SuserType'] == 'g')//系统管理员登陆
{
   $searchItem = array();
   
   if (isset($_POST['btn']))
   {
	   foreach($_POST as $k=>$v) //获取查询条件
		{
			if ($k != 'btn' && $v != NULL)
			{
				$searchItem[$k] = $v;
			}
		}
		
		$_SESSION['SsItem'] = $searchItem;
   }
   else if(isset($_SESSION['SsItem']))
   {
	   foreach($_SESSION['SsItem'] as $k=>$v) //获取查询条件
		{
			if ($k != 'btn' && $v != NULL)
			{
				$searchItem[$k] = $v;
			}
		}
   }
   
	echo '<table width="80%" border="1" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);font-size: 14px; border:solid 10px #dddddd">'; 
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
				   WHERE CE.eventID=E.courseNum AND CE.classID=C.classNum"; 
	if (count($searchItem) != 0)
	{
		foreach($searchItem as $k=>$v) //获取查询条件
		{
			$queryString .= " AND $k = '" . $v . "'";
		}
	}
	
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
		isset($_GET["page"]) && is_numeric($_GET["page"]) ? $page = intval($_GET["page"]) : $page = 1;
		if (isset($_POST['btn']))
			$page = 1;
			
		$pagesize=36;
		$pagecount=ceil($total/$pagesize);
		$queryString .= " ORDER BY CE.trueNum DESC, CE.maxNum DESC, C.id, E.id LIMIT " . ($page-1) * $pagesize . ", $pagesize";  
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
			echo '<td align="center">' .'<a href="updateMaxNum.php?eventID='.$row['eventID'] .'&maxNum='.$row['maxNum'].'&prepage='.$_SERVER['PHP_SELF'] . '">' . $row['maxNum'] . '</a>' . '</td>';
			echo '<td align="center">' . $row['trueNum'] . '</td>';
			echo '<td align="center">' .'<a href="updateClassMaxNum.php?class_eventID='.$row['id']  .'&maxNum='.$row['maxNumClass'] .'&prepage='.$_SERVER['PHP_SELF'] . '">' . $row['maxNumClass'] . '</a>' . '</td>';
			echo '<td align="center">' . $row['trueNumClass'] . '</td>';
			echo '<td align="center">' .'<a href="updateStuRange.php?class_eventID='.$row['id']  .'&maxNum='.$row['maxNum'] .'&stuRange='.$row['stuRange'] .'&prepage='.$_SERVER['PHP_SELF'] . '">' . $stuRangeArray[$row['stuRange']] . '</a>' . '</td>';
			echo '</tr>';
		}  //分页显示
		echo '</table>'; 
	?>
		
		<table width="80%" height="20" border="0" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg)">
			<tr>
			 <td>&nbsp;</td>
			</tr>
		</table>
		<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg)">
			<tr>
			 <td width="400"><div align="left">
			   共有记录&nbsp;<?php echo $total;?>&nbsp;条&nbsp;
			   每页显示&nbsp;<?php echo $pagesize;?>&nbsp;条&nbsp;&nbsp;
			   第&nbsp;<?php echo $page;?>&nbsp;页/共&nbsp;<?php echo $pagecount;?>&nbsp;页
			 </div></td>
			 <td width="300"><div align="right">
			   <a href="<?php echo $_SERVER['PHP_SELF']?>?page=1" class="a1">首页</a>&nbsp;
			 <?php
			   if($page > 1) 
			   {
			 ?>
				   <a href="<?php printf("%s?page=%d", $_SERVER["PHP_SELF"], $page-1);?>" class="a1">上一页</a>&nbsp;
			 <?php
			   }
			 ?>  
			 <?php
			   if($page<$pagecount) 
			   {
			 ?>
				   <a href="<?php printf("%s?page=%d", $_SERVER["PHP_SELF"], $page+1);?>" class="a1">下一页</a>&nbsp;
			 <?php
			   }
			 ?>
			   <a href="<?php printf("%s?page=%d", $_SERVER["PHP_SELF"], $pagecount);?>" class="a1">尾页</a>&nbsp;
			 </div></td>
			</tr>
		</table>
	<?php
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
<?php include("bottom.php"); ?>