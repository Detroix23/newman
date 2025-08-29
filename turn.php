<?php
  // Absolute basis
  // Init
  declare(strict_types=1);
  session_start();

  include './php/common.php';

  /// Forms
  $previous_page = empty($_POST['fPrevPage']) 
    ? './uiTop.php'
    : $_POST['fPrevPage']; 
  $db_export = !empty(FORM->reqc('fDbExport'));

  /// JSON
  $all = [];
  /// Loading Ressources
	$all['r']['str'] = file_get_contents('./objects/ressources.json');
	$all['r'] = json_decode(
		$all['r']['str'],
		associative: true,
		flags: JSON_THROW_ON_ERROR
  );
  /// Loading Buildings
	$all['b']['str'] = file_get_contents('./objects/buildings.json');
	$all['b'] = json_decode(
    $all['b']['str'],
		associative: true,
		flags: JSON_THROW_ON_ERROR
  );

  /// Turn
  isset($_SESSION['turn']['count']) 
    ? $_SESSION['turn']['count'] += 1 
    : $_SESSION['turn']['count'] = 0; 
  $turn = $_SESSION['turn']['count'];

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

<h1>turn <?= $turn;?></h1>
<br>
<a href="<?=$previous_page?>">
  Back to game (<?=htmlspecialchars($previous_page);?>)
</a>

<?php

  // Opened by the turn form, calculate revenues, and go back to the last page
  // Only if turn is not already calculated
  if (
    empty($_SESSION['TurnComputed']) 
    || !$_SESSION['TurnComputed']
  ):
    // Loading.
    // Error array.
    $errors = array();
    $old_session = $_SESSION;

    /// User inputs
    $user_save_on = $_POST['fSave'] ?? "";
    $user_debug = $_SESSION['settings']['debug'] ?? "";
    $user_debug = $user_debug === "on";
    $user_inputs_string = $_POST['fUserInputs'] ?? "";
    $user_inputs = [];
    /// Must double encode
    if ($user_inputs_string) {
      $user_inputs_decoded = json_decode($user_inputs_string, flags: JSON_THROW_ON_ERROR);
      foreach ($user_inputs_decoded as $element => $inputs_string) {
        $user_inputs[$element] = json_decode($inputs_string);
      }
    }

    

    /// Gamedata: all buildings, ressources, infos, that will be the updated next session
    $gamedata_keys_blacklist = array('conn', 'TurnComputed', 'turn');
    $gamedata = array();
    foreach ($_SESSION as $key => $value) {
      if (!in_array($key, $gamedata_keys_blacklist)) $gamedata[$key] = $value;
    }

    

    /// Db
    $SQL = [];
    $conn_elem  = new mysqli(CONN_CREDITS_HOST, CONN_CREDITS_USER, CONN_CREDITS_PWD, 'nm_elems');
    $conn_const = new mysqli(CONN_CREDITS_HOST, CONN_CREDITS_USER, CONN_CREDITS_PWD, 'nm_const');
    $JSON = [];

    /// User inputs; buildings
    foreach ($user_inputs as $element_name => $inputs) {
      foreach ($inputs as $input_id => $input_value) {
        /// Unspecialized building construction
        if (substr($input_id, 0, 24) === 'inp-building-production-') {
          /// Extract
          $input_production = explode("-", $input_id);
          $building = $input_production[3];
          $production = $input_production[4];

          /// Update gamedata info the building
          if ($input_value < 0) {
            $gamedata[$element_name]['b'][$building][$production] = 0;
            $errors[] = "(!) - Negative building: $building, $production, $input_value";
          } else {
            $gamedata[$element_name]['b'][$building][$production] = $input_value;
          }
        }
      }
    }

    /// Add to gamedata, for each loaded element
    foreach ($gamedata as $elem => $info) {
      // Calc
      /// Verif
      //// TODO

      // Export to DB
      
      /// Buildings
      if (array_key_exists('b', $gamedata[$elem])) {
        $JSON['b'][$elem] = json_encode($gamedata[$elem]['b'], JSON_PRETTY_PRINT);
        //// Production
        foreach ($info['b'] as $building => $production) {
          foreach ($production as $production_name => $production_jobs) {
            if ($production_name !== "undefined" && !empty($production_name)) {
              $costs = $all['b'][$building]['production'][$production_name][1];
              $outputs = $all['b'][$building]['production'][$production_name][2];
              $time = $all['b'][$building]['production'][$production_name][0];
              ///// Basic modulo test, to prevent have to build a clock for each production
              if (($production_jobs > 0) && ($turn % $time === 0)) {
                $costs_fullfilled = True;
                ////// Check for available ressources
                foreach ($costs as $cost_ressource => $cost_value) {
                  //// Find correct r class
                  foreach ($all['r'] as $ressources_group => $ressources) {
                    if (array_key_exists($cost_ressource, $ressources)) $ressource_class_found = $ressources_group;
                  }
                  $cost_actual_ressource = $gamedata[$elem]['r'][$ressource_class_found][$cost_ressource];
                  ///// R2 Check
                  if (gettype($cost_actual_ressource) === "array") {
                    $cost_actual_ressource_r2_count = 0;
                    foreach ($cost_actual_ressource as $r2_origin => $r2_value) {
                      $cost_actual_ressource_r2_count += $r2_value;
                    }
                    $cost_actual_ressource = $cost_actual_ressource_r2_count;
                  }
                  if ($cost_actual_ressource < $cost_value) {
                    echo "Insuff: $cost_actual_ressource < $cost_value";
                    $costs_fullfilled = False;
                  }
                }
                ////// Add ressources if costs are fullfilled
                if ($costs_fullfilled) {
                  ///// Remove the ressources costs
                  foreach ($costs as $cost_ressource => $cost_value) {
                    //// Find correct r class
                    foreach ($all['r'] as $ressources_group => $ressources) {
                      if (array_key_exists($cost_ressource, $ressources)) $ressource_class_found = $ressources_group;
                    }
                    //// Substract time the number of building doing it
                    $gamedata[$elem]['r'][$ressource_class_found][$cost_ressource] -= $cost_value * $production_jobs; 
                  }
                  foreach ($outputs as $out_ressource => $out_value) {
                    //// Find correct r class
                    foreach ($all['r'] as $ressources_group => $ressources) {
                      if (array_key_exists($out_ressource, $ressources)) $ressource_class_found = $ressources_group;
                    }
                    //// Add time the number of building doing it
                    $gamedata[$elem]['r'][$ressource_class_found][$out_ressource] += $out_value * $production_jobs; 
                  }
                } else {
                  ///// LOg
                  $errors[] = "(*) - Costs not fullfilled for: $building; producing: $production_name.";
                }
              } else {
                $errors[] = "(*) - Not enough jobs ($production_jobs) or on cooldown ($turn % $time = ". ((string)$turn % $time === 0) . ")";
              }
            } else {
              $errors[] = "(*) - Invalid production: $production_name for $building.";
            }
          }
        }
      } else {
        $JSON['b'][$elem] = [];
      }
      

      /// Ressources
      if (array_key_exists('r', $gamedata[$elem])) {
        $JSON['r'][$elem] = json_encode(
          $gamedata[$elem]['r'], 
          JSON_PRETTY_PRINT
        );
      } else {
        $JSON['r'][$elem] = [];
      }

      /// SQL
      if ($db_export || $user_save_on) {
        if ($gamedata[$elem]['info']['type'] === 'planet') $SQL['table'] = 'planet_info';
        
        $SQL['ins']['elem'] = "INSERT INTO ".$SQL['table']." (planet_id, builds, ressources, infos) 
                  VALUES (?, ?, ?, NULL)
                  ON DUPLICATE KEY UPDATE
                    builds=VALUES(builds), ressources=VALUES(ressources), infos=VALUES(infos);";
        try {
          $conn_elem->execute_query($SQL['ins']['elem'], [$gamedata[$elem]['info']['id'], $JSON['b'][$elem], $JSON['r'][$elem]]);
        } catch (Exception $exception){
          $errors[] = $exception;
        }
      }
    }


    

    // Final update
    $_SESSION = $gamedata;
    /// Adding info outside of gamedata
    $_SESSION['TurnComputed'] = True;
    $_SESSION['turn'] = $old_session['turn'];
    foreach ($_POST as $post_key => $post_value) {
      /// White list to clear POST
      if (!in_array($post_key, ['fPrevPage', 'fTurnComputed'])) {
        unset($_POST[$post_key]);
      }
    }

    // Go back to game immediately if no debug.
    if (!$user_debug) {
      header('Location: '.$previous_page);
      exit();
    }
    
