<?php
class Env {
	private $file; 
	public $attr;

	function __construct() {

		if ($_SERVER['HTTP_HOST'] == 'localhost:8888') {
			$this->file = 'env/work.env';
		} else if ($_SERVER['HTTP_HOST'] == 'localhost') {
			$this->file = 'env/local.env';
		} else {
			$this->file = 'env/prod.env';
		}

		if (isset($this->file)) {
			try {
				$json = $this->get_env_file($this->file);
				$array = $this->get_random_file();
				$this->attr = $this->parse_json($json, $array);
				
				return $this->attr;
			} catch (exception $e) {}
		}
		return null;
	}

	private function parse_json($json, $array) {

		foreach($json as $key=>$item) {
			if (in_array($key, $array) == true) {
				$_SESSION[$key] = $item;
				$array[$key] = $item;
			}
		}

		return $array;
	}

	private function get_env_file($env) {
		if (file_exists($env) == false) {
			$env = '../'.$env;
		}

		if (file_exists($env)==true) {
			$doc = file_get_contents($env, true);
			$json = (array)json_decode($doc, true);
			return $json;
		}
		return null;
	}

	private function get_random_file() {
		$file = 'env/8a96ab342fc21d2a.log';
		if (file_exists($file)==false) {
			$file = '../'.$file;
		}

		if (file_exists($file)==true) {
			$array = file_get_contents($file, true);
			$array = json_decode($array, true);
			return $array;
		}
		return null;
	}

}