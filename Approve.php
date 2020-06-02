<?php
namespace com\kwipped\approve\wordpress\devtools;
//****************************************************************************************
//* You should not modify the code below. It assures the correct format needed by Approve.
//*****************************************************************************************
class Approve{
	private $items = [];
	private $current_total=0;
	private $kwipped_approve_id = null;
	private $mode = "live";
	private $landing_page_url = null;
	private $api_url = null;
	private $cacert_file = null;

	/**
	 * Constructor
	 */
	function __construct() {
		$kwipped_approve_id=get_option(PLUGIN_PREFIX.'_options');
		if(!empty($kwipped_approve_id) && isset($kwipped_approve_id['approve_id'])){
			$this->kwipped_approve_id=$kwipped_approve_id['approve_id'];
		}
		$this->landing_page_url= $this->mode=="test" ? "https://dev.kwipped.com/approve/finance" : "https://www.kwipped.com/approve/finance";
		$this->api_url= $this->mode=="test" ? "https://dev.kwipped.com/api/v2/approve-widget/finance-teasers/" : "https://www.kwipped.com/api/v2/approve-widget/finance-teasers/" ;
		$this->cacert_file= $this->mode=="test" ? "/usr/local/etc/openssl/cert.pem" : __DIR__."/cacert.pem";
	}

	/**
	 * Adds equipment to the current instance of Approve.
	 */
	public function add($model,$price,$quantity,$type){
		$tmp = [];
		$tmp["model"]=$model;
		$tmp["quantity"]=$quantity;
		$tmp["type"]=$type;
		//In Approve the quantity is a representation of how many items are in the total.
		$tmp["price"]=$price;
		$this->current_total+=($tmp["price"]*$quantity);
		$this->items[]=(object)$tmp;
	}

	/**
	 * Returns URL, teaser text, and teaser raw.
	 */
	public function get_approve_information(){
		$teaser = "";
		if(function_exists('curl_version')){
			$teaser_raw = $this->get_teaser($this->current_total);
			if(!empty($teaser_raw)){
				$teaser = "Finance for $".$teaser_raw."/mo";
			}
			else{
				$teaser = null;
			}
			
		}
		else{
			$teaser = "N/A Your server does not suppor CURL requests. Please ask your system administrator to enable it.";
		}

		$data =  [
			"url"=>$this->landing_page_url."?approveid=".$this->kwipped_approve_id.(sizeof($this->items)>0 ? "&items=".json_encode($this->items) : null),
			"teaser"=>$teaser,
			"teaser_raw"=>$teaser_raw
		];

		return $data;
	}

	/**
	 * Returns teaser raw.
	 */
	public function get_teaser($amount){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->api_url.$amount);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		if(!empty($this->cacert_file)) curl_setopt ($ch, CURLOPT_CAINFO, $this->cacert_file);
		//var_dump(openssl_get_cert_locations());
		$headers = array();
		$headers[] = 'Authorization: Basic '.$this->kwipped_approve_id;
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			return 'Error:' . curl_error($ch);
		}
		curl_close($ch);

		$data = json_decode($result);
		if($data->lease_teaser[0]->monthly_rate> 1)
			$teaser = number_format($data->lease_teaser[0]->monthly_rate,0);
		else
			$teaser = null;
		return $teaser;
	}

	public static function ajax_get_teaser(){
		$approve = new \com\kwipped\approve\wordpress\devtools\Approve();
		$value = $_POST['data']['value'];
		wp_send_json($approve->get_teaser($value));
		wp_die(); // this is required to terminate immediately and return a proper response
	}

	public static function ajax_get_button_action() {
		$approve = new Approve();
		$approve->add($_POST['data']['model'],$_POST['data']['price'],$_POST['data']['qty'],$_POST['data']['item_type']);
		wp_send_json($approve->get_approve_information());
		wp_die(); // this is required to terminate immediately and return a proper response
	}

	public static function ajax_get_approve_information() {
		\com\kwipped\approve\wordpress\devtools\dd2($_POST);
		$approve = new Approve();
		// $items = json_decode($_POST['data']['items']);
		// \com\kwipped\approve\wordpress\devtools\dd2($_POST);
		foreach($_POST['data']['items'] as $item){
			$approve->add($item['model'],$item['price'],$item['qty'],$item['type']);
		}
		wp_send_json($approve->get_approve_information());
		wp_die(); // this is required to terminate immediately and return a proper response
	}
}
?>