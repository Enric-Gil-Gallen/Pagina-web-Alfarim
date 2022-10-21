<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Post;
use App\Helpers\JwtAuth;

class PostController extends Controller {

    public function __construct() {
        $this->middleware('api.auth', ['except' => [
                'index',
                'show',
                'getImage',
                'getPostBySubCategory'
        ]]);
    }

    public function index() {
        $post = Post::all();

        return response()->json([
                    'code' => 200,
                    'status' => 'success',
                    'post' => $post
        ],200);
    }

    public function show($id) {
        $post = Post::find($id);

        if (is_object($post)) {
            $data = [
                'code' => 200,
                'status' => 'success',
                'post' => $post
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'post' => 'El producto no existe'
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function store(Request $request) {
        // Recoger los datos por POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        
        if (!empty($params_array)) {
            //Conseguir post                          
            $jwtAuth = new JwtAuth();
            $token = $request->header('Authorization', null);
            $user = $jwtAuth->checkToken($token, true);

            // Validar los datos
            $validate = \Validator::make($params_array, [
                        'title' => 'required',
                        'sub_category_id' => 'required', 
                        'code' => 'required',
                        'measure' => 'required',
                        'weight' => 'required',
                        'price' => 'required',
                        'content' => 'required',
                        'image' => 'required'
            ]);
            //Guardar los datos
            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'No se ha guardado el post, faltan datos'
                ];
            } else {
                $post = new Post();
                $post->sub_category_id = $params->sub_category_id;
                $post->title = $params->title;
                $post->code = $params->code;
                $post->measure = $params->measure;
                $post->weight = $params->weight;
                $post->price = $params->price;
                $post->content = $params->content;
                $post->image = $params->image;
                $post->save();

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'post' => $post
                ];
            }
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'mensage' => 'Envia los datos correctamente.'
            ];
        }

        // Devolcer Sub_Categoria
        return response()->json($data, $data['code']);
    }
    
    public function update($id, Request $request){
      // Recoger datos por post
      $json = $request->input('json', null);
      $params_array = json_decode($json, true);
 
      // Datos para devolver
      $data = array(
        'code' => 404,
        'status' => 'error',
        'message' => 'Datos enviados incorrectos'
        );
 
      if(!empty($params_array)){
      // Validar datos
      $validate = \Validator::make($params_array, [
        'title' => 'required',
        'sub_category_id' => 'required',
        'code' => 'required',
        'measure' => 'required',
        'weight' => 'required',
        'price' => 'required',
        'content' => 'required',
        'image' => 'required'
      ]);
 
      if($validate->fails()){
             $data['erros'] = $validate->errors();
             return response()->json($data, $data['code']);
      }

      // Actualizar el registro en concreto
       $post = Post::find($id);
       $post->title = $params_array['title'];
       $post->sub_category_id = $params_array['sub_category_id'];
       $post->code = $params_array['code'];
       $post->measure = $params_array['measure'];
       $post->content = $params_array['content'];
       $post->weight = $params_array['weight'];
       $post->price = $params_array['price'];
       $post->image = $params_array['image'];
       $post->save();
 
       $data = array(
              'code' => 200,
              'status' => 'success',
              'post' => $post,
              'changes' => $params_array
       );
      }
 
      // Devolver respuesta
      return response()->json($data, $data['code']);
  }
    
    public function destroy($id, Request $request) {
        // Consegir el registro
        $post = Post::find($id);

        if (!empty($post)) {
            // Borrarlo
            $post->delete();

            // Devolver algo
            $data = [
                'code' => 200,
                'status' => 'success',
                'post' => $post
            ];
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'El producto no existe'
            ];
        }
        return response()->json($data, $data['code']);
    }

    public function upload(Request $request) {       
        // Recoger la imagen de la petición
        $image = $request->file('file0');
        // Validar imagen
        $validate = \Validator::make($request->all(), [
            'file0' => 'mimes:jpeg,jpg,png,gif,gif|required'

        ]);

        // Guardar imagen
        if (!$image || $validate->fails()) {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir la imagen'
            ];
        } else {
            $image_name = time().$image->getClientOriginalName();

            \Storage::disk('images')->put($image_name, \File::get($image));

            $data = [
                'code' => 200,
                'status' => 'success',
                'image' => $image_name
            ];
        }
        // Devolver imagen
        return response()->json($data, $data['code']);
    }

    public function getImage($filename) {
        // Comprovar si existe el imagen
        $isset = \Storage::disk('images')->exists($filename);

        if ($isset) {
            // Consegir imagen
            $file = \Storage::disk('images')->get($filename);

            // Devolver la imagen
            return new Response($file, 200);
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'La imagen no existe'
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function getPostBySubCategory($id) {
        $posts = Post::where('sub_category_id', $id)->get();

        return response() -> json([
                    'status' => 'success',
                    'post' => $posts
                ], 200);
    }

}
