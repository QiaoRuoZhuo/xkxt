<?php
@session_start();
include("conn.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登陆成功页面</title>
</head>  

<body>
<?php
$fur0 = "index.php"; 
$fur1 = "studentMenu.php";
$fur2 = "teacherMenu.php";
$fur3 = "adminMenu.php";
$fur4 = "bossMenu.php";

if(isset($_POST['schoolname']) && isset($_POST['userType']) && isset($_POST['username']) && isset($_POST['psw']))
{
	$myschoolname = trim($_POST['schoolname']);
	$myusertype = $_POST['userType'];
	$myusername=trim($_POST['username']);
	$mypassword=trim($_POST['psw']); 
	
	if ($myusertype=='g' && $myschoolname=='总指挥' && $myusername=='总指挥' && $mypassword=='总指挥')//系统管理员登陆
	{
		echo "<meta http-equiv=\"Refresh\" content=\"1;url=$fur4\">2秒钟后转入页面,请稍等....";
	}
	else
	{
		$queryString = "SELECT id, rockFlag FROM school_tb WHERE schoolName='$myschoolname'"; 
		$result = $mysqli->query($queryString);
		if ($rowSchool = $result->fetch_array()) 
		{
			if ($rowSchool['rockFlag'] == 0)
			{
				$user_tb = 'user_tb' . $rowSchool['id'];
				$sql_select = "SELECT permission, nickname, classID, sex FROM $user_tb WHERE username=? AND psw=? AND userType=?";
				$stmt = $mysqli->prepare($sql_select);
				$stmt->bind_param('sss', $myusername, $mypassword, $myusertype);
				if ($stmt->execute())
				{
					$stmt->store_result();
					if ($stmt->num_rows > 0)
					{
						$row = array();
						$stmt->bind_result($row['permission'], $row['nickname'], $row['classID'], $row['sex']);
						if ($stmt->fetch())
						{
							if ($row['permission'] == '1' || $myusertype == 'g' || $myusertype == 'j') //有权限登陆
							{
								$_SESSION['SschoolID'] = $rowSchool['id'];
								$_SESSION['Susername'] = $myusername;
								$_SESSION['Spassword'] = $mypassword;
								$_SESSION['SuserType'] = $myusertype;
								$_SESSION['Snickname'] = $row['nickname'];
								$_SESSION['SclassID'] = $row['classID'];  
								$_SESSION['Ssex'] = $row['sex']; 
								
								echo "<font class=\"#ff0000\">$myusername.恭喜您登录成功！</font>";
								if ($myusertype == 'x')
								{
									echo "<meta http-equiv=\"Refresh\" content=\"1;url=$fur1\">2秒钟后转入页面,请稍等....";
								}
								else if ($myusertype == 'j')
								{
									echo "<meta http-equiv=\"Refresh\" content=\"1;url=$fur2\">2秒钟后转入页面,请稍等....";
								}
								else 
								{
									echo "<meta http-equiv=\"Refresh\" content=\"1;url=$fur3\">2秒钟后转入页面,请稍等....";
								}
							}
							else
							{
								echo "选课系统尚未解锁...";
								echo "<meta http-equiv=\"Refresh\" content=\"2;url=$fur0\">2秒后转入前页...";
							}
						}
					}
					else
					{
						echo "您输入的用户名<b>$myusername</b>不存在或密码不正确...";
						echo "<meta http-equiv=\"Refresh\" content=\"3;url=$fur0\">3秒后转入前页...";
					}
					$stmt->free_result();
				}
				$stmt->close();
			}
			else
			{
				echo "<h1><b>$myschoolname</b> 账号被锁定，请及时通知系统管理员解锁！</h1>";
				echo "<meta http-equiv=\"Refresh\" content=\"3;url=$fur0\">3秒后转入主页...";
			}
		}
		else
		{
			echo "您输入的学校名称<b>$myschoolname</b>不存在！";
			echo "<meta http-equiv=\"Refresh\" content=\"3;url=$fur0\">3秒后转入主页...";
		}
	}
}
else
{
	echo "<font class=\"#ff0000\">用户名或密码不能为空!!!</font>" ;
	echo "<meta http-equiv=\"Refresh\" content=\"3;url=$fur0\">3秒后转入前页...";
}
?>
</body>
</html>
