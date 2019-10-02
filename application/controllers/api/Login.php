<?php
require APPPATH . 'libraries/Rest_Controller.php';
class Login extends REST_Controller
{

	public function __construct()
	{
		parent::__construct("rest");
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
		header('ACcess-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
		header('Allow: GET, POST, OPTIONS, PUT, DELETE');
		$this->load->helper(['jwt', 'authorization']);
	}


	private function verify_request(){
    // Get all the headers
    $headers = $this->input->request_headers();
    // Extract the token
    $token = $headers['Authorization'];
    // Use try-catch
    // JWT library throws exception if the token is not valid
    try {
        // Validate the token
        // Successfull validation will return the decoded user data else returns false
        $data = AUTHORIZATION::validateToken($token);
        if ($data === false) {
            $status = parent::HTTP_UNAUTHORIZED;
            $response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
            $this->response($response, $status);
            exit();
        } else {
            return $data;
        }
    } catch (Exception $e) {
        // Token is invalid
        // Send the unathorized access message
        $status = parent::HTTP_UNAUTHORIZED;
        $response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
        $this->response($response, $status);
    }
}




	//Metodo de logeo que no entregara lo que es nuestro token si la credenciales son correcta con algun usuario de la base de datos
	public function login_post(){
	  
		//Un poco de seguridad para la ejecuccion de este metodo
		if (($_SERVER['REQUEST_METHOD'] == 'POST'))
		{
			//valindando con un catch
			try {
				$data = $this->db->get_where("usuario", ['usuario' => $this->post('usuario'), 'pass' => $this->post('pass')])->row_array();
				
				if ($data == null) {
					$this->response(['msg' => 'Invalid username or password!'], parent::HTTP_NOT_FOUND);
					
				}else{
					//Generando el token usando el nombre del usuario mas la cadena secreta nuestra			
					$token = AUTHORIZATION::generateToken(['username' => $this->post('usuario')]);
					// Preparando la respuesta a devolver
					$status = parent::HTTP_OK;
					$response = ['status' => $status, 'token' => $token, "msg" => "Token creado correctamente"];
					$this->response($response, $status);
				
				}
					
			} catch (PDOException $e) {
				die;
			}
		}

}


public function index_post()
{
    // Call the verification method and store the return value in the variable
    if($data = $this->verify_request()){
		// Send the return data as reponse
		$status = parent::HTTP_OK;
		$response = ['status' => $status, 'data' => $data];
		$this->response($response, $status);
	}else {
		$status = parent::HTTP_UNAUTHORIZED;
		$response = ['status' => $status, 'data' => $data];
		$this->response($response, $status);
	}
}


}



// generar un token y proteger los controlador para solo usar con token eso controladores.
