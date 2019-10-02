<?php
class AUTHORIZATION
{
	//Clase que no ayuda verificar la valides del toque entre otro paramentros
    public static function validateTimestamp($token)
    {
        $CI =& get_instance();
        $token = self::validateToken($token);
        if ($token != false && (now() - $token->timestamp < ($CI->config->item('token_timeout') * 60))) {
            return $token;
        }
        return false;
    }
    public static function validateToken($token)
    {
        $CI =& get_instance();
        return JWT::decode($token, $CI->config->item('jwt_key'));
    }
    public static function generateToken($data)
    {
        $CI =& get_instance();
        return JWT::encode($data, $CI->config->item('jwt_key'));
	}




	// public function verify_request(){
    // // Get all the headers
    // $headers = $this->input->request_headers();
    // // Extract the token
    // $token = $headers['Authorization'];
    // // Use try-catch
    // // JWT library throws exception if the token is not valid
    // try {
    //     // Validate the token
    //     // Successfull validation will return the decoded user data else returns false
    //     $data = self::validateToken($token);
    //     if ($data === false) {
    //         $status = parent::HTTP_UNAUTHORIZED;
    //         $response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
    //         $this->response($response, $status);
    //         exit();
    //     } else {
    //         return $data;
    //     }
    // } catch (Exception $e) {
    //     // Token is invalid
    //     // Send the unathorized access message
    //     $status = parent::HTTP_UNAUTHORIZED;
    //     $response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
    //     $this->response($response, $status);
    // }
// }
	








}
