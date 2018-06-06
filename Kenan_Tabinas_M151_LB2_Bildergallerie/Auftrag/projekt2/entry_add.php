<?php
  $userId = getUserIdFromSession();
  // Falls der Benutzer nicht angemeldet ist, wechselt die App in den Login-Bereich
  if ($userId == 0) {
    header("Location: index.php?function=".getValue('cfg_func_login')[0]);
	exit;
  }
  $titel = "";
  $inhalt = "";
  $meldung = "";
  if (isset($_POST["titel"]) && isset($_POST["inhalt"])) {
	if ((strlen($_POST["titel"]) < 3) || (strlen($_POST["inhalt"]) < 10)) {
	  $titel = $_POST["titel"];
	  $inhalt = $_POST["inhalt"];
	  $meldung = "<br /><div class='alert alert-danger'>Titel muss mind. 3 und Beitrag mind. 10 Zeichen enthalten.</div>";
	} elseif (addEntry($userId, $_POST["titel"], $_POST["inhalt"])) {
		$meldung = "<br /><div class='alert alert-success'>Der Beitrag wurde erfolgreich erstellt.</div>";
	} else {
	  $meldung = "<br /><div class='alert alert-danger'>Der Beitrag konnte nicht eingefügt werden.</div>";
	}
  }
?>
<div class="col-md-8">
  <form class="form" method="post" action="<?php echo $_SERVER['PHP_SELF']."?function=entry_add"; ?>">
	<div class="form-group">
	  <label for="title">Titel</label>
	  <input type="text" class="form-control" id="titel" name="titel" value="<?php echo $titel; ?>" placeholder="Min. 3 Zeichen">
	</div>
	<div class="form-group">
	  <label for="content">Inhalt</label>
	  <textarea class="form-control" id="inhalt" name="inhalt" rows="10" placeholder="Min. 10 Zeichen"><?php echo $inhalt; ?></textarea>
	</div>
	<button type="submit" class="btn btn-success">speichern</button>
	<a  href="index.php" class="btn btn-warning">abbrechen</a>
  </form>
  <?php	echo $meldung; ?>
</div>
