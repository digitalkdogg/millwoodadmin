<?php 
require '../src/Env.php';
require '../src/Sessions.php';

$env = new Env();

$database = Sessions::set_db($env);
$data = Sessions::read_session_db($_POST['sessionid'], $database);

if (sizeof($data) >0 ) {
	foreach ($data as $session) {
		if ($session['secure']=="true") {
			$campaigns = $database->select('wp_campaigns', [
    			'id',
    			'cc_id',
    			'title',
    			'status',
    			'created_at',
    			'permalink_url',
    			'acc_id',
    			'last_updated'
			]);
		}
	} 

	echo (json_encode($campaigns));
}
?>