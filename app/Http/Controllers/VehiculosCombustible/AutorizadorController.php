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
            'apellidos' =>"required|string|max:100",
                     
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

            $guarda_autorizador=new Persona();
            $guarda_autorizador->cedula=$request->cedula;
            $guarda_autorizador->nombres=$request->nombres;
            $guarda_autorizador->apellidos=$request->apellidos;
            $guarda_autorizador->telefono=$request->telefono;
            $guarda_autorizador->email=$request->email;
            $guarda_autorizador->id_usuario_reg=auth()->user()->id;
            $guarda_autorizador->fecha_reg=date('Y-m-d H:i:s');
            $guarda_autorizador->estado="A";

            //validar que la cedula no se repita
            $valida_cedula=Persona::where('cedula', $guarda_autorizador->cedula)
            ->where('estado','A')
            ->first();

            if(!is_null($valida_cedula)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El número de cédula ya existe, en otra persona'
                ]);
            }

            //validar que la cedula no se email
            $valida_cedula=Persona::where('email', $guarda_autorizador->email)
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
            'apellidos.required' => 'Debe ingresar los apellidos',  

        ];
            

        $rules = [
            'cedula' =>"required|string|max:10",
            'nombres' =>"required|string|max:100",
            'apellidos' =>"required|string|max:100",
                       
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
            
            //verificamos que no este asociado a un movimiento 
            

            $veri_Movimiento=DB::table('vc_movimiento')
            ->where('id_chofer',$id)
            ->where('estado','!=', 'Eliminada')
            ->first();
            if(!is_null($veri_Movimiento)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La persona está asociado a un ingreso/salida de vehículo y no se puede actualizar'
                ]);
            }

            $veri_Despacho=DB::table('vc_detalle_despacho')
            ->where('idconductor',$id)
            ->where('estado','!=', 'Eliminado')
            ->first();
            if(!is_null($veri_Despacho)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La persona está asociado a un despacho de combustible de vehículo y no se puede actualizar'
                ]);
            }
            
            $guarda_autorizador= Persona::find($id);
            $guarda_autorizador->cedula=$request->cedula;
            $guarda_autorizador->nombres=$request->nombres;
            $guarda_autorizador->apellidos=$request->apellidos;
            $guarda_autorizador->telefono=$request->telefono;
            $guarda_autorizador->id_usuario_act=auth()->user()->id;
            $guarda_autorizador->fecha_actualiza=date('Y-m-d H:i:s');
            $guarda_autorizador->estado="A";

            //validar que la cedula no se repita
            $valida_cedula=Persona::where('cedula', $guarda_autorizador->cedula)
            ->where('estado','A')
            ->where('idpersona','!=', $id)
            ->first();

            if(!is_null($valida_cedula)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El número de cédula ya existe, en otra persona'
                ]);
            }

            
            if($guarda_autorizador->save()){
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
            //verificamos que no este asociado a un tarea, movimiento y despacho en estado activo
            $veri_Tarea=DB::table('vc_tarea')
            ->where('id_chofer',$id)
            ->where('estado','!=', 'Eliminada')
            ->first();
            if(!is_null($veri_Tarea)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La persona está asociado a una tarea y no se puede eliminar'
                ]);
            }

            $veri_Movimiento=DB::table('vc_movimiento')
            ->where('id_chofer',$id)
            ->where('estado','!=', 'Eliminada')
            ->first();
            if(!is_null($veri_Movimiento)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La persona está asociado a un ingreso/salida de vehículo y no se puede eliminar'
                ]);
            }

            $veri_Despacho=DB::table('vc_detalle_despacho')
            ->where('idconductor',$id)
            ->where('estado','!=', 'Eliminado')
            ->first();
            if(!is_null($veri_Despacho)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La persona está asociado a un despacho de combustible de vehículo y no se puede eliminar'
                ]);
            }
           
            $persona=Persona::find($id);
            $persona->id_usuario_act=auth()->user()->id;
            $persona->fecha_actualiza=date('Y-m-d H:i:s');
            $persona->estado="I";
            if($persona->save()){
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
