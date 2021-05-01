<?php
	require '../src/Env.php';
	require '../src/Sessions.php';
	require '../src/Connect.php';

	$input = $_POST['data'];

	$env = new Env();

	$database = Sessions::set_db($env);
	$session = Sessions::read_session_db($_POST['sessionid'], $database);
	if (sizeof($session)>0) {
		$return = array('rowcount'=>'0',  'cc_id'=>$input['campaign_id']);
		$olddata = $database->select('wp_campaigns', [
			'cc_id'
		], [
			'cc_id' => $input['campaign_id']
		]);

		if (sizeof($olddata)==0) {
			try {
				$data = $database->insert('wp_campaigns', [
		    		'cc_id' => $input['campaign_id'],
		    		'title' => $input['name'],
		    		'status' => $input['current_status'],
		    		'created_at' => $input['created_at'],
		    		'acc_id' => $input['campaign_activity_id'],
		    		'permalink_url' => $input['permalink_url'],
		    		'last_updated' => $input['updated_at']
		    	]);

		    	$return = array('rowcount'=>$data->rowCount(), 'cc_id'=>$input['campaign_id']);
		    } catch (exception $e) {echo $e;}
		 } else {
		 	$data = $database->update('wp_campaigns', [
		    		'title' => $input['name'],
		    		'status' => $input['current_status'],
		    		'created_at' => $input['created_at'],
		    		'acc_id' => $input['campaign_activity_id'],
		    		'permalink_url' => $input['permalink_url'],
		    		'last_updated' => $input['updated_at']
		    	], ['cc_id'=>$input['campaign_id']]);
		 	$return = array('rowcount'=>0, 'cc_id'=>$input['campaign_id']);
		 } 
	}

	echo(json_encode($return));
//	echo (json_encode($return, JSON_FORCE_OBJECT));

?>