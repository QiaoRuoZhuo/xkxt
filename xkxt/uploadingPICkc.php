<?php
session_start();
include("conn.php"); 

$eventName_tb = 'eventName_tb' . $_SESSION['SschoolID'];
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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>上传课程资料照片</title>
</head>

<body>

<table width="800" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
     <td><?php include("head.php"); ?></td>
    </tr>
</table>

<form id="form1" name="form1" method="post" action="">
  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);font-size: 12px; border:solid 2px #dddddd">
    <tr>
    <th align="center" colspan="4" height="40">只需输入需要限制的条件，无输入则输出全部名单</th>
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
		
		$_SESSION['UsItem'] = $searchItem;
   }
   else if(isset($_SESSION['UsItem']))
   {
	   foreach($_SESSION['UsItem'] as $k=>$v) //获取查询条件
		{
			if ($k != 'btn' && $v != NULL)
			{
				$searchItem[$k] = $v;
			}
		}
   }
   
	echo '<table width="800" border="1" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);font-size: 14px; border:solid 10px #dddddd">'; 
	echo '<tr>';
	echo '<th align="center" height="30"> 序号</th>';
	echo '<th align="center"> 课程类别</th>';
	echo '<th align="center"> 教师</th>';
	echo '<th align="center"> 地点</th>';
	echo '<th align="center"> 时间</th>';
	echo '<th align="center"> 性别</th>';
	echo '<th align="center"> 课程编号</th>';
	echo '<th align="center"> 课程名称</th>';
	echo '<th align="center"> 操作</th>';
	echo '</tr>';

	$queryString = "SELECT id, eventName, teacherName, actTime, address, courseNum, courseStyle, sex, pic FROM $eventName_tb";
	if (count($searchItem) != 0)
	{
		$queryString .= " WHERE ";
		foreach($searchItem as $k=>$v) //获取查询条件
		{
			$queryString .= "$k = '" . $v . "' AND ";
		}
		$len = strlen($queryString);   
   		$queryString = substr($queryString, 0, $len-4); 
	}
	
	$resultSearch = $mysqli->query($queryString);
	$total = $resultSearch->num_rows;
	
	$num = 1;
	if ($total==0)
	{
	   echo '<tr>';
	   echo "<div align=center>对不起，暂无数据！</div>";
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
		$queryString .= " ORDER BY id LIMIT " . ($page-1) * $pagesize . ", $pagesize";  
		$result = $mysqli->query($queryString);
		while($row = $result->fetch_array()) 
		{
			echo '<tr>';
			echo '<td align="center" height="22">' . $num++ . '</td>';
			echo '<td align="center">' . $row['courseStyle'] . '</td>';
			echo '<td align="center">' . $row['teacherName'] . '</td>';
			echo '<td align="center">' . $row['address'] . '</td>';
			echo '<td align="center">' . $row['actTime'] . '</td>';
			echo '<td align="center">' . $sexArray[$row['sex']] . '</td>';
			echo '<td align="center">' . $row['courseNum'] . '</td>';
			if ($row['pic'])
			{
				echo '<td align="center">'.'<a href="ShowPicture.php?num=kc'.$row['id'].'" target="_blank">' .$row['eventName'] . '</a>' . '</td>';
				echo '<td align="center">'.'<a href="uploadingPIC.php?num=kc'.$row['id'].'&username='.$row['courseNum'].'&nickname='.$row['eventName'].'" target="_blank">' . '重新上传' . '</a>' . '</td>';
			}
			else
			{
				echo '<td align="center">' . $row['eventName'] . '</td>';
				echo '<td align="center">'.'<a href="uploadingPIC.php?num=kc'.$row['id'].'&username='.$row['courseNum'].'&nickname='.$row['eventName'].'" target="_blank">' . '上传图片' . '</a>' . '</td>';
			}
			echo '</tr>';
		}  //分页显示
		echo '</table>'; 
	?>
		
		<table width="800" height="20" border="0" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg)">
			<tr>
			 <td>&nbsp;</td>
			</tr>
		</table>
		<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg)">
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