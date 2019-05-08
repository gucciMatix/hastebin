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

	function generateCode() {
		global $db;
		global $table;
		$length = 5;
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }

	    $q = $db->prepare("SELECT * FROM $table WHERE code = ?");
	    $q->bindValue(1, $randomString);
	    $q->execute();
	    $fetched = $q->fetch();

	    if (!$fetched == null) {
	    	$randomString = generateCode();
	    }
	    return $randomString;
	}

	$content = $_POST['content'];
	$lang = $_POST['lang'];
	$code = generateCode();
	if (isset($content) && isset($lang) && !empty($content) && !empty($lang)) {
		$q = $db->prepare("INSERT INTO $table (code, content, lang) VALUES (?, ?, ?)");
		$q->bindValue(1, $code);
		$q->bindValue(2, $content);
		$q->bindValue(3, $lang);
		$q->execute();
		exit($base_url."/".$code);
	} else {
		exit("API Failed.");
	}
?>