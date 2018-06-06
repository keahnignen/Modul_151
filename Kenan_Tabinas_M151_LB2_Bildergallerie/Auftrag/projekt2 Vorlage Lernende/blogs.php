<?php
  if (isset($_GET['bid'])) $blogId = $_GET['bid'];
  else $blogId = 0;
  $blogs = getUserNames();
  // Schlaufe über alle Blogs bzw. Benutzer
  foreach ($blogs as $blog) {
	echo "<div>";
	echo "<a href='index.php?function=blogs&bid=".$blog[0]."' title='Blog auswählen'>";
	echo "<h4>".htmlspecialchars($blog[1])."</h4>";
	echo "</a>";
	echo "</div>";
  }
?>