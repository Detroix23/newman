
<?php //A script only to destroy the session
	
	$save = $_SESSION ?? "No session";
	
	$results = session_destroy();

?>

<h1>SESSION DESTROYER</h1>

<h2>State:</h2>
<p>
<?php
	if ($results) {echo "Success";}
	else {echo "Failure";}
?>
</p>

<h2>Session</h2>
<pre>
<?php print_r($save); ?>
</pre>