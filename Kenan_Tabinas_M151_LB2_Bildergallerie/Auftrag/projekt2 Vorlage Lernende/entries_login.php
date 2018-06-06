<?php
  if (isset($_GET['bid'])) $blogId = $_GET['bid'];
  else $blogId = 0;
  $entries = getEntries($blogId);
  // Schlaufe über alle Einträge dieses Blogs
  foreach ($entries as $entry) {
	echo "<div>";
	$datetime = date("d.m.Y H:i:s", $entry[1]);
	echo "<h4>".htmlspecialchars($entry[2]).", ".$datetime."</h4>";
	echo nl2br(htmlspecialchars($entry[3]));
	echo "</div>";
  }
?>

