<?php
@session_start();
include("conn.php");

$sql_select = "SELECT * FROM school_tb WHERE id = '$_GET[schoolID]'";
$result = $mysqli->query($sql_select);
$row = $result->fetch_array();

if (isset($_POST['submit']) && isset($_POST['mypsw']) && $_POST['mypsw']=='ljb')
{
	$sql_update = "UPDATE school_tb SET schoolName=?, adminName=?, adminPWD=?, telephone=?, email=? WHERE id=?"; 
	$stmt = $mysqli->prepare($sql_update);
	$stmt->bind_param('ssssss', $schoolName, $adminName, $adminPWD, $telephone, $email, $id);
	
	$id = trim($_POST['schoolID']);
	$schoolName = trim($_POST['schoolname']);
	$adminName = trim($_POST['username']);
	$adminPWD = trim($_POST['userpsw']);
	$telephone = trim($_POST['telephone']);
	$email = trim($_POST['email']);
	
	if (!$stmt->execute())
	{
		echo "<script>alert('修改学校信息失败！');</script>";
	}
	$stmt->close();
	
	$user_tb = 'user_tb' . $id;
	$sql_update = "UPDATE $user_tb SET username=?, psw=? WHERE id='1'"; 
	$stmt = $mysqli->prepare($sql_update);
	$stmt->bind_param('ss', $adminName, $adminPWD);
	
	$adminName = trim($_POST['username']);
	$adminPWD = trim($_POST['userpsw']);
	
	if (!$stmt->execute())
	{
		echo "<script>alert('修改学校管理员信息失败！');</script>";
	}
	$stmt->close();
	
	echo "<script>window.location.href='bossMenu.php';</script>"; 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改学校信息页面</title>
<style type="text/css">
<!--
.tishi {
	font-size: 12px;
	color: #F00;
}
-->
</style>
</head>

<script language="javascript">
			 
function chkinput(form)
{
	if (form.mypsw.value=="")
	{
		alert('请输入管理员密码');
		form.mypsw.focus();
		return false;
	}
	
	if (form.schoolname.value=="")
	{
		alert('请输入学校名称');
		form.schoolname.focus();
		return false;
	}
	
	if (form.username.value=='')
	{
		alert('请输入用户名');
		form.username.focus();
		return false;
	}
	
	if(form.userpsw.value=="")
	{
	   alert('请输入密码');
	   form.userpsw.focus();
	   return false;
	} 
	
	if(form.email.value=="")
	{
	   alert('请输入邮箱');
	   form.email.focus();
	   return false;
	} 
	
	if(form.telephone.value=="")
	{
	   alert('请输入电话号码');
	   form.telephone.focus();
	   return false;
	} 
	
	return true;
}
			  
 </script>

<body>
<form id="form1" name="form1" method="post" action="" onsubmit="return chkinput(this)">
   <input type="hidden" name="schoolID" id="schoolID" value="<?php echo $_GET["schoolID"]; ?>"/>
  <table width="60%" border="0" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);border:solid 10px #dddddd;font-size:16px;height:300px">
    <tr>
      <th height="50" align="right">管理员密码*&nbsp;&nbsp;</th>
      <td align = "left" ><input name="mypsw" type="password" id="mypsw" size="20"/></td>
    </tr>
    <tr>
      <th height="50" align="right">学校名称*&nbsp;&nbsp;</th>
      <td align = "left" ><input name="schoolname" type="text" id="schoolname" size="20" value=" <?php if ($row)  echo $row['schoolName']; ?> " /></td>
    </tr>
    <tr>
       <th height="50" align="right">用户名*&nbsp;&nbsp;</th>
      <td align="left"><input name="username" type="text" id="username" size="20" value=" <?php if ($row)  echo $row['adminName']; ?> " /></td>
    </tr>
    <tr>
        <th height="50" align="right">密码*&nbsp;&nbsp;</th>
      <td   align="left"><input name="userpsw" type="text" id="userpsw" size="20" value=" <?php if ($row)  echo $row['adminPWD']; ?> " /></td>
    </tr>
    <tr>
      <th height="50"  align="right">邮箱*&nbsp;&nbsp;</th>
      <td align = "left" ><input name="email" type="text" id="email" size="20" value=" <?php if ($row)  echo $row['email']; ?> " /></td>
    </tr>
    <tr>
       <th height="50"  align="right">电话号码*&nbsp;&nbsp;</th>
       <td  align="left"><input name="telephone" type="text" id="telephone" size="20" value=" <?php if ($row)  echo $row['telephone']; ?> " /></td>
    </tr>
    <tr>
      <td height="40" colspan="2" align="center"><input type="submit" name="submit" id="submit" value="提交"/></td>
    </tr>
  </table>
 
</form>

</body>
</html>
<?php include("bottom.php"); ?>