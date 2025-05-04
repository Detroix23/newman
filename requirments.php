<?php
// Run that file to check and initialize minimum requierments for the game.
$LOGS = array();
$SQL = array();
/// Check user inputs
$postInstall = $_POST['fInstall'] ?? "";
$go = ($postInstall == "go");

if ($go) {
	/// Check version
	if (PHP_MAJOR_VERSION === 8 && PHP_MINOR_VERSION === 3) {
		$LOGS['versionCompatibility'] = True;
	} else {
		$LOGS['versionCompatibility'] = False;
	}
	/// SQL DBs
	//// Logins
	require_once "./php/const.php";
	try {
		$conn = mysqli_connect(CONN_CREDITS[0], CONN_CREDITS[1], CONN_CREDITS[2]);
		$LOG['sqlConnection'] = "OK";
	} catch (Exception $e) {
		$LOG['sqlConnection'] = $e->getMessage();
	}
	//// If login correct, further tests
	if ($LOG['sqlConnection'] == "OK") {
		//// SQL Version
		$LOG['sqlVersion'] = mysqli_get_server_info($conn); 
		//// Database creation
		$SQL['create']['const']['query'] = "CREATE DATABASE IF NOT EXISTS nm_const";
		$SQL['create']['elems']['query'] = "CREATE DATABASE IF NOT EXISTS nm_elems";

		try {
			$SQL['create']['const']['result'] = mysqli_query($conn, $SQL['create']['const']['query']);
		} catch (Exception $e) {
			$SQL['create']['const']['result'] = $e->getMessage();
		} try {
			$SQL['create']['elems']['result'] = mysqli_query($conn, $SQL['create']['elems']['query']);
		} catch (Exception $e) {
			$SQL['create']['elems']['result'] = $e->getMessage();
		}
	}

	//// If DBs are set up
	if ($SQL['create']['const']['result'] && $SQL['create']['const']['result']) {
		///// New connections
		$conn_elems = mysqli_connect(CONN_CREDITS[0], CONN_CREDITS[1], CONN_CREDITS[2], 'nm_elems');
		$conn_const = mysqli_connect(CONN_CREDITS[0], CONN_CREDITS[1], CONN_CREDITS[2], 'nm_const');

		$SQL['tables']['const']['query'] = file_get_contents("./sql/nm_const.sql");
		$SQL['tables']['elems']['query'] = file_get_contents("./sql/nm_elems.sql");

		try {
			///// Constants
			$SQL['tables']['const']['result'] = mysqli_multi_query($conn_const, $SQL['tables']['const']['query']);
			/* do {
				///// Store the result set in PHP
				if ($result = mysqli_store_result($conn_const)) {
					while ($row = mysqli_fetch_row($result)) {
						printf("%s\n", $row[0]);
					}
				}
				////// print divider
				if (mysqli_more_results($conn_const)) {
					printf("---\n");
				}
			} while (mysqli_next_result($conn_const)); */
		} catch (Exception $e) {
			$SQL['tables']['const']['result'] = $e->getMessage();
		} 
		try {
			///// Elems
			$SQL['tables']['elems']['result'] = mysqli_multi_query($conn_elems, $SQL['tables']['elems']['query']);
			/* do {
				////// store the result set in PHP
				if ($result = mysqli_store_result($conn_elems)) {
					while ($row = mysqli_fetch_row($result)) {
						printf("%s\n", ($row[0] ? "True" : "False"));
					}
				}
				////// print divider
				if (mysqli_more_results($conn_elems)) {
					printf("---\n");
				}
			} while (mysqli_next_result($conn_elems)); */
		} catch (Exception $e) {
			$SQL['tables']['elems']['result'] = $e->getMessage();
		}

	}


}




?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Newman REQUIRMENTS</title>
	<link rel="stylesheet" href="./css/general1.css" />
</head>
<body>
	<h1> Newman Game PREREQUISITES</h1>

	<form id="form-install" method="POST">
		<label for="inp-install-submit">Start initialization</label><br>
		<input id="inp-install-value" type="hidden" name="fInstall" value="go" />
		<input id="inp-install-submit" type="submit" value="Install" />
	</form>

	<?php if ($go): ?>
	<h2>Output</h2>
	
	<h3>PHP Version</h3>
	<ul>
		<li>Your version: <b><?= PHP_VERSION;?></b>;</li>
		<li>Required version: <b>8.3</b>;</li>
		<li>Compatible: <b><?= $go ? "Yes" : "No";?></b>.</li>
	</ul>

	<h3>Database</h3>
	<ul>
		
		<li>Default connection logins are <?= "host=<b>".CONN_CREDITS[0]."</b>, user=<b>".CONN_CREDITS[1]."</b>, password=<b>".CONN_CREDITS[2]."</b>";?>;</li>
		<li>Modify logins in <b><a href="./php/const.php">const.php</a></b> (edit it);</li>
		<li>Connection status: <b><?= $LOG['sqlConnection'] ?></b>;</li>
		<?php if ($LOG['sqlConnection'] == "OK"): ?>
		<li>MySQLi compatibility and installed MySQL version: <b><?= $LOG['sqlVersion']?></b>;</li>
		<li>Required version: <b>9.1.0</b>;</li>
		<br>
		<li>DBs intialization: </li>
		<ol>
			<li>`nm_const` = <b><?= $SQL['create']['const']['result'] === True ? "OK (Already existed or correctly created)" : "Error - ".$SQL['create']['const']['result'];?></b>;</li>
			<li>`nm_elems` = <b><?= $SQL['create']['elems']['result'] === True ? "OK (Already existed or correctly created)" : "Error - ".$SQL['create']['elems']['result'];;?></b>;</li>
		</ol>
		<li>Tables initialization</li>
		<ol>
			<li>`nm_const` = <b><?= $SQL['tables']['const']['result'] === True ? "OK (Already existed or correctly created)" : "Error - ".$SQL['tables']['const']['result'];?></b>;</li>
			<li>`nm_elems` = <b><?= $SQL['tables']['elems']['result'] === True ? "OK (Already existed or correctly created)" : "Error - ".$SQL['tables']['elems']['result'];;?></b>;</li>
		</ol>
			
		<?php endif;?>
	</ul>
	
	<?php endif; ?>
<br><br>
<hr>
<p>When finished: </p>
<a href="./"><i>game</i> folder</a><br>
<a href="./uiTop.php">Game ui</a><br>










</body>
</html>