<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Category;
use App\Helpers\JwtAuth;

class CategoryController extends Controller {

    public function __construct() {
        $this->middleware('api.auth', ['except' => ['index', 'show', 'getCategoryById']]);
    }

    public function index() {
        $categories = Category::all();

        return response()->json([
                    'code' => 200,
                    'status' => 'success',
                    'categories' => $categories
        ]);
    }

    public function show($id) {
        $category = Category::find($id);

        if (is_object($category)) {
            $data = [
                'code' => 200,
                'status' => 'success',
                'category' => $category
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'category' => 'La categoria no existe'
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function store(Request $request) {
        // Recoger los datos
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        
        if (!empty($params_array)) {            
            // Validar los datos
            $validate = \Validator::make($params_array, [
                        'name' => 'required'
            ]);
            
            //Guardar los datos
            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'No se ha podido guardar la categoria'
                ];
            } else {
                $category = new Category();
                $category->name = $params_array['name'];
                $category->save();

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'category' => $category
                ];
            }
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'mensage' => 'No has enviado ninguna categoria'
            ];
        }

        // Devolcer Categoria
        return response()->json($data, $data['code']);
    }

    public function update($id, Request $request) {
        // Recoger los datos
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);

        if (!empty($params_array)) {
            // Validar los datos
            $validate = \Validator::make($params_array, [
                        'name' => 'required',
            ]);

            if ($validate->fails()) {
                $data['errors'] = $validate->errors();
                return response()->json($data, $data['code']);
            }

            //Quitar lo que no quero actualizar
            unset($params_array['id']);
            unset($params_array['created_at']);

            // Actualizar 
            $categoty = Category::where('id', $id)->update($params_array);

            // Devolver respuesta 
            $data = [
                'code' => 200,
                'status' => 'success',
                'category' => $params_array
            ];
        }else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'mensage' => 'No has enviado ninguna categoria'
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function destroy($id, Request $request) {
        // Consegir el registro
        $category = Category::find($id);

        if (!empty($category)) {
            // Borrarlo
            
            $category->delete();

            // Devolver algo
            $data = [
                'code' => 200,
                'status' => 'success',
                'category' => $category
            ];
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'La categoria no existe'
            ];
        }
        return response()->json($data, $data['code']);
    }
    
    /*public function getCategoryById($id) {
        $category = Category::where('user_id', $id)->get();

        return response() - json([
                    'status' => 'success',
                    'category' => $category
                        ], 200);
    }*/

    
}
