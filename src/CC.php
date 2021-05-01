<?php 
class CC {
	public $client_id;
	public $redirect_uri;
	private $base_url;
	public $auth_method;
	private $response_type;
	private $scope;
	public $auth_url;
	public $access_token;

	function __construct ($ccdata) {

		$this->client_id = $ccdata['client_id'];
		$this->redirect_uri = $ccdata['redirect_uri'];
		$this->base_url = $ccdata['base_url'];
		$this->auth_mehtod = $ccdata['auth_method'];
		$this->response_type = $ccdata['response_type'];
		$this->scope = $ccdata['scope'];
		$this->auth_url = $this->getAuthURL();

	}

	function getAuthURL() {

		return $this->base_url . 'idfed?response_type='. $this->response_type . '&client_id=' . $this->client_id . '&scope='. $this->scope . '&redirect_uri=' . $this->redirect_uri;
	}

	function getCampaigns() {
		//require "src/Ccurl.php";
		$curl_data = array(
			'url'=> 'https://api.cc.email/v3/emails',
			'maxredirs'=>'10',
			'timeout' => '30',
			'httpheader' => array ('Accept: application/json', 'Authorization: Bearer '. $this->access_token)
		);


		$curl = new Ccurl($curl_data);
			
		$campaigns = $curl->exec_curl();
		return $campaigns;
	}


}