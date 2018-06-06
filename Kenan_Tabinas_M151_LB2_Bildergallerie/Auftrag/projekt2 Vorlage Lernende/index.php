<?php
  session_start();
  require_once("include/functions.php");
  require_once("include/functions_db.php");
  define("DBNAME", "db/blog.db");
  // Datenbankverbindung herstellen
  if (!file_exists(DBNAME)) exit("Die Datenbank 'blog.db' konnte nicht gefunden werden!");
  $db = new SQLite3(DBNAME);
  setValue("cfg_db", $db);
  // Einfacher Dispatcher: Aufruf der Funktionen via index.php?function=xy
  if (isset($_GET['function'])) $function = $_GET['function'];
  else $function = "login";
  // Prüfung, ob bereits ein Blog ausgewählt worden ist
  if (isset($_GET['bid'])) $blogId = $_GET['bid'];
  else $blogId = 0;
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
<!-- 
  Die nächsten 4 Zeilen sind Bootstrap, falls nicht gewünscht entfernen.
-->
  <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
  <link href="css/bootstrap.min.css" rel="stylesheet" />
  <script src="js/jquery-3.1.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="include/functions.js"></script>
  <title>Blog-Projekt</title>
</head>

<body>
<!-- 
  nav, div und ul class="..." ist Bootstrap, falls nicht gewünscht entfernen oder anpassen.
  Die Einteilung der Website in verschiedene Bereiche (Menü-, Content-Bereich, usw.) kann auch selber mit div realisiert werden.
-->
  <nav class="navbar navbar-default navbar-fixed-top">
	<div class="container">
      <div class="navbar-header">
		<a class="navbar-brand"><?php echo "Blog (Namen einsetzen...)"; ?></a>
      </div>
      <ul class="nav navbar-nav">
		<?php 
		  echo "<li><a href='index.php?function=login&bid=$blogId'>Login</a></li>";
		  echo "<li><a href='index.php?function=blogs&bid=$blogId'>Blog wählen</a></li>";
		  echo "<li><a href='index.php?function=entries_login&bid=$blogId'>Beiträge anzeigen</a></li>";
		?>
      </ul>
	</div>
  </nav>
  <div class="container" style="margin-top:80px">
  <?php
	if (!file_exists("$function.php")) exit("Die Datei '$function.php' konnte nicht gefunden werden!");
	require_once("$function.php");
  ?>
  </div>
</body>
</html>
<?php
  $db = getValue('cfg_db');
  $db->close();
?>
