<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>上传文件</title>
<style type="text/css">
<!--
.STYLE1 {
	font-size: 36px;
	color: #990000;
	font-weight: bold;
}
body,td,th {
	font-size: 12px;
}
-->
</style>
</head>

<body>
<div align="center">
  <form action="uploading_ok.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
    <input type="hidden" name="myfile" id="myfile" value="<?php echo $_GET["myfile"]; ?>"/>
    <table id="__01" width="600" height="300" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="550" height="150" align="center" valign="top"><table width="400" height="150" border="1" align="center">
          <tr>
            <td  width="150" height="30">文件上传：（<font color="#FF0000">.xls文件</font>）</td>
            <td align="left"><input name="files" type="file" size="31" /></td>
          </tr>
          <tr>
            <td height="20" colspan="2"><div align="center">
                <input type="submit" name="Submit" value="上传" />
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="reset" name="Reset" value="取消" />
            </div></td>
          </tr>
        </table></td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>
