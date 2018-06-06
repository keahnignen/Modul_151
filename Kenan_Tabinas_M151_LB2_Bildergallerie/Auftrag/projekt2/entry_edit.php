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
  // Wenn die geänderten Daten in die DB geschrieben werden sollen
  if (isset($_POST["titel"]) && isset($_POST["inhalt"])) {
    $titel = $_POST["titel"];
	$inhalt = $_POST["inhalt"];
	if ((strlen($titel) < 3) || (strlen($inhalt) < 3)) $meldung = "<br /><div class='alert alert-danger'>Titel und Inhalt müssen je mind. 3 Zeichen enthalten.</div>";
	elseif (updateEntry($entryId, $_POST["titel"], $_POST["inhalt"])) $meldung = "<br /><div class='alert alert-success'>Der Beitrag wurde erfolgreich geändert.</div>";
	else $meldung = "<br /><div class='alert alert-danger'>Der Beitrag konnte nicht geändert werden.</div>";
  // Wenn die Seite zum Editieren aufgerufen wird
  } else {
	$entry = getEntry($entryId);
	if (is_array($entry)) {
      $titel = htmlentities($entry[2]);
	  $inhalt = htmlentities($entry[3]);
	}
  }
?>
<div class="col-md-8">
  <form class="form" method="post" action="<?php echo $_SERVER['PHP_SELF']."?function=entry_edit&eid=$entryId"; ?>">
	<div class="form-group">
	  <label for="title">Titel</label>
	  <input type="text" class="form-control" id="titel" name="titel" value="<?php echo $titel; ?>">
	</div>
	<div class="form-group">
	  <label for="content">Inhalt</label>
	  <textarea class="form-control" id="inhalt" name="inhalt" rows="10"><?php echo $inhalt; ?></textarea>
	</div>
	<button type="submit" class="btn btn-success">speichern</button>
	<a  href="index.php?function=entries_member&delete=true&eid=<?php echo $entryId; ?>" onclick="return confirmDelete();" class="btn btn-danger">löschen</a>
	<a  href="index.php?function=entries_member&eid=<?php echo $entryId; ?>" class="btn btn-warning">abbrechen</a>
  </form>
  <?php echo $meldung; ?>
</div>
