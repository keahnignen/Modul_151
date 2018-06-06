<?php
  $userId = getUserIdFromSession();
  // Falls der Benutzer bereits angemeldet ist, wechselt die App in den Member-Bereich
  if ($userId > 0) {
    header("Location: index.php?function=".getValue('cfg_func_member')[0]);
	exit;
  }
?>
<div class="row">
	<?php
	  // Anzahl Spalten für die Ausgabe der Namen
	  $anzRows = 3;
	  $row = 1;
	  $blogs = getUserNames();
	  $array = [];
	  // Die Benutzer auf die Spalten aufteilen
	  foreach ($blogs as $blog) {
		$array[$row][] = $blog;
		$row++;
		if ($row > $anzRows) $row = 1;
	  }
	  // Schlaufe über alle Spalten
	  for ($row=1; $row<=$anzRows; $row++) {
		echo "<div class='col-md-4'>";
		// Schlaufe über den Array dieser Spalte
		foreach ($array[$row] as $blog) {
		  if ($blog[0] == $blogId) $active = " active";
		  else $active = "";
		  echo "<div class='list-group'>";
		  echo "<a class='list-group-item$active' href='index.php?function=entries_login&bid=".$blog[0]."&eid=".getMaxEntryId($blog[0])."' title='Blog auswählen'>";
		  echo "<h4 class='list-group-item-heading'>".htmlspecialchars($blog[1])."</h4>";
		  echo "</a>";
		  echo "</div>";
		}
		echo "</div>";
	  }
	?>
</div>
