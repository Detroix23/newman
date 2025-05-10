
<?php
	// Contain all redondant functions of Newman code
	/// Ported from train2
	
	/**
	*  Variables: Constants that might be used often to reduce writtings or flags
	*/
	include "const.php";
	
	/**
	* Created : 20/12/2024
	* Function : Removing spaces, slashes, securing the user's input values
	*/
	function test_input($val) {
		$val = trim($val);
		$val = stripslashes($val);
		$val = htmlspecialchars($val);
		return $val;
	}
	
	/*
	* Created : 08/01/2025
	* Class : Store nicely all method about getting data of forms
	*/
	class form {
		
		/**
		* Created : ORIGINAL 20/12/2024 - EDIT1 02/03/2025		
		* Function : DUPLICATE for form class. Removing spaces, slashes, securing the user's input values
		*/
		function clean($val) {
			$val = trim($val);
			$val = stripslashes($val);
			$val = htmlspecialchars($val);
			return $val;
		}
		
		
		/**
		* Created : 08/01/2025
		* Function: Acquire data from form, allows more compact synthax
		* Prereq : Be sure to put the func in a if that verifies the REQUEST_METHOD
		* Returns : [bool] if success, True, otherwise False. Update vars by reference
		*/
		function get($fData, &$data, &$dataErr=NULL, $pattern="~.~", $errEmpty="Chain Required", $errFormat="Incorrect format") {
			
			$success = False;
			
			if (empty($_GET["$fData"]) /* AND $_GET["fData"] !== "0" */ ) {
				$dataErr = $errEmpty;
			
			} else {	
				/// Verif data
				if (!preg_match($pattern, $this->clean($_GET["$fData"]))) {
					$dataErr = $errFormat;

				} else {
					$data = test_input($_GET["$fData"]);
					$success = True;
				}
			}
			
			return $success; 
		}
		
		function post($fData, &$data, &$dataErr=NULL, $pattern="~.~", $errEmpty="Chain Required", $errFormat="Incorrect format") {
			$success = False;
			
			if (empty($_POST["$fData"]) /* AND $_GET["iChain"] !== "0" */ ) {
				$dataErr = $errEmpty;
			
			} else {	
				/// Verif data
				if (!preg_match($pattern, $this->clean($_POST["$fData"]))) {
					$dataErr = $errFormat;

				} else {
					$data = test_input($_POST["$fData"]);
					$success = True;
				}
			}
			
			return $success; 
		}
		
		function reqc($fName, &$dataErr=NULL, $pattern="~.~", $errEmpty="Chain Required", $errFormat="Incorrect format") {
			/**
			 * Function: Retrive data from form via REQUEST.
			 * Returns: Data if exists, False (bool) if not.
			 */

			$success = False;
			$data = NULL;

			if (!isset($_REQUEST["$fName"])) {
				$dataErr = $errEmpty;
				$data = False;

			} else {	
				/// Verif data
				if (!preg_match($pattern, $this->clean($_REQUEST["$fName"]))) {
					$dataErr = $errFormat;

				} else {
					$data = test_input($_REQUEST["$fName"]);
					$success = True;
				}
			}
			
			return $data;
		}
	
	}

		
	$FORM = new form;
	define('FORM', $FORM);	
	
	class ui {
		/**
		* Created : 15/01/2025
		* Class : Store nicely everything about the UI. Note the parent block of each category is still in the html file
		*/
		
		function generate_table($th, $tds, $class=null, $bk="#") {
			/**
			* Function: Echoes a HTML table.
			* Args:
				$class (str): CSS class to be applied to the table
				$th  (array): Table header / top record / legend
				$tds (array): Array of array for each record; only values will be displayed. Order matters !
				$bk    (str): To fill if null
			*/
			// Vars
			$xMax = count($tds);
			/// Longest td array
			$maxLen = -1;
			foreach ($tds as $td) {
				$tempLen = count($td);
				if ($tempLen > $maxLen) $maxLen = $tempLen;
			}
			
			// Generation
			/// Main node
			empty($class) ? $table = "<table>\n" : $table = "<table class='$class'>\n";
			
			/// Top record
			$table .= "<tr>\n";
			foreach ($th as $col) { $table .= "<th>".htmlspecialchars($col)."</th>"; }
			$table .= "</tr>\n\n";
			
			/// All other records
			for ($y=0; $y<$maxLen; $y++) {
				$table .= "<tr>\n";
				
				//// Individuals columns
				for ($x=0; $x<$xMax; $x++) {
					if (array_key_exists($y, $tds[$x])) {
						//// This is the elem, if X and Y align
						$elem = $tds[$x][$y];
						$table .= "<td>".htmlspecialchars($elem)."</td>\n";
					} else {
						$table .= "<td>".htmlspecialchars($bk)."</td>\n";
					}
					
				}
				$table .= "</tr>\n";
			}
			
			///Close
			$table .= "</table>"; 
			
			// Return
			return $table;
		}
		
		function generate_list($head, $lines, $class=null, $type="ulist") {
			/**
			* Function: List arrays
			* Args:
				$head (array): First line. Empty string to ignore.
			*/
			
			// Vars
			$list = "";
			$typeOk = True;
			
			switch ($type) {
				case "ulist":
					$block0 = "<ul>";
					$block1 = "</ul>";
					$head0 = "";
					$head1 = "";
					$sep0 = "<li>";
					$sep1 = "</li>";
					break;
				case "olist":
					$block0 = "<ol>";
					$block1 = "</ol>";
					$head0 = "";
					$head1 = "";
					$sep0 = "<li>";
					$sep1 = "</li>";
					break;
				case "table":
					$block0 = "<table>\n<tr>";
					$block1 = "</tr>\n</table>";
					$head0 = "<th>";
					$head1 = "</th>";
					$sep0 = "<td>";
					$sep1 = "</td>";
					break;
				default:
					$typeOk = False;
			}
			
			if ($typeOk) {
				// Open block
				$list .= $block0;
				
				// Insert header, if not blank
				if (!empty($head)) {
					$list .= ($head0 . $head . $head1);
				}
				// Insert all other lines
				foreach ($lines as $line) {
					$list .= ($sep0 . $line . $sep1);
				}
				// Close block
				$list .= $block1;
			}
			
			return $list;
			
			
			
			
		}
		
	}
	
	$UI = new ui;
	define("UI", $UI);
	
	class db {
		/**
		* Created: 1st method - 26/02/2025
		* Class: Shorten DB relatedd action
		*/
		const ASSOC_ONE_RECORD = 1<<0;
		const ASSOC_KEY_1 = 1<<1;
		
		function connect($db=NULL, $db_credit=CONN_CREDITS, $pre=DB_PREF) {
			/**
			* Function: Connect to DB using a creditential array. DB non mandatory.
			* Args:
			$db (string | array): 
			$db_credit (array): Contains (named WIP):
				"host" | 0 	=> (string)
				"user" | 1 	=> (string)
				"pwd" | 2 	=> (string & not NULL)
				"db" | 3 	=> (string) (NULL)
			* Returns:
			$conn: Mysqli connection token.
			*/
			// 3 Modes
			if ($db===NULL) { /// Completely blank, default connection
				$host 	= $db_credit[0];
				$user 	= $db_credit[1];
				$pwd 	= $db_credit[2];
				
				$conn = mysqli_connect($host, $user, $pwd);
			} else if (count($db_credit) == 4) { /// If creditential are all in compact array at 2nd spot.
				$host 	= $db_credit[0];
				$user 	= $db_credit[1];
				$pwd 	= $db_credit[2];
				$db 	= $pre . $db_credit[3];
				
				$conn = mysqli_connect($host, $user, $pwd, $db);
			} else if (is_array($db)) { /// If creditential are all in compact array at 1st spot.
				$host 	= $db[0];
				$user 	= $db[1];
				$pwd 	= $db[2];
				$db 	= $pre . $db[3];
				
				$conn = mysqli_connect($host, $user, $pwd, $db);
			} else if (!empty($db)) { /// If the DB name is defined. credits can be blank.
				$host 	= $db_credit[0];
				$user 	= $db_credit[1];
				$pwd 	= $db_credit[2];
				$db		= $pre . $db;
				
				$conn = mysqli_connect($host, $user, $pwd, $db);
			} else {
				$conn = False;
			}

			return $conn;
		}
		
		
		function fetch($conn, $sql_request, $flags=NULL) {
			/**
			* Function: Sends a sql request to the DB and process it into usable PHP and puts it into an array ["elem", "elem"] or ["1st elem" => "2nd elem"] or ["1st" => ["2nd", "3rd", ...]]
			*/
			// Get data
			$sql_request_result = mysqli_query($conn, $sql_request);
			$fetched = mysqli_fetch_all($sql_request_result, MYSQLI_ASSOC);
			// Reogranize
			$len = count($fetched);
			$nice = array();
			$i = 0;
			if ($len < 1) {
				$nice = False;
			} else if (count($fetched[0]) == 1) {
				while ($i < $len) {
					$nice[] = array_values($fetched[$i])[0];
					$i++;
				}
			} else if (count($fetched[0]) == 2) {
				while ($i < $len) {
					$nice[array_values($fetched[$i])[0]] = array_values($fetched[$i])[1];
					$i++;
				}
			} else if ($flags & self::ASSOC_ONE_RECORD) {
				while ($i < $len) {
					$nice = $fetched[$i];
					$i++;
				}
			} else if (($flags & self::ASSOC_KEY_1) OR (empty($flags))) {
				while ($i < $len) {
					$nice[array_values($fetched[$i])[0]] = array_slice($fetched[$i], 1, $len, True);
					$i++;
				}
			} else {
				$nice = False;
			}
			
			
			return $nice;
		}
		
		function query($conn, $sql_request) {
			
		}
		
	}
	
	$DB = new db;
	define("DB", $DB);
	
	class maths {
		/**
		* Created: 02/03/2025
		* Class: Contains all non standard math operations.
		*/
		
		const MISS_ZERO = 1<<0;
		const MISS_ONE = 1<<1;
		const MISS_FALSE = 1<<2;
		
		function dot_product($a, $b, $flags=NULL) {
			/**
			* Function: Dot or scalar product of 2 arrays; S(0->i) = a[1]*b[1] + a[i]*b[i]. Include verfication and filling.
			*/
			$s = True;
			if (count($a) == count($b)) {
				// alert("Same length");
				$len = count($a);
			} else if (($flags === self::MISS_FALSE) OR ($flags === NULL)) {	
				// alert("Not same length. FALSE");
				$s = False;
			} else {
				// alert("Not same length. FILLING");
				///In case array's lengths dont match and filling is allowed
				if (count($a) > count($b) ) {
					$miss = $b;
					$target = $a;
				} else {
					$miss = $a;
					$target = $b;
				}
				while (count($miss) < count($target)) {
					if ($flags === self::MISS_ZERO) {$miss[] = 0;}
					else if ($flags === self::MISS_ONE) {$miss[] = 1;}
					
				}
				$a = $miss;
				$b = $target;
				
				$len = count($a);
			}
			// After verif, do the product, if s valid
			if ($s) {
				$s = 0;
				for ($i=0; $i<$len; $i++) {
					$s += $a[$i] * $b[$i];
				}
			}
			return $s;
		}
		
		function loop_interval($val, $min, $max) {
			/**
			* Function: If the value is not in the interval defined by min and max, loop it in the interval to fit in
			*/
			$d = $max - $min + 1;
			if ($d <= 0) {
				$r = False;
			} else {
				$r = $val;
				while ($r > $max) {
					$r -= $d;
				}
				while ($r < $min) {
					$r += $d ;
				}
			}
			return $r;
		}
		
		function restrict_max(&$val, $restr) {
			/**
			* Function: If the value is SUPERIOR to the restriction, max it to it's ceiling
			*/
			if ($val > $restr) $val = $restr;
		}
		
		
	}
	
	$MATHS = new maths;
	define("MATHS", $MATHS);
	
	class precM {
		/**
		* Created: 03/04/2025
		* Class: Advanced and precise big number maths, analog and completing BcMaths.
		*/
		

		
	}
	
	class js {
		/**
		* Created: 02/03/2025
		* Class: All JavaScript operation done in PHP
		*/
		
		function alert($msg) {
		/**
		* Created: 02/03/2025
		* Function: Call a JS alert
		*/
		echo "<script>alert('".$msg."')</script>";
		}
		
	}

	$JS = new js;
	define("JS", $JS);
	
	class cryptage {
		/**
		* Created: 31/03/2025
		* Class: Crypting related methods
		*/
		
		function al_uni($min, $max) {
			/**
			* Function: Create an alphabet from Unicode, from Min to Max.
			*/
			$al = [];
			for ($i=$min; $i<=$max; $i++) {
				$al[chr($i)] = $i;
			}
			
			return $al;
		}
		
		function combinationsNoRecursion($t, $l) {
			/**
			* NON FUNCTIONAL
			* Function: Create an array of all combinations of elements in t on a l lenght.
			* Args:
				$t (iter): Elements to be combined
				$l (int): Combinations length
			*/
			// Verif
			if (gettype($t) == "array" AND $t !== []) {
				$r = array();
				
				foreach ($t as $e1) {
					$rt1 = array();
					
					for ($i=0; $i<$l; $i++) {
						$rt2 = array();
						foreach ($t as $e2) {
							$rt2[] = $e2;
						}
					}
				}
				
			} else {
				$r = "Warning - Type";
			}
			
			return $r;
			
		}
		
		function combinations($chars, $size, $combinations = array()) {
			/**
			* Function: Get all combinations from $chars
			* Credits: Joel Hinz on Stackoverflow (https://stackoverflow.com/a/19067884)
			*/


			# if it's the first iteration, the first set 
			# of combinations is the same as the set of characters
			if (empty($combinations)) {
				$combinations = $chars;
			}
			
			# initialise array to put new values in
			$new_combinations = array();

			# loop through existing combinations and character set to create strings
			foreach ($combinations as $combination) {
				foreach ($chars as $char) {
					$new_combinations[] = $combination . $char;
				}
			}

			# we're done if we're at size 1
			if ($size == 1) {
				return $combinations;
			}
			# call same function again for the next iteration
			return $this->combinations($chars, $size - 1, $new_combinations);

		}
	
	}

	$CRYPT = new cryptage;
	define("CRY", $CRYPT);
	
	
	class arr {
		/**
		* Created: 03/04/2025
		* Class: All related to arrays calcul, manipulation
		*/
		
		function implode_deep($t) {
			/**
			* Function: Implode an array and all its nested arrays
			* Args:
				$t (array): Iterable to be stringifed
			*/
			$str = $t;
			if (gettype($t) == "array") {
				$str = "";
				// Loop trough all elements
				foreach ($t as $e0) {
					// Implode array
					$e1 = $this->implode_deep($e0);
					// Append
					$str .= $e1;
				}
			}
			
			return $str;
		}
		
		function search_deep($needle, $haystack) {
			/**
			* Created: 24/02/2025
			* Function: Search if the value exists at least one time in any level of a nested array
			* Args:
			$needle: Value to be found.
			$haystack (array): Array to be searched .
			* Returns:
			$find (bool): True if value is in haystack, False otherwise.
			*/
			$find = False;
			$i = 0;
			while (($i < count($haystack)) AND (!$find)) { // Go trought all elem of main array
				$hay = $haystack[$i];
				if ($hay === $needle) {			// If find, stop
					$find = True;
				}
				else if (is_array($hay)) { 		// Else if array, recursion.
					$find = $this->search_deep($needle, $hay);
				}
				$i++;
			}
			return $find;
		}
		
		function take_exist($vals, $flags=NULL) {
			/**
			* Function: Returns the first value that is not empty
			*/
			$r = NULL;
			$i = 0;
			$len = count($vals);;
			while (($r === NULL) AND ($i<$len)) {
				// echo "- Debug - take_exist [i]: ".$vals[$i].HTML_EOL;
				if ($flags & EMPTY_NOT_ZERO) {
					// echo "- Debug - Empty: not zero (".EMPTY_NOT_ZERO."). Flags:".$flags.HTML_EOL;
					if (($vals[$i] === 0) OR ($vals[$i] === "0")) $r = $vals[$i];
				} if ($flags & EMPTY_NOT_FALSE) {
					if ($vals[$i] === False) $r = $vals[$i];
				}
				/// Base condition.
				if (!empty($vals[$i])) $r = $vals[$i]; 
				
				$i++;
			}
			return $r;
		}
		
		/**
		* Created : 25/12/2024
		* Function : Convert array given to string (without separators) and highlight a position with CSS class "emp1"
		*/
		function tostr_highlight($array, $highPos, $highClass) {
			$i = 0;
			foreach ($array as $char) {
				if ($i != $highPos) {
					$string[$i] = "$char";
				} else {
					$string[$i] = "<span class='$highClass'>$char</span>";
				}
				$i++;
			}
			return implode($string);
		}
		
		
	}
	
	$ARR = new arr;
	define("ARR", $ARR);
	
	
	
	

	function num_to_var($number) {
		/**
		* Function: transform any number into string and show all digit, even for a scientific notation.
		*/

	}

	





































	echo "<!-- DYNAMICS - Loaded: Common -->";
?>
