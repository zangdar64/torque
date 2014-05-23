<?php
require("./conf.php");

//Ouverture de base
try {
        $db_handle = new PDO('sqlite:'.$_SERVER['DOCUMENT_ROOT'].'/'.$db_path);
        $db_handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
        die('Erreur1 : '.$e->getMessage());
}

//Table info
$dbfields = array_reduce(
	$db_handle->query("PRAGMA table_info('$db_table');")->fetchAll(),
	function($rV,$cV) {$rV[]=$cV['name']; return $rV; },
	array()
);
// Iterate over all the k* _GET arguments to check that a field exists
foreach ($_GET as $key => $value) {
	// Keep columns starting with k
	if (preg_match("/^k/", $key)) {
		$keys[] = $key;
		$values[] = $value;
		$submitval = 1;
	}
 	// Skip columns matching userUnit*, defaultUnit*, and profile*
 	else if (preg_match("/^userUnit/", $key) or preg_match("/^defaultUnit/", $key) or (preg_match("/^profile/", $key) and (!preg_match("/^profileName/", $key)))) {
 		$submitval = 0;
 	}
 	// Keep anything else
 	else {
 		$keys[] = $key;
 		$values[] = "'".$value."'";
 		$submitval = 1;
 	}
 	// If the field doesn't already exist, add it to the database
 	if (!in_array($key, $dbfields) and $submitval == 1) {
 		$db_handle->query("ALTER TABLE $db_table ADD $key TEXT default 'null';");
	}
}

// Now insert the data for all the fields
$sql = "INSERT INTO $db_table (".join(",", $keys).") VALUES (".join(",", $values).")";
$db_handle->query($sql);

echo 'OK';
?>
