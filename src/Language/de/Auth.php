<?php

return [

  //
  // _Global
  //
  'current_user' => 'Aktueller Benutzer',
  'description' => 'Beschreibung',
  'for' => 'f&uuml;r',
  'getHelpForPage' => 'Hilfe f&uuml;r diese Seite...',
  'name' => 'Name',
  'no_login' => 'Niemand ist eingeloggt.',
  'no_selection' => 'Eine Auswahl in dieser Listbox hat keinen Effekt. Sie wird hier nur zu Informationswzwecken eingesetzt.',
  'user_id' => 'Konto ID',

  //
  // 2FA
  //
  '2fa' => [
    'setup' => [
      '2fa_optional' => 'Diese Applikation erlaubt das Einrichten einer Zwei-Faktor-Authentifizierung.',
      '2fa_required' => 'Diese Applikation erfordert das Einrichten einer Zwei-Faktor-Authentifizierung.',
      'authenticator_code' => 'Authenticator Code (sechstellige Nummer)',
      'authenticator_code_desc' => 'F&uuml;ge einen Eintrag in deiner Authenticator App hinzu indem du den oben angezeigten Barcode einscanst oder den Schl&uuml;ssel manuell eingibst. Nach Einrichten des neuen Eintrags gib den n&auml;chsten generierten Code in das Feld unten ein und klicke auf "Verifizieren".',
      'authenticator_code_missing' => 'Bitte gebe einen Authenticator Code zur Verifizierung ein.',
      'header' => 'Zwei-Faktor-Authentifizierung Einrichten',
      'mismatch' => 'Der angegebene Code stimmt nich &uuml;berein. Bitte versuche es nochmal.',
      'onboarding_comment' => 'Hier kannst du den Onboarding Prozess durchf&uuml;hren. Dazu brauchts du ein mobiles Ger&auml;t und eine Authenticator App wie Google Authenticator oder Microsoft Authenticator.',
      'secret_exists' => 'Du hast deine 2FA schon eingerichtet. Wenn du es erneut tust, wird dein alter Schl&uuml;ssel &uuml;berschrieben. Authenticator App Eintr&auml;ge, die auf dem alten Schl&uuml;ssel basieren, werden dann nicht mehr funktionieren.',
      'success' => 'Deine 2FA Einrichtung wurde erfolgreich abgeschlossen.',
    ],
    'login' => [
      'header' => '2FA Login',
      'pin' => 'Bitte gib die PIN von deiner Authenticator App ein.',
      'pin_login' => 'PIN Login',
      'no_2fa_in_progress' => 'Es ist kein 2FA Login ausstehend.'
    ],
  ],

  //
  // About
  //
  'about' => [
    'about' => '&Uuml;ber',
    'copyright' => 'Copyright',
    'credits' => 'Credits',
    'documentation' => 'Dokumentation',
    'release_info' => 'Release Information',
    'support' => 'Support',
    'version' => 'Version',
  ],

  //
  // Alerts
  //
  'alert' => [
    'error' => 'Fehler',
    'information' => 'Information',
    'no_change' => 'Es wurd nichts ge&auml;ndert.',
    'warning' => 'Warnung',
  ],

  //
  // Account
  //
  'account' => [
    '2fa' => '2FA konfiguriert',
    'active' => 'Konto aktiv',
    'banned' => 'Konto gebannt',
    'inactive' => 'Konto nicht aktiv',
  ],

  //
  // Activation
  //
  'activation' => [
    'no_user' => 'Es konnte kein Benutzer mit diesem Aktivierungscode gefunden werden.',
    'resend' => 'Aktivierungs-Mail nocheinmal schicken.',
    'subject' => 'Benutzerkonto aktivieren',
    'success' => 'Bitte best&auml;tige dein Benutzerkonto mit Klick auf den Aktivierungsklink der per Email an dich verschickt wurde.',
    'error_sending' => 'Fehler beim Senden der Aktivierungsmail an: {0}',
    'not_activated' => 'Dieses Benutzerkonto ist noch nicht aktiviert.',
  ],

  //
  // Buttons
  //
  'btn' => [
    'action' => 'Aktion',
    'cancel' => 'Abbrechen',
    'create' => 'Anlegen',
    'createGroup' => 'Gruppe Anlegen',
    'createPermission' => 'Berechtigung Anlegen',
    'createRole' => 'Rolle Anlegen',
    'createUser' => 'Benutzer Anlegen',
    'delete' => 'L&ouml;schen',
    'edit' => 'Bearbeiten',
    'editGroup' => 'Gruppe Bearbeiten',
    'editPermission' => 'Berechtigung Bearbeiten',
    'editRole' => 'Rolle Bearbeiten',
    'editUser' => 'Benutzer Bearbeiten',
    'remove_secret' => '2FA Schl&uuml;ssel Entfernen',
    'reset' => 'Zur&uuml;cksetzen',
    'search' => 'Suchen',
    'submit' => 'Abschicken',
    'verify' => 'Verifizieren',
    'view' => 'Ansicht',
  ],

  //
  // Exceptions
  //
  'exception' => [
    'invalid_fields' => 'Das "{0}" Feld kann nicht f&uuml;r die Validierung beim Anmelden benutzt werden.',
    'invalid_model' => 'Das {0} Modell muss vor seiner Nutzung geladen werden.',
    'no_user_entity' => 'Das Benutzerobjekt muss f&uuml;r die Passwortvalidierung angegeben werden.',
    'not_logged_in' => 'Diese Seite kann ohne Anmeldung nicht aufgerufen werden.',
    'insufficient_permissions' => 'Der Zugriff auf diese Seite erfordert eine spezielle Berechtigung.',
    'too_many_credentials' => 'Neben dem Passwort kann nur ein weiterer Anmeldewert validiert werden.',
    'unknown_error' => 'Leider ist ein Feheler beim Versenden der Email aufgetreten. Bitte versuche es sp&auml;ter noch einmal.',
    'password_length_not_set' => 'Der Wert `minimumPasswordLength` muss in der CI4-Auth Config Datei gesetzt sein.',
    'user_not_found' => 'Es kann kein Benutzerkonto mit der ID = {0, number} gefunden werden.',
  ],

  //
  // Forgot Password
  //
  'forgot' => [
    'error_email' => 'Die Email zum Zur&uuml;cksetzen des Passworts konnte nicht an {0} versendet werden.',
    'error_reset' => 'Die Email zum Zur&uuml;cksetzen konnte nicht an {0} versendet werden.',
    'disabled' => 'Die Funktion zum Zur&uum;cksetzen des Passworts ist nicht aktiviert.',
    'no_user' => 'Ein Benutzerkonto mit dieser Email Addresse konnte nicht gefunden werden.',
    'subject' => 'Anweisungen zum Zur&uuml;cksetzen des Passworts',
    'email_sent' => 'Ein Sicheheitscode wurde per Email an dich verschickt. Gib ihn im u.a. Feld ein.',
    'reset_success' => 'Dein Passwort wurde erfolgreich ge&auml;ndert. Bitte logge dich micht dem neuen Passwort ein.',
  ],

  //
  // Group
  //
  'group' => [
    'create_success' => 'Neue Gruppe anagelegt: {0}',
    'delete_confirm' => 'Bist du sicher, dass du diese Gruppe l&ouml;schen willst?',
    'delete_success' => 'Gruppe gel&ouml;scht: {0}',
    'description' => 'Gruppenbeschreibung',
    'description_desc' => 'Gib eine Beschreibung f&uuml;r diese Gruppe ein.',
    'name' => 'Gruppenname',
    'name_desc' => 'Gib einen Namen f&uuml;r diese Gruppe ein.',
    'none_found' => 'Keine Gruppen gefunden.',
    'not_found' => 'Die Gruppe konnte nicht gefunden werden: {0}.',
    'not_unique' => 'Der Gruppenname "{0}" existiert bereits. Gruppennamen m&uuml;ssen eindeutig sein.',
    'permissions' => 'Gruppenrechte',
    'permissions_desc' => 'W&auml;hle eine oder mehrere Berechtigungen aus, die dieser Gruppe erlaubt sind.',
    'update_success' => 'Gruppe "{0}" wurde erfolgreich aktualisiert.',
    'group' => 'Gruppe',
    'groups' => 'Gruppen',
  ],

  //
  // Login
  //
  'login' => [
    'already_registered' => 'Bist du schon registriert?',
    'bad_attempt' => 'Login fehlgeschlagen. Bitte &uuml;berpr&uuml;fe deine Eingaben.',
    'current' => 'Aktuell',
    'home' => 'Home',
    'enter_email_instructions' => 'Kein Problem! Gib deine Email Adresse ein und wir schicken die Anweisungen zum Zur&uuml;cksetzen des Passworts.',
    'email' => 'Email',
    'email_address' => 'Email Adresse',
    'email_or_username' => 'Email oder Benutzername',
    'enter_code_email_password' => 'Gib den Code aus der Email ein, deine Email Adresse und dein neues Passwort.',
    'forgot_password' => 'Passwort vergessen?',
    'forgot_your_password' => 'Passwort vergessen?',
    'invalid_password' => 'Login fehlgeschlagen. Bitte &uuml;berpr&uuml;fe dein Passwort.',
    'action' => 'Login',
    'title' => 'Login',
    'success' => 'Willkommen zur&uuml;ck!',
    'need_an_account' => 'Brauchst du ein Benutzerkonto?',
    'new_password' => 'Neues Passwort',
    'new_password_repeat' => 'Wiederhole das neue Passwort',
    'password' => 'Passwort',
    'remember_me' => 'An mich erinnern',
    'register' => 'Registrieren',
    'repeat_password' => 'Passwort wiederholen',
    'reset_password' => 'Passwort zur&uuml;cksetzen',
    'reset_your_password' => 'Passwort zur&uuml;cksetzen',
    'send_instructions' => 'Anweisungens schicken',
    'sign_in' => 'Einloggen',
    'token' => 'Token',
    'too_many_requests' => 'Zu viele Anfragen. Bitte warte {0, number} Sekunden.',
    'username' => 'Benutzername',
    'we_never_share' => 'Wir geben die Email Adresse an niemanden weiter.',
  ],

  //
  // Modal Dialogs
  //
  'modal' => [
    'confirm' => 'Bitte Best&auml;tigen',
  ],

  //
  // Navbar
  //
  'nav' => [
    'home' => 'Home',
    'authorization' => [
      'self' => 'Authorisierung',
      'groups' => 'Gruppen',
      'permissions' => 'Berechtigungen',
      'roles' => 'Rollen',
      'users' => 'Benutzer',
    ],
    'authentication' => [
      'self' => 'Authentifizierung',
      'login' => 'Login',
      'logout' => 'Logout',
      'forgot_password' => 'Password vergessen',
      'reset_password' => 'Password zur&uuml;cksetzen',
      'register' => 'Registrieren',
      'setup2fa' => '2FA Einrichten',
      'whoami' => 'Wer bin ich?',
    ],
    'settings' => 'Einstellungen',
  ],

  //
  // Password
  //
  'password' => [
    'error_common' => 'Das Passwort darf kein gebr&auml;chlicher Begriff sein.',
    'error_empty' => 'Ein Passwort ist erforderlich.',
    'error_length' => 'Das Passwort muss mindestens {0, number} Zeichen lang sein.',
    'error_personal' => 'Das Passwort darf keine pers&ouml;nlichen Information enthalten.',
    'error_pwned' => 'Das Passwort {0} wurde geknackt und erscheint {1, number} mal in {2} kompromittierten Passw&ouml;rtern.',
    'error_pwned_database' => 'eine Datenbank',
    'error_pwned_databases' => 'Datenbanken',
    'error_similar' => 'Das Passwort ist dem Benutzernamen zu &auml;hnlich.',
    'change_success' => 'Das Passwort wurde erfolgreich ge&auml;ndert.',
    'reset_token_expired' => 'Der Token zum Zur&uuml;cksetzen ist leider abgelaufen.',
    'suggest_length' => 'Passwortphrasen - bis zu 255 Zeichen lang - sind sicherer und einfacher zu merken.',
    'suggest_common' => 'Das Passwort wurde gegen &uuml;ber 65T gebr&auml;chlichen oder gehackten Passw&ouml;rtern gecheckt.',
    'suggest_personal' => 'Variationen der Email Adresse oder des Benutzernamens sollten nicht als Passwort benutzt werden.',
    'suggest_similar' => 'Benutze keine Teile deines Benutzernamens im Passwort.',
    'suggest_pwned' => '{0} sollte nie als Passwort benutzt werden. Wenn du es anderswo auch benutzt, &auml;ndere es umgehend.',
    'user_not_exist' => 'Das Passwort wurde nicht ge&auml;ndert. Der Benutzer existiert nicht.',
  ],

  //
  // Permission
  //
  'permission' => [
    'create_success' => 'Neue Berechtigung anagelegt: {0}',
    'delete_confirm' => 'Bist du sicher, dass du diese Berechtigung l&ouml;schen willst?',
    'delete_success' => 'Berechtigung gel&ouml;scht: {0}',
    'description' => 'Berechtigungsbechreibung',
    'description_desc' => 'Gib eine Beschreibung f&uuml;r diese Berechtigung ein.',
    'name' => 'Berechtigungsname',
    'name_desc' => 'Gib einen Namen f&uuml;r diese Berechtigung ein.',
    'none_found' => 'Keine Berechtigungen gefunden.',
    'not_found' => 'Die Berechtigung wurde nicht gefunden: {0}.',
    'not_unique' => 'Der Berechtigungsname "{0}" already exists. Berechtigungsnamen m&uuml;ssen eindeutig sein.',
    'update_success' => 'Berechtigung "{0}" wurde erfolgreich aktualisiert.',
    'permission' => 'Berechtigung',
    'permissions' => 'Berechtigungen',
    'perm_groups' => 'Berechtigungs-Gruppen',
    'perm_groups_desc' => 'Diese Gruppen haben diese Berechtigung.',
    'perm_roles' => 'Berechtigung-Rollen',
    'perm_roles_desc' => 'Diese Rollen haben diese Berechtigung.',
    'perm_users' => 'Berechtigungs-Nutzer',
    'perm_users_desc' => 'Diese Nutzer haben diese Berechtigung als individuelle Berechtigung (nicht &uuml;ber Gruppe oder Rolle).',
    'tab_details' => 'Details',
    'tab_usage' => 'Nutzung',
  ],

  //
  // Register
  //
  'register' => [
    'create_success' => 'Neuer Benutzer anegelgt: {0}, #{1}',
    'disabled' => 'Leider sind neue Benutzerkonten zurzeit nicht erlaubt.',
    'success' => 'Willkommen an Bord! Du kannst dich nun einloggen.',
  ],

  //
  // Role
  //
  'role' => [
    'create_success' => 'Neue Rolle anegelegt: {0}',
    'delete_confirm' => 'Bist du sicher, dass du diese Rolle l&ouml;schen willst?',
    'delete_success' => 'Rolle gel&ouml;scht: {0}',
    'description' => 'Rollenbschreibung',
    'description_desc' => 'Gib eine Beschreibung f&uuml;r diese Rolle ein.',
    'name' => 'Rollenname',
    'name_desc' => 'Gib einen Namen f&uuml;r diese Rolle ein.',
    'none_found' => 'Keine Rollen gefunden.',
    'not_found' => 'Die Rolle wurde nicht gefunden: {0}.',
    'not_unique' => 'Der Rollenname "{0}" existiert bereits. Rollennamen m&uuml;ssen eindeutig sein.',
    'permissions' => 'Rollenberechtigungen',
    'permissions_desc' => 'W&auml;hle eine oder mehrere Berechtigungen aus, die dieser Rolle erlaubt sind.',
    'update_success' => 'Rolle "{0}" wurde erfolgreich aktualisiert.',
    'role' => 'Rolle',
    'roles' => 'Rollen',
  ],

  //
  // User
  //
  'user' => [
    'active' => 'Aktiv',
    'active_desc' => 'Aktiviere oder deaktiviere diesen Nutzer.',
    'banned' => 'Gebannt',
    'banned_desc' => 'Banne oder unbanne diesen Nutzer.',
    'create_success' => 'Neuer Benutzer anegelegt: {0} ({1})',
    'delete_confirm' => 'Bist du sicher, dass du diesen Benutzer l&ouml;schen willst?',
    'delete_success' => 'Benutzer gel&ouml;scht: {0}',
    'displayname' => 'Anzeigename',
    'displayname_desc' => 'Gib einen Anzeigenamen f&uuml;r diesen Benuzter ein.',
    'email' => 'Email',
    'email_desc' => 'Gib eine g&uuml;tige Email Adresse ein.',
    'firstname' => 'Vorname',
    'firstname_desc' => 'Gib den Vornamen des Benutzers ein.',
    'fullname' => 'Voller Name',
    'groups' => 'Benutzergruppen',
    'groups_desc' => 'W&auml;hle eine oder meherere Gruppen f&uuml;r diesen Benutzer aus.',
    'is_banned' => 'Der Benuzter ist geblockt. Bitte kontaktiere den Administrator.',
    'lastname' => 'Nachname',
    'lastname_desc' => 'Gib den Nachnamen des Benutzers ein.',
    'none_found' => 'Keine Benutzer gefunden.',
    'not_found' => 'Der Benutzer wurde nicht gefunden: {0}.',
    'password' => 'Passwort',
    'password_desc' => 'Gib ein neues Passwort ein. Wenn das aktuelle Passwort nicht ge&auml;ndert werden soll, lasse dieses Feld leer.',
    'pass_confirm' => 'Passwort wiederholen',
    'pass_confirm_desc' => 'Wiederhole das neue Passwort. Wenn das aktuelle Passwort nicht ge&auml;ndert werden soll, lasse dieses Feld leer.',
    'pass_resetmail' => 'Password E-Mail',
    'pass_resetmail_desc' => 'Wenn diese Option aktiviert is, wird dem Nutzer eine Password Reset E-Mail geschickt.',
    'permissions' => 'Pers&ouml;nliche Berechtigungen',
    'permissions_desc' => 'W&auml;hle eine oder meherere pers&ouml;nliche Berechtigungen f&uuml;r diesen Benutzer aus. Diese Berechtigungen hat der Benutzer zus&auml;tzlich zu den von Gruppen und Rollen.',
    'permissions_all' => 'Alle Berechtigungen',
    'permissions_all_desc' => 'Dies ist die Liste aller aktuellen Berechtigungen des Benuzters. Enthalten sind pers&ouml;nliche, Gruppen- und Rollenberechtigungen.',
    'remove_secret_confirm' => '2FA Schl&uuml;ssel Entfernen',
    'remove_secret_confirm_desc' => 'Bist du sicher, dass du den 2FA Schl&uuml;ssel f&uuml;r diesen Benutzer entfernen willst? Der Benutzer kann einen neuen anlegen, indem er den 2FA Einrichten Prozess neu durchf&uuml;hrt.',
    'remove_secret_success' => '2FA Schl&uuml;ssel des Benutzers gel&ouml;scht: {0} ({1})',
    'roles' => 'Benutzerrollen',
    'roles_desc' => 'W&auml;hle eine oder meherere Rollen f&uuml;r diesen Benutzer aus.',
    'status' => 'Status',
    'tab_account' => 'Konto',
    'tab_groups' => 'Gruppen',
    'tab_permissions' => 'Berechtigungen',
    'tab_roles' => 'Rollen',
    'update_success' => 'Benutzer "{0} ({1})" wured aktualisiert.',
    'user' => 'Benutzer',
    'users' => 'Benuzter',
    'username' => 'Benutzername',
    'username_desc' => 'Gib einen Benutzernamen ein.',
  ],
];
