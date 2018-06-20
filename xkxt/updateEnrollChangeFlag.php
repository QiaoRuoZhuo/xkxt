<?php
@session_start();
include("conn.php");

$id = $_GET["id"];
$prePage = $_GET["prepage"]; 
$changeFlag = $_GET["changeFlag"]; 
$enroll_tb = 'enroll_tb' . $_SESSION['SschoolID'];

if ($changeFlag == '0')
{
	$sql_update = "UPDATE $enroll_tb SET changeFlag='0' WHERE userID='$_SESSION[Susername]' AND changeFlag='1'";
	if ($mysqli->query($sql_update))
	{
		$sql_update = "UPDATE $enroll_tb SET changeFlag='1' WHERE id='$id'"; 
		$mysqli->query($sql_update);
	}		   
}
else
{
	$sql_update = "UPDATE $enroll_tb SET changeFlag='0' WHERE id='$id'"; 
	$mysqli->query($sql_update);
}

echo "<script>window.location.href='$prePage';</script>"; 
?>