<?php

namespace App\Http\Controllers\VehiculosCombustible;
use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\VehiculoCombustible\Vehiculo;
use App\Models\VehiculoCombustible\Ticket;
use App\Models\VehiculoCombustible\Tarea;
use App\Models\VehiculoCombustible\TipoMedicion;
use App\Models\VehiculoCombustible\Movimiento;
use App\Models\VehiculoCombustible\TareaMovimento;
use \Log;
use Storage;
use Illuminate\Http\Request;
use PDF;
use DB;
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
        $vehiculo=Vehiculo::where('estado','A')
        ->where('estado_vehiculo','Operativo')
        ->get();

        $autorizado=DB::table('vc_autorizado_salida')
        ->where('estado','A')
        ->where('estado_autoriza','Activo')
        ->get();

        $area=DB::table('vc_area')
        ->where('estado','A')
        ->get();
       
        return view('combustible.patio',[
            "persona"=>$persona,
            "vehiculo"=>$vehiculo,
            "autorizado"=>$autorizado,
            "area"=>$area,
        ]);
    }


    public function listar(){
        try{

            //comprobamos si hay tareas sin fecha final y actualizamos el estado en caso d q tenga fecha fin menor a la actual
            // $comprobar=$this->objTareas->actualizaTarea();

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

    public function vistaSalidas(){
        return view('combustible.salidas');
    }

    public function obtenerSalidas(){
        try{

            $mov=Movimiento::with('vehiculo','chofer')->where('estado','!=','Eliminada')
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
      
        $movimiento = Movimiento::with('vehiculo','chofer', 'autoriza')
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
        $numero_ticket=$movimiento[0]->nro_ticket;

        $ticket=Ticket::where('estado','A')
        ->where('numero_ticket',$numero_ticket )
        ->first();
        
        $crearpdf=PDF::loadView('combustible.reportes.pdf_movimiento',['datos'=>$movimiento,'fecha'=>$fecha, "ticket"=>$ticket]);
        $crearpdf->setPaper("A4", "landscape");

        // return $crearpdf->stream("ss.pdf");

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

    public function descargarElimina($archivo)
    {
        
        $exists = Storage::disk('public')
        ->exists($archivo);       
        if($exists){
            // return Storage::disk('public')->download($archivo);
            return response()->download( storage_path('app/public/'.$archivo))->deleteFileAfterSend(true);
        }else{
            return back()->with(['error'=>'No se pudo descargar el archivo','estadoP'=>'danger']);
        }
      
    }

    public function descargar($archivo)
    {
        
        $exists = Storage::disk('public')
        ->exists($archivo);       
        if($exists){
            return Storage::disk('public')->download($archivo);
        }else{
            return back()->with(['error'=>'No se pudo descargar el archivo','estadoP'=>'danger']);
        }
      
    }
    public function buscarTicket(Request $request){
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
        $infoTicket=DB::table('vc_ticket')->where('numero_ticket', $nro)->first();
        return response()->json([
            'error'=>false,
            'data'=>$data,
            'infoTicket'=>$infoTicket
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
            
            if($request->tiene_novedad=="Si"){
                if(is_null($request->txt_novedad)){
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>'Ingrese la descripción de la novedad'
                    ]);
                }
            }

            //validar que no se repita el numero de ticket
            $existe_ticket=Movimiento::where('estado','!=','Eliminada')
            ->where('nro_ticket', $request->n_ticket)
            ->first();
            if(!is_null($existe_ticket)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El número de ticket ya se encuentra asociado a otra salida'
                ]);
            }

            //validar que la fecha de salida este dentro del rango de despacho ticket
            $valida_rango=Ticket::where('numero_ticket',$request->n_ticket)
            ->where('estado','A')
            ->whereBetween('f_despacho', [$request->fecha_h_salida_patio, $request->fecha_h_llegada_patio])
            ->first();
           
            if(is_null($valida_rango)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La hora y fecha de despacho del ticket, esta fuera del rango de fecha del movimiento ingresado'
                ]);
            }
            if($valida_rango->id_vehiculo!=$request->vehiculo_tarea){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El vehículo seleccionado no esta asociado al ticket #'.$valida_rango->numero_ticket
                ]);
            }

            if($valida_rango->idchofer!=auth()->user()->id_persona){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El chofer seleccionado no esta asociado al ticket #'.$valida_rango->numero_ticket
                ]);
            }
            

            $guarda_movi=new Movimiento();
            $guarda_movi->id_vehiculo=$request->vehiculo_tarea;
            $guarda_movi->id_chofer=auth()->user()->id_persona;
            $guarda_movi->motivo=$request->motivo;
            $guarda_movi->persona_solicita=$request->solicitante;
            $guarda_movi->id_area_solicita=$request->area_sol;
            $guarda_movi->acompanante=$request->acompanante;
            $guarda_movi->nro_ticket=$request->n_ticket;
            $guarda_movi->tiene_novedad=$request->tiene_novedad;
            $guarda_movi->novedad=$request->txt_novedad;
            $guarda_movi->id_autorizado_salida=$request->autorizado;

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

                      
            $hora_fecha_sale=$guarda_movi->fecha_hora_salida_patio;
            $hora_fecha_llega=$guarda_movi->fecha_hora_llega_patio;
            $verificaFecha=Movimiento::where(function($c)use($hora_fecha_sale,$hora_fecha_llega) {
                $c->whereBetween('fecha_hora_salida_patio',[$hora_fecha_sale, $hora_fecha_llega])
                ->orwhereBetween('fecha_hora_llega_patio', [$hora_fecha_sale, $hora_fecha_llega]);
            })
            ->where('id_vehiculo',$guarda_movi->id_vehiculo)
            ->where('estado','!=','Eliminada')
            ->first();
            if(!is_null($verificaFecha)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El vehículo se encuentra asociado a un movimiento en el rango de fecha seleccionado'
                ]);
            }

            $verificaFecha2=Movimiento::where(function($c)use($hora_fecha_sale,$hora_fecha_llega) {
                $c->where('fecha_hora_salida_patio', '<=', $hora_fecha_sale)
                ->where('fecha_hora_llega_patio','>=', $hora_fecha_llega);
            })
         
            ->where('id_vehiculo',$guarda_movi->id_vehiculo)
            ->where('estado','!=','Eliminada')
            ->first();
            if(!is_null($verificaFecha)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El vehículo se encuentra asociado a un movimiento en el rango de fecha seleccionado'
                ]);
            }


            $ultimoCod=Movimiento::where('estado','!=','Eliminada')
            ->get()->last();
            // dd($ultimoCod);
            if(!is_null($ultimoCod)){
                $codigo=$ultimoCod->codigo_orden;  
                $codigo=explode('-', $codigo);
                $codigo=$codigo[1]+1;  
                $cod='HGNDV-'.sprintf("%'.05d",$codigo);
            }else{
                $codi=1;
                $cod='HGNDV-'.sprintf("%'.05d",$codi);
            }
         
                
            if($guarda_movi->save()){

              
                $movim=Movimiento::where('nro_ticket',$request->n_ticket)->first();
                $movim->codigo_orden=$cod;
                $movim->save();

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
            Log::error('MovimientoVehController => guardar => mensaje => '.$e->getMessage(). ' Linea => '.$e->getLine());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }


    public function eliminar($id){
        try{
            //verificamos que no se haya generado despacho
            $mov=Movimiento::find($id);
            if(!is_null($mov->codigo_orden)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'Ya existen despacho generado con esta orden'
                ]);
            }
            
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
