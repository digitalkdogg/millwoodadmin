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
		var_dump($this->client_id);

		return $this->base_url . '?client_id=' .$this->client_id . '&redirect_uri=' . $this->redirect_uri . '&response_type='. $this->response_type .'&scope='. $this->scope . '&state=randomState_121&nonce=randomNonce_121';
		
		//return 'https://authz.constantcontact.com/oauth2/default/v1/authorize?client_id=' .$this->client_id . '&redirect_uri=http://localhost/millwoodadmin&response_type=token&scope=account_read+account_update+contact_data+campaign_data+offline_access&state=randomState_121&nonce=randomNonce_121';
		//return 'https://authz.constantcontact.com/oauth2/default/v1/authorize?client_id=06866a08-4450-4010-b8f0-545ecec14b94&redirect_uri=http://localhost/millwoodadmin&response_type=token&scope=account_read+account_update+contact_data+campaign_data+offline_access&state=randomState_121&nonce=randomNonce_121';
		//return $this->base_url . '?response_type='. $this->response_type . '&client_id=' . $this->client_id . '&scope='. $this->scope . '&redirect_uri=' . $this->redirect_uri;
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