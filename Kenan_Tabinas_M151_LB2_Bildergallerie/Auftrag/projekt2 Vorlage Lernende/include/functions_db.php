<?php
  /************************************************************************************************
   getUserIdFromDb:	Sucht mit der Mailadresse und dem Passwort den Benutzer in der Datenbank
					(Authentifizierung mit den Login-Daten).
   $email:			Mailadresse, die der Benutzer eingegeben hat
   $password:		Passwort, das der Benutzer eingegeben hat
   md5():			Verschlüsselt das Passwort mit md5()
   Rückgabe:		- User-ID uid, falls erfolgreich
					- 0, falls Benutzer nicht gefunden
   ************************************************************************************************/
  function getUserIdFromDb($email, $password) {
	$db = getValue('cfg_db');
	$email = strtolower($email);
	$result = $db->query("SELECT uid FROM user WHERE lower(email)='".$email."' AND password='".md5($password)."'");
	if ($user = $result->fetchArray()) return $user[0];
	else return 0;
  }

  /************************************************************************************************
   userExists: Sucht mit der Mailadresse den Benutzer in der Datenbank (damit eine Mailadresse
			   nicht 2x registriert werden kann).
   $email:	   Mailadresse, die der Benutzer eingegeben hat
   Rückgabe:   - True, falls Benutzer vorhanden
			   - False, falls Benutzer nicht vorhanden
   ************************************************************************************************/
  function userExists($email) {
	$db = getValue('cfg_db');
	$email = strtolower($email);
	$result = $db->query("SELECT uid FROM user WHERE lower(email)='".$email."'");
	if ($user = $result->fetchArray()) true;
	else return false;
  }

  /************************************************************************************************
   getUserName:	Liefert den Namen der übergebenen UserView-ID zurück
   Hinweis:		Ist nützlich, um den Benutzer z.B. mit "Willkommen 'Marc Muster'" zu begrüssen
   $uid:		UserView-ID des gewünschten Benutzers
   Rückgabe:	- Name, falls vorhanden (NULL-Wert möglich)
				- Mailadresse, falls Name = NULL
				- Leerer String, falls Benutzer-ID nicht vorhanden
   ************************************************************************************************/
  function getUserName($uid) {
	$db = getValue('cfg_db');
	$result = $db->query("SELECT name, email FROM user WHERE uid=".$uid);
	if ($user = $result->fetchArray()) {
	  if (strlen($user[0]) > 0) return $user[0];
	  else return $user[1];
	} else return "";
  }

  /************************************************************************************************
   getUserNames: Liefert die Namen aller registrierter Benutzer zurück
   Hinweis:		 Jeder Benutzer hat einen Blog, der auf seinen Namen lautet. Mit der Liste können
				 demzufolge alle Blogs angezeigt werden. Die Funktion könnte auch getBlogs() heissen.
   Rückgabe:	 2-dimensionales Array, 
				 - 1. Dimension = Benutzer
				 - 2. Dimension = Attribute des Benutzers
					* Name, falls vorhanden (NULL-Wert möglich)
					* Mailadresse, falls Name = NULL
   Sortierung:	 1. nach Name und 2. nach Mailadresse
   ************************************************************************************************/
  function getUserNames() {
	$alle = [];
	$db = getValue('cfg_db');
	$users = $db->query("SELECT uid, name, email FROM user ORDER BY name, email");
	while ($user = $users->fetchArray()) {
	  if (strlen($user[1]) > 0) $name = $user[1];
	  else $name = $user[2];
	  $alle[] = array($user[0], $name);
	}
	return $alle;
  }

  /************************************************************************************************
   getUsers: Liefert alle registrierten Benutzer zurück
   Hinweis:	 Diese Funktion kann dazu benutzt werden, alle Benutzer in eine Textdatei zu exportieren
   Rückgabe: 2-dimensionales Array, 
			 - 1. Dimension = Benutzer
			 - 2. Dimension = Attribute des Benutzers
				* User-ID
				* Name, falls vorhanden (NULL-Wert möglich)
				* Mailadresse
				* md5-verschlüsseltes Passwort
   Sortierung:	 1. nach Name und 2. nach Mailadresse
   ************************************************************************************************/
  function getUsers() {
	$alle = [];
	$db = getValue('cfg_db');
	$users = $db->query("SELECT uid, name, email, password FROM user ORDER BY name, email");
	while ($user = $users->fetchArray()) {
	  $alle[] = $user;
	}
	return $alle;
  }

  /************************************************************************************************
   addUser:	  Schreibt einen neuen Benutzer in die Datenbank
   Hinweis:	  Diese Funktion kann dazu benutzt werden, Benutzer aus einer Textdatei zu importieren
   $name:	  Name des Benutzers, kann leer sein (NULL oder leerer String)
   $email:	  Mailadresse des Benutzers, NOT NULL
   $passwort: Verschlüsseltes Passwort des Benutzers, NOT NULL
   Rückgabe: - True bei Erfolg
			 - False bei Fehler
   ************************************************************************************************/
  function addUser($name, $email, $password) {
	$db = getValue('cfg_db');
	$name = SQLite3::escapeString($name);
	$email = SQLite3::escapeString($email);
	$sql = "INSERT INTO user (name, email, password) values ('$name', '$email', '$password')";
	return $db->exec($sql);
  }

  /************************************************************************************************
   getEntries: Liefert alle Beiträge eines Benutzers/Blogs zurück
   Hinweis:	   Möglichkeit 1: Es werden in einem ersten Schritt nur die Titel der Beiträge angezeigt. In diesem
			   Fall sind nur Entry-ID, Datum und Titel relevant.
			   Möglichkeit 2. Es werden gleich alle Blog-Beiträge untereinander angezeigt.
   $uid:	   User-ID des gewünschten Benutzers
   Rückgabe:   2-dimensionales Array, 
			   - 1. Dimension = Blog-Beitrag
			   - 2. Dimension = Attribute des Beitrags
					* Entry-ID
					* Datum als Unix-Timestamp (muss mit der Funktion date() in ein lesbares
					  Datum umgewandelt werden)
					* Titel
					* Inhalt (der eigentliche Beitrag)
					* Pfad und Dateiname der Bilder 1-3
   Sortierung: Nach Entry-ID absteigend (d.h. der aktuellste zuerst)
   ************************************************************************************************/
  function getEntries($uid) {
	$alle = [];
	$db = getValue('cfg_db');
	$entries = $db->query("SELECT eid, datetime, title, content, picture1, picture2, picture3 FROM entry WHERE uid=$uid ORDER BY eid DESC");
	while ($entry = $entries->fetchArray()) {
	  $alle[] = $entry;
	}
	return $alle;
  }

  /************************************************************************************************
   getEntriesTheme:	Siehe Beschreibung "getEntries"
   Unterschied:		Es werden alle Beiträge eines Blogs zu einem bestimmten Thema zurückgegeben
   $tid:			Thema-ID (damit wird die Abfrage auf das gewünschte Thema eingeschränkt)
   ************************************************************************************************/
  function getEntriesTheme($uid, $tid) {
	$alle = [];
	$db = getValue('cfg_db');
	$entries = $db->query("SELECT eid, datetime, title, content, picture1, picture2, picture3 FROM entry WHERE uid=$uid AND tid=$tid ORDER BY eid DESC");
	while ($entry = $entries->fetchArray()) {
	  $alle[] = $entry;
	}
	return $alle;
  }

  /************************************************************************************************
   getEntry: Liefert einen bestimmten Beitrag zurück
   Hinweis:	 Falls in einem ersten Schritt nur die Titel der Beiträge angezeigt werden, kann mit
			 dieser Funktion ein einzelner Beitrag zur Anzeige zurückgeliefert werden.
   $eid:	 Entry-ID eines Blog-Beitrags
   Rückgabe:   1-dimensionales Array (Attribute des Beitrags)
					* Entry-ID
					* Datum als Unix-Timestamp (muss mit der Funktion date() in ein lesbares
					  Datum umgewandelt werden)
					* Titel
					* Inhalt (der eigentliche Beitrag)
					* Pfad und Dateiname der Bilder 1-3
   ************************************************************************************************/
  function getEntry($eid) {
	$db = getValue('cfg_db');
	$result = $db->query("SELECT eid, datetime, title, content, picture1, picture2, picture3 FROM entry WHERE eid=$eid");
	if ($entry = $result->fetchArray()) {
	  return $entry;
	} else return "";
  }

  /************************************************************************************************
   addEntry: Schreibt einen neuen Beitrag in die Datenbank, mit den min. erforderlichen Attributen
   $uid:	 User-ID - Jeder Beitrag muss einem Benutzer/Blog zugeordnet werden
   $title:	 Der Titel des Beitrags
   $content: Der Inhalt des Beitrags
   time():	 Erstellt den aktuellen UNIX-Timestamp
   Rückgabe: - True bei Erfolg
			 - False bei Fehler
   ************************************************************************************************/
  function addEntry($uid, $title, $content) {
	$db = getValue('cfg_db');
	$title = SQLite3::escapeString($title);
	$content = SQLite3::escapeString($content);
	$sql = "INSERT INTO entry (uid, datetime, title, content) values ($uid, ".time().", '$title', '$content')";
	return $db->exec($sql);
  }

  /************************************************************************************************
   addEntryExtended: Schreibt einen neuen Beitrag in die Datenbank, mit allen Attributen
   $uid:			 User-ID - Jeder Beitrag muss einem Benutzer/Blog zugeordnet werden
   $tid:			 Thema-ID (Falls kein Thema eingefügt wird, dann muss für $tid der String
					 "NULL" übergeben werden)
   $title:			 Der Titel des Beitrags
   $content:		 Der Inhalt des Beitrags
   $picture1:		 Pfad + Dateiname des Bildes 1-3 (Falls kein Bild 1 eingefügt wird, dann muss
					 für $picture1 ein leerer String "" übergeben werden - analog Bilder 2 und 3)
   Rückgabe:		 - True bei Erfolg
					 - False bei Fehler
   ************************************************************************************************/
  function addEntryPlus($uid, $tid, $title, $content, $picture1, $picture2, $picture3) {
	$db = getValue('cfg_db');
	$title = SQLite3::escapeString($title);
	$content = SQLite3::escapeString($content);
	$picture1 = SQLite3::escapeString($picture1);
	$picture2 = SQLite3::escapeString($picture2);
	$picture3 = SQLite3::escapeString($picture3);
	$sql = "INSERT INTO entry (uid, tid, datetime, title, content, picture1, picture2, picture3) values ($uid, $tid, ".time().", '$title', '$content', '$picture1', '$picture2', '$picture3')";
	return $db->exec($sql);
  }

  /************************************************************************************************
   updateEntry:	Schreibt Änderungen eines bestehenden Blog-Beitrags in die DB - minimale Variante
   $eid:		Entry-ID des zu ändernden Beitrags
   $title:		Der Titel des Beitrags
   $content:	Der Inhalt des Beitrags
   Rückgabe:	- True bei Erfolg
				- False bei Fehler
   ************************************************************************************************/
  function updateEntry($eid, $title, $content) {
	$db = getValue('cfg_db');
	// Zuerst wird mit einem SELECT sichergestellt, dass der Datensatz existiert, denn das
	// UPDATE-Statement liefert auch TRUE zurück, wenn die Entry-ID nicht vorhanden ist
	$result = $db->query("SELECT * FROM entry WHERE eid=$eid");
	if ($entry = $result->fetchArray()) {
	  $title = SQLite3::escapeString($title);
	  $content = SQLite3::escapeString($content);
	  $sql = "UPDATE entry set title='$title', content='$content' WHERE eid=$eid";
	  return $db->exec($sql);
	} return false;
  }

  /************************************************************************************************
   deleteEntry:	Löscht einen bestimmten Blog-Beitrag aus der Datenbank
   $eid:		Entry-ID des zu löschenden Beitrags
   Rückgabe:	- True bei Erfolg
				- False bei Fehler
   ************************************************************************************************/
  function deleteEntry($eid) {
	$db = getValue('cfg_db');
	// Zuerst wird mit einem SELECT sichergestellt, dass der Datensatz existiert, denn das
	// DELETE-Statement liefert auch TRUE zurück, wenn die Entry-ID nicht vorhanden ist
	$result = $db->query("SELECT * FROM entry WHERE eid=$eid");
	if ($entry = $result->fetchArray()) {
	  $sql = "DELETE FROM entry WHERE eid=$eid";
	  return $db->exec($sql);
	} false;
  }

  /************************************************************************************************
   getComments:	Liefert alle Kommentare eines Blog-Beitrags zurück
   $eid:		Entry-ID des gewünschten Beitrags
   Rückgabe:   2-dimensionales Array, 
			   - 1. Dimension = Kommentar
			   - 2. Dimension = Attribute des Kommentars
					* Comment-ID
					* Entry-ID
					* User-ID (> 0 falls Kommentare den Benutzern zugeordnet werden)
					* Datum als Unix-Timestamp (muss mit der Funktion date() in ein lesbares
					  Datum umgewandelt werden)
					* Der Inhalt des Kommentars
					* Name des Kommentarerstellers (falls Kommentare nicht den registrierten
													Benutzern zugeordnet werden)
					* Zufallszahl, die fürs Löschen von Kommentaren verwendet werden kann
					  (falls Kommentare nicht den registrierten Benutzern zugeordnet werden)
   Sortierung: Nach Entry-ID absteigend (d.h. der aktuellste zuerst)
   ************************************************************************************************/
  function getComments($eid) {
	$alle = [];
	$db = getValue('cfg_db');
	$comments = $db->query("SELECT cid, eid, uid, date, content, name, randomnr FROM comment WHERE eid=$eid ORDER BY cid DESC");
	while ($comment = $comments->fetchArray()) {
	  $alle[] = $entry;
	}
	return $alle;
  }

  /************************************************************************************************
   addComment: Schreibt einen neuen Kommentar in die DB, Variante 1
   Variante 1: Der Benutzer muss angemeldet sein, um einen Kommentar zu schreiben. In diesem
			   Fall wird die UserView-ID als Fremdschlüssel in die Tabelle geschrieben.
   $eid:	   Entry-ID - ID des Beitrgs, zu dem der Kommentar geschrieben wird
   $uid:	   UserView-ID - ID des Benutzers, der den Kommentar schreibt
   $content:   Der Inhalt des Beitrags
   time():	   Erstellt den aktuellen UNIX-Timestamp
   Rückgabe:   - True bei Erfolg
			   - False bei Fehler
   ************************************************************************************************/
  function addComment($eid, $uid, $content) {
	$db = getValue('cfg_db');
	$content = SQLite3::escapeString($content);
	$sql = "INSERT INTO comment (eid, uid, datetime, content) values ($eid, $uid, ".time().", '$content')";
	return $db->exec($sql);
  }
  
  /************************************************************************************************
   addCommentNoUser: Schreibt einen neuen Kommentar in die DB, Variante 2
   Variante 2:		 Der Benutzer, der einen Kommentar schreibt, ist nicht angemeldet. In diesem
					 Fall muss er einen Namen angeben und es wird eine Zufallszahl generiert und
					 zurückgegeben, damit der Kommentar später gelöscht werden kann.
   $eid:			 Entry-ID - ID des Beitrgs, zu dem der Kommentar geschrieben wird
   $content:		 Der Inhalt des Beitrags
   Rückgabe:		 - Bei Erfolg wird die Zufallszahl zurückgegeben
					 - Bei Fehler wird 0 zurückgegeben
   ************************************************************************************************/
  function addCommentNoUser($eid, $name, $content) {
	$db = getValue('cfg_db');
	$name = SQLite3::escapeString($name);
	$content = SQLite3::escapeString($content);
	$randomnr = mt_rand();
	$sql = "INSERT INTO comment (eid, datetime, name, content, randomnr) values ($eid, ".time().", '$name', '$content', $randomnr)";
	if ($db->exec($sql)) return $randomnr;
	else return 0;
  }

  /************************************************************************************************
   deleteComment: Löscht einen bestimmten Kommentar zu einem Blog-Beitrag aus der Datenbank
   Variante 1:	  Der Benutzer muss angemeldet sein, um einen von ihm erstellten Kommentar zu
				  löschen.
   $cid:		  Comment-ID des zu löschenden Kommentars
   Rückgabe:	  - True bei Erfolg
				  - False bei Fehler
   ************************************************************************************************/
  function deleteComment($cid) {
	$db = getValue('cfg_db');
	// Zuerst wird mit einem SELECT sichergestellt, dass der Datensatz existiert, denn das
	// DELETE-Statement liefert auch TRUE zurück, wenn die Comment-ID nicht vorhanden ist
	$result = $db->query("SELECT * FROM comment WHERE cid=$cid");
	if ($comment = $result->fetchArray()) {
	  $sql = "DELETE FROM comment WHERE cid=$cid";
	  return $db->exec($sql);
	} false;
  }

  /************************************************************************************************
   deleteCommentNoUser: Löscht einen bestimmten Kommentar zu einem Blog-Beitrag aus der Datenbank
   Variante 2:			Beim Erstellen eines Kommentars hat der Benutzer eine Zufallszahl erhalten.
						Mithilfe dieser Zahl kann er seinen Kommentar wieder löschen.
   $cid:				Comment-ID des zu löschenden Kommentars
   $randomnr:			Die Zufallszahl
   Rückgabe:			- True bei Erfolg
						- False bei Fehler
   ************************************************************************************************/
  function deleteCommentPlus($cid, $randomnr) {
	$db = getValue('cfg_db');
	// Zuerst wird mit einem SELECT sichergestellt, dass der Datensatz existiert, denn das
	// DELETE-Statement liefert auch TRUE zurück, wenn die Comment-ID nicht vorhanden ist
	$result = $db->query("SELECT * FROM comment WHERE cid=$cid AND randomnr=$randomnr");
	if ($comment = $result->fetchArray()) {
	  $sql = "DELETE FROM comment WHERE cid=$cid AND randomnr=$randomnr";
	  return $db->exec($sql);
	} false;
  }

  /************************************************************************************************
   getTopics:	Liefert alle Themen eines Benutzers zurück
   $uid:		UserView-ID des gewünschten Benutzers
   Rückgabe:	2-dimensionales Array, 
				- 1. Dimension = Thema
				- 2. Dimension = Attribute des Themas
					* Topic-ID
					* UserView-ID
					* Name bzw. Bezeichnung des Themas, darf nicht leer sein
					* Beschreibung des Themas, kann leer sein (NULL bzw. leerer String)
   Sortierung:	Nach Name des Themas
   ************************************************************************************************/
  function getTopics($uid) {
	$alle = [];
	$db = getValue('cfg_db');
	$topics = $db->query("SELECT tid, uid, name, description FROM topic WHERE uid=$uid ORDER BY name");
	while ($topic = $topics->fetchArray()) {
	  $alle[] = $topic;
	}
	return $alle;
  }

  /************************************************************************************************
   getTopic: Liefert ein bestimmtes Thema zurück (z.B. zum Editieren)
   $tid:	 Topic-ID des gewünschten Themas
   Rückgabe:   1-dimensionales Array (Attribute des Themas)
					* Topic-ID
					* User-ID
					* Name bzw. Bezeichnung des Themas
					* Beschreibung des Themas
   ************************************************************************************************/
  function getTopic($tid) {
	$db = getValue('cfg_db');
	$result = $db->query("SELECT tid, uid, name, description FROM topic WHERE tid=$tid");
	if ($topic = $result->fetchArray()) {
	  return $topic;
	} else return "";
  }

  /************************************************************************************************
   addTopic:	 Schreibt ein neues Thema in die Datenbank
   $uid:		 User-ID - Jedes Thema muss einem Benutzer/Blog zugeordnet werden
   $name:		 Der Name bzw. die Bezeichnung des Themas
   $description: Die Beschreibung des Themas
   Rückgabe:	 - True bei Erfolg
				 - False bei Fehler
   ************************************************************************************************/
  function addTopic($uid, $name, $description) {
	$db = getValue('cfg_db');
	$name = SQLite3::escapeString($name);
	$description = SQLite3::escapeString($description);
	$sql = "INSERT INTO topic (uid, name, description) values ($uid, '$name', '$description')";
	return $db->exec($sql);
  }

  /************************************************************************************************
   updateTopic:	 Schreibt Änderungen eines bestehenden Themas in die DB
   $tid:		 Topic-ID des zu ändernden Themas
   $name:		 Der Name bzw. die Bezeichnung des Themas
   $description: Die Beschreibung des Themas
   Rückgabe:	 - True bei Erfolg
				 - False bei Fehler
   ************************************************************************************************/
  function updateTopic($tid, $name, $description) {
	$db = getValue('cfg_db');
	// Zuerst wird mit einem SELECT sichergestellt, dass der Datensatz existiert, denn das
	// UPDATE-Statement liefert auch TRUE zurück, wenn die Topic-ID nicht vorhanden ist
	$result = $db->query("SELECT * FROM topic WHERE tid=$tid");
	if ($topic = $result->fetchArray()) {
	  $title = SQLite3::escapeString($title);
	  $content = SQLite3::escapeString($content);
	  $sql = "UPDATE topic set name='$name', description='$description' WHERE tid=$tid";
	  return $db->exec($sql);
	} return false;
  }

  /************************************************************************************************
   deleteEntry:	Löscht einen bestimmten Blog-Beitrag aus der Datenbank
   $eid:		Entry-ID des zu löschenden Beitrags
   Rückgabe:	- True bei Erfolg
				- False bei Fehler
   ************************************************************************************************/
  function deleteTopic($tid) {
	$db = getValue('cfg_db');
	// Zuerst wird mit einem SELECT sichergestellt, dass der Datensatz existiert, denn das
	// DELETE-Statement liefert auch TRUE zurück, wenn die Topic-ID nicht vorhanden ist
	$result = $db->query("SELECT * FROM topic WHERE tid=$tid");
	if ($topic = $result->fetchArray()) {
	  $sql = "DELETE FROM topic WHERE tid=$tid";
	  return $db->exec($sql);
	} false;
  }
?>
