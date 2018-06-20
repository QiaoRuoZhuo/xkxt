<?php
@session_start();
include("conn.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统管理员主页面</title>
</head>

<body>
<table width="800" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
     <td><?php include("head.php"); ?></td>
    </tr>
</table>

<?php
if (isset($_SESSION['SuserType']) && $_SESSION['SuserType'] == 'g')//系统管理员登陆
{
?>

<table width="800" height="360" border="0" align="center" cellpadding="0" cellspacing="0" style="font-size: 14px;background:url(images/bj1.jpg)">
  <tr> 
     <td height="120" align="center"><a href="downloadExample.php" target="_blank"><img src="images/down.jpg" border="0"></a></td>
     <td align="center"><a href="uploadingMenu.php"  target="_blank"><img src="images/upload.jpg" border="0"></a></td>
     <td align="center"><a href="manageUser.php"  target="_blank"><img src="images/user.jpg" border="0"></a></td>
  </tr>
  <tr> 
     <td height="120" align="center"><a href="manageClassEvent.php" target="_blank"><img src="images/classEvent.jpg" border="0"></a></td>
     <td align="center"><a href="displayEnroll.php" target="_blank"><img src="images/baoming.jpg" border="0"></a></td>
     <td align="center"><a href="printMenu.php" target="_blank"><img src="images/dayin.jpg" border="0"></a></td>
  </tr>
  <tr>
  <td height="120" align="center"><a href="javascript:if(confirm('确定新学期到了，要更新课程信息吗?'))location='newTerm.php'"><img src="images/xinxueqi.jpg" border="0"></a></td> 
 <script type="text/javascript">
function SubmitOne()
{
    $("lockForm").submit();
}
</script>
  <?php
  	$user_tb = 'user_tb' . $_SESSION['SschoolID'];
  	if (isset($_POST['baoLock_x']))
	{ 
		$sql_update = "UPDATE $user_tb SET permission = '0' WHERE userType = 'x'";
		$mysqli->query($sql_update);
	}
	else if (isset($_POST['baoUnlock_x']))
	{
		$sql_update = "UPDATE $user_tb SET permission = '1' WHERE userType = 'x'";
		$mysqli->query($sql_update);
	}
	
	if (isset($_POST['dengLock_x']))
	{
		$sql_update = "UPDATE $user_tb SET permission = '0' WHERE userType = 'j'";
		$mysqli->query($sql_update);
	}
	else if (isset($_POST['dengUnlock_x']))
	{
		$sql_update = "UPDATE $user_tb SET permission = '1' WHERE userType = 'j'";
		$mysqli->query($sql_update);
	}
  ?>
 <form action="" method="post" name="lockForm" id="lockForm">
    <td height="120" align="center">
  <?php
	 //设置“报名锁定”
	$sql_select = "SELECT id FROM $user_tb WHERE userType = 'x' AND permission = '1'"; 
	$result = $mysqli->query($sql_select);
	if ($result->num_rows > 0) //只要有一个或更多的报名管理员是有权限的，就说明此时报名处于解锁状态，点击按钮后报名被锁定
	{
		echo '<input type="image" src="images/baoLock.jpg" alt="报名锁定" name="baoLock" id="baoLock" onclick="SubmitOne();" /> ';
	}
	else
	{
		echo '<input type="image" src="images/baoUnlock.jpg" alt="报名解锁" name="baoUnlock" id="baoUnlock" onclick="SubmitOne();" /> ';
	}
 ?>	  
   </td>
   <td align="center">
 <?php
	 //设置“登分锁定”	 
	$sql_select = "SELECT id FROM $user_tb WHERE userType = 'j' AND permission = '1'"; 
	$result = $mysqli->query($sql_select);
	if ($result->num_rows > 0)//只要有一个或更多的登分管理员是有权限的，就说明此时登分处于解锁状态，点击按钮后登分被锁定
	{
		echo '<input type="image" src="images/dengLock.jpg" alt="登分锁定" name="dengLock" id="dengLock" onclick="SubmitOne();" /> ';
	}
	else
	{
		echo '<input type="image" src="images/dengUnlock.jpg" alt="登分解锁" name="dengUnlock" id="dengUnlock" onclick="SubmitOne();" /> ';
	}
  ?>
    </td></form>
  </tr>
</table>
<?php
}
else
{
	echo "<h1>系统管理员尚未登录！请登录系统管理员账号！</h1>";
}
?>
</body>
</html>

<?php include("bottom.php"); ?>