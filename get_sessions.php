<?php
require("./conf.php");

//Ouverture de base
try {
        $db_handle = new PDO('sqlite:'.$db_path);
        $db_handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
        die('Erreur1 : '.$e->getMessage());
}


// Get list of unique session IDs
$stmt = $db_handle->prepare("SELECT COUNT(*) as 'Session Size', session
                      FROM $db_table
                      GROUP BY session
                      ORDER BY time DESC");
	$stmt->execute();

// Create an array mapping session IDs to date strings $seshdates = array(); $sids=array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$session_size = $row["Session Size"];
	// Drop sessions smaller than 60 data points
	if ($session_size >= 60) {
		$sid = $row["session"];
		$sids[] = $sid;
		$seshdates[$sid] = date("F d, Y  h:ia", intval(substr($sid, 0, -3)));
	}
}

?>
