<?php

namespace App\Http\Controllers\VehiculosCombustible;
use App\Http\Controllers\Controller;
use App\Models\VehiculoCombustible\Gasolinera;
use \Log;
use Illuminate\Http\Request;
use DB;

class GasolineraController extends Controller
{
      
    public function index(){
       
        return view('combustible.gasolinera');
    }


    public function listar(){
        try{
            $gasolinera=Gasolinera::where('estado','!=','I')->get();
            return response()->json([
                'error'=>false,
                'resultado'=>$gasolinera
            ]);
        }catch (\Throwable $e) {
            Log::error('GasolineraController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function editar($id){
        try{
            $gasolinera=Gasolinera::where('estado','A')
            ->where('id_gasolinera', $id)
            ->first();
            
            return response()->json([
                'error'=>false,
                'resultado'=>$gasolinera
            ]);
        }catch (\Throwable $e) {
            Log::error('GasolineraController => editar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }
    

    public function guardar(Request $request){
        
        $messages = [
            'descripcion.required' => 'Debe ingresar la descripción',         
        ];
           

        $rules = [
            'descripcion' =>"required|string|max:100",
        ];

        $this->validate($request, $rules, $messages);
        try{

            $guarda_gasolinera=new Gasolinera();
            $guarda_gasolinera->descripcion=$request->descripcion;
            $guarda_gasolinera->id_usuario_reg=auth()->user()->id;
            $guarda_gasolinera->fecha_reg=date('Y-m-d H:i:s');
            $guarda_gasolinera->estado="A";

            //validar que la gasolinera no se repita
            $valida_gasolinera=Gasolinera::where('descripcion', $guarda_gasolinera->descripcion)
            ->where('estado','A')
            ->first();

            if(!is_null($valida_gasolinera)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'la gasolinera ya existe'
                ]);
            }

            if($guarda_gasolinera->save()){
                return response()->json([
                    'error'=>false,
                    'mensaje'=>'Información registrada exitosamente'
                ]);
            }else{
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'No se pudo registrar la información'
                ]);
            }


        }catch (\Throwable $e) {
            Log::error('GasolineraController => guardar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }


    public function actualizar(Request $request, $id){
        $messages = [
            'descripcion.required' => 'Debe ingresar la descripción',       
        ];
           

        $rules = [
            'descripcion' =>"required|string|max:100",
        ];
        $this->validate($request, $rules, $messages);
        try{

           $actualiza_gasolinera= Gasolinera::find($id);
           $actualiza_gasolinera->descripcion=$request->descripcion;
           $actualiza_gasolinera->id_usuario_act=auth()->user()->id;
           $actualiza_gasolinera->fecha_actualiza=date('Y-m-d H:i:s');
           $actualiza_gasolinera->estado="A";

            //validar que el menu no se repita
            $valida_gasolinera=Gasolinera::where('descripcion',$actualiza_gasolinera->descripcion)
            ->where('estado','A')
            ->where('id_gasolinera','!=',$id)
            ->first();

            if(!is_null($valida_gasolinera)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La gasolinera ya existe'
                ]);
            }

           
            if($actualiza_gasolinera->save()){
                return response()->json([
                    'error'=>false,
                    'mensaje'=>'Información actualizada exitosamente'
                ]);
            }else{
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'No se pudo actualizar la información'
                ]);
            }

        }catch (\Throwable $e) {
            Log::error('GasolineraController => actualizar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

    public function eliminar($id){
        try{

            //verificamos que no este asociado a un despacho activo (cabecera) y  a gasoli-comb
            $veri_CabDesp=DB::table('vc_cabecera_despacho')
            ->where('id_gasolinera',$id)
            ->where('estado','Activo')
            ->first();
            if(!is_null($veri_CabDesp)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La gasolinera se encuentra asociado a un despacho y no se puede eliminar'
                ]);
            }

            $veri_GasComb=DB::table('vc_gasolinera_comb')
            ->where('id_gasolinera',$id)
            ->where('estado','A')
            ->first();
            if(!is_null($veri_GasComb)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La gasolinera se encuentra asociado a una gasolinera combustible y no se puede eliminar'
                ]);
            }


            
            $gasolinera=Gasolinera::find($id);
            $gasolinera->id_usuario_act=auth()->user()->id;
            $gasolinera->fecha_actualiza=date('Y-m-d H:i:s');
            $gasolinera->estado="I";
            if($gasolinera->save()){
                return response()->json([
                    'error'=>false,
                    'mensaje'=>'Información eliminada exitosamente'
                ]);
            }else{
                return response()->json([
                    'error'=>false,
                    'mensaje'=>'No se pudo eliminar la información'
                ]);
            }
               
        }catch (\Throwable $e) {
            Log::error('GasolineraController => eliminar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

}
