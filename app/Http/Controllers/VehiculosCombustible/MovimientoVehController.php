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
use Storage;
use Illuminate\Http\Request;
use PDF;
use SplFileInfo;
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

        if(sizeof($movimiento)==0){
           
            return response()->json([
                'error'=>true,
                'mensaje'=>'No se encontró infomación'
            ]);
        }
        
        $fecha=date('Y-m-d',strtotime($movimiento[0]->fecha_salida_patio));
       
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp"); //IDIOMA ESPAÑOL
                $fecha= $fecha;
                $fecha = strftime("%d de %B de %Y", strtotime($fecha));

        
        $nombrePDF="movimiento_".$movimiento[0]->idmovimiento.".pdf"; 
        
        $crearpdf=PDF::loadView('combustible.reportes.pdf_movimiento',['datos'=>$movimiento,'fecha'=>$fecha]);
        $crearpdf->setPaper("A4", "landscape");

        $estadoarch = $crearpdf->stream();
                        
        //lo guardamos en el disco temporal
        Storage::disk('public')->put(str_replace("", "",$nombrePDF), $estadoarch);
        $exists_destino = Storage::disk('public')->exists($nombrePDF); 
        if($exists_destino){   
            return response()->json([
                'error'=>false,
                'pdf'=>$nombrePDF
            ]);
        }else{
            return response()->json([
                'error'=>true,
                'mensaje'=>'No se pudo crear el documento'
            ]);
        }

        
    }


    
    //funcion que permite visualizar un documento selecciondo
    public function visualizarDocumento($documentName){
        try {
            //obtenemos la extension
            $info = new SplFileInfo($documentName);
            $extension = $info->getExtension();
            if($extension!= "pdf" && $extension!="PDF"){
                return \Storage::disk('public')->download($documentName);
            }else{
                // obtenemos el documento del disco en base 64
                $documentEncode= base64_encode(\Storage::disk('public')->get($documentName));
                return view("vistaPrevia")->with([
                    "documentName"=>$documentName,
                    "documentEncode"=>$documentEncode
                ]);        
            }            
        }   catch (\Throwable $th) {
            Log::error("ProcesosIniciadosController => visualizarDocumento => Mensaje: ".$th->getMessage());
            abort("404");            
        }

    }

    public function descargar($archivo)
    {
        
        $exists = Storage::disk('public')
        ->exists($archivo);       
        if($exists){
            return Storage::disk('public')
            ->download($archivo);
        }else{
            return back()->with(['error'=>'No se pudo descargar el archivo','estadoP'=>'danger']);
        }
      
    }
    public function buscarTicket(Request $request){
        log::info($request->all());
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $text=mb_strtoupper($search);
            $data=Movimiento::where(function($query)use($text){
                $query->where('nro_ticket', 'like', '%'.$text.'%');
            })
            ->take(10)->get();
        }
        
        return response()->json($data);

    }

    public function ticketVehiculo($nro){
        $data=Movimiento::where('nro_ticket',$nro)->first();
        return response()->json([
            'error'=>false,
            'data'=>$data
        ]);

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
            $guarda_movi->area=$request->solicitante;
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
            // $valida_lugar=Movimiento::where('id_vehiculo', '=',$guarda_movi->id_vehiculo)
            // ->where('estado','Activo')
            // ->get()->last();

            //comprobamos que el vehiculo no se encuentre ocupado en el rango de fecha
            $salida=$guarda_movi->fecha_salida_patio;
            $llegada=$guarda_movi->fecha_llega_patio;
            $verificaVeh=Movimiento::where(function($c)use($salida,$llegada) {
                $c->WhereDate('fecha_salida_patio','>=',$salida)
                ->Where('fecha_llega_patio', '<=', $llegada);
            })
            ->first();
            if(!is_null($verificaVeh)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El vehículo se encuentra asociado a un movimiento en el rango de fecha seleccionado'
                ]);
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
