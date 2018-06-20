<?php
@session_start();
@header("content-type:text/html;charset=utf-8");

//存储学校比赛信息
function CreateTB($schoolID, $adminName, $adminPWD)
{
	include("conn.php");
	
	$myflag = true;
	//创建user_tb
	$tableName = 'user_tb' . $schoolID;
	$sql_create = "CREATE TABLE IF NOT EXISTS $tableName 
	(
		id int NOT NULL AUTO_INCREMENT, 
		PRIMARY KEY(id),
		nickname	varchar(20),
		username	varchar(20),
		psw         varchar(60) DEFAULT '123456',
		userType	char(1) DEFAULT 'j',
		permission	tinyint(1) DEFAULT 1,
		pic	        tinyint(1) DEFAULT 0,
		classID     int(11) DEFAULT 1,
		sex	        char(1) DEFAULT 'f',
		course1     int(11) DEFAULT 0,
		course2     int(11) DEFAULT 0,
		course3     int(11) DEFAULT 0,
		course4     int(11) DEFAULT 0,
		course5     int(11) DEFAULT 0,
		course6     int(11) DEFAULT 0,
		course7     int(11) DEFAULT 0,
		course8     int(11) DEFAULT 0
	)";  
	if ($mysqli->query($sql_create))
	{
		$sql_insert = "INSERT INTO $tableName(nickname, username, psw, userType) VALUES ('管理员','$adminName', '$adminPWD', 'g')";
		if (!$mysqli->query($sql_insert))
		{
			$myflag = false;
			echo $tableName . '设置系统管理员失败！';
		}
	}
	else
	{
		$myflag = false;
		echo '创建数据库表 ' . $tableName . ' 失败！';
	}	
	
	//创建class_tb
	$tableName = 'class_tb' . $schoolID;
	$sql_create = "CREATE TABLE IF NOT EXISTS $tableName 
	(
		id int NOT NULL AUTO_INCREMENT, 
		PRIMARY KEY(id),
		gradeName varchar(20),
		majorNum varchar(2) DEFAULT '0',
		className varchar(50),
		classNum	int(11) DEFAULT 0,
		leader varchar(20),
		studentsNum  int(11) DEFAULT 0
	)";  
	if (!$mysqli->query($sql_create))
	{
		$myflag = false;
		echo '创建数据库表 ' . $tableName . ' 失败！';
	}	
	
	//创建eventName_tb
	$tableName = 'eventName_tb' . $schoolID;
	$sql_create = "CREATE TABLE IF NOT EXISTS $tableName 
	(
	 	id int NOT NULL AUTO_INCREMENT, 
		PRIMARY KEY(id),
		eventName varchar(100),
		teacherName varchar(20),
		teacherID   varchar(20),
		actTime varchar(10),
		address varchar(20),
		sex	char(1) DEFAULT 'b',
		courseNum int(11),
		courseStyle tinyint(4),
		pic tinyint(1) DEFAULT 0,
		termNum tinyint(4) DEFAULT 0,
		maxNum	int(11) DEFAULT 0,
		trueNum	int(11) DEFAULT 0,
		limitmajor1 smallint(6)  DEFAULT 0,
		limitmajor2 smallint(6)  DEFAULT 0,
		limitmajor3 smallint(6)  DEFAULT 0,
		limitmajor4 smallint(6)  DEFAULT 0,
		limitmajor5 smallint(6)  DEFAULT 0,
		limitmajor6 smallint(6)  DEFAULT 0
	)";  
	if (!$mysqli->query($sql_create))
	{
		$myflag = false;
		echo '创建数据库表 ' . $tableName . ' 失败！';
	}	
	
	//创建enroll_tb
	$tableName = 'enroll_tb' . $schoolID;
	$sql_create = "CREATE TABLE IF NOT EXISTS $tableName 
	(
		id int NOT NULL AUTO_INCREMENT, 
		PRIMARY KEY(id),
		userID	varchar(20),
		classID	int(11),
		eventID	int(11),
		restOfTerm tinyint(4) DEFAULT 0,
		changeFlag	char(1) DEFAULT '0',
		level	char(1) DEFAULT 'C',
		score float DEFAULT 0,
		credit float DEFAULT 0,
		crunkNum int(11) DEFAULT 0,
		upTime	datetime
	)";  
	if (!$mysqli->query($sql_create))
	{
		$myflag = false;
		echo '创建数据库表 ' . $tableName . ' 失败！';
	}	
	
	//创建class_event_tb
	$tableName = 'class_event_tb' . $schoolID;
	$sql_create = "CREATE TABLE IF NOT EXISTS $tableName 
	(
		id int NOT NULL AUTO_INCREMENT, 
		PRIMARY  KEY(id),
		classID	 int(11),
		eventID	 int(11),
		maxNum	 int(11) DEFAULT 0,
		trueNum	 int(11) DEFAULT 0,
		stuRange char(1) DEFAULT 'b'
	)";  
	if (!$mysqli->query($sql_create))
	{
		$myflag = false;
		echo '创建数据库表 ' . $tableName . ' 失败！';
	}	
	
	$mysqli->close(); 
	
	return $myflag;
}
?>