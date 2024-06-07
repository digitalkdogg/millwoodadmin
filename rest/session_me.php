<?php 

require '../src/Env.php';
require '../src/Sessions.php';
require '../src/Connect.php';
$sess = new Sessions();
if (isset($_POST['pin'])) {
	$env = new Env();
	$database= $sess->set_db($env);
	$data = $sess->read_session_db($_POST['sessionid'], $database);

	//$database = Sessions::set_db($env);
	//$data = Sessions::read_session_db($_POST['sessionid'], $database);

	//if (trim($_POST['pin']) == trim(cipher::decrypt($env->attr['pin']))) {
	if (trim($_POST['pin']) == '0922') {
		$now = new DateTime();
		$expire = new DateTime();
		$expire = $expire->add(new DateInterval('P1D'));
		
		$data = $database->update('wp_sessions', [
				'last_updated' => $now->format('Y-m-d H:m:s'),
				'expire_date' => $expire->format('Y-m-d H:m:s'),
				'secure' => "true"
			], [
				'session_id' => $_POST['sessionid']
			]);
		
		header('Content-Type: application/json; charset=utf-8');

		if ($data->rowCount()>=0) {
			$return = [];
			$return['status'] = 'success';
			$return['msg'] = 'Login Successful!';
			echo json_encode($return);
		//echo ('{"status":"success", "msg": "Login Successful!"}');
		}
	} else {
		$return = [];
		$return['status'] = 'error';
		$return['msg'] = 'Incorrect Pin';
		echo json_encode($return);
	//	echo ('{"status":"error", "msg": "Incoorect Pin"}');
	}
}

?>