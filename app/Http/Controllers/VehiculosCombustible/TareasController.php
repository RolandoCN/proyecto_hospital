<?php

namespace App\Http\Controllers\VehiculosCombustible;
use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\VehiculoCombustible\Vehiculo;
use App\Models\VehiculoCombustible\Tarea;
use \Log;
use Illuminate\Http\Request;

class TareasController extends Controller
{


    public function index(){
        $persona=Persona::where('estado','A')->get();
        $vehiculo=Vehiculo::where('estado','A')->get();
      
        return view('combustible.tarea',[
            "persona"=>$persona,
            "vehiculo"=>$vehiculo
        ]);
    }


    public function listar(){
        try{
            $tarea=Tarea::with('vehiculo','chofer')->where('estado','!=','Eliminada')->get();
            return response()->json([
                'error'=>false,
                'resultado'=>$tarea
            ]);
        }catch (\Throwable $e) {
            Log::error('TareasController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function editar($id){
        try{
            $tarea=Tarea::where('estado','Pendiente')
            ->where('id_tarea', $id)
            ->first();
            
            return response()->json([
                'error'=>false,
                'resultado'=>$tarea
            ]);
        }catch (\Throwable $e) {
            Log::error('TareasController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }
    

    public function guardar(Request $request){
      
        $messages = [
            
            'vehiculo_tarea.required' => 'Debe seleccionar el vehículo',
            'choferSalvo.required' => 'Debe seleccionar el chofer',
            'fecha_ini.required' => 'Debe ingresar la fecha inicio',
            'motivo.required' => 'Debe ingresar el motivo',           
        ];
           

        $rules = [
            'vehiculo_tarea' => "required",
            'choferSalvo' => "required",
            'fecha_ini' => "required",
            'motivo' =>"required|string|max:500",
                     
        ];

        $this->validate($request, $rules, $messages);
        try{

            $fechaactual=strtotime(date("d-m-Y",time()));
            $fechaini=strtotime($request->fecha_ini);

            if($fechaini < $fechaactual){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La fecha de inicio no puede ser mayor a la fecha actual'
                ]);
            }

            $guarda_tarea=new Tarea();
            $guarda_tarea->id_vehiculo=$request->vehiculo_tarea;
            $guarda_tarea->id_chofer=$request->choferSalvo;
            $guarda_tarea->fecha_inicio=$request->fecha_ini;
            $guarda_tarea->fecha_fin=$request->fecha_fin;
            $guarda_tarea->motivo=$request->motivo;
            $guarda_tarea->id_usuario_solicita=auth()->user()->id;
            $guarda_tarea->fecha_solicitud=date('Y-m-d H:i:s');
            $guarda_tarea->estado="Pendiente";

            //validar que el chofer no tenga tareas en esa fecha para otro vehiculo
            $valida_chofer=Tarea::where('id_chofer', $guarda_tarea->id_chofer)
            ->where('id_vehiculo', '!=',$guarda_tarea->id_vehiculo)
            ->where('fecha_inicio', $guarda_tarea->fecha_inicio)
            ->where('estado','Pendiente')
            ->first();

            if(!is_null($valida_chofer)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El chofer seleccionado ya tiene asociado una tarea pendiente, en otro vehículo'
                ]);
            }

           
            if($guarda_tarea->save()){
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
            Log::error('TareasController => guardar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }


    public function actualizar(Request $request, $id){
       
        $messages = [
            
            'vehiculo_tarea.required' => 'Debe seleccionar el vehículo',
            'choferSalvo.required' => 'Debe seleccionar el chofer',
            'fecha_ini.required' => 'Debe ingresar la fecha inicio',
            'motivo.required' => 'Debe ingresar el motivo',           
        ];
           

        $rules = [
            'vehiculo_tarea' => "required",
            'choferSalvo' => "required",
            'fecha_ini' => "required",
            'motivo' =>"required|string|max:500",
                     
        ];

        $this->validate($request, $rules, $messages);
        try{

            $actualiza_tarea= Tarea::find($id);
            $actualiza_tarea->id_vehiculo=$request->vehiculo_tarea;
            $actualiza_tarea->id_chofer=$request->choferSalvo;
            $actualiza_tarea->fecha_inicio=$request->fecha_ini;
            $actualiza_tarea->fecha_fin=$request->fecha_fin;
            $actualiza_tarea->motivo=$request->motivo;
            $actualiza_tarea->id_usuario_solicita=auth()->user()->id;
            $actualiza_tarea->fecha_solicitud=date('Y-m-d H:i:s');
            $actualiza_tarea->estado="Pendiente";

            //validar que el chofer no tenga tareas en esa fecha para otro vehiculo
            $valida_chofer=Tarea::where('id_chofer', $actualiza_tarea->id_chofer)
            ->where('id_vehiculo', '!=',$actualiza_tarea->id_vehiculo)
            ->where('fecha_inicio', $actualiza_tarea->fecha_inicio)
            ->where('estado','Pendiente')
            ->where('id_tarea','!=',$id)
            ->first();

            if(!is_null($valida_chofer)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El chofer seleccionado ya tiene asociado una tarea pendiente, en otro vehículo'
                ]);
            }

           
            if($actualiza_tarea->save()){
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
            Log::error('TareasController => actualizar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

    public function eliminar($id){
        try{
            $tarea=Tarea::find($id);
            $tarea->id_usuario_act=auth()->user()->id;
            $tarea->fecha_actualizacion=date('Y-m-d H:i:s');
            $tarea->estado="Eliminada";
            if($tarea->save()){
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
            Log::error('TareasController => eliminar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

}
