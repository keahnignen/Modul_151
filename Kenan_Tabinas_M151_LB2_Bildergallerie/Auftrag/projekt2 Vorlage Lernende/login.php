<?php
  $meldung = "";
  $email = "";
  $passwort = "";
  // Wenn Formular gesendet worden ist und die Login-Daten korrekt sind:
  // Session-Variable mit Benutzer-ID setzen und Wechsel in Memberbereich
  // $_SESSION['uid'] = $uid;	
  // header('Location: index.php?function=entries_member');
  // Wenn Formular gesendet worden ist, die Login-Daten aber nicht korrekt sind:
  // $meldung = "Login-Daten nicht korrekt... bitte nochmals versuchen oder registrieren.";
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']."?function=login"; ?>">
  <label for="email">Benutzername</label>
  <div>
	<input type="email" id="email" name="email" placeholder="E-Mail" value="<?php echo $email; ?>" />
  </div>
  <label for="passwort">Passwort</label>
  <div>
	<input type="password" id="passwort" name="passwort" placeholder="Passwort" value="<?php echo $passwort; ?>" />
  </div>
  <div>
	<button type="submit">senden</button>
  </div>
</form>
<?php
  if (strlen($meldung) > 0) echo "<div class='col-md-offset-2 col-md-6 alert alert-danger'>$meldung</div>";
?>
