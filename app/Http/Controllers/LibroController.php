<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use Carbon\Carbon;

class LibroController extends Controller{

    public  function index(){

    $datosLibro= Libro::all();

    return response()->json($datosLibro);

    }

    public  function guardar(Request $request){

        // $datosLibro es una variable de base de datos.
        // Creo que abro la conexion con la base de datos Libro.
        $datosLibro= new Libro();

        if($request->hasfile('imagen')){

            $nombreArchivoOriginal = $request->file('imagen')->getClientOriginalName();

            $nuevoNombre= Carbon::now()->timestamp."_".$nombreArchivoOriginal;

            $carpetaDestino = './upload/';

            $request->file('imagen')->move($carpetaDestino,$nuevoNombre);

            $datosLibro->título=$request->titulo;
            $datosLibro->imagen=ltrim($carpetaDestino,'.').$nuevoNombre;
            $datosLibro->save();

        }
        //Esta instruccion ya no es necesaria
        //$request->file('imagen');


        //aqui solo tomo los datos solicitados de postman y los guardo en la columna título e imagen de la tabla de libros
        ////////////////////////////////////////////////////////////////Esta parte fue subida a la funcion que valida la imagen
        //$datosLibro->título=$request->titulo;
        //$datosLibro->imagen=$request->imagen;
        ////////////////////////////////////////////////////////////////
        //guardo los datos que se cargaron en las columnas.
        //$datosLibro->save();

        //Respondo enviando los datos que se enviaron desde postman y ademas que se guardaron en la base de datos.
        // return  response()->json($request);

        return  
            //response()->json($request->file('imagen')->getClientOriginalName());
            response()->json($nuevoNombre);
        
    }

    public function ver($id){

        $datosLibro =  new Libro();
        $datosEncontrados = $datosLibro->find($id);

        return response()->json($datosEncontrados);

    }

    public  function eliminar($id){

        //Otra forma de acceder a los datos y sin instanciar es: 
            $datosLibro = Libro::find($id);

            if($datosLibro){
                $rutaArchivo = base_path('public').$datosLibro->imagen;
                if(file_exists($rutaArchivo)){

                    unlink($rutaArchivo);

                }
                $datosLibro->delete();

            }
        return response()->json("Registro Borrado");

    }

    public  function actualizar(Request $request, $id){

        $datosLibro = Libro::find($id);


        //Valida si la informacion que llega de postamn es una imagen.
        if($request->hasfile('imagen')){

            $datosLibro = Libro::find($id);

            if($datosLibro){
                $rutaArchivo = base_path('public').$datosLibro->imagen;
                if(file_exists($rutaArchivo)){

                    unlink($rutaArchivo);

                }
                $datosLibro->delete();

            }
        //return response()->json("Registro Borrado");


            $nombreArchivoOriginal = $request->file('imagen')->getClientOriginalName();

            $nuevoNombre= Carbon::now()->timestamp."_".$nombreArchivoOriginal;

            $carpetaDestino = './upload/';

            $request->file('imagen')->move($carpetaDestino,$nuevoNombre);

            $datosLibro->imagen=ltrim($carpetaDestino,'.').$nuevoNombre;
            $datosLibro->save();

        }


        //Valida la informacion que llega de postman es el titulo.
        if($request->input('titulo')){

            $datosLibro->título = $request->input('titulo');

        }
        $datosLibro->save();

        return response()->json("Datos Actualizados");

    }

}