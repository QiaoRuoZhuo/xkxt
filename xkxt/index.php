<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>选课系统</title>
<link rel="stylesheet" href="indexstyle.css" />
	<link href='http://fonts.googleapis.com/css?family=Oleo+Script' rel='stylesheet' type='text/css'>
	<script type="text/javascript" src="jquery-1.7.min.js"></script>
	<script type="text/javascript">
	function chkinput(form)
	{
		if (form.username.value=="")
		{
			alert('请输入用户名');
			form.username.focus();
			return false;
		}
		
		if (form.psw.value=='')
		{
			alert('请输入密码');
			form.psw.focus();
			return false;
		}
		
		if(form.userType.value=="")
		{
		   alert("请选择用户类型！");
		   form.userType.focus();
		   return false;
		} 

		return true;
	}
	</script>
</head>

<body>
<div class="lg-container">
		<form action="enterOk.php" id="lg-form" name="lg-form" method="post" onsubmit="return chkinput(this)">
            <div>
                <label for="userType">用户类型：</label>
				<select name="userType" id="userType">
                  <option value="">请选择</option>
                  <option value="x">学生</option>
                  <option value="j">教师</option>
                  <option value="g">管理员</option>
              </select>
			</div>
            <div>
                <label for="schoolname">学校名称：</label>
				<input type="text" name="schoolname" id="schoolname" placeholder="示例：余姚市第二中学"/>
			</div>
			<div>
                <label for="username">用户名：</label>
				<input type="text" name="username" id="username" placeholder="示例：G330281200001127444"/>
			</div>
			<div>
				<label for="psw">密码:</label>
				<input type="password" name="psw" id="psw"/>
			</div>
			<div align="center">
              <button type="submit" id="btnSubmit">登陆</button> 
			</div>
		</form>
</div>
 
</body>
</html>