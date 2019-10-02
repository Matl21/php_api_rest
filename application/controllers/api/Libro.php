<?php
require APPPATH . 'libraries/Rest_Controller.php';
class Libro extends REST_Controller
{

	public function __construct()
	{
		parent::__construct("rest");
		//Encabezamiento de la peticiones
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
		header('ACcess-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
		header('Allow: GET, POST, OPTIONS, PUT, DELETE');

		//Estos no permitira cargar los archivo para la creacion y validacion del token
		$this->load->helper(['jwt', 'authorization']);
		


	}


	//METODO ENCARGADO DE LA VALIDACION DE LOS TOKEN
	private function verify_request(){
		// Obtiene todo lo header 
		$headers = $this->input->request_headers();
		// Extraer el toquen
		$token = $headers['Authorization'];
		// Usando un try-catch
		// La biblioteca JWT lanza una excepción si el token no es válido
		try {
			// Validado el token
			// JWT La validación exitosa devolverá los datos de usuario decodificados; de lo contrario, devolverá false
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
			// Si el token es invalido
			// Se mandara un mensaje de 
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$this->response($response, $status);
		}
}

	
//METODO QUE PERMITE CONSULTAR 1 O TODOS LO REGISTRO DE LA BASE DE DATOS CON AUTENTIFICACION TOKEN
	public function index_get($id = null)
	{
		
		if($dat= $this->verify_request()){
		if (!empty($id)) {
			$data = $this->db->get_where("libro", ['isbn' => $id])->row_array();
			if ($data == null) {
				$this->response(["No hay datos coincidentes al id: " . $id], REST_Controller::HTTP_NOT_FOUND);
			}
		} else {

			$this->db->select('l.isbn, l.titulo, l.autor, g.titulo as genero');
			$this->db->from('libro as l');
			$this->db->join('genero g','l.id_genero = g.id_genero');
			$data=$this->db->get_where("libro",['l.isbn'=>$id])->row_array();
			$data = $this->db->get("libro")->result();
		}
		array_push($data, $dat);
		$this->response($data, REST_Controller::HTTP_OK);
		}
		else{
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'data' => $dat, "msg" => "NO TIENE AUTORIZACION"];
			$this->response($response, $status);
		}
	}





		
//METODO QUE PERMITE INSERTAR UN REGISTRO EN LA BASE DE DATOS CON AUTENTIFICACION TOKEN
	public function index_post()
	{
		if($dat= $this->verify_request()){
			$data = [
				'isbn' => $this->post("isbn"),
				'titulo' => $this->post("titulo"),
				'autor' => $this->post("autor"),
				'id_genero' => $this->post("id_genero")
			];

			$this->db->insert("libro", $data);
			$query = $this->db->get("libro")->result();
			array_push($query, $dat);
			$this->response($query);
			
		}else{
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'data' => $dat, "msg" => "NO TIENE AUTORIZACION"];
			$this->response($response, $status);
		}
			
	}


//METODO QUE PERMITE MODIFICAR UN REGISTRO EN LA BASE DE DATOS CON AUTENTIFICACION TOKEN
	public function index_put($id)
	{
		if($dat= $this->verify_request()){
			$data = $this->put();
			$this->db->update("libro", $data, array('isbn' => $id));
			$this->response(["Registro actualizado por usuario" => $dat], REST_Controller::HTTP_OK);
		}else{
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'data' => $dat, "msg" => "NO TIENE AUTORIZACION"];
			$this->response($response, $status);
		}
	}

	//METODO QUE PERMITE BORRAR UN REGISTRO EN LA BASE DE DATOS CON AUTENTIFICACION TOKEN
	public function index_delete($id)
	{
		if($dat= $this->verify_request()){
			$this->db->delete("libro", array('isbn' => $id));
			
			$this->response(["Registro eliminado por" => $dat], REST_Controller::HTTP_OK);
		}else{
			$status = parent::HTTP_UNAUTHORIZED;
			$response = ['status' => $status, 'data' => $dat, "msg" => "NO TIENE AUTORIZACION"];
			$this->response($response, $status);
		}
	}
}
