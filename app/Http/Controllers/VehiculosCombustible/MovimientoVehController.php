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
use PDF;
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
            "vehiculo"=>$vehiculo,
    
        ]);
    }


    public function listar(){
        try{

            //comprobamos si hay tareas sin fecha final y actualizamos el estado en caso d q tenga fecha fin menor a la actual
            $comprobar=$this->objTareas->actualizaTarea();

            $mov=Movimiento::with('vehiculo','chofer')->where('estado','!=','Eliminada')
            ->where('id_chofer', auth()->user()->id_persona)
            ->get();
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

    public function reporteIndividual($id){
      
        $movimiento = Movimiento::with('vehiculo','chofer')
        ->where('idmovimiento',$id)->where('estado','Activo')
        ->get();

        if(is_null($movimiento)){
            return back()->with(['mensajePInfoDespacho'=>'Aún no se han registrado despachos aprobados','estadoP'=>'danger']);
        }
        
        $fechaw=date('H:i:s',strtotime($movimiento[0]->fecha_salida_patio));
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp"); //IDIOMA ESPAÑOL
                $fecha= $fechaw;
                $fecha = strftime("%d de %B de %Y", strtotime($fecha));

        
        $nombre="movimiento"; 
        
        $crearpdf=PDF::loadView('combustible.reportes.pdf_movimiento',['datos'=>$movimiento,'fecha'=>$fecha]);
        $crearpdf->setPaper("A4", "landscape");

        return $crearpdf->stream($nombre."_".date('YmdHis').'.pdf');
    }

    public function guardar(Request $request){
       
        $messages = [
            
            'vehiculo_tarea.required' => 'Debe seleccionar el vehículo',
            'motivo.required' => 'Debe ingresar el motivo',           
        ];
           

        $rules = [
            'vehiculo_tarea' => "required",
                      
            'motivo' =>"required|string|max:500",
                     
        ];

        $this->validate($request, $rules, $messages);
        try{
            $valorRecorrido=null;
            
            //ultimo km o hm
            
            if(!is_null($request->kilometraje)){
                //calculamos el valor recorrido
                $valorRecorrido=$request->km_llegada_patio -  $request->km_salida_patio;
           
            }else{
               //calculamos el valor recorrido
               $valorRecorrido=$request->km_llegada_patio -  $request->km_salida_patio;
            } 
           

            $guarda_movi=new Movimiento();
            $guarda_movi->id_vehiculo=$request->vehiculo_tarea;
            $guarda_movi->id_chofer=auth()->user()->id_persona;
            $guarda_movi->motivo=$request->motivo;
            $guarda_movi->acompanante=$request->acompanante;
            $guarda_movi->nro_ticket=$request->n_ticket;

            $guarda_movi->lugar_salida_patio="Chone";
            $guarda_movi->km_salida_patio=$request->km_salida_patio;
            $guarda_movi->fecha_salida_patio=date('Y-m-d',strtotime($request->fecha_h_salida_patio));
            $guarda_movi->hora_salida_patio=date('H:i:s',strtotime($request->fecha_h_salida_patio));
            $guarda_movi->fecha_hora_salida_patio=date('Y-m-d H:i:s',strtotime($request->fecha_h_salida_patio));

            $guarda_movi->lugar_llegada_destino=$request->l_destino_ll;
            $guarda_movi->km_llegada_destino=$request->km_destino_ll;
            $guarda_movi->fecha_llega_destino=date('Y-m-d',strtotime($request->fecha_h_destino));
            $guarda_movi->hora_llega_destino=date('H:i:s',strtotime($request->fecha_h_destino));
            $guarda_movi->fecha_hora_llega_destino=date('Y-m-d H:i:s',strtotime($request->fecha_h_destino));

            $guarda_movi->km_salida_destino=$request->km_salida_dest;
            $guarda_movi->fecha_salida_destino=date('Y-m-d',strtotime($request->fecha_h_destino_salida));
            $guarda_movi->hora_salida_destino=date('H:i:s',strtotime($request->fecha_h_destino_salida));
            $guarda_movi->fecha_hora_salida_destino=date('Y-m-d H:i:s',strtotime($request->fecha_h_destino_salida));

            $guarda_movi->km_llegada_patio=$request->km_llegada_patio;
            $guarda_movi->fecha_llega_patio=date('Y-m-d',strtotime($request->fecha_h_llegada_patio));
            $guarda_movi->hora_llega_patio=date('H:i:s',strtotime($request->fecha_h_llegada_patio));
            $guarda_movi->fecha_hora_llega_patio=date('Y-m-d H:i:s',strtotime($request->fecha_h_llegada_patio));

            $guarda_movi->firmaconductor=$request->b64_firma;

            
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
