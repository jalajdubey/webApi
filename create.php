<?php

/**
* Author : https://www.roytuts.com
*/

require_once 'db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

/*if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// get posted data
	$data = json_decode(file_get_contents("php://input", true));
	
	$sql = "INSERT INTO company(name) VALUES('" . mysqli_real_escape_string($dbConn, $data->name) . "')";
	dbQuery($sql);
}*/

//End of file
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// get posted data
	$data = json_decode(file_get_contents("php://input", true));
if(!empty($data->name)){

$duplicate=mysqli_query($dbConn,"select * from company where name='$data->name'");
	if (mysqli_num_rows($duplicate)>0)
	{
		echo json_encode(array("statusCode"=>201));
	}
	else{
		$sql = "INSERT INTO company(name) VALUES('" . mysqli_real_escape_string($dbConn, trim($data->name)) . "')";
		if (dbQuery($sql)) {
			echo json_encode(array("statusCode"=>200));
		} 
		else {
			echo json_encode(array("statusCode"=>201));
		}
	}
}else{
    echo json_encode(array("statusCode"=>202));
}
}



?>