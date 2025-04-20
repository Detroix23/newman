<?php
	if (session_status() !== PHP_SESSION_ACTIVE) session_start();

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
	
	/// Retrive element from form
	FORM->get("fId", $id);
	FORM->get("fName", $name);

	///Extracting
	//// General elements
	///// Multiple methods
	$valid['sel'] = True; //// Is the player somewhere ?

	$elem_info["id"] = $elem_info["default_name"] = $elem_info["type"] = $elem_info["local_name"] = $elem_info["global_name"] = "";
	$elem_info_list = ['id', 'default_name', 'type', 'local_name', 'global_name'];
	
	if ($id) {
		$SQL["sel_elem_info_id"] = "SELECT * FROM all_index WHERE id = $id";
		$elem_info = DB->fetch($conn_elems, $SQL["sel_elem_info_id"], DB::ASSOC_ONE_RECORD);
	} else if ($name) {
		$SQL["sel_elem_info_name"] = "SELECT * FROM all_index WHERE default_name = '$name'";
		$elem_info = DB->fetch($conn_elems, $SQL["sel_elem_info_name"], DB::ASSOC_ONE_RECORD);
	} else {
		$valid['sel'] = False;
	}
	///// Check validity
	if ($elem_info) {
		///// If selection is correct or pre-empty (on non-select)
		$valid['elem'] = True;
	} else {
		//// If player selected an element taht doesnt exist in DB
		$valid['elem']  = False;
		$elem_info = [];
	}
	
	// var_dump($elem_info);

	foreach (['id', 'default_name'] as $info) {
		if (!isset($elem_info[$info])) {
			$valid['elem'] = False;
			$elem_info[$info] = '';
		}
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

	/// Language
	//// First check POST, then browser
	$lang = '';
	FORM->post('fLang', $lang);
	$acceptLangs = ['fr' => 'Français', 'en' => 'English'];
	if ($lang === '') {
		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	}
    $lang = array_key_exists($lang, $acceptLangs) ? $lang : 'en';
	// echo $lang;

	//// Translation text
	$all['txt']['str'] = file_get_contents('./lang/lang.json');
	$all['txt'] = json_decode(
		$all['txt']['str'],
		associative: true,
		flags: JSON_THROW_ON_ERROR);
		
?>
<!DOCTYPE HTML>
<html class="html-top">

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $all['txt']['top']['title'][$lang] ?></title>

    <link rel="stylesheet" href="./css/general1.css">
    <!-- cf Scripts at end of BODY -->


</head>

<body class="ctnr-body">

    <!-- Approved grid based layout -->

    <div id="grid">
        <!-- Left column, logo and quick access, shortcuts -->
        <div class="quickAccess">
            <img id="logoMain" src="./img/NM_Logo1AlphaFull_V0-1.svg" title="Newman" width="100%">
            <h2 class="emp1 i"><span class="flw"><?= $all['txt']['top']['quickAccess'][$lang] ?></span></h2>
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
                <form method='post' id="setRight">
                    <select id="sel-lang" name="fLang" onchange="submit();">
                        <!-- Language selection, with auto submit -->
                        <?php foreach ($acceptLangs as $accLang => $accFull): ?>
                        <option value="<?=$accLang?>" id="sel-lang-<?=$accLang?>"
                            <?php echo $accLang==$lang ? 'selected' : '' ?>><?=$accFull?></option>
                        <?php endforeach; ?>
                    </select>
                    <!-- Setting -->
                    <button type="button"><?= $all['txt']['top']['settingMain'][$lang] ?></button>
                </form>
            </div>
        </div>




		<!-- Main area of the game to play it, panels of constructions, stats, overviews -->
		<div class="mainBody">
			<?php
				// INCLUDE AREA.

				if (type == "planet") {
					/// Planet view selected
					require "ctrlPanel_planet_1-0.php";
				} else {
					/// If lost in the universe
					require "localizer.php";	
				}

			?>
        </div>

		<?php if ($valid['elem'] AND $valid['sel']): ?>
        <div id="ctnr-turn" class="ctnr-btn-turn">
            <!-- Form to pass a turn, sending this page to the 'turn.php' -->
            <form method="POST" action="./turn.php">
				<?php
					// $pageUrl = "http" . ((($_SERVER['HTTPS'] ?? "") == 'on') ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
					$pageUri = $_SERVER['REQUEST_URI'];
				?>
                <input type="hidden" id="input-turn-hid-prevPage" name="fPrevPage" value="<?=$pageUri?>">
                <button type="submit" tabindex="1" id="btn-turn" class="btn-turn">
                    <?= $all['txt']['top']['turnButton'][$lang] ?>
                </button>
            </form>
        </div>
		<?php endif; ?>
	
	</div>






    <script src="./js/ctrlPanel.js">
    	CtrlPanels Script
    </script>
</body>

</html>