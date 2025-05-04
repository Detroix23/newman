
<?php //A script only to destroy the session
	
	$session_loaded = false;
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
        $session_loaded = true;
    }

	$OLD_SESSION = $_SESSION ?? "No session";
	$empty_session = $OLD_SESSION == [];

	$Orders['phpSession'] = ($_POST['fPhpSession'] ?? '') === 'on';
	$Results['phpSession'] = False;

	if ($Orders['phpSession']) {
		$Results['phpSession'] = session_destroy();
	}

?>

<!DOCTYPE HTML>
<html class="html-top">

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Next turn...</title>

    <link rel="stylesheet" href="./css/general1.css">
	<script src="./js/common.js"></script>
</head>

<body class="ctnr-body">

	<?php if ($session_loaded): ?>
		<h2>Session loaded</h2>
	<?php endif; ?>

	<h1>SESSION DESTROYER</h1>

	<h2>PHP Session</h2>
	<form method="POST" id="form-clearPhpSession">
		<input type="hidden" name="fPhpSession" value="on"/>
		<input type="submit" value="Destroy PHP Session"  />
	</form>
	<table class="table-build1">
		<tr>
			<th>Results</th>	
			<th>Old session</th>
		</tr>
		<tr>
			<td><?php if ($Results['phpSession']):?>
					Success, session destroyed. <br>
				<?php elseif (!$Orders['phpSession']): ?>
					Waiting.
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

	<h2>JS LocalStorage </h2>
	<button onclick="localStorage.clear(); location.replace(location.href);">Destroy JS LocalStorage</button>
	<table class="table-build1">
		<tr>
			<th>Results</th>	
			<th>Old storage</th>
		</tr>
		<tr>
			<td></td>
			<td><data id="jsLocalStorage" class="data-main" style="display: block"></data></td>
		</tr>
	</table>

	<br>
	<a href="./uiTop.php">Go back to game</a><br>
	
	<script type="text/javascript">
		/**
		 * Storing in DOM player's actions
		 */
		data_local_storage("jsLocalStorage");
		
		console.log(localStorage);

	</script>
</body>
	
</html>