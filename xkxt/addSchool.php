<?php
session_start();
@header("content-type:text/html;charset=utf-8");
include("conn.php");
include("funCreateTB.php");
		
if (isset($_POST['submit']))
{
	$sql_insert = "INSERT INTO school_tb(schoolName, adminName, adminPWD, telephone, email, regDate) VALUES(?, ?, ?, ?, ?, ?)"; 
	$stmt = $mysqli->prepare($sql_insert);
	$stmt->bind_param('ssssss', $schoolName, $adminName, $adminPWD, $telephone, $email, $regDate);
	
	$schoolName = trim($_POST['schoolname']);
	$adminName = trim($_POST['username']);
	$adminPWD = trim($_POST['userpsw']);
	$telephone = trim($_POST['telephone']);
	$email = trim($_POST['email']);
	$regDate = date("Y-m-d H:i:s");
	
	if (!$stmt->execute())
	{
		echo "<script>alert('添加学校失败！');</script>";
	}
	$id = $mysqli->insert_id;  
	$stmt->close();
	
	if (CreateTB($id, $adminName, $adminPWD) == true)
	{
		echo "<script>alert('所有数据库均创建成功！');</script>";
	}
	else
	{
		echo "<script>alert('数据库创建失败！请联系网站管理员！');</script>";
	}
	echo "<script>window.location.href='bossMenu.php';</script>"; 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加学校页面</title>
</head>

<script language="javascript">
			 
function chkinput(form)
{
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
  <table width="60%" border="0" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);border:solid 10px #dddddd;font-size:16px;height:300px">
    <tr>
      <th height="50" align="right">学校名称*&nbsp;&nbsp;</th>
      <td align = "left" ><input name="schoolname" type="text" id="schoolname" size="20"/></td>
    </tr>
    <tr>
       <th height="50" align="right">用户名*&nbsp;&nbsp;</th>
      <td align="left"><input name="username" type="text" id="username" size="20"/></td>
    </tr>
    <tr>
        <th height="50" align="right">密码*&nbsp;&nbsp;</th>
      <td   align="left"><input name="userpsw" type="text" id="userpsw" size="20"/></td>
    </tr>
    <tr>
      <th height="50"  align="right">邮箱*&nbsp;&nbsp;</th>
      <td align = "left" ><input name="email" type="text" id="email" size="20"/></td>
    </tr>
    <tr>
       <th height="50"  align="right">电话号码*&nbsp;&nbsp;</th>
       <td  align="left"><input name="telephone" type="text" id="telephone" size="20"/></td>
    </tr>
    <tr>
      <td height="40" colspan="2" align="center"><input type="submit" name="submit" id="submit" value="提交"/></td>
    </tr>
  </table>
 
</form>

</body>
</html>
<?php include("bottom.php"); ?>