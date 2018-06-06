<?php
  define("DBNAME", "db/blog.db");
  define("EXCHANGE_PATH", "/exchange/");
  define("IMPORT_FILE", "import.csv");
  define("EXPORT_FILE", "export.csv");
  // Akzeptierte Funktionen Login
  setValue("cfg_func_login", array("login","blogs","entries_login"));
  // Akzeptierte Funktionen Memberbereich
  setValue("cfg_func_member", array("entries_member","entry_add","entry_edit","logout"));
  // Inhalt des Login-Menus
  setValue("cfg_menu_login", array("login"=>"Login","blogs"=>"Blog wählen","entries_login"=>"Beiträge anzeigen","import"=>"Benutzer importieren","export"=>"Benutzer exportieren"));
  // Inhalt des Menus im Memberbereich
  setValue("cfg_menu_member", array("entries_member"=>"Beiträge anzeigen","entry_add"=>"Beitrag hinzufügen","entry_edit"=>"Beitrag ändern","logout"=>"Logout"));
//  setValue("cfg_menu_disabled", array("entries_login","entry_edit"));
  setValue("cfg_menu_disabled", array("entries_login"));
  // Datenbankverbindung herstellen
  if (!file_exists(DBNAME)) exit("Die Datenbank 'blog.db' konnte nicht gefunden werden!");
  $db = new SQLite3(DBNAME);
  setValue("cfg_db", $db);
?>