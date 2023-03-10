<?php

namespace App\Http\Controllers\VehiculosCombustible;
use App\Http\Controllers\Controller;
use App\Models\VehiculoCombustible\TipoCombustible;
use \Log;
use DB;
use Illuminate\Http\Request;

class CombustibleController extends Controller
{
      
    public function index(){
       
        return view('combustible.tipo_combustible');
    }


    public function listar(){
        try{
            $combustible=TipoCombustible::where('estado','!=','I')->get();
            return response()->json([
                'error'=>false,
                'resultado'=>$combustible
            ]);
        }catch (\Throwable $e) {
            Log::error('CombustibleController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function editar($id){
        try{
            $combustible=TipoCombustible::where('estado','A')
            ->where('id_tipocombustible', $id)
            ->first();
            
            return response()->json([
                'error'=>false,
                'resultado'=>$combustible
            ]);
        }catch (\Throwable $e) {
            Log::error('CombustibleController => editar => mensaje => '.$e->getMessage());
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

            $guarda_combust=new TipoCombustible();
            $guarda_combust->detalle=$request->descripcion;
            $guarda_combust->estado="A";

            //validar que el combustible no se repita
            $valida_combustible=TipoCombustible::where('detalle', $guarda_combust->detalle)
            ->where('estado','A')
            ->first();

            if(!is_null($valida_combustible)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El combustible ya existe'
                ]);
            }

            if($guarda_combust->save()){
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
            Log::error('CombustibleController => guardar => mensaje => '.$e->getMessage());
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

           $actualiza_combust= TipoCombustible::find($id);
           $actualiza_combust->detalle=$request->descripcion;
           $actualiza_combust->estado="A";

            //validar que el menu no se repita
            $valida_combust=TipoCombustible::where('detalle',$actualiza_combust->detalle)
            ->where('estado','A')
            ->where('id_tipocombustible','!=',$id)
            ->first();

            if(!is_null($valida_combust)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El combustible ya existe'
                ]);
            }

           
            if($actualiza_combust->save()){
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
            Log::error('CombustibleController => actualizar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

    public function eliminar($id){
        try{
            //verificamos que no este asociado a un vehiculo y gasolinera en estado activo
            $veri_Vehiculo=DB::table('vc_vehiculo')
            ->where('id_tipocombustible',$id)
            ->where('estado','A')
            ->first();
            if(!is_null($veri_Vehiculo)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El combustible se encuentra asociado a un vehículo y no se puede eliminar'
                ]);
            }

            $veri_Gasolinera=DB::table('vc_gasolinera_comb')
            ->where('id_tipocombustible',$id)
            ->where('estado','A')
            ->first();
            if(!is_null($veri_Gasolinera)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El combustible se encuentra asociado a una  gasolinera combustible y no se puede eliminar'
                ]);
            }

           
            $combustible=TipoCombustible::find($id);
            $combustible->estado="I";
            if($combustible->save()){
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
            Log::error('CombustibleController => eliminar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

}
