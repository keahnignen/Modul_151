<?php
  $userId = getUserIdFromSession();
  // Falls der Benutzer bereits angemeldet ist, wechselt die App in den Member-Bereich
  if ($userId > 0) {
    header("Location: index.php?function=".getValue('cfg_func_member')[0]);
	exit;
  }
  $meldung = "";
  $alert = "alert-danger";
  $targetFile = str_replace("\\", "/", getcwd()).EXCHANGE_PATH.EXPORT_FILE;
  if (isset($_POST["submit"])) {
	$stat = exportUsers($targetFile);
	if ($stat["alert"] == 1) $alert = "alert-warning";
	elseif ($stat["alert"] == 2) $alert = "alert-success";
	$meldung = $stat["meldung"];
  }
?>

<form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']."?function=export"; ?>">
  <div class="col-md-10 text-center">
	<h4>
	  <?php
		echo "Die Benutzer werden in die Datei \"$targetFile\" exportiert.";
	  ?>
	</h4>
	<br /><br />
  </div>
  <div class="form-group">
	<div class="col-md-offset-3 col-md-4 text-center">
		<button type="submit" name="submit" class="btn btn-success">Export starten</button>
	</div>
  </div>
</form>
<?php if (strlen($meldung) > 0) echo "<br /><div class='col-md-offset-2 col-md-6 alert $alert'>$meldung</div>"; ?>
