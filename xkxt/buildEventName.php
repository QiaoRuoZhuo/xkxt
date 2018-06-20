<?php
    @header("content-type:text/html;charset=utf-8");
	@session_start();
	include("conn.php");
	require_once 'reader.php';  
	
	$eventName_tb = 'eventName_tb' . $_SESSION['SschoolID'];
	$sql_truncate = "TRUNCATE TABLE $eventName_tb"; //清空数据表
	$mysqli->query($sql_truncate);

    $data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('utf-8');//设置在页面中输出的编码方式,而不是utf
	$fileName = 'schoolFile/user' . $_SESSION['SschoolID'] . 'kecheng.xls'; //文件的存储路径和名称
	$data->read($fileName); 
	
	$limitmajorArr = array();
	$sql_insert = "INSERT INTO $eventName_tb (eventName, courseNum, courseStyle, address, actTime, teacherName, teacherID, sex, termNum, maxNum";
	for ($j=1; $j<=6; ++$j) 
	{
		$sql_insert .= ", limitmajor" . $j;
	}	
	
	$sql_insert .= ") VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?";		
	for ($j=1; $j<=6; ++$j) 
	{
		$sql_insert .= ", ?";
	}	
	$sql_insert .= ")"; 
	$stmt = $mysqli->prepare($sql_insert);
	$stmt->bind_param('siisssssiiiiiiii', $eventName, $courseNum, $courseStyle, $address, $actTime, $teacherName, $teacherID, $sex, $termNum, $maxNum, $limitmajorArr[1], $limitmajorArr[2], $limitmajorArr[3], $limitmajorArr[4], $limitmajorArr[5], $limitmajorArr[6]);

	for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) //把excel表格中的数据插入到数据库表
	{ 
		if (!isset($data->sheets[0]['cells'][$i][1]) || '' == $data->sheets[0]['cells'][$i][1])
		{
			break;
		}
		$courseNum = $data->sheets[0]['cells'][$i][1]; //存储课程编号
		$eventName = $data->sheets[0]['cells'][$i][2]; //存储课程名称
		$courseStyle = $data->sheets[0]['cells'][$i][3]; //存储课程类别
		$address =  $data->sheets[0]['cells'][$i][4]; //存储上课地点
		$actTime =  $data->sheets[0]['cells'][$i][5]; //存储上课时间
		$teacherName = $data->sheets[0]['cells'][$i][6]; //存储任课教师姓名
		$teacherID = $data->sheets[0]['cells'][$i][7]; //存储任课教师用户名
		$sex = $data->sheets[0]['cells'][$i][8]; //存储报名学员性别，“男”表示只有男子组，“女”表示只有女子组，“男女”表示两者皆有
		$termNum = $data->sheets[0]['cells'][$i][9]; //存储该课程跨学期数量
		$maxNum = $data->sheets[0]['cells'][$i][10]; //存储该课程报名限额
		
		if ($sex == '男')
		{
			$sex = 'm';
		}
		else if ($sex == '女')
		{
			$sex = 'f';
		}
		else 
		{
			$sex = 'b';
		}
		
		for ($j=1, $k=$j+10; $j<=6; ++$j, ++$k) //存储专业j人数上限
		{
			if (isset($data->sheets[0]['cells'][$i][$k]) && '' != $data->sheets[0]['cells'][$i][$k])
			{
				$limitmajorArr[$j] = $data->sheets[0]['cells'][$i][$k];
			}
			else
			{
				$limitmajorArr[$j] = 0;
			}
		}
		
		if (!$stmt->execute())
		{
			echo "<script>alert('读取课程信息错误！');</script>";
		}
	}

	$stmt->close();
	$mysqli->close();
	
	echo "<script>window.location.href='saveClassEvent.php';</script>"; 
?>
  