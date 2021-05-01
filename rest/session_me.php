<?php 

require '../src/Env.php';
require '../src/Sessions.php';
require '../src/Connect.php';
//$sess = new Sessions();

$env = new Env();

$database = Sessions::set_db($env);
$data = Sessions::read_session_db($_POST['sessionid'], $database);

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

	if ($data->rowCount()>=0) {
		echo ('{"status":"success", "msg": "Login Successful!"}');
	}
} else {
	echo ('{"status":"error", "msg": "Incoorect Pin"}');
}

?>