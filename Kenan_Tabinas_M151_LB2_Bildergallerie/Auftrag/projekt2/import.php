<?php
  $userId = getUserIdFromSession();
  // Falls der Benutzer bereits angemeldet ist, wechselt die App in den Member-Bereich
  if ($userId > 0) {
    header("Location: index.php?function=".getValue('cfg_func_member')[0]);
	exit;
  }
  $meldung = "";
  $alert = "alert-danger";
  if (isset($_POST["submit"])) {
	if (strlen($_FILES["import"]["name"]) < 3) $meldung = "Bitte eine Datei für den Import auswählen.";
	else {
	  $targetFile = "exchange/import.csv";
	  // Falls bereits ein Import durchgeführt worden ist, wird die alte Datei gelöscht
	  if (file_exists($targetFile)) unlink($targetFile);
	  if (move_uploaded_file($_FILES["import"]["tmp_name"], $targetFile)) {
		$stat = importUsers(basename($_FILES["import"]["name"]), $targetFile);
		if ($stat["alert"] == 1) $alert = "alert-warning";
		elseif ($stat["alert"] == 2) $alert = "alert-success";
		$meldung = $stat["meldung"];
      } else $meldung = "Die Datei '".basename($_FILES["import"]["name"])."' konnte nicht auf den Server geladen werden.";
    }
  }
?>

<form class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']."?function=import"; ?>">
  <div class="form-group">
	<div class="col-md-offset-2 col-md-6">
	  <input type="file" class="filestyle" name="import" data-icon="true" data-buttonText="&nbsp;Datei auswählen" data-buttonName="btn-default" />
	</div>
  </div>
  <div class="form-group">
	<div class="col-md-offset-2 col-md-4">
		<button type="submit" name="submit" class="btn btn-success">Import starten</button>
	</div>
  </div>
</form>
<?php if (strlen($meldung) > 0) echo "<br /><div class='col-md-offset-2 col-md-6 alert $alert'>$meldung</div>"; ?>
