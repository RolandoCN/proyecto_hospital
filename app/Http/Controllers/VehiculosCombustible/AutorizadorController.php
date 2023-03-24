<?php

namespace App\Http\Controllers\VehiculosCombustible;
use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\VehiculoCombustible\Vehiculo;
use App\Models\VehiculoCombustible\Autoriza;
use \Log;
use DB;
use Illuminate\Http\Request;

class AutorizadorController extends Controller
{
    
  
    public function index(){
      
        return view('combustible.autorizador');
    }


    public function listar(){
        try{
            $persona_aut=Autoriza::where('estado','!=','I')->get();
            return response()->json([
                'error'=>false,
                'resultado'=>$persona_aut
            ]);
        }catch (\Throwable $e) {
            Log::error('AutorizadorController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function editar($id){
        try{
            $persona_au=Autoriza::where('estado','A')
            ->where('id_autorizado_salida', $id)
            ->first();
            
            return response()->json([
                'error'=>false,
                'resultado'=>$persona_au
            ]);
        }catch (\Throwable $e) {
            Log::error('AutorizadorController => editar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }
    

    public function guardar(Request $request){
        
        $messages = [
            'cedula.required' => 'Debe ingresar la cédula',  
            'nombres.required' => 'Debe ingresar los nombres',           
            'apellidos.required' => 'Debe ingresar los apellidos',  
          
        ];
           

        $rules = [
            'cedula' =>"required|string|max:10",
            'nombres' =>"required|string|max:100",
      
        ];

        $this->validate($request, $rules, $messages);
        try{

            $validaCedula=validarCedula($request->cedula);
            if($validaCedula==false){
                return response()->json([
                    "error"=>true,
                    "mensaje"=>"El numero de identificacion ingresado no es valido"
                ]);
            }          

            $guarda_autorizador=new Autoriza();
            $guarda_autorizador->cedula=$request->cedula;
            $guarda_autorizador->nombres=$request->nombres;
            $guarda_autorizador->telefono=$request->telefono;
            $guarda_autorizador->abreviacion_titulo=$request->abreviacion_titulo;
            $guarda_autorizador->email=$request->email;
            $guarda_autorizador->estado="A";
            $guarda_autorizador->estado_autoriza=$request->estado_autoriza;

            //validar que la cedula no se repita
            $valida_cedula=Autoriza::where('cedula', $guarda_autorizador->cedula)
            ->where('estado','A')
            ->first();

            if(!is_null($valida_cedula)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El número de cédula ya existe, en otra persona'
                ]);
            }

            //validar que la cedula no se email
            $valida_cedula=Autoriza::where('email', $guarda_autorizador->email)
            ->where('estado','A')
            ->first();

            if(!is_null($valida_cedula)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El email ya existe, en otra persona'
                ]);
            }

           
            if($guarda_autorizador->save()){
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
            Log::error('AutorizadorController => guardar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }


    public function actualizar(Request $request, $id){
       
    
        $messages = [
            'cedula.required' => 'Debe ingresar la cédula',  
            'nombres.required' => 'Debe ingresar los nombres',           

        ];
            

        $rules = [
            'cedula' =>"required|string|max:10",
            'nombres' =>"required|string|max:100",
                       
        ];

        $this->validate($request, $rules, $messages);
        try{

            $validaCedula=validarCedula($request->cedula);
            if($validaCedula==false){
                return response()->json([
                    "error"=>true,
                    "mensaje"=>"El numero de identificacion ingresado no es valido"
                ]);
            } 
            
           
            $actualiza_autorizador= Autoriza::find($id);
            $actualiza_autorizador->cedula=$request->cedula;
            $actualiza_autorizador->nombres=$request->nombres;
            $actualiza_autorizador->telefono=$request->telefono;
            $actualiza_autorizador->abreviacion_titulo=$request->abreviacion_titulo;
            $actualiza_autorizador->estado="A";
            $actualiza_autorizador->estado_autoriza=$request->estado_autoriza;
            $actualiza_autorizador->email=$request->email;

            //validar que la cedula no se repita
            $valida_cedula=Autoriza::where('cedula', $actualiza_autorizador->cedula)
            ->where('estado','A')
            ->where('id_autorizado_salida','!=', $id)
            ->first();

            if(!is_null($valida_cedula)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El número de cédula ya existe, en otra persona'
                ]);
            }

            
            if($actualiza_autorizador->save()){
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
            Log::error('AutorizadorController => actualizar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

    public function eliminar($id){
        try{
            
            //verificamos que no este asociado a un movimiento 
          
            $veri_Movimiento=DB::table('vc_movimiento')
            ->where('id_autorizado_salida',$id)
            ->where('estado','=', 'Activo')
            ->first();
            if(!is_null($veri_Movimiento)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La persona está asociado a un ingreso/salida de vehículo y no se puede eliminar'
                ]);
            }

            $persona_aut=Autoriza::find($id);
            $persona_aut->estado="I";
            if($persona_aut->save()){
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
            Log::error('AutorizadorController => eliminar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

}
