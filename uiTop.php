<?php
	if (session_status() !== PHP_SESSION_ACTIVE) session_start();
	/// Paths
	define('__ROOT__', dirname(dirname(__FILE__)));

	/// Main common functions
	include "./php/common.php";
	/// All data
	require_once "./loaderTop.php";
?>

<?php
	
		
?>
<!DOCTYPE HTML>
<html class="html-top">

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $all['txt']['top']['title'][$lang] ?></title>

    <link rel="stylesheet" href="./css/general1.css">
    <script src="./js/common.js"></script>
    <script src="./js/ctrlPanel.js" defer></script>
    


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
                    <h1><span class="flw ttl1"><?php
						/// Name of the elem
						$H["h1-planet"] = "";
						if ((empty(name)) OR (empty(id))) {
							$H["h1-planet"] = "Planet not loaded";
						} else if ((empty(global_name)) AND (empty(local_name))) {
							$H["h1-planet"] = name;
						} else if ((!empty(global_name)) AND (empty(local_name))) {
                            $H["h1-planet"] = global_name." [" . name . "]";
                        } else if ((empty(global_name)) AND (!empty(local_name))) {
                            $H["h1-planet"] = " (".local_name.")"." [" . name . "]";
						} else {
							$H["h1-planet"] = global_name. " (". local_name . ")" . " [" . name . "]";
						}
							
						echo $H["h1-planet"];
						
					?></span></h1>

                    <h3 class="emp1 i">
                        <a href="./uiTop?fName=G00001" class="ttl1" title="Galaxy">Galaxy</a> /
                        <a href="./uiTop?fName=A00001" class="ttl1" title="Stellar object">Q1</a> /
                        <a href="./uiTop?fName=S00001" class="ttl1" title="Solar sytem">Solar system</a>

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
					require "ctrlPanel_planet.php";
				} else {
					/// If lost in the universe
					require "localizer.php";	
				}

			?>
        </div>

		<?php if ($valid['elem'] AND $valid['sel']): ?>
        <div id="ctnr-turn" class="ctnr-btn-turn">
            <!-- Form to pass a turn, sending this page to the 'turn.php' -->
            <form id="form-game" method="POST" action="./turn.php">
				<?php
					// $pageUrl = "http" . ((($_SERVER['HTTPS'] ?? "") == 'on') ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
					$pageUri = $_SERVER['REQUEST_URI'];
				?>
                <input type="hidden" id="input-turn-hid-prevPage" name="fPrevPage" value="<?=$pageUri?>">
                <button type="submit" tabindex="1" id="btn-turn" class="btn-turn ttl1">
                    <?= $all['txt']['top']['turnButton'][$lang] ?>
                </button>
            </form>
        </div>
		<?php endif; ?>
	
	</div>






    
</body>

</html>