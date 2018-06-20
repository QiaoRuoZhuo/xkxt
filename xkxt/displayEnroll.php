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

$restOfTermArray = array();  //记录剩余学期数
$sql = "SELECT DISTINCT restOfTerm
		FROM $enroll_tb" . "  
		ORDER BY id";
$result = $mysqli->query($sql);
while ($row = $result->fetch_array()) 
{
	$restOfTermArray[] = $row['restOfTerm'];
}

$sexArray = array('m'=>'男', 'f'=>'女'); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>查询报名信息</title>
</head>

<body>

<table width="800" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
     <td><?php include("head.php"); ?></td>
    </tr>
</table>

<form id="form1" name="form1" method="post" action="">
  <table width="80%" border="1" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);font-size: 12px; border:solid 2px #dddddd">
    <tr>
    <?php
     	$scoreFile = 'chengji' . $_SESSION['Susername'];
		echo '<th align="center" colspan="3" height="40"><a href="uploading.php?myfile=' . $scoreFile .'">批量导入成绩</a>
		&nbsp;(<a href="chengji.xls">下载成绩模板</a>)</th>';
     ?>
    <th align="center" colspan="3" height="40">只需输入需要限制的条件，无输入则输出全部名单</th>
    <th align="center" colspan="2" height="40"><input type="submit" name="btn" id="btn" value="查询" /></th>
    </tr>
    <tr>
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
      <td align="center">任课教师
        <select name="teacherName" id="teacherName">
        <option value="">选择</option>
      <?php
	  	$sql = "SELECT DISTINCT teacherName 
				FROM $eventName_tb" . "  
				ORDER BY id";  
		$result = $mysqli->query($sql);
		while ($teacherNamerow = $result->fetch_array())
		{
			echo '<option value="'. $teacherNamerow['teacherName'] . '">' .$teacherNamerow['teacherName'] . '</option>';
		}
	  ?>
      </select></td>
      <td align="center">剩余学期
        <select name="restOfTerm" id="restOfTerm">
          <option value="">选择</option>
          <?php
		  	foreach($restOfTermArray as $restOfTerm)
			{
				echo '<option value="'. $restOfTerm . '">' . $restOfTerm . '</option>';
			}
		  ?>
      </select></td>
      <td align="center">修习状态
        <select name="changeFlag" id="changeFlag">
          <option value="">选择</option>
          <option value="3">已修</option>
          <option value="2">在修</option>
          <option value="1">可换选</option>
          <option value="0">已报名</option>
      </select></td>
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
		
		$_SESSION['EsItem'] = $searchItem;
   }
   else if(isset($_SESSION['EsItem']))
   {
	   foreach($_SESSION['EsItem'] as $k=>$v) //获取查询条件
		{
			if ($k != 'btn' && $v != NULL)
			{
				$searchItem[$k] = $v;
			}
		}
   }
   
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
	echo '<th> 操作</th>';
	echo '</tr>';

	$queryString = "SELECT EN.courseNum, EN.eventName, EN.teacherName, EN.actTime, EN.address, E.restOfTerm, E.upTime, C.majorNum, C.className, C.gradeName, U.username, U.nickname, U.sex, E.id, E.score, E.level, E.credit, E.crunkNum, EN.id AS kcid, EN.pic AS kcpic 
				   FROM $eventName_tb AS EN, 
				   $user_tb AS U, 
				   $enroll_tb AS E, 
				   $class_tb AS C
				   WHERE E.eventID=EN.courseNum AND E.classID=C.classNum AND E.userID=U.username";  
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
	   echo '<th align="center" colspan="16" height="40">' . '对不起，暂无数据！' . '</th>';
	   echo '</tr>';
	   echo '</table>';
	}
	else
	{
		isset($_GET["page"]) && is_numeric($_GET["page"]) ? $page = intval($_GET["page"]) : $page = 1;
		if (isset($_POST['btn']))
			$page = 1;
			
		$pagesize=60;
		$pagecount=ceil($total/$pagesize);
		$queryString .= " ORDER BY U.userType, C.id, U.id LIMIT " . ($page-1) * $pagesize . ", $pagesize";  
		$result = $mysqli->query($queryString);
		while($row = $result->fetch_array()) 
		{
			echo '<tr>';
			echo '<td align="center">' . $row['majorNum'] . '</td>';
			echo '<td align="center">' . $row['gradeName'] . '(' . $row['className'] . ')' . '</td>';
			echo '<td align="center">' . $row['username'] . '</td>';
			echo '<td align="center">' . $row['nickname'] . '</td>';
			echo '<td align="center">' . $sexArray[$row['sex']] . '</td>';
			if ($row['kcpic'])
			{
				echo '<td align="center">'.'<a href="showPicture.php?num=kc'.$row['kcid'].'" target="_blank">' .$row['eventName'] . '(' . $row['courseNum'] . ')' . '</a>' . '</td>';
			}
			else
			{
				echo '<td align="center">' . $row['eventName'] . '(' . $row['courseNum'] . ')' . '</td>';
			}
			
			echo '<td align="center">' . $row['teacherName'] . '</td>';
			echo '<td align="center">' .$row['actTime'] . '</td>';
			echo '<td align="center">' .$row['address'] . '</td>';
			echo '<td align="center">' .$row['restOfTerm'] . '</td>';
			echo '<td align="center">' . substr($row['upTime'], 0, 10) . '</td>'; 
			echo '<td align="center">' .$row['crunkNum'] . '</td>'; 
			echo '<td align="center">' .$row['score'] . '</td>'; 
			echo '<td align="center">' .$row['level'] . '</td>'; 
			echo '<td align="center">' .$row['credit'] . '</td>'; 
			if ($row['restOfTerm'] == '0') //该课程不允许该生报名或者改报
			{
				echo '<td align="center">&nbsp;</td>';
			}
			else
			{
				echo '<td align="center">' .'<a href="javascript:if(confirm(' . "'确定删除文件吗?'))location='deleteItem.php?id=" . $row['id'] .'&prepage='.$_SERVER['PHP_SELF']."'" .'">删除</a>' . '</td>';
			}
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