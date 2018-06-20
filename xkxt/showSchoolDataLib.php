<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>建立大数据库</title>
</head>

<body>
<?php
include("conn.php");
include("funDownloadSchoolDataLib.php");

if (isset($_POST['bigDataBtn']) && $_POST['psw'] == 'ljb')
{
	$beginID = trim($_POST['beginID']); 
	$endID = trim($_POST['endID']); 

	if (is_numeric($beginID) && is_numeric($endID) && $beginID <= $endID)
	{
		for ($i=$beginID; $i<=$endID; $i++)
		{
			DownloadSchoolDataLib($i);//下载学校比赛信息
		}
	}
	else
	{
		echo "<script>alert('请输入正确的ID范围!');</script>";
	}
}
?>
</body>
</html>