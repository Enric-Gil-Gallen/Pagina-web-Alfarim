<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Sub_Category;
use App\Helpers\JwtAuth;

class SubCategoryController extends Controller {

    public function __construct() {
        $this->middleware('api.auth', ['except' => [
                'index',
                'show',
                'getPostByCategory'
        ]]);
    }

    public function index() {
        $sub_categories = Sub_Category::all();

        return response()->json([
                    'code' => 200,
                    'status' => 'success',
                    'subcategories' => $sub_categories
        ]);
    }

    public function show($id) {
        $sub_category = Sub_Category::find($id);

        if (is_object($sub_category)) {
            $data = [
                'code' => 200,
                'status' => 'success',
                'sub_category' => $sub_category
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'sub_category' => 'La sub_categoria no existe'
            ];
        }

        return response()->json($data, $data['code']);
    }

    public function store(Request $request) {
        // Recoger los datos
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        
        if (!empty($params_array)) {

            $jwtAuth = new JwtAuth();
            $token = $request->header('Authorization', null);
            $user = $jwtAuth->checkToken($token, true);

            // Validar los datos
            $validate = \Validator::make($params_array, [
                        'name' => 'required'
            ]);
            //Guardar los datos
            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Envia el nombre'
                ];
            } else {
                $sub_category = new Sub_Category();
                $sub_category->name = $params_array['name'];
                $sub_category->category_id = $params->category_id;
                $sub_category->save();

                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'sub_category' => $sub_category
                ];
            }
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'mensage' => 'No has enviado ninguna sub_categoria'
            ];
        }

        // Devolcer Sub_Categoria
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
                        'category_id' => 'required'
            ]);

            if ($validate->fails()) {
                $data['errors'] = $validate->errors();
                return response()->json($data, $data['code']);
            }

            //Quitar lo que no quero actualizar
            unset($params_array['id']);
            unset($params_array['created_at']);

            // Actualizar 
            $sub_categories = Sub_Category::where('id', $id)->update($params_array);

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
                'mensage' => 'No has enviado ninguna sub-categoria'
            ];
        }

        return response()->json($data, $data['code']);
    }

 
    public function getSubCategoryByCategory($id) {
        $sub_category = Sub_Category::where('category_id', $id)->get();

        return response() -> json([
                    'status' => 'success',
                    'sub_category' => $sub_category
                        ], 200);
    }
    
    public function destroy($id, Request $request) {
        // Consegir el registro
        $sub_category = Sub_Category::find($id);

        if (!empty($sub_category)) {
            // Borrarlo
            $sub_category->delete();

            // Devolver algo
            $data = [
                'code' => 200,
                'status' => 'success',
                'sub_category' => $sub_category
            ];
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'La Sub-categoria no existe'
            ];
        }
        return response()->json($data, $data['code']);
    }
}
