<?php
require("./conf.php");

//Ouverture de base
try {
        $db_handle = new PDO('sqlite:'.$db_path);
        $db_handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
        die('Erreur1 : '.$e->getMessage());
}


if (isset($_GET["sid"])) {
    $session_id = $_GET['sid'];
}
else {
    exit;
}

// Get data for session
$sql = $db_handle->prepare("SELECT * FROM $db_table WHERE session = :sid ORDER BY time DESC;");
$sql->execute(array(':sid'=>$session_id));
$dbfields = array_reduce(
        $db_handle->query("PRAGMA table_info('$db_table');")->fetchAll(),
        function($rV,$cV) {$rV[]=$cV['name']; return $rV; },
        array()
);
$output="";
// Get The Field Name
for ($i = 0; $i < count($dbfields); $i++) {
    $heading = $dbfields[$i];
    $output .= '"'.$heading.'",';
}
$output .="\n";

while($row = $sql->fetch(PDO::FETCH_ASSOC)){
	$output .='"'.implode('","',array_values($row))."\"\n";
}

//Download the file
$csvfilename = "torque_session_".$session_id.".csv";
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$csvfilename);

echo $output;
exit;

?>
