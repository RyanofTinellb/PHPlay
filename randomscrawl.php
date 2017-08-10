<!doctype HTML>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{background-color: black;color: white;background-image:url('large.png')}
p{text-align:center; padding-top:5em; line-height:1.5em;}
@font-face {font-family: trek; src:url('/finalnew.ttf')}
trek {font-family: trek}
a {color: yellow};
</style>
</head>
<body>
<p>Watch some random Trek:<br><br><marquee><trek>
<?php
$mysqli = mysqli_connect("localhost", "root", "password", "eplist");
if (mysqli_connect_errno($mysqli)) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$res = mysqli_query($mysqli, "select `episode name`, year(airdate) from eplist where `metaseries name` = \"Star Trek\" order by rand() limit 10;");

$row = mysqli_fetch_all($res,MYSQLI_NUM);
foreach ($row as $i) {
echo "<a href=\"http://memory-alpha.wikia.com/wiki/Special:Search?search=" . $i[0] . "&fulltext=Search\">";
echo $i[0] . " (" . $i[1] . ")</a><br>";
}
?>
</trek>
<img src="uss-voyager.jpg">
</marquee>
</p>
</body>
</html>
