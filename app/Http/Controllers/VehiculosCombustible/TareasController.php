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

    public function actualizaTarea(){
        //recorremos todas las tareas que no ingresaron fecha final para actualizar temporalmente la fecha final a la actual
        $tareaSinFF=Tarea::where('estado','!=','Eliminada')
        ->where('fecha_fin_ing', 'N')
        ->update(["fecha_fin"=>date('Y-m-d')]);

        //recorremos todas las tareas que si ingresaron fecha final para actualizar el estado en caso de q esa fecha fin sea menor a la actual
        $tareaEstado=Tarea::where('estado','!=','Eliminada')
        ->where('fecha_fin_ing', 'S')
        ->where('fecha_fin', '<',date('Y-m-d') )
        ->update(["estado"=>'Finalizada']);
    }

    public function listar(){
        try{
            //comprobamos si hay tareas sin fecha final y actualizamos el estado en caso d q tenga fecha fin menor a la actual
            $comprobar=$this->actualizaTarea();

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

            //si no manda la fecha final guardamos la misma que la inicial mas otro campo para saber que la tarea no tiene fecha final ingresada
            if(is_null($request->fecha_fin)){
                $fecha_final=$request->fecha_ini;
                $fecha_fin_ing='N';
            }else{
                $fecha_final=$request->fecha_fin;
                $fecha_fin_ing='S';
            }

            $guarda_tarea=new Tarea();
            $guarda_tarea->id_vehiculo=$request->vehiculo_tarea;
            $guarda_tarea->id_chofer=$request->choferSalvo;
            $guarda_tarea->fecha_inicio=$request->fecha_ini;
            $guarda_tarea->fecha_fin=$fecha_final;
            $guarda_tarea->fecha_fin_ing=$fecha_fin_ing;
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

            //si no manda la fecha final guardamos la misma que la inicial mas otro campo para saber que la tarea no tiene fecha final ingresada
            if(is_null($request->fecha_fin)){
                $fecha_final=$request->fecha_ini;
                $fecha_fin_ing='N';
            }else{
                $fecha_final=$request->fecha_fin;
                $fecha_fin_ing='S';
            }

            $actualiza_tarea= Tarea::find($id);
            $actualiza_tarea->id_vehiculo=$request->vehiculo_tarea;
            $actualiza_tarea->id_chofer=$request->choferSalvo;
            $actualiza_tarea->fecha_inicio=$request->fecha_ini;
            $actualiza_tarea->fecha_fin=$fecha_final;
            $actualiza_tarea->fecha_fin_ing=$fecha_fin_ing;
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
            if($tarea->estado!="Pendiente"){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La tarea ya no se puede eliminar'
                ]);
            }
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
