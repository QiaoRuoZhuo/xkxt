<?php
    @session_start();
	include("conn.php");
	require_once 'reader.php'; 
	
    $data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('utf-8');//设置在页面中输出的编码方式,而不是u
	$fileName = 'schoolFile/user' . $_SESSION['SschoolID'] . 'chengji' . $_SESSION['Susername'] . '.xls'; //文件的存储路径和名称
	$data->read($fileName); 
	
	$eventName_tb = 'eventName_tb' . $_SESSION['SschoolID'];
    $enroll_tb = 'enroll_tb' . $_SESSION['SschoolID'];
	$sql_update = "UPDATE $enroll_tb SET crunkNum=?, score=?, level=?, credit=? WHERE eventID=? AND userID=?"; 
	$stmt = $mysqli->prepare($sql_update);
	$stmt->bind_param('idsdis', $crunkNum, $score, $level, $credit, $eventID, $userID);		
	
	$num1 = 0;   //更新该生的新选课程编号和剩余学期数出错
	$num2 = 0;   //课程编号信息错误
	for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) //把excel表格中的数据插入到数据库表
	{ 
		if (!isset($data->sheets[0]['cells'][$i][1]) || '' == $data->sheets[0]['cells'][$i][1])
		{
			break;
		}
		
		$eventID = $data->sheets[0]['cells'][$i][1]; //存储课程编号
		$userID = $data->sheets[0]['cells'][$i][2]; //存储准考证号
		$crunkNum = $data->sheets[0]['cells'][$i][3]; //存储旷课数量
		$score = $data->sheets[0]['cells'][$i][4]; //存储成绩
		$level =  $data->sheets[0]['cells'][$i][5]; //存储等级
		$credit =  $data->sheets[0]['cells'][$i][6]; //存储学分
		
		$queryString =  "SELECT E.id  
					   FROM $eventName_tb AS EN, 
					   $enroll_tb AS E 
					   WHERE EN.teacherID='$_SESSION[Susername]' AND E.restOfTerm='1' AND E.eventID='$eventID' AND E.eventID=EN.courseNum"; //判断教师上传的成绩是否为其带的班，并且要求该科目剩余学期为1，即本学期将结束的科目
		$resultSearch = $mysqli->query($queryString); //echo $queryString . "<br/>";
		$total = $resultSearch->num_rows;
		
		if ($total > 0 || $_SESSION['SuserType'] == 'g')
		{
			if (!$stmt->execute())//更新该生的新选课程编号和剩余学期数
			{
				$num1++;
				$errorMessage = '错误' . $num1 . '提示：请检查' . $userID . '是否选修了该课程' .$eventID;
				echo "<script>alert('". $errorMessage ."');</script>";
			}
		}
		else
		{
			$num2++;
		}
	}
	
	$stmt->close();
	$mysqli->close();
	if ($num2 > 0)
	{
		echo "<script>alert('请检查成绩表中是否有课程编号信息错误，修改后重新提交！');</script>";
	}
	else
	{
		echo "<script>alert('输入成绩成功！');</script>";
	} 
	if ($_SESSION['SuserType'] != 'g')
	{
		echo "<script>window.location.href='inputScore.php';</script>"; 
	}
	else
	{
		echo "<script>window.location.href='displayEnroll.php';</script>"; 
	}
?>
  