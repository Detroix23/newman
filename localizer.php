
<?php
	// If lost in the universe, find here a way to get back anywhere. List of all locations
	
	$SQL['sel']['allElem'] = "SELECT default_name FROM all_index;";
	$allElems = DB->fetch($conn_elems, $SQL['sel']['allElem']);
	
	// Generate cols
	#allElems: indivdual elem names
	/// Individual links
	$allLinks = array();
	foreach ($allElems as $elem) {
		$allLinks[] = '<a href="./uiTop_1-1.php?fName='.$elem.'">'.$elem.'</a>';	
	}
	/// Legend
	$legend = 'Elements';
	
	// Generate table
	$list = UI->generate_list("", $allLinks);

	
?>

<h2>Lost in the universe?</h2>

<h3>You arrived here because: </h3>
<ul>
<?php
	if (!$valid['elem']) echo '<li>Invalid element, where you think you are going ?</li>';
	if (!$valid['sel']) echo '<li>No selection, you silly bot.</li>';
?>
</ul>

<h3>Find your way with this NAVIGATOR list: </h2>
<?= $list; ?>