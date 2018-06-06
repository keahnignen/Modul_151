<?php
  // Schreibt einen Wert in den globalen Array
  function setValue($key, $value) {
    global $params;
    $params[$key] = $value;
  }

  // Holt einen Wert aus dem globalen Array
  function getValue($key) {
    global $params;
	if (isset($params[$key])) return $params[$key];
	else return "";
  }

  // Prüft, ob der Benutzer angemeldet ist
  function getUserIdFromSession() {
	if (isset($_SESSION['uid'])) return $_SESSION['uid'];
	else return 0;
  }

  // Generiert den Titel, der links von der Navigation angezeigt wird
  function getMenuTitle($area, $function, $userId, $blogId) {
	if ($area == "member") {
	  if ($userId > 0) return "Willkommen '".getUserName($userId)."'";
	  else return "Willkommen im Memberbereich";
	} else {
	  if ($blogId > 0 && $function != "login") return "Blog '".getUserName($blogId)."'";
	  else return "Blog-Projekt M133";
	}
  }
  
  // Baut die Bootstrap-Navigation auf
  function getMenu($area, $function, $blogId, $entryId, $delete) {
	$menu = "";
	$active = "";
	$disabled = "";
	// Je nachdem das Login- bzw. Member-Menü anzeigen
	if ($area == "member") $menuArray = "cfg_menu_member";
	else $menuArray = "cfg_menu_login";
	foreach (getValue($menuArray) as $key => $value) {
	  // Der ausgewählte Menüpunkt wird speziell markiert
	  if ($key == $function) $active = " class='active'";
	  else {
		$active = "";
		// Die Menüeinträge "Beiträge anzeigen" bzw. "Beitrag ändern" werden je nach Fall deaktiviert 
		if (in_array($key, getValue("cfg_menu_disabled")) && $blogId == 0 && ($entryId == 0 || $delete)) $disabled = " class='disabled'";
		else $disabled = "";
	  }
	  $menu .= "<li$active$disabled><a href='index.php?function=$key&bid=$blogId&eid=$entryId'>$value</a></li>";
	}
	return $menu;
  }

  // Fügt aus einer importierten Datei Benutzer in die Datenbank ein
  function importUsers($sourceFile, $targetFile) {
	$meldung = "";
	// Das Format der Meldung wird auf Fehler (=rot) voreingestellt
	$alert = 0;
	$anzOk = 0;
	$anzFehler = 0;
	if (file_exists($targetFile)) {
	  // Liest die gesamte Datei in ein Array ein
	  $users = file($targetFile);
	  // Prüfen, ob mind. die Kopfzeile und 1 Benutzer vorhanden sind
	  if (count($users) > 1) {
		// Prüfen, ob alle Attribute vorhanden sind und in der richtigen Reihenfolge vorliegen
		$user = explode(";", $users[0]);
		if (trim($user[0]) == "name" && trim($user[1]) == "email" && trim($user[2]) == "password") {
		  // Alle Benutzer einlesen (die 1. Zeile enthält die Attributnamen)
		  for($i = 1; $i < count($users); $i++) {
			$user = explode(";", $users[$i]);
			// Wenn die Mailadresse bereits vorhanden ist, kann der Benutzer nicht eingefügt werden
			if (userExists($user[1])) $anzFehler++;
			// Falls ok, wird der Benutzer eingefügt
			elseif (addUser($user[0], $user[1], $user[2], 1)) $anzOk++;
			else $anzFehler++;
		  }
		  $meldung = "$anzOk Benutzer konnten erfasst werden.<br />$anzFehler Fehler ergaben sich, weil z.B. die Mail-Adresse bereits existiert.";
		  // Wenn ein paar Benutzer ok und ein paar mit Fehler, wird eine Warnung ausgegeben
		  if ($anzOk > 0 && $anzFehler > 0) $alert = 1;
		  // Wenn alle Benutzer ok
		  if ($anzOk > 0 && $anzFehler == 0) $alert = 2;
		} else $meldung = "Die Datei '$sourceFile' enthält keine gültigen Daten für den Import.";
	  } else $meldung = "In der Datei '$sourceFile' sind keine Benutzer für den Import vorhanden.";
	} else $meldung = "Die Datei '$targetFile' ist nicht vorhanden, Import nicht möglich.";
	return array("alert"=>$alert, "meldung"=>$meldung);
  }

  // Exportiert alle registrierten Benutzer in eine externe Datei
  function exportUsers($targetFile) {
	$meldung = "";
	// Das Format der Meldung wird auf alles ok (=grün) voreingestellt
	$alert = 2;
	$anzOk = 0;
	$anzFehler = 0;
	$handle = fopen($targetFile, "w");
	// Die Namen der Attribute schreiben
	fwrite($handle, "name;email;password");
	// Alle Benutzer holen und in einer Schlaufe diese in die Datei schreiben
	$users = getUsers();
	foreach ($users as $user) {
	  if (fwrite($handle, "\n".trim($user[1]).";".trim($user[2]).";".trim($user[3]))) $anzOk++;
	  else $anzFehler++;
	}
	fclose($handle);
	$meldung = "$anzOk Benutzer konnten exportiert werden.<br />$anzFehler Fehler ergaben sich.";
	// Wenn ein paar Benutzer ok und ein paar mit Fehler, wird eine Warnung ausgegeben
	if ($anzOk > 0 && $anzFehler > 0) $alert = 1;
	// Wenn keine Benutzer geschrieben werden konnten
	if ($anzOk == 0 && $anzFehler > 0) $alert = 0;
	return array("alert"=>$alert, "meldung"=>$meldung);
  }
?>
