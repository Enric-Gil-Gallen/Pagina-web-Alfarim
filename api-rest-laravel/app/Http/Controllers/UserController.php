<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller {

    public function register(Request $request){

      //Recoger datos y decodificar los datos
      $json = $request->input('json' , null);
      $params = json_decode($json); // Objeto
      $params_array = json_decode($json, true); // Array (mucho mejor)

      if(!empty($params) && !empty($params_array)){
      // Limpiar datos
      $params_array = array_map('trim', $params_array);

      // Validar datos
      $validate = \Validator::make($params_array, [
      'name'       => 'required|alpha',
      'surname'    => 'required|alpha',
      'email'      => 'required|email|unique:users', // Compruebas si existe el usuario, si existe error
      'password'   => 'required'
      ]);

      if ($validate->fails()) {
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Introduce bien los datos',
                    'errors' => $validate->errors()
                );
            } else {
                // Cifrado de la contraseña
                $pwd = hash('sha256', $params->password);

                // Crear el usuario
                $user = new User();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->password = $pwd;
                $user->role = 'ROLE_ADMIN';

                // Guardar el usuario
                $user->save();

                $data = array(
                    'status' => 'sucsess',
                    'code' => 200,
                    'message' => 'El usuario se ha creado correctamente',
                );
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Introdice los datos'
            );
        }
        return response()->json($data, $data['code']);
    }

    public function login(Request $request) {
        
        $jwtAuth = new \JwtAuth();
        
        // Recibir post
        $json = $request->input('json' , null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        
        //Validar esos datos
        $validate = \Validator::make($params_array, [
                        'email' => 'required|email', 
                        'password' => 'required'
            ]);
        
        if ($validate->fails()) {
            // La validación ha fallado
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El usuario no se ha podido identificar',
                'errors' => $validate->errors()
            );
        } else {
           //Cifrar la password
           $pwd = hash('sha256', $params->password);

           //Devolver token o datos  
           $signup = $jwtAuth->signup($params->email, $pwd);

           if(!empty($params->gettoken)){
                $signup = $jwtAuth->signup($params->email, $pwd, true);
           }
        }

        return response()->json($signup,200);

    }
    
    public function update(Request $request) {
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        
        if($checkToken){
            echo "logn Corecto";
        }
        else{
            echo "logn incorrecto";
        }
        
        die();
    }
    
    public function checkToken(Request $request) {
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if ($checkToken) {
            echo "<h1>Login correcto </h1>";
        } else {
            echo "<h1>Login incorrecto </h1>";
        }
        die();
    }

}
