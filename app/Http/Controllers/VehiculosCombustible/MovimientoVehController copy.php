<?php

namespace App\Http\Controllers\VehiculosCombustible;
use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\VehiculoCombustible\Vehiculo;
use App\Models\VehiculoCombustible\Tarea;
use App\Models\VehiculoCombustible\TipoMedicion;
use App\Models\VehiculoCombustible\Movimiento;
use App\Models\VehiculoCombustible\TareaMovimento;
use \Log;
use Illuminate\Http\Request;
use App\Http\Controllers\VehiculosCombustible\TareasController;

class MovimientoVehController extends Controller
{
    public function __construct() {
        try {       
            $this->objTareas = new TareasController();
                           
        } catch (\Throwable $e) {
            Log::error(__CLASS__." => ".__FUNCTION__." => Mensaje =>".$e->getMessage());
        }
    }

    public function index(){
        $persona=Persona::where('estado','A')->get();
        $vehiculo=Vehiculo::where('estado','A')->get();
      
        return view('combustible.patio',[
            "persona"=>$persona,
            "vehiculo"=>$vehiculo
        ]);
    }


    public function listar(){
        try{

            //comprobamos si hay tareas sin fecha final y actualizamos el estado en caso d q tenga fecha fin menor a la actual
            $comprobar=$this->objTareas->actualizaTarea();

            $mov=Movimiento::with('vehiculo','chofer')->where('estado','!=','Eliminada')->get();
            return response()->json([
                'error'=>false,
                'resultado'=>$mov
            ]);
        }catch (\Throwable $e) {
            Log::error('MovimientoVehController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function tareaVehiculo($id){
        try{
            $tarea=Tarea::where('estado','Pendiente')
            ->where('id_vehiculo', $id)
            ->WhereDate('fecha_inicio','<=',date('Y-m-d'))
            ->WhereDate('fecha_fin','>=',date('Y-m-d'))
            ->get();

            $medicion=Vehiculo::with('TipoMedicion')->where('id_vehiculo',$id)->first();

            $ultimoKm_Hm=Movimiento::where('id_vehiculo',$id)
            ->where('estado','!=','Eliminada')
            ->get()->last();
            
            return response()->json([
                'error'=>false,
                'resultado'=>$tarea,
                'medicion'=>$medicion,
                'ultimoKm_Hm'=>$ultimoKm_Hm
            ]);
        }catch (\Throwable $e) {
            Log::error('MovimientoVehController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }


    public function guardar(Request $request){
        dd($request->all());
        $messages = [
            
            'vehiculo_tarea.required' => 'Debe seleccionar el vehículo',
            'chofer.required' => 'Debe seleccionar el chofer',
            'motivo.required' => 'Debe ingresar el motivo',           
        ];
           

        $rules = [
            'vehiculo_tarea' => "required",
            'chofer' => "required",
            'entrada_salida' => "required",
            'motivo' =>"required|string|max:500",
                     
        ];

        $this->validate($request, $rules, $messages);
        try{
            $valorRecorrido=null;
            //cuando es entrada a patio calculamos el km o hm recorrido de ese vehiculo
            if($request->entrada_salida=="Entrada"){
                //ultimo km o hm
                $verValor=Movimiento::where('id_vehiculo', '=',$request->vehiculo_tarea)
                ->where('estado','Activo')
                ->where('entrada_salida','Salida')
                ->get()->last();
                if(!is_null($verValor)){
                    if(!is_null($request->kilometraje)){
                        $ultimoValor=$verValor->kilometraje;
                        $valor_actual=$request->kilometraje;
                    }else{
                        $ultimoValor=$verValor->horometro;
                        $valor_actual=$request->horometro;
                    } 
                    //calculamos el valor recorrido
                    $valorRecorrido=$valor_actual -  $ultimoValor;
                }else{
                    $valorRecorrido=null;
                }

            }
            $guarda_movi=new Movimiento();
            $guarda_movi->id_vehiculo=$request->vehiculo_tarea;
            $guarda_movi->id_chofer=$request->chofer;
            $guarda_movi->entrada_salida=$request->entrada_salida;
            $guarda_movi->observaciones=$request->observacion;
            $guarda_movi->firmaconductor=$request->b64_firma;

            if($guarda_movi->entrada_salida=="Entrada"){
                $guarda_movi->fecha_ingreso=date('Y-m-d');
                $guarda_movi->hora_entrada=date('H:i:s');
                $guarda_movi->fecha_hora_entrada=date('Y-m-d H:i:s');
            }else{
                $guarda_movi->fecha_salida=date('Y-m-d');
                $guarda_movi->hora_salida=date('H:i:s');           
                $guarda_movi->fecha_hora_salida=date('Y-m-d H:i:s');
            }
                
            $guarda_movi->kilometraje=$request->kilometraje;
            $guarda_movi->horometro=$request->horometro;
            $guarda_movi->km_hm_recorrido=$valorRecorrido;

            $guarda_movi->idusuarioregistra=auth()->user()->id;
            $guarda_movi->fecha_registro=date('Y-m-d H:i:s');
            $guarda_movi->estado="Activo";

            //validar el lugar del vehiculo
            $valida_lugar=Movimiento::where('id_vehiculo', '=',$guarda_movi->id_vehiculo)
            ->where('estado','Activo')
            ->get()->last();
           
            if(!is_null($valida_lugar)){
                if($valida_lugar->entrada_salida == $guarda_movi->entrada_salida){
                    if($valida_lugar->entrada_salida=="Entrada"){
                        $estado_luga="Dentro";
                    }else{
                        $estado_luga="Fuera";
                    }
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>'El vehículo ya se encuentra '.$estado_luga. ' del patio'
                    ]);
                }
            }else{
              
                //si es la primera vez obligamos que se empiece una salida de patio
                if($guarda_movi->entrada_salida == "Entrada"){
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>'El vehículo debe iniciar con una salida del patio'
                    ]);
                }
            }


           
            if($guarda_movi->save()){
                //guardamos las tareas asociados al vehiculo en el movimiento
                if(isset($request->tareasguard)){
                    if(sizeof($request->tareasguard)>0 ){
                        foreach($request->tareasguard as $tarea){
                            $tareaMov=new TareaMovimento();
                            $tareaMov->id_tarea=$tarea;
                            $tareaMov->id_movimiento=$guarda_movi->idmovimiento;
                            $tareaMov->save();
                        }
                    }
                }
                    
                    
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
            Log::error('MovimientoVehController => guardar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }


    public function eliminar($id){
        try{
            $mov=Movimiento::find($id);
            $mov->id_usuario_actualiza=auth()->user()->id;
            $mov->fecha_act=date('Y-m-d H:i:s');
            $mov->estado="Eliminada";
            if($mov->save()){
                //eliminamos las tareas movimientos
                $eliminaTareasMov=TareaMovimento::where('id_movimiento',$id);
                $eliminaTareasMov->delete();
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
            Log::error('MovimientoVehController => eliminar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

}
