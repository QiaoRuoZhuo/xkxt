<?php
@session_start();
include("conn.php");

$prePage = $_GET["prepage"]; 

if (isset($_POST['submit']))
{
	$psw = trim($_POST['psw1']); 
	$user_tb = 'user_tb' . $_SESSION['SschoolID'];
	$sql_update = "UPDATE $user_tb SET psw = '$psw' WHERE username = '$_SESSION[Susername]'"; 
	if ($mysqli->query($sql_update))
	{
		echo "<script>alert('修改密码成功!');</script>";
	}
	else
	{
		echo "<script>alert('修改密码失败!');</script>";
	}
	echo "<script>window.location.href='$prePage';</script>"; 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改密码页面</title>
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
	if (form.psw1.value=="")
	{
		alert('密码不能为空！');
		form.psw1.focus();
		return false;
	}
	
	if (form.psw2.value=="")
	{
		alert('密码不能为空！');
		form.psw2.focus();
		return false;
	}
	
	if (form.psw1.value != form.psw2.value)
	{
	   alert("两次密码不相同！");
	   form.psw1.focus();
	   return false;
	} 

	return true;
}
			  
 </script>

<body>
<table width="800" height="25" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
     <td><?php include("head.php"); ?></td>
    </tr>
</table>
<form id="form1" name="form1" method="post" action="" onsubmit="return chkinput(this)">
  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);border:solid 10px #dddddd;font-size:12px;height:100px">
    <tr>
      <th height="40" align="center" >用户名: &nbsp;&nbsp;<?php echo $_SESSION['Snickname']; ?></th>
    </tr>
    <tr>
       <th height="40" align="center" >请输入新密码*: &nbsp;&nbsp;&nbsp;&nbsp;<input name="psw1" type="password" id="psw1" size="12"/></th>
    </tr>
    <tr>
       <th height="40" align="center" >请再次输入密码*: &nbsp;&nbsp;<input name="psw2" type="password" id="psw2" size="12"/></th>
    </tr>
    <tr>
      <th height="40" align="center" ><input type="submit" name="submit" id="submit" value="提交"/></td>
    </tr>
  </table>
 
</form>

</body>
</html>
<?php include("bottom.php"); ?>