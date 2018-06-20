<?php
session_start();
include("conn.php"); 

$user_tb = 'user_tb' . $_SESSION['SschoolID'];
$class_tb = 'class_tb' . $_SESSION['SschoolID'];
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

$sexArray = array('m'=>'男', 'f'=>'女');
$userTypeArray = array('j'=>'教师', 'x'=>'学生');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>查询用户信息</title>
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
    <th align="center" colspan="5" height="30">只需输入需要限制的条件，无输入则输出全部名单</th>
    <th align="center"><input type="submit" name="btn" id="btn" value="查询" /></th>
    </tr>
    <tr>
      <td height="30" align="center">用户类型
        <select name="userType" id="userType">
          <option value="">选择</option>
          <option value="j">教师</option>
          <option value="x">学生</option>
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
      <td align="center">性别
        <select name="sex" id="sex">
          <option value="">选择</option>
          <option value="m">男</option>
          <option value="f">女</option>
      </select></td>
      <td align="center">姓名
      <input name="nickname" type="text" id="nickname" size="10" /></td>
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
	echo '<th align="center"> 序号</th>';
	echo '<th align="center"> 用户类型</th>';
	echo '<th align="center"> 专业编号</th>';
	echo '<th align="center"> 年级</th>';
	echo '<th align="center"> 班级</th>';
	echo '<th align="center"> 用户名</th>';
	echo '<th align="center"> 姓名</th>';
	echo '<th align="center"> 性别</th>';
	echo '<th align="center"> 权限</th>';
	echo '<th align="center"> 操作</th>';
	echo '</tr>';

	$queryString = "SELECT C.gradeName, C.className, C.majorNum, U.id, U.username, U.nickname, U.sex, U.permission, U.userType  				
					FROM $user_tb AS U INNER JOIN $class_tb AS C 
				    ON U.classID=C.classNum";
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
		$queryString .= " ORDER BY U.userType, C.id, U.id LIMIT " . ($page-1) * $pagesize . ", $pagesize";  
		$result = $mysqli->query($queryString);
		while($row = $result->fetch_array()) 
		{
			if ($row['userType'] == 'g')
			{
				continue;
			}
			
			echo '<tr>';
			echo '<td align="center">' . $num++ . '</td>';
			echo '<td align="center">' . $userTypeArray[$row['userType']] . '</td>';
			if ($row['userType'] == 'x')
			{
				echo '<td align="center">' . $row['majorNum'] . '</td>';
				echo '<td align="center">' . $row['gradeName'] . '</td>';
				echo '<td align="center">' . $row['className'] . '</td>';
			}
			else
			{
				echo '<td align="center">&nbsp;</td>';
				echo '<td align="center">&nbsp;</td>';
				echo '<td align="center">&nbsp;</td>';
			}
			
			echo '<td align="center">' . $row['username'] . '</td>';
			echo '<td align="center">' . $row['nickname'] . '</td>';
			echo '<td align="center">' . $sexArray[$row['sex']] . '</td>';
			echo '<td align="center">' . $row['permission'] . '</td>';
			echo '<td align="center">' . '<a href="javascript:if(confirm(' . "'确定删除文件吗?'))location='deleteUser.php?id=" . $row['id'] .'&prepage='.$_SERVER['PHP_SELF']."'" .'">删除</a> &nbsp;<a href="updateUser.php?id='.$row['id'] .'&prepage='.$_SERVER['PHP_SELF'].'">修改</a>' . '</td>';
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