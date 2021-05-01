<?php 
//return
	require "../src/Ccurl.php";
	require "../src/Connect.php";
	require "../src/Sessions.php";

	$access_token = $_POST['access_token'];
	sessions::start();

	$curl_data = array(
		'url' => $_POST['base_url'] . 'emails?limit=20',
		'maxredirs'=>'10',
		'timeout' => '30',
		'httpheader' => array ('Accept: application/json', 'Content-type: application/json', 'Authorization: Bearer '. $access_token)
	);


	$curl = new Ccurl($curl_data);
			
	$campaigns = $curl->exec_curl();

	//$connobj= new Connect();
	//$current_campaigns = $connobj->select('SELECT * FROM millwood_wp.wp_campaigns');

//	 array_push($current_campaigns, array('ccdata'=>'$campaigns'));

	 return json_encode($campaigns);