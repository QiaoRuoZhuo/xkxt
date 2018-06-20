<?php
session_start();
include("conn.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理用户</title>

</head>

<body>

<table width="80%" height="80" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
    <td align="center"><a href="addSchool.php" target="_blank">添加学校</a></td>
    <td align="center"><a href="buildBigDataLib.php" target="_blank">存储大数据</a></td>
  </tr>
</table>
        
<?php
		echo ' <table width="80%" border="1" align="center" cellpadding="0" cellspacing="0" style="background:url(images/bj1.jpg);font-size: 12px; border:solid 10px #dddddd">'; 
		echo '<tr>';
		echo '<th align="center" height="20"> 序号</th>';
		echo '<th align="center"> 学校名称</th>';
		echo '<th align="center"> 用户名</th>';
		echo '<th align="center"> 密码</th>';
		echo '<th align="center"> 邮箱</th>';
		echo '<th align="center"> 电话号码</th>';
		echo '<th align="center"> 注册日期</th>';
		echo '<th align="center"> 状态</th>';
		echo '<th align="center"> 操作</th>';
		echo '</tr>';
		
		$queryString = "SELECT * FROM school_tb"; 
		$result = $mysqli->query($queryString);
		$total = $result->num_rows;
		
		if ($total==0)
		{
		   echo '<tr>';
		   echo "<div align=center>对不起，暂无数据！</div>";
		   echo '</tr>';
		   echo '</table>';
		}
		else
		{
			$result = $mysqli->query($queryString);
			while($row = $result->fetch_array()) 
			{
				echo '<tr>';
				echo '<td align="center" height="20">' . $row['id'] . '</td>';
				echo '<td align="center">' .'<a href="updateSchool.php?schoolID='.$row['id'] .'">'. $row['schoolName'] .'</a>' . '</td>';
				echo '<td align="center">' . $row['adminName'] . '</td>';
				echo '<td align="center">' . $row['adminPWD'] . '</td>';
				echo '<td align="center">' . $row['email'] . '</td>';
				echo '<td align="center">' . $row['telephone'] . '</td>';
				echo '<td align="center">' . $row['regDate'] . '</td>';
				if ($row['rockFlag'] == '0')
				{
					echo '<td align="center">' .'<a href="updateFlag.php?id='.$row['id'] .'&prepage='.$_SERVER['PHP_SELF'].'&flag='.'rockFlag'.'&value='.$row['rockFlag'].'">' . '允许'. '</a>' . '</td>';
				}
				else
				{
					echo '<td align="center">' .'<a href="updateFlag.php?id='.$row['id'] .'&prepage='.$_SERVER['PHP_SELF'].'&flag='.'rockFlag'.'&value='.$row['rockFlag'].'">' . '禁用'. '</a>' . '</td>';
				}
				
				echo '<td align="center" width="100">' .'<a href="truncateTB.php?id='.$row['id'] .'&prepage='.$_SERVER['PHP_SELF'].'">清空</a> &nbsp;' . '<a href="javascript:if(confirm(' . "'确定删除文件吗?'))location='" . 'dropTB.php?id='.$row['id'] .'&prepage='.$_SERVER['PHP_SELF']. "'" . '">删除</a>' . '</td>';
				echo '</tr>';
			}  //分页显示
			echo '</table>'; 
		}
		$mysqli->close();
	
  ?>
</body>
</html>
<?php include("bottom.php"); ?>