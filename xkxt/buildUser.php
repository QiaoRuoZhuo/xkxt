<?php
	@session_start();
	include("conn.php");
	require_once 'reader.php'; 
	
	$class_tb = 'class_tb' . $_SESSION['SschoolID'];
	$user_tb = 'user_tb' . $_SESSION['SschoolID'];
	$sql_truncate = "TRUNCATE TABLE $user_tb"; //清空数据表
	$mysqli->query($sql_truncate);
	
	//插入系统管理员
	$sql_insert = "INSERT INTO $user_tb(nickname, username, psw, userType) VALUES ('管理员','$_SESSION[Susername]', '$_SESSION[Spassword]', 'g')";
	$mysqli->query($sql_insert);

    $data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('utf-8');//设置在页面中输出的编码方式,而不是utf
	$fileName = 'schoolFile/user' . $_SESSION['SschoolID'] . 'yonghu.xls'; //文件的存储路径和名称
	$data->read($fileName); 
	//插入教师信息
	$sql_insert_j = "INSERT INTO $user_tb (username, psw, nickname, sex, userType) VALUES(?, ?, ?, ?, ?)"; 
	$stmt_j = $mysqli->prepare($sql_insert_j);
	$stmt_j->bind_param('sssss', $username, $psw, $nickname, $sex, $userType); 
	//插入学生信息
	$courseArr = array();
	$sql_insert_x = "INSERT INTO $user_tb(classID, username, psw, nickname, sex, userType";
	for ($j=1; $j<=8; ++$j) 
	{
		$sql_insert_x .= ", course" . $j;
	}	
	$sql_insert_x .= ") VALUES(?, ?, ?, ?, ?, ?"; 
	for ($j=1; $j<=8; ++$j) 
	{
		$sql_insert_x .= ", ?";
	}	
	$sql_insert_x .= ")";   
	$stmt_x = $mysqli->prepare($sql_insert_x);
	$stmt_x->bind_param('isssssiiiiiiii', $classID, $username, $psw, $nickname, $sex, $userType,
			$courseArr[1], $courseArr[2], $courseArr[3], $courseArr[4], $courseArr[5], $courseArr[6], $courseArr[7], $courseArr[8]);

	for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) //把excel表格中的数据插入到数据库表
	{ 
		if (!isset($data->sheets[0]['cells'][$i][1]) || '' == $data->sheets[0]['cells'][$i][1])
		{
			break;
		}
		$username = $data->sheets[0]['cells'][$i][1]; //存储用户名
		$psw = $data->sheets[0]['cells'][$i][2]; //存储密码
		$gradeName = $data->sheets[0]['cells'][$i][3]; //存储年级
		$className = $data->sheets[0]['cells'][$i][4]; //存储班级
		$nickname = $data->sheets[0]['cells'][$i][5]; //存储姓名
		$sex = $data->sheets[0]['cells'][$i][6]; //存储性别
		$userType = $data->sheets[0]['cells'][$i][7]; //存储用户类型
		
		if ($sex == '男')
		{
			$sex = 'm';
		}
		else
		{
			$sex = 'f';
		}
		
		for ($j=1, $k=$j+7; $j<=8; ++$j, ++$k) //存储课程类型j限额（共8个课程类型）
		{
			if (isset($data->sheets[0]['cells'][$i][$k]) && '' != $data->sheets[0]['cells'][$i][$k])
			{
				$courseArr[$j] = $data->sheets[0]['cells'][$i][$k];
			}
			else
			{
				$courseArr[$j] = 0;
			}
		}
		
		if ($userType == 'j')
		{
			if (!$stmt_j->execute())
			{
				echo "<script>alert('读取教师信息错误！');</script>";
			}
		}
		else if ($userType == 'x')
		{
			$sql = "SELECT classNum FROM $class_tb    
					WHERE gradeName='$gradeName' AND className='$className'"; 
			$result = $mysqli->query($sql);
			if ($row = $result->fetch_array()) 
			{
				$classID = $row['classNum'];
				if (!$stmt_x->execute())
				{
					echo "<script>alert('读取学生信息错误！');</script>";
				}
			}
			else
			{
				echo "<script>alert('用户" . $username . "的级段名称与班级模板中不一致！请确认已经上传了正确的班级模板！');</script>";
				echo "<script>window.location.href='manageUser.php';</script>"; 
			}
		}
	}
	$stmt_j->close();
	$stmt_x->close();
	$mysqli->close();
	
	echo "<script>window.location.href='manageUser.php';</script>"; 
?>
  