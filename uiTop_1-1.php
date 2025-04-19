<?php
	session_start();

	include "./php/common.php";
	
	$page_title = 'Newman';
	
	// Getting info
	///Vars
	$H = array(); ////HTML to print
	$id = "";
	$name = "";
	$SQL = array();
	$SQL_RES = array(); //NU
	////Connection to DB
	// $db_credit_elems = ["train2", "root", "1", ];
	$conn_elems = DB->connect("nm_elems", CONN_CREDITS);
	// $db_credit_const = ["train2", "root", "1", "nm_const"];
	$conn_const = DB->connect("nm_const", CONN_CREDITS);
	
	/// Retrive data
	FORM->get("fId", $id);
	FORM->get("fName", $name);
	
	///Extracting
	//// General elements
	///// Multiple methods
	if ($id) {
		$SQL["sel_elem_info_id"] = "SELECT * FROM all_index WHERE id = $id";
		$elem_info = DB->fetch($conn_elems, $SQL["sel_elem_info_id"], DB::ASSOC_ONE_RECORD);
	} else if ($name) {
		$SQL["sel_elem_info_name"] = "SELECT * FROM all_index WHERE default_name = '$name'";
		$elem_info = DB->fetch($conn_elems, $SQL["sel_elem_info_name"], DB::ASSOC_ONE_RECORD);
	} else {
		$elem_info["id"] = $elem_info["default_name"] = $elem_info["type"] = $elem_info["local_name"] = $elem_info["global_name"] = "";
	}
	//// Precise info on element
	
	
	
	//// ATE, All info defined (if there is info)
	// echo "- Debug - Const defining";
	define("id"			, $elem_info["id"]);
	define("name" 		, $elem_info["default_name"]);
	define("type"		, $elem_info["type"]);
	define("local_name" , $elem_info["local_name"]);
	define("global_name", $elem_info["global_name"]);
	
	// print_r($elem_info);

?>
<!DOCTYPE HTML>
<html>
<head>
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= $page_title ?></title>
	
	<link rel="stylesheet" href="./css/general1.css">
	<!-- cf Scripts at end of BODY -->
	
	
</head>

<body>

	<!-- Experimenting with grid based layout -->
	
	<div id="grid">
		<!-- Left column, logo and quick access, shortcuts -->
		<div class="quickAccess">
			<img id="logoMain" src="./img/NM_Logo1AlphaFull_V0-1.svg" title="Newman" width="100%">
			<h2 class="emp1 i"><span class="flw">Quick access</span></h2>
			<img class="help1" src="./img/help1.png" height="25" width="25">
		
		</div>
		
		<!-- Top navigation bar and settings -->
		<div class="navTop">
			<div class="flx-nav-1">
				<div class="flex2">
					<h1><span class="flw"><?php
						/// Name of the elem
						$H["h1-planet"] = "";
						if ((empty(name)) OR (empty(id))) {
							$H["h1-planet"] = "Planet not loaded";
						} else if ((empty(global_name)) AND (empty(local_name))) {
							$H["h1-planet"] = name;
						} else if ((!empty(global_name)) XOR (!empty(local_name))) {
							$H["h1-planet"] = ARR->take_exist([local_name, global_name], EMPTY_NOT_ZERO) . " [" . name . "]";
						} else {
							$H["h1-planet"] = global_name. " (". local_name . ")" . " [" . name . "]";
						}
							
						echo $H["h1-planet"];
						
					?></span></h1>
					
					<h3 class="emp1 i">
						<a href="#" title="Galaxy">Galaxy</a> /
						<a href="#" title="Stellar object">Q1</a> /
						<a href="#" title="Solar sytem">Solar system</a>
						
					</h3>
					
					<img class="help1" src="./img/help1.png" height="25" width="25">
					
					
				</div>
				<div id="setRight">
						<button type="button">Lang V</button>
						<button type="button">Settings O</button>
				</div>
			</div>
		</div>
		
		
		
		
		
		<!-- Main area of the game to play it, panels of constructions, stats, overviews -->
		<div class="mainBody">
			<?php 
				// INCLUDE AREA. TESTS FOR UI
				
				if (type == "planet") {
					require "ctrlPanel_planet_1-0.php";
				} else {
					require "localizer.php";	
				}
			
			
			
			
			
			
			
			
			
			?>
		</div>

		<div id="ctnr-turn" class="ctnr-btn-turn">
			<button tabindex=1 id="btn-turn" class="btn-turn">
				TURN >
			</button>
		</div>
	</div>












	
	<script src="./js/ctrlPanel.js">CtrlPanels Script</script>
</body>
</html>