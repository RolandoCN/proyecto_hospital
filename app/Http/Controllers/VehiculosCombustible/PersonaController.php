<?php

namespace App\Http\Controllers\VehiculosCombustible;
use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\VehiculoCombustible\Vehiculo;
use App\Models\VehiculoCombustible\Tarea;
use \Log;
use DB;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    
  
    public function index(){
      
        return view('combustible.persona');
    }


    public function listar(){
        try{
            $persona=Persona::where('estado','!=','I')->get();
            return response()->json([
                'error'=>false,
                'resultado'=>$persona
            ]);
        }catch (\Throwable $e) {
            Log::error('PersonaController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function editar($id){
        try{
            $persona=Persona::where('estado','A')
            ->where('idpersona', $id)
            ->first();
            
            return response()->json([
                'error'=>false,
                'resultado'=>$persona
            ]);
        }catch (\Throwable $e) {
            Log::error('PersonaController => editar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }
    
    public function guardarFirma(Request $request){
        try{
            if(is_null($request->b64_firma)){
                return [
                    'error'=>true,
                    'mensaje'=>'Se necesita la firma'
                ];
            }

            //buscamos la persona para agregar o actualizar su firma
            $personaFirma=Persona::find($request->idPersonaFirma);
            $personaFirma->firma_persona=$request->b64_firma;
            if($personaFirma->save()){
                return [
                    'error'=>false,
                    'mensaje'=>'Firma agregada exitosamente'
                ];
            }else{
                return [
                    'error'=>true,
                    'mensaje'=>'No se pudo registrar la firma'
                ];
            }
        }catch (\Throwable $e) {
            Log::error('PersonaController => guardarFirma => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function guardar(Request $request){
        
        $messages = [
            'cedula_persona.required' => 'Debe ingresar la cédula',  
            'nombres.required' => 'Debe ingresar los nombres',           
            'apellidos.required' => 'Debe ingresar los apellidos',  
            'telefono.required' => 'Debe ingresar el telefono',  

        ];
           

        $rules = [
            'cedula_persona' =>"required|string|max:10",
            'nombres' =>"required|string|max:100",
            'apellidos' =>"required|string|max:100",
            'telefono' =>"required|string|max:10",
                     
        ];

        $this->validate($request, $rules, $messages);
        try{

            $validaCedula=validarCedula($request->cedula_persona);
            if($validaCedula==false){
                return response()->json([
                    "error"=>true,
                    "mensaje"=>"El numero de identificacion ingresado no es valido"
                ]);
            }          

            $guarda_persona=new Persona();
            $guarda_persona->cedula=$request->cedula_persona;
            $guarda_persona->nombres=$request->nombres;
            $guarda_persona->apellidos=$request->apellidos;
            $guarda_persona->telefono=$request->telefono;
            $guarda_persona->id_usuario_reg=auth()->user()->id;
            $guarda_persona->fecha_reg=date('Y-m-d H:i:s');
            $guarda_persona->estado="A";

            //validar que la cedula no se repita
            $valida_cedula=Persona::where('cedula', $guarda_persona->cedula)
            ->where('estado','A')
            ->first();

            if(!is_null($valida_cedula)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El número de cédula ya existe, en otra persona'
                ]);
            }

           
            if($guarda_persona->save()){
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
            Log::error('PersonaController => guardar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }


    public function actualizar(Request $request, $id){
       
    
        $messages = [
            'cedula_persona.required' => 'Debe ingresar la cédula',  
            'nombres.required' => 'Debe ingresar los nombres',           
            'apellidos.required' => 'Debe ingresar los apellidos',  
            'telefono.required' => 'Debe ingresar el telefono',  

        ];
            

        $rules = [
            'cedula_persona' =>"required|string|max:10",
            'nombres' =>"required|string|max:100",
            'apellidos' =>"required|string|max:100",
            'telefono' =>"required|string|max:10",
                        
        ];

        $this->validate($request, $rules, $messages);
        try{

            $validaCedula=validarCedula($request->cedula_persona);
            if($validaCedula==false){
                return response()->json([
                    "error"=>true,
                    "mensaje"=>"El numero de identificacion ingresado no es valido"
                ]);
            } 
            
            //verificamos que no este asociado a un tarea, movimiento y despacho en estado activo
            $veri_Tarea=DB::table('vc_tarea')
            ->where('id_chofer',$id)
            ->where('estado','!=', 'Eliminada')
            ->first();
            if(!is_null($veri_Tarea)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La persona está asociado a una tarea y no se puede actualizar'
                ]);
            }

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
            
            $guarda_persona= Persona::find($id);
            $guarda_persona->cedula=$request->cedula_persona;
            $guarda_persona->nombres=$request->nombres;
            $guarda_persona->apellidos=$request->apellidos;
            $guarda_persona->telefono=$request->telefono;
            $guarda_persona->id_usuario_act=auth()->user()->id;
            $guarda_persona->fecha_actualiza=date('Y-m-d H:i:s');
            $guarda_persona->estado="A";

            //validar que la cedula no se repita
            $valida_cedula=Persona::where('cedula', $guarda_persona->cedula)
            ->where('estado','A')
            ->where('idpersona','!=', $id)
            ->first();

            if(!is_null($valida_cedula)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El número de cédula ya existe, en otra persona'
                ]);
            }

            
            if($guarda_persona->save()){
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
            Log::error('PersonaController => actualizar => mensaje => '.$e->getMessage());
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
            Log::error('PersonaController => eliminar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

}
