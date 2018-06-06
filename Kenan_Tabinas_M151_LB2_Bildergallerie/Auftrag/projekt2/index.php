<?php
  session_start();
  require_once("include/functions.php");
  require_once("include/functions_db.php");
  require_once("include/config.php");
  if (isset($_GET['function'])) $function = $_GET['function'];
  else $function = "login";
  // Wenn es sich um eine Funktion des Member-Bereichs handelt
  if (in_array($function, getValue('cfg_func_member'))) $area = "member";
  else $area = "login";
  $userId = getUserIdFromSession();
  if (isset($_GET['bid'])) $blogId = $_GET['bid'];
  else $blogId = 0;
  if (isset($_GET['eid'])) $entryId = $_GET['eid'];
  else $entryId = 0;
  if (isset($_GET['delete'])) $delete = true;
  else $delete = false;

?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
  <link href="css/bootstrap.min.css" rel="stylesheet" />
  <script src="js/jquery-3.1.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/bootstrap-filestyle.min.js"></script>
  <script src="include/functions.js"></script>
  <title>Blog-Projekt</title>
</head>

<body>
  <nav class="navbar navbar-default navbar-fixed-top">
	<div class="container">
      <div class="navbar-header">
		<a class="navbar-brand"><?php echo getMenuTitle($area, $function, $userId, $blogId); ?></a>
      </div>
      <ul class="nav navbar-nav">
		<?php echo getMenu($area, $function, $blogId, $entryId, $delete); ?>
      </ul>
	</div>
  </nav>
  <div class="container" style="margin-top:80px">
  <?php
	if (!file_exists("$function.php")) exit("Die Datei '$function.php' konnte nicht gefunden werden!");
	require_once("$function.php");
  ?>
  </div>
  <div class="container" style="margin-top:20px; margin-bottom:20px">
	<div class="row">
	  <div class="col-md-offset-3 col-md-4 text-center small text-muted">
		&copy;&nbsp;Copyright Michael Abplanalp
	  </div>
	</div>
  </div>
</body>
</html>
<?php
  $db = getValue('cfg_db');
  $db->close();
?>
