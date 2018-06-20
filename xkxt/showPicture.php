<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>显示图片资料</title>
</head>

<body>
<table width="68%" border="1" align="center" style="border:solid 10px #dddddd">
  <tr>
     
    <td align="center"><?php echo '<img src="' . 'schoolFile/user' . $_SESSION['SschoolID'] . $_GET["num"] . ".jpg" . '" width="100%"/>'; ?></td>
  </tr>
</table>
</body>
</html>