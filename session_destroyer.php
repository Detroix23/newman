
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
			<td><ul id="jsLocalStorage" class="data-main" style="display: block"></ul></td>
		</tr>
	</table>

	<br>
	<a href="./uiTop_1-1.php">Go back to game</a><br>
	
	<script type="text/javascript">
		/**
		 * Storing in DOM player's actions
		 */
		const ls_key_black = ['_'];
		const jsLocalStorage = document.querySelector("#jsLocalStorage");
		let jsLocalStorageTxt = "";
		if (localStorage.length !== 0) {
			const len_ls = localStorage.length;
			for (i = 0; i < len_ls; i++) {
				const ls_key = localStorage.key(i);
				const ls_val = localStorage.getItem(ls_key)
				if (!ls_key_black.includes(ls_key)) {
					//jsLocalStorageTxt += ls_key + "=[" + ls_val + "]<br>\n";
					//jsLocalStorage.innerHTML = jsLocalStorageTxt;
					//// DOM
					const ls_line = document.createElement('info');
					ls_line.id = "data-ls-" + ls_key;
					ls_line.key = ls_key;
					ls_line.value = ls_val;
					ls_line.innerHTML = ls_val;
					jsLocalStorage.appendChild(ls_line);
					
					const testLs = document.querySelector("#" + ls_line.id);
					alert("LS_line: " + testLs.id + ", value=" + testLs.value + ", key=" + testLs.key);
				}
			}
		} else {
			jsLocalStorageTxt = "Empty local storage!";
			localStorage.setItem("_", "Local Js storage: mainly saving player's inputs");
			jsLocalStorage.innerHTML = jsLocalStorageTxt;
		}
		
		console.log(localStorage);

	</script>
</body>
	
</html>