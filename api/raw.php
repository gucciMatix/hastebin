<?php
	include_once('../configuration/config.php');

	try { 
		$db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password); 
	
		if (!$db->query("DESCRIBE $table")) {
			$db->query("CREATE TABLE $table ( `id` INT NOT NULL AUTO_INCREMENT , `code` VARCHAR(5) NOT NULL , `content` TEXT NOT NULL , `lang` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
			$db->query("INSERT INTO $table (code, content, lang) VALUES ('about', '#mini hastebin remake - by matix\nhello...', 'yaml')");
		}
	} catch (PDOException $e) { 
		exit("API Failed."); 
	}

	$code = $_GET['code'];
	$q = $db->prepare("SELECT * FROM $table WHERE code = '$code'");
	$q->execute();
	$f = $q->fetch();
	if ($f != null) {
		echo htmlspecialchars($f['content']);
	} else {
		exit("API Failed.");
	}
?>