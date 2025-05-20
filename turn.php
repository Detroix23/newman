<?php
    // Absolute basis
    // Init
    declare(strict_types=1);

    session_start();
    $session_loaded = true;

    include './php/common.php';

    /// Forms
    $prev = !empty($_POST['fPrevPage']) ? $_POST['fPrevPage'] : './uiTop.php';
    $db_export = empty(FORM->reqc('fDbExport')) ? False : True;

    /// JSON
    $all = [];
    /// Loading Buildings
	$all['b']['str'] = file_get_contents('./objects/buildings.json');
	$all['b'] = json_decode(
		$all['b']['str'],
		associative: true,
		flags: JSON_THROW_ON_ERROR);
    
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

<h1>TURN</h1>
<br><a href="<?=$prev?>">Back to game (<?=htmlspecialchars($prev);?>)</a>

<?php
    // Opened by the turn form, calculate revenues, and go back to the last page
    // Only if turn is not already calculated
    if (empty($_SESSION['TurnComputed']) || !$_SESSION['TurnComputed']):

    

    // Loading
    $ERRS = [];
    
    /// User inputs
    $USER_INPUTS_STRING = $_POST['fUserInputs'];
    $USER_INPUTS = [];
    /// Must double encode
    if ($USER_INPUTS_STRING) {
        $USER_INPUTS_SEMI = json_decode($USER_INPUTS_STRING, flags: JSON_THROW_ON_ERROR);
        foreach ($USER_INPUTS_SEMI as $element => $inputs_string) {
            $USER_INPUTS[$element] = json_decode($inputs_string);
        }
    } else {
        $USER_INPUTS = [];
    }

    /// Gamedata, all buildings, ressources, infos, that will be the updated next session
    $GamedataBlackKeys = array('conn', 'TurnComputed');
    $Gamedata = array();
    foreach ($_SESSION as $sessKey => $sessVal) {
        if (!in_array($sessKey, $GamedataBlackKeys)) $Gamedata[$sessKey] = $sessVal;
    }
    $OLD_SESSION = $_SESSION;

    /// Db
    $SQL = [];
    $conn_elem  = new mysqli(CONN_CREDITS_HOST, CONN_CREDITS_USER, CONN_CREDITS_PWD, 'nm_elems');
    $conn_const = new mysqli(CONN_CREDITS_HOST, CONN_CREDITS_USER, CONN_CREDITS_PWD, 'nm_const');
    $JSON = [];

    /// Add to Gamedata
    foreach ($Gamedata as $elem => $info) {
        // Calc
        /// Verif
        //// TODO

        
        

        // Export to DB
        
        /// Buildings
        if (array_key_exists('b', $Gamedata[$elem])) {
            $JSON['b'][$elem] = json_encode($Gamedata[$elem]['b'], JSON_PRETTY_PRINT);
        } else {
            $JSON['b'][$elem] = [];
        }
        /// Ressources
        if (array_key_exists('r', $Gamedata[$elem])) {
            $JSON['r'][$elem] = json_encode($Gamedata[$elem]['r'], JSON_PRETTY_PRINT);
        } else {
            $JSON['r'][$elem] = [];
        }

        /// SQL
        if ($db_export) {
            if ($Gamedata[$elem]['info']['type'] === 'planet') $SQL['table'] = 'planet_info';
            
            $SQL['ins']['elem'] = "INSERT INTO ".$SQL['table']." (planet_id, builds, ressources, infos) 
                                   VALUES (?, ?, ?, NULL)
                                   ON DUPLICATE KEY UPDATE
                                    builds=VALUES(builds), ressources=VALUES(ressources), infos=VALUES(infos);";
            try {
                $conn_elem->execute_query($SQL['ins']['elem'], [$Gamedata[$elem]['info']['id'], $JSON['b'][$elem], $JSON['r'][$elem]]);
            } catch (Exception $exc){
                $ERRS[] = $exc;
            }
        }
    }


    /// User inputs; buildings
    foreach ($USER_INPUTS as $element_name => $inputs) {
        foreach ($inputs as $input_id => $input_value) {
            /// Unspecialized building construction
            if (substr($input_id, 0, 24) === 'inp-building-itemValue1-') {
                /// Remove the id part
                $building = substr($input_id, 24);
                /// Update Gamedata info the building
                if ($input_value <= 0) {
                    $Gamedata[$element_name]['b'][$building]['unspecified'] = 0;
                } else {
                    $Gamedata[$element_name]['b'][$building]['unspecified'] = $input_value;
                }
            }
        
        /// Croiser avec le JSON, verifier que toutes les production sont initialisées
        } foreach ($all['b'] as $building => $building_value) {
            foreach ($building_value['production'] as $production) {
                if (!array_key_exists($production, $Gamedata[$element_name]['b'][$building])) {
                    $Gamedata[$element_name]['b'][$building][$production] = 0;
                }
            }
        }
    }

    // Final update
    $_SESSION = $Gamedata;
    $_SESSION['TurnComputed'] = True;
    foreach ($_POST as $post_key => $post_value) {
        /// White list to clear POST
        if (!in_array($post_key, ['fPrevPage', 'fTurnComputed'])) {
            unset($_POST[$post_key]);
        }
    }

?>
    <script>
        localStorage.clear();
    </script>


    <?php if ($ERRS !== array()): ?>
    <h2>Errors</h2>
    <pre><?php print_r($ERRS); ?></pre>
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
            <td><pre class="lim-h-vh"><?php print_r($USER_INPUTS); ?></pre></td>
            <td><pre class="lim-h-vh"><?php print_r($OLD_SESSION); ?></pre></td>
        </tr>
    </table>

    <h1>RESULTS</h1>
    
    <h2>Elements loaded</h2>
    <p><?php foreach ($Gamedata as $elem => $details): ?>
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
