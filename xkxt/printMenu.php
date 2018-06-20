<?php
@session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>打印报表菜单</title>
</head>

<body>
<?php
if (isset($_SESSION['SuserType']) && $_SESSION['SuserType'] == 'g')//系统管理员登陆
{
?>
<p><a href="printEnroll.php?changeFlag=0" target="_blank">打印新选课程信息</a></p>
<p><a href="printEnroll.php?changeFlag=2" target="_blank">打印在修课程信息</a></p>
<p><a href="printEnroll.php?changeFlag=3" target="_blank">打印已修课程信息</a></p>
<p><a href="printClassEvent.php" target="_blank">打印班级报名情况</a></p>
<?php
}
else
{
	echo "<h1>系统管理员尚未登录！请登录系统管理员账号！</h1>";
}
?>
</body>
</html>