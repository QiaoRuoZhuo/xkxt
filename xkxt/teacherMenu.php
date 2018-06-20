<?php
session_start();
include("conn.php");
set_time_limit(0);

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
$sexArray = array('m'=>'男', 'f'=>'女');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>教师管理页面</title>
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
if (isset($_SESSION['SuserType']) && $_SESSION['SuserType'] == 'j')
{
?>

<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);border:solid 10px #dddddd;font-size:14px;height:60px">
  <tr>
     <th align="center"> <?php echo "热烈欢迎 " . $_SESSION['Snickname'] . "老师" ?> </th>
     <th align="center"><a href="uploadingPICMenu.php" target="_blank">提交资料</a></th>
     <th align="center"><a href="updatePSW.php?prepage=teacherMenu.php">修改密码</a>(系统分配的原始密码不安全，请尽快修改密码)</th>
     <th align="center"><a href="inputScore.php" target="_blank">输入成绩</a></th>
    </tr>
</table>
<form id="form1" name="form1" method="post" action="">
  <table width="800" border="1" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);font-size: 13px; border:solid 1px #dddddd">
    <tr>
    <th align="center" colspan="3" height="40">只需输入需要限制的条件，无输入则输出全部名单</th>
    <th align="center" colspan="1" height="40"><input type="submit" name="btn" id="btn" value="查询" /></th>
    </tr>
    <tr>
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
      <td align="center">姓名
      <input name="nickname" type="text" id="nickname" size="10" /></td>
      <td align="center">课程
        <select name="courseNum" id="courseNum">
        <option value="">选择</option> 
      <?php
	  	$sql = "SELECT eventName, courseNum  
				FROM $eventName_tb" . "  
				ORDER BY courseNum"; 
		$result = $mysqli->query($sql);
		while ($eventRow = $result->fetch_array()) 
		{
			echo '<option value="'. $eventRow['courseNum'] . '">' .$eventRow['eventName'] . "(" . $eventRow['courseNum']. ')</option>';
		}
	  ?>
      </select></td>
    </tr>
 <tr>
      <td colspan="4" height="90"><table width="100%" height="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
    	<th> 序号</th>
        <th> 课程</th>
        <th> 任课教师</th>
        <th> 时间</th>
        <th> 地点</th>
        <th> 剩余学期</th>
        <th> 班级</th>
        <th> 准考证号</th>
        <th> 姓名</th>
        <th> 性别</th>
        <th> 成绩</th>
    </tr>
    <?php
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
		
		$_SESSION['TsItem'] = $searchItem;
   }
   else if(isset($_SESSION['TsItem']))
   {
	   foreach($_SESSION['TsItem'] as $k=>$v) //获取查询条件
		{
			if ($k != 'btn' && $v != NULL)
			{
				$searchItem[$k] = $v;
			}
		}
   }
	$queryString = "SELECT C.gradeName, C.className, EN.eventName, EN.courseNum, EN.teacherName, EN.actTime, EN.address, E.restOfTerm, E.score, U.username, U.nickname, U.sex, EN.id AS kcid, EN.pic AS kcpic      
				   FROM $class_tb AS C,  
				   $eventName_tb AS EN, 
				   $enroll_tb AS E, 
				   $user_tb AS U  
				   WHERE EN.teacherID='$_SESSION[Susername]' AND E.userID=U.username AND E.eventID=EN.courseNum AND E.classID=C.classNum AND E.restOfTerm>'0' AND ";  //查找该教师任教的科目                 
				
	if (count($searchItem) != 0)
	{
		foreach($searchItem as $k=>$v) //获取查询条件
		{
			$queryString .= "$k = '" . $v . "' AND ";
		}
	}
	
	$len = strlen($queryString);   
   	$queryString = substr($queryString, 0, $len-4);
	
	$resultSearch = $mysqli->query($queryString);
	$total = $resultSearch->num_rows;
	
	if ($total==0)
	{
	   echo '<tr>';
	   echo '<th colspan="12" align="center">没有任教的科目！</th>';
	   echo '</tr>';
	}
	else
	{
		$num = 1;
		$queryString .= " ORDER BY E.restOfTerm, EN.courseNum";
		$result = $mysqli->query($queryString);
		while($row = $result->fetch_array()) 
		{
			echo '<tr>';
			echo '<td align="center">' . $num++ . '</td>';
			if ($row['kcpic'])
			{
				echo '<td align="center">'.'<a href="ShowPicture.php?num=kc'.$row['kcid'].'" target="_blank">' .$row['eventName'] . '(' . $row['courseNum'] . ')' . '</a>' . '</td>';
			}
			else
			{
				echo '<td align="center">' . $row['eventName'] . '(' . $row['courseNum'] . ')' . '</td>';
			}
		
			echo '<td align="center">' .$row['teacherName'] . '</td>';
			echo '<td align="center">' .$row['actTime'] . '</td>';
			echo '<td align="center">' .$row['address'] . '</td>';
			echo '<td align="center">' .$row['restOfTerm'] . '</td>';
			echo '<td align="center">' . $row['gradeName'] . '(' . $row['className'] . ')' . '</td>';
			echo '<td align="center">' .$row['username'] . '</td>';
			echo '<td align="center">' .$row['nickname'] . '</td>';
			echo '<td align="center">' .$sexArray[$row['sex']] . '</td>';
			echo '<td align="center">' .$row['score'] . '</td>';
			echo '</tr>';
		}  
	}//esle
    ?>
    </table></td>
    </tr>
  </table>
</form>
<?php
}
else
{
	echo "<h1>教师管理员尚未登录！请登录教师管理员账号！</h1>";
}
?>
</body>
</html>
<?php include("bottom.php"); ?>