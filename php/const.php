<?php
	/**
	*  Variables: Constants that might be used often to reduce writtings or flags
	*/

	///Formatting
	define('HTML_EOL', "<br>" . PHP_EOL);
	
	///Flags
	define('EMPTY_EMPTY', 1<<0);
	define('EMPTY_NOT_ZERO', 1<<1);
	define('EMPTY_NOT_FALSE', 1<<2);
	
	/// Env consts

	try {
		define('CONN_CREDITS', ["newman", "studio", "85125141.23"]);
	} catch (Exception $e) {
		$e;
	}
	const CONN_CREDITS_HOST = 'newman';
	const CONN_CREDITS_USER = 'studio';
	const CONN_CREDITS_PWD = '85125141.23';
	const DB_PREF = "";




?>