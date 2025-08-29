
<h1>Settings</h1>

<form method="post" action="./session_destroyer.php">
    <input 
      type="submit" 
      class="btn-ctr" 
      name="fGoToSessionDestroyer" 
      value="Destroyer of session"
    />
</form>

<label for="form-debug">Debug mode</label>
<form method="post" id="form-debug">
    <label for="btn-check-debug-on" class="btn-ctr">On: </label>
    <input 
      type="radio" 
      id="btn-check-debug-on" 
      class="btn-check-debug" 
      name="fDebug" 
      value="on" 
      onclick="submit()"
      <?= $_SESSION['settings']['debug'] ? "checked" : "";?>
    />

    <label for="btn-check-debug-off" class="btn-ctr">Off: </label>
    <input 
      type="radio" 
      id="btn-check-debug-off" 
      class="btn-check-debug" 
      name="fDebug" value="off" 
      onclick="submit();"
      <?= $_SESSION['settings']['debug'] ? "" : "checked";?> 
    />
</form>

<?php
    /// Receive debug form
    if (isset($_POST['fDebug'])) {
        $_SESSION['settings']['debug'] = $_POST['fDebug'];
        $_POST['fDebug'] = "";
    }
?>