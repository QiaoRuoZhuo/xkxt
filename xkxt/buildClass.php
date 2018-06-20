<?php
    @session_start();
	include("conn.php");
	require_once 'reader.php'; 
	
	$class_tb = 'class_tb' . $_SESSION['SschoolID'];
	$sql_truncate = "TRUNCATE TABLE $class_tb"; //清空数据表
	$mysqli->query($sql_truncate);
	
    $data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('utf-8');//设置在页面中输出的编码方式,而不是utf
	$fileName = 'schoolFile/user' . $_SESSION['SschoolID'] . 'banji.xls'; //文件的存储路径和名称
	$data->read($fileName); 
	
	$sql_insert = "INSERT INTO $class_tb (classNum, gradeName, majorNum, className, leader, studentsNum) VALUES(?, ?, ?, ?, ?, ?)"; 
	$stmt = $mysqli->prepare($sql_insert);
	$stmt->bind_param('issssi', $classNum, $gradeName, $majorNum, $className, $leader, $studentsNum);

	for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) //把excel表格中的数据插入到数据库表
	{ 
		if (!isset($data->sheets[0]['cells'][$i][1]) || '' == $data->sheets[0]['cells'][$i][1])
		{
			break;
		}
		$classNum = $data->sheets[0]['cells'][$i][1]; //存储班级编号
		$gradeName = $data->sheets[0]['cells'][$i][2]; //存储年级名称
		$majorNum = $data->sheets[0]['cells'][$i][3]; //存储班级
		$className = $data->sheets[0]['cells'][$i][4]; //存储班级
		$leader =  $data->sheets[0]['cells'][$i][5]; //存储班主任名字
		$studentsNum =  $data->sheets[0]['cells'][$i][6]; //存储班级总人数
		
		if (!$stmt->execute())
		{
			echo "<script>alert('读取班级信息错误！');</script>";
		}
	}
	
	$stmt->close();
	$mysqli->close();
	echo "<script>window.location.href='displayClassMode.php';</script>"; 
?>
  