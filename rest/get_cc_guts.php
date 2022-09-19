<?php 

require "../src/Ccurl.php";
require "../src/Env.php";

$env = new Env();



$curl_data = array(
		//'url' => $env->attr['base_url'] . 'emails/'.$_POST['cc_id'],
		'url' => 'https://api.cc.email/v3/emails/' . $_POST['cc_id'],
		'maxredirs'=>'10',
		'timeout' => '30',
		'httpheader' => array ('Accept: application/json', 'Content-type: application/json', 'Authorization: Bearer '. $_POST['access_token'])
	);


	$curl = new Ccurl($curl_data);
			
	$campaign_data =$curl->exec_curl();

	 return json_encode($campaign_data);
	//echo ('{"data":'.$campaign_data.'}');

?>