<?php
$nev=$_POST['nev'];
$score=$_POST['score'];
$dbconnect=mysqli_connect('localhost:3308','root','','snake');
$sql=mysqli_query($dbconnect,"INSERT INTO users(nev,score) values('$nev','$score')");
if($sql)
{
	echo "Siker!";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Menü</title>
</head>
<body>
<script>
 window.location = "Snake.php";
	

</script>
</body>
</html>