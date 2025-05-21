<?php
    ///
	//// Session loaded on main file
    //// common.php loaded on main file
	include_once "./php/common.php";

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
	$conn_elems = DB->connect("nm_elems", [CONN_CREDITS_HOST, CONN_CREDITS_USER, CONN_CREDITS_PWD]);
	$_SESSION['conn']['elems'] = $conn_elems;
	// $db_credit_const = ["train2", "root", "1", "nm_const"];
	$conn_const = DB->connect("nm_const", [CONN_CREDITS_HOST, CONN_CREDITS_USER, CONN_CREDITS_PWD]);
	$_SESSION['conn']['const'] = $conn_const;
	
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

	foreach ($elem_info_list as $info) {
		if (!isset($elem_info[$info])) {
			if ($info == 'id' OR $info == 'default_name') $valid['elem'] = False;
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
	$acceptLangs = ['fr' => 'Français', 'en' => 'English', 'es' => 'Español'];
	if ($lang === '') {
		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	}
    $lang = array_key_exists($lang, $acceptLangs) ? $lang : 'en';
	// echo $lang;

	/// Loading Ressources
	$all['r']['str'] = file_get_contents('./objects/ressources.json');
	$all['r'] = json_decode(
		$all['r']['str'],
		associative: true,
		flags: JSON_THROW_ON_ERROR);
	/// Loading Buildings
	$all['b']['str'] = file_get_contents('./objects/buildings.json');
	$all['b'] = json_decode(
		$all['b']['str'],
		associative: true,
		flags: JSON_THROW_ON_ERROR);

	//// Translation text
	$all['txt']['str'] = file_get_contents('./lang/lang.json');
	$all['txt'] = json_decode(
		$all['txt']['str'],
		associative: true,
		flags: JSON_THROW_ON_ERROR);


    //// Log
    
?>
<!-- DYNAMICS - Loaded: Top loader & data -->
