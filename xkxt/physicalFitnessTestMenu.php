<?php
session_start();
@header("content-type:text/html;charset=utf-8");
include("conn.php");
include("FunCreateTestTB.php");

$honorNum = 0;
$schoolID = $_SESSION['schoolID'];
$sql_select = "SELECT honorNum, physicalFitnessTestFlag FROM school_tb WHERE id = '$schoolID'";
$honorResult = $mysqli->query($sql_select);
if ($honorRow = $honorResult->fetch_array())
{
	$honorNum = $honorRow['honorNum'];
	$physicalFitnessTestFlag = $honorRow['physicalFitnessTestFlag'];
}

if ($honorNum < 6)
{
	echo '<h2 style="text-align: center">老师，您好！欢迎使用“爱运动管理系统”！</h2>';
	echo '<h2 style="text-align: center">本功能的开通权限为：荣誉勋章数量不少于 6 枚</h2>';
	echo '<h2 style="text-align: center">很遗憾，您当前荣誉勋章数量为：<span style="color: #F00">' . $honorNum  . '</span></h2>';
	echo '<h2 style="text-align: center">“爱运动管理系统”希望能为您提供更好的服务，同时也希望能得到您善意的回应。</h2>';
	echo '<h2 style="text-align: center">荣誉勋章数量代表您对网站贡献的大小！把系统介绍给更多的人，是您对我们最大的支持！</span></h2>';
	echo "<script>alert('本功能的开通权限为：荣誉勋章数量不少于 6 枚');</script>";
	echo "<script>alert('我知道了，我会积极关注爱运动，努力赢取勋章！');</script>";
	echo "<script>window.location.href='honorNumInfo.php?honorNum=" . $honorNum . "';</script>";
}
else
{
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>体能测试菜单</title>
    </head>
    
    <body>
    <?php
    if (isset($_SESSION['usertype']) && $_SESSION['usertype'] == 'x')
    {
		if ($physicalFitnessTestFlag == '0')
		{
			echo "<script>alert('创建体测数据库！');</script>";
			if (CreateTestTB($schoolID) == true)
			{
				$sql_update = "UPDATE school_tb SET physicalFitnessTestFlag = '1' WHERE id = '$schoolID'"; 
				if ($mysqli->query($sql_update))
				{
					echo "<script>alert('体测数据库创建成功！');</script>";
				}
			}
		}
		else
		{
    ?>
            <table width="90%" height="150" border="1" align="center" cellpadding="0" cellspacing="1" style="font-size: 14px;background:url(images/bj1.jpg)">
            <tr>
                <th colspan="5" height="40" align="center">国家学生体质健康标准（2014年修订）成绩转换和统计</th>
            </tr>
            <tr>
                <th height="40" align="center">阶段</th>
                <th height="40" align="center">功能1</th>
                <th height="40" align="center">功能2</th>
                <th height="40" align="center">功能3</th>
                <th height="40" align="center">功能4</th>
              </tr>
              <tr>
                <td height="40" align="center">小学</td>
                <td height="40" align="center"><a href="downloadPhysicalFitnessTestPrimary.php?table=primaryschoollib_tb" target="_blank">生成学生成绩表</a></td>
                <td height="40" align="center"><a href="downloadAveragePrimary.php" target="_blank">统计班级平均分</a></td>
                <td height="40" align="center">登分卡数据源&nbsp;<a href="downloadPhysicalFitnessScorePrimary.php?table=12_tb" target="_blank">(1-2)</a>&nbsp;<a href="downloadPhysicalFitnessScorePrimary.php?table=34_tb" target="_blank">(3-4)</a>&nbsp;<a href="downloadPhysicalFitnessScorePrimary.php?table=56_tb" target="_blank">(5-6)</a></td>
                <td height="40" align="center">登分卡邮件合并模板&nbsp;<a href="xxchengjika1_2.doc" target="_blank">(1-2)</a>&nbsp;<a href="xxchengjika3_4.doc" target="_blank">(3-4)</a>&nbsp;<a href="xxchengjika5_6.doc" target="_blank">(5-6)</a></td>
              </tr>
              <tr>
                <td height="40" align="center">初中</td>
                <td height="40" align="center"><a href="downloadPhysicalFitnessTest.php?table=midschoollib_tb" target="_blank">生成学生成绩表</a></td>
                <td height="40" align="center"><a href="downloadAverage.php" target="_blank">统计班级平均分</a></td>
                <td height="40" align="center"><a href="downloadPhysicalFitnessScore.php?table=midschoollib_tb" target="_blank">登分卡excel数据源</a></td>
                <td height="40" align="center">登分卡邮件合并模板&nbsp;<a href="czchengjika.doc" target="_blank">（版本1）</a>&nbsp;<a href="czchengjika2.doc" target="_blank">（版本2）</a></td>
              </tr>
              <tr>
                <td height="40" align="center">高中</td>
                <td height="40" align="center"><a href="downloadPhysicalFitnessTest.php?table=highschoollib_tb" target="_blank">生成学生成绩表</a></td>
                <td height="40" align="center"><a href="downloadAverage.php" target="_blank">统计班级平均分</a></td>
                <td height="40" align="center"><a href="downloadPhysicalFitnessScore.php?table=highschoollib_tb" target="_blank">登分卡excel数据源</a></td>
                <td height="40" align="center">登分卡邮件合并模板&nbsp;<a href="gzchengjika.doc" target="_blank">（版本1）</a>&nbsp;<a href="gzchengjika2.doc" target="_blank">（版本2）</a></td>
              </tr>
              <tr>
                <td height="40" align="center">大学</td>
                <td height="40" align="center"><a href="downloadPhysicalFitnessTest.php?table=universitylib_tb" target="_blank">生成学生成绩表</a></td>
                <td height="40" align="center"><a href="downloadAverage.php" target="_blank">统计班级平均分</a></td>
                <td height="40" align="center"><a href="downloadPhysicalFitnessScore.php?table=universitylib_tb" target="_blank">登分卡excel数据源</a></td>
                <td height="40" align="center"><a href="dxchengjika.doc" target="_blank">登分卡邮件合并word模板</a></td>
              </tr>
              <tr>
                <th height="40" colspan="5" align="center">注意：执行功能1,2,3之前需要先<a href="uploadingMenu.php" target="_blank">上传体能测试成绩文件</a>；不了解文件格式的，请先<a href="downloadExample.php" target="_blank">下载体能测试模板</a></th>
              </tr>
               <tr>
                <th height="40" colspan="5" align="center">为了满足不同word版本的格式需求，初中和高中的“登分卡邮件合并word模板”提供了2个版本，大家可以选择使用适合自己的版本</th>
              </tr>
            </table>
            <table width="90%" height="150" border="1" align="center" cellpadding="0" cellspacing="1" style="font-size: 14px;background:url(images/bj1.jpg)">
            <tr>
                <th height="40" align="center"><a href="tncsydh.php" target="_blank">私人定制体能测试运动会</a></th>
              </tr>
            </table>
    <?php
		}
    }
    else
    {
        echo "<h1>系统管理员尚未登录！请登录系统管理员账号！</h1>";
    }
}
?>
</body>
</html>