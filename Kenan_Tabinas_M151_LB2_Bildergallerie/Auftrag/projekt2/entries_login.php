<?php
  $userId = getUserIdFromSession();
  // Falls der Benutzer bereits angemeldet ist, wechselt die App in den Member-Bereich
  if ($userId > 0) {
    header("Location: index.php?function=".getValue('cfg_func_member')[0]);
	exit;
  }
  $name = "";
  $kommentar = "";
  $meldung = "";
  if (isset($_POST["name"]) && isset($_POST["kommentar"])) {
	if ((strlen($_POST["name"]) < 3) || (strlen($_POST["kommentar"]) < 10)) {
	  $name = $_POST["name"];
	  $kommentar = $_POST["kommentar"];
	  $meldung = "<br /><div class='alert alert-danger'>Name muss mind. 3 und Kommentar mind. 10 Zeichen enthalten.</div>";
	} elseif (addCommentNoUser($entryId, $_POST["name"], $_POST["kommentar"]) > 0) {
	  $meldung = "<br /><div class='alert alert-success'>Der Kommentar wurde erfolgreich hinzugefügt.</div>";
	} else {
	  $meldung = "<br /><div class='alert alert-danger'>Der Kommentar konnte nicht eingefügt werden.</div>";
	}
  }
?>
<div class="row">
  <div class="col-md-4">
	<?php
	  $entries = getEntries($blogId);
	  foreach($entries as $entry) {
		if ($entry[0] == $entryId) {
		  $active = " active";
		} else {
		  $active = "";
		}
		echo "<div class='list-group'>";
		echo "<a class='list-group-item$active' href='index.php?function=entries_login&bid=$blogId&eid=$entry[0]' title='Beitrag anzeigen'>";
		$datetime = date("d.m.Y H:i:s", $entry[1]);
		echo "<h4 class='list-group-item-heading'>".htmlspecialchars($entry[2]).", ".$datetime."</h4>";
		$string = htmlspecialchars(substr($entry[3],0,95))."...";
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
	}
	if ($entryId > 0) {
	  $entry = getEntry($entryId);
	  if (is_array($entry)) {
		echo "<div class='col-md-7'>";
		$datetime = date("d.m.Y H:i:s", $entry[1]);
		echo "<h3>".htmlspecialchars($entry[2]).", ".$datetime."</h3>";
		echo nl2br(htmlspecialchars($entry[3]));
		echo "<p>&nbsp;</p>";
		echo "<h4>Kommentare</h4>";
		$comments = getComments($entryId);
		foreach($comments as $comment) {
		  $datetime = date("d.m.Y H:i:s", $comment[3]);
		  echo "<p>";
		  echo htmlentities($comment[5]).", $datetime<br />";
		  echo "<small>".nl2br(htmlspecialchars($comment[4]))."</small><br />";
		  echo "</p>";
		}
		echo "<form name='formular' method='post' action='".$_SERVER['PHP_SELF']."?function=entries_login&bid=$blogId&eid=$entry[0]'>";
		echo "<div class='form-group'>";
		echo "<input type='text' class='form-control' id='name' name='name' placeholder='Name (mind. 3 Zeichen)' value='$name' />";
		echo "</div>";
		echo "<div class='form-group'>";
		echo "<textarea class='form-control' id='kommentar' name='kommentar' rows='5' placeholder='Kommentar (mind. 10 Zeichen)'>$kommentar</textarea>";
		echo "</div>";
		echo "<div style='text-align:right';>";
		echo "<a href='javascript: document.formular.submit()'>hinzufügen</a>";
		echo "</div>";
		echo "</form>";
		echo "</div>";
	  }
	}
	?>
</div>
