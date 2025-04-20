
<?php //A script only to destroy the session
	
	$session_loaded = false;
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
        $session_loaded = true;
    }

	$OLD_SESSION = $_SESSION ?? "No session";
	$empty_session = $OLD_SESSION == [];

	$results = session_destroy();

?>

<!DOCTYPE HTML>
<html class="html-top">

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Next turn...</title>

    <link rel="stylesheet" href="./css/general1.css">

</head>

<body class="ctnr-body">

<?php if ($session_loaded): ?>
	<h2>Session loaded</h2>
<?php endif; ?>

<h1>SESSION DESTROYER</h1>

<table class="table-build1">
	<tr>
		<th>Results</th>	
		<th>Old session</th>
	</tr>
	<tr>
		<td><?php if ($results):?>
				Success, session destroyed. <br>
			<?php else: ?>
				Failure, session not destoyed. <br>
			<?php endif; 
			if ($empty_session): ?>
				Session was empty; already cleared. <br>
			<?php endif; ?>
		</td>
		<td><pre><?php print_r($OLD_SESSION); ?></pre></td>
	</tr>

</table>
<br>
<a href="./uiTop_1-1.php">Go back to game</a><br>


</body>
</html>