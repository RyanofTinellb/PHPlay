<!doctype HTML>
<html>
<head>
<title>Episodes of Stuff We've Got</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
p{text-align:center; line-height:2em;color:light-green;}
td{text-align:center;}
body{background-color:powderblue;}
table{padding-left:5em; margin-left:auto; margin-right: auto;}
div{padding-top: 2em;}
bright{font-weight:bold; color:green; font-size:large; text-decoration: overline;}
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

$res = mysqli_query($mysqli, "select summary, `special features` sfs, `metaseries name` meta, `series name` series, `episode name` episode, airdate, dayname(airdate) day, type from eplist where seendate = (select max(seendate) from eplist);");
$row = mysqli_fetch_assoc($res);
// <a href="https://www.google.com.au/search?q=msql+string+imdb">Summary</a><br>
$series = $row['series'] ?? "";
$meta = $row['meta'] ?? "";
$name = $row['episode'] ?? "";
$air = nicedate($row['airdate'], $row['day']) ?? "";
$search = $series . "+" . $meta . "+" . $name;
$search = "https://www.google.com.au/search?q=" . $search;
echo "<a href=\"" . $search . "\">" . $row['summary'] . "</a>,</strong><br>";
echo "which aired on " . $air . ".<br>";
if ($row['type']) {
	echo "It is a" . n($row['type']) . ".<br>";
}
if ($row['sfs']) {
	echo "<bright>There are special features</bright>";
}
echo '<a href="index.php">Refresh</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="next.php">Next episode</a> &rarr;';
$res = mysqli_query($mysqli, 'select `series name` series from eplist where airdate in (select max(airdate) from eplist where seendate is not null and type is not null and type not in ("Film", "Movie", "Alone") group by `series name`) and type not in ("End", "Finale", "Hiatus") order by `airdate`;');
echo "<table><tr><th>Also watching:</th></tr>";
$row = mysqli_fetch_all($res,MYSQLI_ASSOC);
foreach ($row as $i) {
	if ($series != $i['series']) {
		echo "<tr><td>" . $i['series'] . "</td></tr>";
	}
}
echo "</table>";
$res = mysqli_query($mysqli, 'select type, airdate, dayname(airdate) as day from eplist where airdate = (select min(airdate) from eplist where type is not null and seendate is null);');
$row = mysqli_fetch_all($res,MYSQLI_ASSOC);
echo "<p>The next event is a" . n($row[0]['type']) . ", which will occur on " . nicedate($row[0]['airdate'], $row[0]['day']) . ".</p>";
$res = mysqli_query($mysqli, "select @timetaken:=datediff(now(),min(seendate))/ count(*) from eplist where seendate > '2011-01-01';");
$res = mysqli_query($mysqli, "select @end:=date_add(now(), interval (@timetaken * 86400 * count(*)) second) as end from eplist where seendate is null;");
$end = mysqli_fetch_assoc($res)['end'];
$res = mysqli_query($mysqli, "select dayname(date_add(now(), interval (@timetaken * 86400 * count(*)) second)) as end from eplist where seendate is null;");
$dayend = mysqli_fetch_assoc($res)['end'];
echo "<p>The current cycle will see its completion on or around " . nicedate($end, $dayend) . ".</p>";
function n($item) {
	if ($item == "End" || $item == "Hiatus" || $item == "Alone") {
		return "n " . $item;
	} else {
		return " " . $item;
	}
}
function nicedate ($date, $day) {
	$parts = preg_split("/[- :]/", $date);
	$year = $parts[0];
	$month = $parts[1];
	$dayofmonth = intval($parts[2]);
	$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	$month = $months[$month - 1];
	switch ($dayofmonth) {
		case 1: case 21: case 31:
			$ordinal = "st"; break;
		case 2: case 22:
			$ordinal = "nd"; break;
		case 3: case 23:
			$ordinal = "rd"; break;
		default:
			$ordinal = "th";
	}
	return $day . ", the " . $dayofmonth . $ordinal . " of " . $month . ", " . $year;
}
?>
</p>
</div>
</body>
</html>
