<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/Rest_Controller.php';
require APPPATH . 'libraries/Format.php';
// use application\Libraries\REST_Controller;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Api extends REST_Controller {
    public function __construct() {
        parent::__construct();
        
        // Load these helper to create JWT tokens
        $this->load->helper(['jwt', 'authorization']);
		
	}



	public function hello_get()
    {
        $tokenData = 'Hola mundo';
        
        // Create a token
        $token = AUTHORIZATION::generateToken($tokenData);
		
		// Set HTTP status code
        $status = parent::HTTP_OK;
		
		// Prepare the response
        $response = ['status' => $status, 'token' => $token];
		
		// REST_Controller provide this method to send responses
        $this->response($response, $status);
    }
	








}
