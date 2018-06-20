<?php
@session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>上传各类模板和资料照片</title>
</head>

<body>
<?php
if (isset($_SESSION['SuserType']) && $_SESSION['SuserType'] == 'g')//系统管理员登陆
{
?>
<table width="80%" height="80" border="1" align="center" style="border:solid 10px #dddddd">
  <tr>
    <td colspan="5" align="center" height="40">请按从左到右顺序上传相关模板</td>
  </tr>
  <tr>
    <td align="center" height="40"><a href="uploading.php?myfile=banji">上传班级模板</a>&nbsp;&nbsp;(<a href="displayClassMode.php">修改班级模板</a>)</td>
    <td align="center"><a href="uploading.php?myfile=yonghu" target="_blank">上传用户模板</a></td>
    <td align="center"><a href="uploading.php?myfile=kecheng">上传课程模板</a></td>
    <td align="center"><a href="uploadingPICjs.php">上传教师资料照片</a></td>
    <td align="center"><a href="uploadingPICkc.php">上传课程资料照片</a></td>
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