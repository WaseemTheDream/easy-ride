<?php
require_once 'functions/functions.php';
require_once 'functions/database.php';
if ($_POST) {
    $data = json_decode($_POST['data'], true);
  	// Required Values
    $spots = $data['spots'];
    $Trip_length = $data['route']['trip_length']; 
    $departure = $data['departure'];
	$origin_id = $data['route']['from']['address'];
    $destination_id = $data['route']['to']['address'];

    // Non Required Values
    $message = $data['message'];
    $women_only = $data['women_only'];

    // Generated Values
    // Origin
    $latFrom = $data['route']['from']['lat'];
    $lonFrom = $data['route']['from']['lon'];
    // Destination 
    $latTo = $data['route']['to']['lat'];
    $lonTo = $data['route']['to']['lon'];

    $tripData = array();
    $requiredVals = array(
			        $spots => 'spots',
			        $Trip_length => 'length',
			        $departure => 'departure',
			        $origin_id => 'origin_id',
			       	$destination_id  =>'destination_id'
			       	);

    // Make sure all required fields are defined
    $missing_fields = array();
    foreach ($requiredVals as $post_key => $db_key)
     {
		    if ($post_key == "") {
		            $missing_fields[] = $db_key;
		    } 
		    else {
		        $tripData[$db_key] = sanitize_string($post_key);
		    }
    } // End of the foreach Statement 

    // Now Add to the array non Required Values
    $tripData['message'] = sanitize_string($message);
    $tripData['women_only'] = sanitize_string($women_only);

    // AddressFrom Array

    $AddressFrom = array(
    				'address' => $origin_id,
    				'lat' => $latFrom,
    				'lon' => $lonFrom
    				);
    $AddressTo = array (
    				'address' => $destination_id,
    				'lat' => $latTo,
    				'lon' => $lonTo
    				);

    // Check for missing Fields

    if($missing_fields)
    {
        $status = 'Error!';
        $msg = 'Missing fields: ' . implode(', ', $missing_fields);
    } 
    else {
	    add_trip($tripData);
	    add_place($AddressFrom);
	    add_place($AddressTo);
	    $status= 'OK';
	    $msg = 'Trip Saved!';
	} 

	json_respond($status, $msg);
}

?>