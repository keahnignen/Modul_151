<?php
  $userId = getUserIdFromSession();
  // Falls der Benutzer nicht angemeldet ist, wechselt die App in den Login-Bereich
  if ($userId == 0) {
    header("Location: index.php?function=".getValue('cfg_func_login')[0]);
	exit;
  }
  $meldung = "";
  // Wenn ein Beitrag gelöscht werden soll
  if ($delete) {
	if (deleteEntry($entryId)) {
	  $entryId = 0;
	  $meldung = "<div class='alert alert-success'><p>Der Beitrag wurde erfolgreich gelöscht.</p></div>";
	} else {
	  $meldung = "<div class='alert alert-danger'>Der Beitrag konnte nicht gelöscht werden.</div>";
	}
  }
?>
<div class="row">
  <div class="col-md-4">
	<?php
	  $entries = getEntries($userId);
	  foreach ($entries as $entry) {
		if ($entry[0] == $entryId) $active = " active";
		else $active = "";
		echo "<div class='list-group'>";
		echo "<a class='list-group-item$active' href='index.php?function=entries_member&eid=".$entry[0]."' title='Beitrag anzeigen'>";
		$datetime = date("d.m.Y H:i:s", $entry[1]);
		echo "<h4 class='list-group-item-heading'>".htmlentities($entry[2]).", ".$datetime."</h4>";
		$string = htmlentities(substr($entry[3],0,95))."...";
		echo "<p class='list-group-item-text'>".$string."</p>";
		echo "</a>";
		echo "</div>";
	  }
	?>
  </div>
  <?php
	if (strlen($meldung) > 0) {
	  echo "<div class='col-md-7'>";
	  echo $meldung;
	  echo "</div>";
	} elseif ($entryId > 0) {
	  $entry = getEntry($entryId);
	  if (is_array($entry)) {
		echo "<div class='col-md-7'>";
		$datetime = date("d.m.Y H:i:s", $entry[1]);
		echo "<h3>".htmlentities($entry[2]).", ".$datetime."</h3>";
		echo nl2br(htmlentities($entry[3]));
		echo "</div>";
		echo "<div class='col-md-1'>";
		echo "<a href='index.php?function=entry_edit&eid=$entry[0]' title='Beitrag bearbeiten'><span class='glyphicon glyphicon-pencil'></span></a><br />";
		echo "<a href='index.php?function=entries_member&delete=true&eid=$entry[0]' title='Beitrag löschen' onclick='return confirmDelete();'><span class='glyphicon glyphicon-remove'></span></a>";
		echo "</div>";
	  }
	}
  ?>
</div>
