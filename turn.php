<?php
    // Opened by the turn form, calculate revenues, and go back to the last page
    
    // Init
    $session_loaded = false;
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
        $session_loaded = true;
    }
    
    include './php/common.php';

    // Loading
    $ERRS = [];
    
    /// Gamedata
    $Gamedata = $_SESSION;
    $OLD_SESSION = $_SESSION;
    
    /// Forms
    $prev = FORM->reqc('fPrevPage');
    $prev = !empty($prev) ? $prev : './uiTop_1-1.php';
    $db_export = empty(FORM->reqc('fDbExport')) ? False : True;
    //// Treat all player actions (building,...); count additions (or substractions)
    $Pvals = array();
    $PvalBlackKeys = array('fPrevPage');
    foreach ($_POST as $PostKey => $PostValue) {
        if (!in_array($PostKey, $PvalBlackKeys)) {
            $Pvals[$PostKey] = $PostValue; 
        }
    }

    /// Db
    $SQL = [];
    $conn_elem = new mysqli(CONN_CREDITS[0], CONN_CREDITS[1], CONN_CREDITS[2], 'nm_elems');
    $conn_const = new mysqli(CONN_CREDITS[0], CONN_CREDITS[1], CONN_CREDITS[2], 'nm_const');
    $JSON = [];
    /// Add to Gamedata

    foreach ($Gamedata as $elem => $info) {
        // Calc
        /// Verif

        /// Update
        

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

    <?php if ($session_loaded): ?>
        <h2>Loading session</h2>
    <?php endif; ?>

    <?php if ($ERRS !== array()): ?>
    <h2>Errors</h2>
    <pre><?php print_r($ERRS); ?></pre>
    <?php endif; ?>

    <h1>TURN</h1>

    <img src="./img/anim-load-rocket1.gif" alt="Loading animation" style="width:150px;height:150px;">
    <br><a href="<?=$prev?>">Back to game (<?=$prev?>)</a>
    
    <h1>RECEIVED</h1>
    <table class="table-build1">
        <tr>
            <th>Game inputs</th>
            <th>Old Session</th>
        </tr>
        <tr>
            <!-- Js local storage - All turn's player inputs -->
            <td><pre><data id="jsLocalStorage" class="data-main" ><script type="text/javascript">data_local_storage("jsLocalStorage")</script></td>
            <td><pre><?php print_r($OLD_SESSION); ?></pre></td>
        </tr>
    </table>

    <h1>RESULTS</h1>
    
    <h2>Elements loaded</h2>
    <p><?php foreach ($Gamedata as $elem => $details): ?>
        <?php echo $elem.' ('.$details['info']['type'].') '; ?>
    <?php endforeach;?></p>

    <h2>Gamedata</h2>
    <a href="./session_destroyer">Destroy session</a>
    <table class="table-build1">
        <tr>
            <th>Logs</th>
            <th>New Session</th>
        </tr>
        <tr>
            <td>No logs</td>
            <td>No updates</td>
        </tr>
    </table>
    <br>

    <h1>EXPORTS</h1>
    
    <form method="POST">
        <input type="hidden" id="fDbExport" name="fDbExport" value="on">
        <input type="submit" value="Export to DB" style="color: black;">
    </form>

    <h2>Local data</h2> 
    <table class="table-build1">
        <tr>
            <th>Buildings</th>
            <th>Ressources</th>
            <th>Info</th>
        </tr>
        <tr>
            <td><pre><?php print_r($JSON['b']);?></pre></td>
            <td><pre><?php print_r($JSON['r']);?></pre></td>
            <td><pre><?php ?></pre></td>
        </tr>
    </table>
    
</body>
</html>