<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $respuesta["datos"] = false;
        $respuesta["status"] = false;
        $respuesta["error"] = true;

        $usuario = Usuario::all();
        if ($usuario) {
            $respuesta["datos"] = $usuario;
            $respuesta["status"] = true;
            $respuesta["error"] = false;
        }
        return response()->json($respuesta);

    }


    public function show($id)
    {
        $respuesta["datos"] = false;
        $respuesta["status"] = false;
        $respuesta["error"] = true;

        $usuario = Usuario::where('id', $id)->first();
        if ($usuario) {
            $respuesta["datos"] = $usuario;
            $respuesta["status"] = true;
            $respuesta["error"] = false;
        }
        return response()->json($respuesta);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $respuesta = [];
        $respuesta['datos'] = false;
        $respuesta["error"] = true;
        $respuesta["status"] = false;
        $datos = $request->all();

        $messages = [
            'required' => 'El campo :attribute es requerido.',
            'max' => 'El campo :attribute ha sobrepasado la cantidad de caràcteres.',
            'email.unique' => 'El Email ingresado ya esta registrado en el sistema.',
        ];
        $rules = [
            'nombres' => 'required|max:50',
            'apellidos' => 'required|max:50',
            'email' => 'required|max:50|unique:usuarios,email',
            'password' => 'required|max:50',
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $respuesta['validator'] = $validator->errors()->all();
            $respuesta['mensaje'] = 'Errores en los datos ingresados.';
            $respuesta["error"] = true;
            $respuesta["status"] = true;
        } else {
            $usuario = new Usuario($datos);
            if ($usuario->save()) {
                $respuesta["datos"] = $usuario;
                $respuesta["status"] = true;
                $respuesta["error"] = false;
                $respuesta['mensaje'] = 'Guardado.';

            } else {
                $respuesta['mensaje'] = 'No se puedo registrar.';
                $respuesta["status"] = false;
                $respuesta["error"] = true;
            }
        }
        return response()->json($respuesta);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $respuesta = [];
        $respuesta['datos'] = false;
        $respuesta["error"] = true;
        $respuesta["status"] = false;
        $datos = $request->all();
        $usuario = $request->user();

        $messages = [
            'required' => 'El campo :attribute es requerido.',
            'max' => 'El campo :attribute ha sobrepasado la cantidad de caràcteres.',
            'email.unique' => 'El Email ingresado ya esta registrado en el sistema.',
        ];
        $rules = [
            'nombres' => 'required|max:200',
            'apellidos' => 'required|max:50',
            'email' => 'required|max:100|unique:usuarios,email,' . $id,
            'password' => 'required|max:100',
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $respuesta['validator'] = $validator->errors()->all();
            $respuesta['mensaje'] = 'Error en los datos ingresados.';
            $respuesta["error"] = true;
            $respuesta["status"] = true;
        } else {

            $usuario = Usuario::find($id);
            $usuario->fill($datos);
            if ($usuario->save()) {
                $respuesta['mensaje'] = 'Actualizado.';
                $respuesta['datos'] = $usuario;
                $respuesta["status"] = true;
                $respuesta["error"] = false;
            } else {
                $respuesta['mensaje'] = 'No se pudo actualizar.';
                $respuesta["status"] = false;
                $respuesta["error"] = true;
            }
        }
        return response()->json($respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = Usuario::find($id);
        if ($usuario) {
            $usuario->delete();
            if ($usuario->trashed()) {
                $respuesta['datos'] = $usuario;
                $respuesta['mensaje'] = "Eliminado.";
                $respuesta["status"] = true;
                $respuesta["error"] = false;
            } else {
                $respuesta['mensaje'] = "No se pudo eliminar.";
                $respuesta["status"] = false;
                $respuesta["error"] = true;
            }
        } else {
            $respuesta['mensaje'] = 'No se encontro el proveedor que intenta eliminar.';
        }
        return response()->json($respuesta);
    }
}
