<?php
use Connect\Connect;
class Sessions {
	public $session_id;
	public $last_hit='test';
	private $sess;
	private $env;
	private $secure = 'false';

	function __construct () {
		//$sess = $this->start();

		if (isset($_GET["debug"])) {
			if ($_GET["debug"]=="true") {
				error_reporting(E_ALL);
				ini_set('display_errors', 1);
			}
		}
		
		$env = new Env();

		$now = new DateTime();
		$expire = new DateTime();
		$expire = $expire->add(new DateInterval('P1D'));

		if (isset($_SERVER['REMOTE_ADDR'])) {
			if ($_SERVER['REMOTE_ADDR'] != '::1') {
				$sess =  str_replace('.', '||', $_SERVER['REMOTE_ADDR']) . '||||||' . str_replace('-', '', $now->format('Y-m-d'));
			} else {
				$sess = 'localhost' . '||||||' . str_replace('-', '', $now->format('Y-m-d'));
			}
		}

    	echo '<script type = "application/javascript">var session_id = "'. $sess . '";</script>';

		$database = $this->set_db($env);

		$data = $this->read_session_db($sess, $database);

		//$status = $this->get_session_status($sess, $data, $cookie);

		if (sizeof($data)==0) {
			$this->new_session($sess, $database);
	 } else if (sizeof($data) > 0) {
			foreach ($data as $item) {
				$dbexpiredate = (new DateTime($item['expire_date']));
				$secure = $item['secure'];
			}

			if ($this->check_expired($dbexpiredate, $now)==true) {
				if ($secure == "true") {
				 	$this->update_session($sess, $database, $data, "true");
				 	$this->secure = "true";
				 	$this->session_id = session_id();
				} else {
					$this->session_id = session_id();
					$this->secure = "false";
				}
			} else {
				var_dump('i expire');
			
				 $this->update_session($sess, $database, $data, 'false');
				 $this->secure = "false";
			}
		}
		
		echo ('<script type = "application/javascript">var secure = '. $this->secure .';</script>');
	}

	function start() {
		if (session_status() == PHP_SESSION_NONE) {
    		$sess = session_start();
    		return session_id();
		}
		return null;
	}

	function set_db($env) {
		if (class_exists('Connect') == false) {
			require_once 'Connect.php';
		}

		$database = new Connect([
    		'database_type' => 'mysql',
    		'database_name' => $env->attr['database'],
    		'server' => $env->attr['server'],
    		'username' => $env->attr['username'],
    		'password' => $env->attr['password']
		]);
		return $database;
	}

	function check_expired($expire, $now) {
		$now = strtotime($now->format('Y-m-d H:m:s'));
		$expire = strtotime($expire->format('Y-m-d H:m:s'));
		$newdate = $now-$expire;
		if ($newdate > 0) {
			return false;
		} else {
			return true;
		}
	}

	function read_session_db($sess, $database) {

		$data = $database->select('wp_sessions', [
    		'session_id',
    		'secure',
    		'expire_date'
		], [
			'session_id' => $sess
		]);

		return $data;
	}

	private function update_session($sess, $database, $data, $secure = 'false') {
		$now = new DateTime();
		$expire = new DateTime();
		$expire = $expire->add(new DateInterval('P1D'));

		$data = $database->update('wp_sessions', [
				'last_updated' => $now->format('Y-m-d H:m:s'),
				'expire_date' => $expire->format('Y-m-d H:m:s'),
				'secure' => $secure
			], [
				'session_id' => $sess
			]);
	}

	private function new_session($sess, $database) {
		$now = new DateTime();
		$expire = new DateTime();
		$expire = $expire->add(new DateInterval('P1D'));
		
		$data = $database->insert('wp_sessions', [
    		'session_id' => $sess,
    		'secure' => 'false',
    		'expire_date' => $expire->format('Y-m-d H:m:s'),
    		'last_updated' => $now->format('Y-m-d H:m:s')
    	]);

		if ($data->rowCount() >0) {
    		$this->session_id = $sess;
		} else {
			$this->session_id = 'error';
		}
	}

	function check_session() {
		if ($this->secure == 'true') {
			return true;
		} else {
			return false;
		}

	}

	function is_valid() {
		if (isset($_SESSION['login'])) {
			if ($_SESSION['login']==true) {
				$this->set_login_session(true);
				$this->valid_session=true;
				return true;
			} else {
				$this->set_login_session(true);
				$this->valid_session=false;
				return false;
			}
		} else {
			$this->set_login_session(true);
			$this->valid_session=true;
			return true;
		}

	}

	function set_login_session($data) {
		$_SESSION['login'] = $data;
	}
}