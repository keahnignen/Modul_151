<?php
  $userId = getUserIdFromSession();
  // Falls der Benutzer bereits angemeldet ist, wechselt die App in den Member-Bereich
  if ($userId > 0) {
    header("Location: index.php?function=".getValue('cfg_func_member')[0]);
	exit;
  }
  $meldung = "";
  $email = "";
  $passwort = "";
  if (isset($_POST['email']) && isset($_POST['passwort'])) {
  	$uid = getUserIdFromDb($_POST['email'], $_POST['passwort']);
  	if ($uid > 0) {
	  $_SESSION['uid'] = $uid;	
	  header("Location: index.php?function=entries_member&eid=".getMaxEntryId($uid));
	}
  	else {
	  $meldung = "Login-Daten nicht korrekt... bitte nochmals versuchen oder registrieren.";
	  $email = $_POST['email'];
	  $passwort = $_POST['passwort'];
	}
  }
?>
<form class="form-horizontal" method="post" action="<?php echo $_SERVER['PHP_SELF']."?function=login"; ?>">
  <div class="form-group">
	<label class="control-label col-md-offset-2 col-md-2" for="email">Benutzername</label>
	<div class="col-md-4">
	  <input type="email" class="form-control" id="email" name="email" placeholder="E-Mail" value="<?php echo $email; ?>" />
	</div>
  </div>
  <div class="form-group">
	<label class="control-label col-md-offset-2 col-md-2" for="passwort">Passwort</label>
	<div class="col-md-4">
	  <input type="password" class="form-control" id="passwort" name="passwort" placeholder="Passwort" value="<?php echo $passwort; ?>" />
	</div>
  </div>
  <div class="form-group">
	<div class="col-md-offset-4 col-md-4">
		<button type="submit" class="btn btn-success">senden</button>
	</div>
  </div>
</form>
<?php
  if (strlen($meldung) > 0) echo "<div class='col-md-offset-2 col-md-6 alert alert-danger'>$meldung</div>";
?>
