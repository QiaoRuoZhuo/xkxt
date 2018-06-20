<?php
@session_start();
include("conn.php");

$id = $_GET["id"];
$prePage = $_GET["prepage"]; 

$user_tb = 'user_tb' . $_SESSION['SschoolID'];
$sql_select = "SELECT * FROM $user_tb WHERE id = '$id'";
$result = $mysqli->query($sql_select);
$row = $result->fetch_array();

if (isset($_POST['submit']))
{
	$psw = trim($_POST['psw']);
	$permission = trim($_POST['permission']);
	
	$sql_update = "UPDATE $user_tb" . 
				 " SET psw = '$psw', permission = '$permission'
				   WHERE id = '$id'"; 
	if ($mysqli->query($sql_update))
	{
		echo "<script>alert('更新管理员信息成功！');</script>";
	}
	else
	{
		echo "<script>alert('更新管理员信息失败!');</script>";
	}
	echo "<script>window.location.href='$prePage';</script>"; 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改管理员信息页面</title>
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
	if (form.psw.value=="")
	{
		alert('请输入密码');
		form.psw.focus();
		return false;
	}

	if(form.permission.value=="")
	{
	   alert("请选择权限！");
	   form.permission.focus();
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
<?php
if (isset($_SESSION['SuserType']) && $_SESSION['SuserType'] == 'g')//系统管理员登陆
{
?>
<form id="form1" name="form1" method="post" action="" onsubmit="return chkinput(this)">
  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);border:solid 10px #dddddd;font-size:12px;height:100px">
    <tr>
      <th width="140">用户名（准考证号）：</th>
      <th align = "left" width="160" >&nbsp; <?php if ($row)  echo $row['username']; ?></th>
      <th width="60">姓名：</th>
      <th align = "left" width="80" >&nbsp; <?php if ($row)  echo $row['nickname']; ?></th>
      <th width="60" >密码</th>
      <th align="left" width="120" ><input name="psw" type="text" id="psw" size="12"  value=" <?php if ($row)  echo $row['psw']; ?> " /></th>
      <th width="100" >管理权限</th>
      <th align="left"><select name="permission" id="permission">
     <option value="0" <?php  if ($row && $row['permission'] == "0")  echo 'selected="selected"'; ?> >0</option>
        <option value="1" <?php  if ($row && $row['permission'] == "1")  echo 'selected="selected"'; ?> >1</option>
      </select></th>
    </tr>
    <tr>
      <th colspan="2" align="center"><a href="addEnroll.php?userID=<?php if ($row)  echo $row['username']; ?>&classID=<?php if ($row)  echo $row['classID']; ?>&sex=<?php if ($row)  echo $row['sex']; ?>" target="_blank">选课报名</a></th>
      <td colspan="6" align="center"><input type="submit" name="submit" id="submit" value="提交"/></td>
    </tr>
  </table>
 
</form>
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