<?php
session_start();
@header("content-type:text/html;charset=utf-8");
include("conn.php");
$eventName_tb = 'eventName_tb' . $_SESSION['SschoolID'];

if (isset($_SESSION['SuserType']) && $_SESSION['SuserType'] == 'g')//系统管理员登陆
{
	if (isset($_GET["prepage"]) && isset($_GET["maxNum"]) && isset($_GET["eventID"])) //修改学校限额
	{
		$prePage = $_GET["prepage"]; 
		$oldMaxNum = $_GET["maxNum"];  
		$maxNumID = $_GET["eventID"];
	}
		
	if (isset($_POST['submit']))
	{
		$newMaxNum = trim($_POST['maxNum']); 
		$sql_update = "UPDATE " . $eventName_tb . 
					 " SET maxNum='$newMaxNum' 
					   WHERE courseNum = '$maxNumID'"; 
		if ($mysqli->query($sql_update))//修改限额信息
		{
			echo "<script>alert('修改限额信息成功！');</script>";
		}
		else
		{
			echo "<script>alert('修改限额信息失败！');</script>";
		}
		
		echo "<script>window.location.href='$prePage';</script>"; 
	}
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>系统管理员修改限额信息页面</title>
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
		if (form.maxNum.value=="")
		{
			alert('请输入一个正整数');
			form.name.focus();
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
	  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);border:solid 10px #dddddd;font-size:18px;height:100px">
		<tr>
          <th align="center" width="20%">&nbsp;</th>
		  <th align = "right" height="30"> 修改全校选课人数限额*&nbsp;&nbsp;</th>
		  <th align = "left"><input name="maxNum" type="text" id="maxNum" size="8"  value=" <?php echo $oldMaxNum; ?>" /></th>
		  <th align="center"><input type="submit" name="submit" id="submit" value="提交"/></th>
          <th align="center" width="20%">&nbsp;</th>
		</tr>
	  </table>
	</form>
	
	</body>
	</html>
<?php 
}
else
{
	echo "<h1>系统管理员尚未登录！请登录系统管理员账号！</h1>";
}
include("bottom.php"); 
?>