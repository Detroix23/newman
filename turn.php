<?php 
    $session_loaded = false;
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
        $session_loaded = true;
    }
    $OLD_SESSION = $_SESSION;
?>

<!DOCTYPE HTML>
<html class="html-top">

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Next turn...</title>

    <link rel="stylesheet" href="./css/general1.css">

</head>

<body class="ctnr-body">

    <?php if ($session_loaded): ?>
        <h2>Loading session</h2>
    <?php endif; ?>


    <h1>LOADING - Computing</h1>
    <img src="./img/anim-load-rocket1.gif" alt="Loading animation" style="width:150px;height:150px;">
    <br>
    <table class="table-build1">
        <tr>
            <th>Logs</th>
            <th>Old Session</th>
            <th>New Session</th>
        </tr>
        <tr>
            <td>No logs</td>
            <td><pre><?php print_r($OLD_SESSION); ?></pre></td>
            <td>No updates</td>
        </tr>
    </table>
    
    <?php
        include './php/common.php';
        // Opened by the turn form, calculate revenues, and go back to the last page
        $prev = FORM->reqc('fPrevPage');
        
    ?>

    <br>
    <br><a href="<?=$prev?>">Back to game</a>
    <br><a href="./session_destroyer">Destroy session</a>
</body>
</html>