?>
    <script>
      localStorage.clear();
    </script>


    <?php if ($errors !== array()): ?>
    <h2>Errors</h2>
    <pre><?php print_r($errors); ?></pre>
    <?php endif; ?>


    <img src="./img/anim-load-rocket1.gif" alt="Loading animation" style="width:150px;height:150px;">
    
    
    <h1>RECEIVED</h1>
    <table class="table-build1">
      <tr>
        <th>Player inputs</th>
        <th>Old Session</th>
      </tr>
      <tr>
        <!-- Js local storage - All turn's player inputs -->
        <td><pre class="lim-h-vh"><?php print_r($user_inputs); ?></pre></td>
        <td><pre class="lim-h-vh"><?php print_r($old_session); ?></pre></td>
      </tr>
    </table>

    <h1>RESULTS</h1>
    
    <h2>Elements loaded</h2>
    <p><?php foreach ($gamedata as $elem => $details): ?>
      <?php echo $elem.' ('.($details['info']['type'] ?? 'noneType').') '; ?>
    <?php endforeach;?></p>

    <h2>User inputs</h2>
    <form action="./session_destroyer">
      <input type="submit" value="Destroy session" style="color: black"/>
    </form><br>
    
    <table class="table-build1">
      <tr>
        <th>Logs</th>
        <th>Player inputs</th>
      </tr>
      <tr>
        <td>No logs</td>
        <td><pre class="lim-h-vh"><?php print_r($_POST);?></pre></td>
      </tr>
    </table>
    <br>

    <h1>EXPORTS</h1>
    
    <form method="POST">
      <input type="hidden" id="fDbExport" name="fDbExport" value="on">
      <input type="submit" value="Export to DB" style="color: black;">
    </form>

    <h2>Original data</h2> 
    <table class="table-build1">
      <tr>
        <th>Buildings</th>
        <th>Ressources</th>
        <th>Info</th>
      </tr>
      <tr>
        <td><pre class="lim-h-vh"><?php print_r($JSON['b']);?></pre></td>
        <td><pre class="lim-h-vh"><?php print_r($JSON['r']);?></pre></td>
        <td><pre class="lim-h-vh"><?php ?></pre></td>
      </tr>
    </table>

    <h2>Updated data</h2> 
    <table class="table-build1">
      <tr>
        <th>Buildings</th>
        <th>Ressources</th>
        <th>Info</th>
      </tr>
      <tr>
        <td><pre class="lim-h-vh"><?php print_r($_SESSION[$elem]['b']);?></pre></td>
        <td><pre class="lim-h-vh"><?php print_r($_SESSION[$elem]['r']);?></pre></td>
        <td><pre class="lim-h-vh"><?php ?></pre></td>
      </tr>
    </table>

<?php else: ?>
  
  <h2>Turn already computed</h2>

<?php endif; ?>

</body>
</html>
