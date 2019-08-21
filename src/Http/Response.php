<?php
namespace App\Http;
use App\Http\Request;
/**
 * Response Class
 */
class Response extends Request 
{	
	protected $headers=[], $http_code, $http_msg, $http_codes;
	public $view, $log;
	
	function __construct(Request $request)
	{
		foreach ($request as $key => $value) {
				$this->$key = $value;

			if ( (!is_null($key) && !is_null($value)) && !is_Array($value) ){
				$this->set_header_line($key, $value);
			}

		}

		$this->codes = $this->get_http_code_list();
	}





	public function set_http_code($code){

		if ( isset($code) ){
			 	$this->http_code = $code;
			  	$set_name=null;

			 	if ( is_null($this->http_msg) ){
			 		$set_name = $this->set_http_msg($this->http_codes[$code]);
			 	}

			 	if ($set_name && $this->get_http_code() ){
			 		return true;
			 	}
		} 
		return false;
	}




	public function get_http_code(){
		return (isset($this->http_code)) ? $this->http_code : false;
	}



	public static function get_http_code_list(){
		return (array) json_decode( file_get_contents('../src/Http/http_codes.json') );
	}



	public function set_http_msg($msg){

		if ((isset($msg) && is_string($msg) ) ){
			$this->http_msg = ucwords($msg);
			return true;
		}
		return false;
		
	}



	public function get_http_msg($code=null){

		if ( isset($this->http_msg) && is_null($code) ){
			return $this->http_msg;
		}else{

			if( $this->get_http_code() && is_null($code) ){
				return $this->http_codes[$this->get_http_code()];
			}elseif( !is_null($code) ){
				return $this->http_codes[$code];
			}

		}


		return false;
	}



	public function get_content_type(){
		isset($this->get_headers()['Content-Type'] )? $this->get_headers()['Content-Type'] : false;
	}



	public function set_content_type($content_type = 'html'){

		switch (strtolower($content_type) ) {
			
			default:
			case 'html':
				$content_type = 'text/html'; 
				break;

			case 'json':
				$content_type = 'application/json; charset=utf-8;'; 
				break;

			case 'text':
				$content_type = 'document'; 
				break;		
			
		}

		return $this->set_header_line('content_type', $content_type);
	}



	public function set_header_line(String $key, String $value){
		
		$key = str_replace(' ','-' ,ucwords(str_replace(['-', '_'],' ' , $key ))  ) ;

		if ( isset($key) && isset($value) ){
			 $this->headers[$key] = $value;

			 if (isset($this->headers[$key]) ){
			 	return true;
			 }

			 return false;
		} 

	}




	public function get_headers(){
		if( isset($this->headers)){
			return $this->headers;
		}
		return false;
	}



	public function json($data, $code=200, $msg = 'Found!' ){
		$this->set_content_type('json');
		$this->set_http_code($code);
		$this->set_http_msg( !is_null($msg) ? $msg :  $this->get_msg($code) );
		$data->token = isset($this->token) ? $this->token : false ;

		//var_dump($data['token']); exit;
		$this->view = json_encode($data);
	}



	public function write(String $data , $type = 'html', $code=200, $msg = 'OK!'){
		$this->set_content_type($type);
		$this->set_http_code($code);
		$this->set_http_msg($msg);
		$this->view = $data;
	}



	public function view(){
		echo $this->view;
	}


	public function set_log($msg,$class='warning'){

		if( is_string($msg) ) {
			$this->log = [
				'msg'=> (string) $msg , 
				'class' => $class
				];
		}elseif (is_array($msg)) {
			
			foreach ($msg as $key => $value) {
				$this->log[$key] = $value;
			}

			$this->log['class'] = $class;
		}		

		return !is_null($this->log);
	}


	public function get_log(){
		return (!is_null($this->log)) ? (object) $this->log : false;
	}



	public function set_data($data){
		$this->data = $data;
	}



	public function get_data(){
		return (!is_null($this->data)) ? $this->data : false;
	}


}