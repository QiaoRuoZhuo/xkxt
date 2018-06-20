<?php
@session_start();
@header("content-type:text/html;charset=utf-8");

if(isset($_POST['myfile']) and $_POST['Submit']=="上传")
{
	if(isset($_FILES['files']['name'])) //判断文件是否存在
	{		
		$fileName = 'schoolFile/user' . $_SESSION['SschoolID'] . $_POST['myfile'] . ".xls"; //文件的存储路径和名称
		$fileName = iconv("utf-8","gb2312//IGNORE",$fileName);
		if (file_exists($fileName))
		{
			if (!unlink($fileName))
			{
				echo "<script>alert('删除原文件失败！');</script>";
			}
		}
		
		if(move_uploaded_file($_FILES['files']['tmp_name'], $fileName))
		{	//执行上传
			echo "<script>alert('上传成功！');</script>";
			
			if ($_POST['myfile'] == "kecheng") //课程模板
			{
				include("buildEventName.php");
			}
			else if ($_POST['myfile'] == "banji") //班级情况模板
			{
				include("buildClass.php");
			}
			else if ($_POST['myfile'] == "yonghu") //用户模板
			{
				include("buildUser.php");
			}
			else //成绩模板
			{
				include("buildScore.php");
			}
		}
		else
		{
			echo "<script>alert('复制文件失败！');</script>";
		}
	}
	else
	{
		echo "<script>alert('文件不存在！');</script>";
	}
}
else
{
	echo "<script>alert('请选择上传文件！');</script>";
}

echo "<script>window.location.href='uploadingMenu.php';</script>"; 
?>