<!doctype HTML>
<html>
<head>
<style>
p{text-align:center; padding-top:5em; line-height:1.5em;};
</style>
</head>
<body>
<p>Watch something random:<br><br>
<?php
$mysqli = mysqli_connect("localhost", "root", "password", "eplist");
if (mysqli_connect_errno($mysqli)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$res = mysqli_query($mysqli, "select `summary`, `series name` series from eplist order by rand() limit 10;");

$row = mysqli_fetch_all($res,MYSQLI_NUM);
foreach ($row as $i) {
echo "<a href=\"http://www.google.com.au/search?q=" . $i[0] . "+" . $i[1] . "\">";
echo $i[0] . "</a><br>";
}
?>
</p>
</body>
</html>
