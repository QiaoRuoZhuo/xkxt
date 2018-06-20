<?php
@session_start();
include("conn.php");
@header("content-type:text/html;charset=utf-8");

if(isset($_POST['myfile']) and $_POST['Submit']=="上传")
{
	if(isset($_FILES['files']['name']) && $_FILES["files"]["size"] <= 20*1024*1024 && 
	  ($_FILES["files"]["type"] == "image/jpeg" || $_FILES["files"]["type"] == "image/pjpeg")) //判断文件是否存在
	{		
		$format = pathinfo($_FILES["files"]["name"],PATHINFO_EXTENSION); //取到上传文件的后缀，即jpg
		if ($format == "jpg" || $format == "JPG")
		{
			$fileName = 'schoolFile/user' . $_SESSION['SschoolID'] . $_POST['myfile'] . ".jpg"; //文件的存储路径和名称
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
				$mode = substr($_POST['myfile'], 0, 2); //教师js或课程kc
				$num = substr($_POST['myfile'], 2); //id
				if ($mode == 'js')
				{
					$tbName = 'user_tb' . $_SESSION['SschoolID']; 
				}
				else
				{
					$tbName = 'eventName_tb' . $_SESSION['SschoolID']; 
				}
				$sql_update = "UPDATE ".$tbName. " SET pic = '1' WHERE id = '$num'"; 
				if ($mysqli->query($sql_update))
				{
					echo "<script>alert('上传成功！');</script>";
					echo "<script>window.location.href='showPicture.php?num=" . $_POST['myfile'] . "';</script>"; 
				}
				else
				{
					echo "<script>alert('修改数据库失败!');</script>";
				}
			}
			else
			{
				echo "<script>alert('复制文件失败！');</script>";
			}
		}
		else
		{
			echo "<script>alert('请上传jpg文件！');</script>";
		}
	}
	else
	{
		echo "<script>alert('文件过大或不存在！');</script>";
	}
}
else
{
	echo "<script>alert('请选择上传文件！');</script>";
}
?>