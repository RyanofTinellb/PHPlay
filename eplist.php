<!doctype HTML>
<html>
<head>
<title>Episode Selection Screen</title>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<style>
body {padding-left:5%; padding-top: 1em;}
table {margin-top: 2em; border: 1px solid black;}
</style>
</head>
<body>
<form>
<?php
	$meta = $_GET['meta'] ?? '';
	$series = $_GET['series'] ?? '';
	$season = $_GET['season'] ?? '';
	$id = $_GET['id'] ?? '';

	// declare results
	$metaRes = null;
	$seriesRes = null;
	$seasonRes = null;
	$episodeRes = null;

	$mys = mysqli_connect("localhost", "root", "password", "eplist");

	$metaRes = mysqli_query($mys, 'SELECT `type` = "Film" film, `metaseries name` meta from eplist GROUP BY meta;');
	$metaTable = mysqli_fetch_all($metaRes, MYSQLI_ASSOC);

	if ($id) {
		mysqli_query($mys, 'UPDATE eplist SET `special features` = not `special features` where id="' . $id . '";');
	}
	if ($meta) {
		$seriesRes = mysqli_query($mys, 'SELECT `series name` series, `series number` number FROM eplist where `metaseries name` = "' . $meta . '" GROUP BY series;');
		$seriesTable = mysqli_fetch_all($seriesRes, MYSQLI_ASSOC);
		if (sizeof($seriesTable) == 1) {
			if (!$series) {
			header('location:ForkEpList.php?meta=' . $meta . '&series=' . $seriesTable[0]['series']); exit;
			} else {
				$seriesHidden = 'hidden="hidden"';
			}
		}
	}
	if ($series) {
		$seasonRes = mysqli_query($mys, 'SELECT `season name` name, `season number` number FROM eplist WHERE `metaseries name` = "' . $meta . '" AND `series name` = "' . $series . '" GROUP BY `season number`;');
	}
	if ($season) {
		$episodeRes = mysqli_query($mys, 'SELECT `episode name` episode, date_format(`airdate`, "%W, the %D of %M, %Y") airdate, `special features` features, id FROM eplist WHERE `metaseries name` = "' . $meta . '" AND `series name` = "' . $series . '" AND `season number` = "' . $season . '";');
	}

	// display metaseries option box
	$focus = ($series ? 'autofocus ' : '');
	if ($metaRes) {
		$metaTable = neatColumn($metaTable, 'meta');
		usort($metaTable, 'cmpFilm');
		// <select name="meta" autofocus onKeyUp="if (event.keyCode == 13) {submit();}" onChange="submit();">
		echo '<select name="meta" ' . $focus . 'onKeyUp="if (event.keyCode == 13) {submit();}" onChange="if (event.explicitOriginalTarget.id == \'option\') {submit();};">';
		// <option value="The Avatar" selected>Avatar, the</option>
		foreach($metaTable as $row) {
			$selected = ($row['meta'] == $meta ? ' selected' : '');
			echo '<option id="option" value="' . $row['meta'] . '"' . $selected . '>' . $row['newName'] . '</option>
';
		}
		echo '</select><br>';
	}

	// display series option box
	$focus = ($season ? 'autofocus ' : '');
	if ($seriesRes) {
		usort($seriesTable, function ($a, $b) {return $a['number'] <=> $b['number'];});
		// <select name="series" autofocus onKeyUp="if (event.keyCode == 13) {submit();}">
		echo '<select name="series" ' . $focus . 'onKeyUp="if (event.keyCode == 13) {submit();}" onChange="if (event.explicitOriginalTarget.id == \'option\') {submit();};">';
		// <option value="The Avatar" selected>Avatar, the</option>
		foreach($seriesTable as $row) {
			$selected = ($row['series'] == $series ? ' selected' : '');
			echo '<option id="option" value="' . $row['series'] . '"' . $selected . '>' . $row['series'] . '</option>
';
		}
		echo '</select><br>';
	}

	// display season option box
	$focus = 'autofocus ';
	if ($seasonRes) {
		$table = mysqli_fetch_all($seasonRes, MYSQLI_ASSOC);
		if (sizeof($table)) {
			// <select name="season" autofocus onKeyUp="if (event.keyCode == 13) {submit();}">
			echo '<select name="season" ' . $focus . 'onKeyUp="if (event.keyCode == 13) {submit();}" onChange="if (event.explicitOriginalTarget.id == \'option\') {submit();};">';
			// <option value="5" selected>Season 5: Green</option>
			foreach($table as $row) {
				$selected = ($row['number'] == $season ? ' selected' : '');
				$name = 'Season ' . $row['number'] . ($row['name'] ? ': ' . $row['name'] : '');
				echo '<option id="option" value="' . $row['number'] . '"' . $selected . '>' . $name . '</option>';
			}
			echo '</select><br>';
		}
	}

	$name = ($episodeRes ? "Switch" : "Submit");
	echo '<input type="submit" value="' . $name . '">';
	echo '<input type="button" value="Reset"  onClick="window.location.href=\'ForkEpList.php\'" onkeyup="window.location.href=\'ForkEpList.php\'">';

	if ($episodeRes) {
		$table = mysqli_fetch_all($episodeRes, MYSQLI_ASSOC);
		echo '<table>';
			foreach ($table as $row) {
				echo '<tr><td>' . $row['airdate'] . '</td><td>' . $row['episode'] . '</td>';
				echo '<td><input type="radio" name="id" value="' . $row['id'] . '" />';
				echo ($row['features'] == 1  ? ' (special features)' : '');
				echo '</td>';
				echo '</tr>';
			}
		echo '</table>';
	}

	/*
	*  Order series before films, then sort by the new series name.
	*/
	function cmpFilm($a, $b) {
		if ($a['film'] != $b['film']) {return $a['film'] <=> $b['film'];}
		// else
		return $a['newName'] <=> $b['newName'];
	}

	// Put determiners at the end of the string.
	function neaten($a) {
		if (substr($a, 0, 4) == 'The ') {
			return substr($a, 4) . ', the';}
		if (substr($a, 0, 2) == 'A ' && left($a, 5) != 'A is ') {
			return substr($a, 2) . ', a';}
		if (substr($a, 0, 3) == 'An ') {
			return substr($a, 3) . ', an';}
		// else
		return $a;
	}

	// create a new column in the array with "neatened" names
	function neatColumn($arr, $column) {
		for ($i = 0; $i < sizeof($arr); $i++) {
			$arr[$i]['newName'] = neaten($arr[$i][$column]);
		}
		return $arr;
	}
?>
</form>
</body>
</html>
