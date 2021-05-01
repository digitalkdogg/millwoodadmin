<?php 
class Ccurl {
	private $url; 
	private $maxredirs;
	private $timeout;
	private $httpheader;

	function __construct ($curl_data) {
		$this->url = $curl_data['url'];
		$this->maxredirs = $curl_data['maxredirs'];
		$this->timeout = $curl_data['timeout'];
		$this->httpheader = $curl_data['httpheader'];
	}

	function exec_curl() {
		$curl = curl_init();

		curl_setopt_array($curl, array(
  			CURLOPT_URL => $this->url,
  			CURLOPT_MAXREDIRS => $this->maxredirs,
  			CURLOPT_TIMEOUT => $this->timeout,
  			CURLOPT_HTTPHEADER => $this->httpheader,
		));

		$response = json_decode(curl_exec($curl));
		$err = curl_error($curl);

		curl_close($curl);

		

		if ($err) {
			return $err;
 	 		//echo "cURL Error #:" . $err;
		} else {
			if (isset($response)) {
  				return $response;
  			}
		}

		return null;
	}
}