<!doctype HTML>
<html>
<head>
<style>
p{text-align:center; line-height:2em;color:light-green;}
td{text-align:center;}
body{background-color:powderblue;}
table{padding-left:5em; margin-left:auto; margin-right: auto;}
div{padding-top: 3em;}
</style>
</head>
<body>
<div>
<p>Current episode: <strong>
<?php
$mysqli = mysqli_connect("localhost", "root", "password", "eplist");
if (mysqli_connect_errno($mysqli)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$res = mysqli_query($mysqli, "select @min:=min(airdate) from eplist where seendate is null;");
$res = mysqli_query($mysqli, "update eplist set seendate = now() where airdate = @min;");
?>
<script>
window.open("index.php", "_self", false)
</script>
</p>
</div>
</body>
</html>
