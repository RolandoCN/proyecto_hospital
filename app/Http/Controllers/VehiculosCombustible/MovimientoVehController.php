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
use App\Models\VehiculoCombustible\DetalleDespacho;
use \Log;
use Storage;
use Illuminate\Http\Request;
use PDF;
use DB;
use SplFileInfo;
use App\Http\Controllers\VehiculosCombustible\TareasController;
use App\Http\Controllers\VehiculosCombustible\DespachoCombustibleController;
use App\Http\Controllers\VehiculosCombustible\JobController;

class MovimientoVehController extends Controller
{
    public function __construct() {
        try {       
            $this->objTareas = new TareasController();
            $this->objDespacho = new DespachoCombustibleController();
            $this->ordenes = new JobController();
                           
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

    public function obtenerSalidas($ini, $fin){
        try{

            $mov=Movimiento::with('vehiculo','chofer')->where('estado','!=','Eliminada')
            ->whereBetween('fecha_salida_patio',[$ini, $fin])
            ->orderby('fecha_salida_patio','asc')
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

    public function actualizaSalidas(){
        return view('combustible.actualiza_salidas');
    }

    public function actualizarSalidasMes($fecha){
        try{
            $separa=explode("-", $fecha);
            $anio=$separa[0];
            $mes=$separa[1];
            $mov=Movimiento::where('estado','!=','Eliminada')
            ->whereYear('fecha_salida_patio',$anio)
            ->whereMonth('fecha_salida_patio',$mes)
            ->orderby('fecha_salida_patio','asc')
            ->select('idmovimiento','nro_ticket','fecha_salida_patio')
            ->get();
            #agrupamos los despachos por departamento
            $lista_final_agrupada=[];
            foreach ($mov as $key => $item){                
                if(!isset($lista_final_agrupada[$item->nro_ticket])) {
                    $lista_final_agrupada[$item->nro_ticket]=array($item);
            
                }else{
                    array_push($lista_final_agrupada[$item->nro_ticket], $item);
                }
            }
          
            //actualizamos el codigo_orden segun la fecha
            $codigo=0;
            foreach($lista_final_agrupada as $data){
                $codigo=$codigo+1;
                foreach($data as $item){
                    $actualizaNumeroCod=Movimiento::where('idmovimiento',$item->idmovimiento)
                    ->first();
                    $actualizaNumeroCod->codigo_orden='HGNDC-'.$anio.'-'.$mes.'-'.sprintf("%'.05d",$codigo);
                    $actualizaNumeroCod->save();

                }

                //mandamos a actualizar las ordenes
                $actualizaOrden=$this->ordenes->guardarDetalleDespachoManual($data[0]->fecha_salida_patio);
                log::info("FECHA ORDENES A ACTUALIZAR".$data[0]->fecha_salida_patio);
              
            }
           
            return response()->json([
                'error'=>false,
                'resultado'=>"Informacion actualizada exitosamente"
            ]);
        }catch (\Throwable $e) {
            Log::error('MovimientoVehController => actualizarSalidasMes => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function tareaVehiculo($id){
        try{
           
            $tarea=[];

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

        // $exists_destino = Storage::disk('OrdenesCombustible')->exists($nombrePDF); 
        // if($exists_destino){   
        //     return response()->json([
        //         'error'=>false,
        //         'pdf'=>$nombrePDF
        //     ]);
        // }

        $ticket=Ticket::where('estado','A')
        ->where('numero_ticket',$numero_ticket )
        ->first();
        
        $crearpdf=PDF::loadView('combustible.reportes.pdf_movimiento',['datos'=>$movimiento,'fecha'=>$fecha, "ticket"=>$ticket]);
        $crearpdf->setPaper("A4", "landscape");

        // return $crearpdf->stream("ss.pdf");

        $estadoarch = $crearpdf->stream();
                        
        //lo guardamos en el disco temporal
        Storage::disk('OrdenesCombustible')->put(str_replace("", "",$nombrePDF), $estadoarch);
        $exists_destino = Storage::disk('OrdenesCombustible')->exists($nombrePDF); 
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
            return Storage::disk('public')->download($archivo);
            //return response()->download( storage_path('app/public/'.$archivo))->deleteFileAfterSend(true);
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

            // $data=Movimiento::where(function($query)use($text){
            //     $query->where('nro_ticket', 'like', '%'.$text.'%');
            // })
            // ->where('estado','Activo')
            // ->distinct()
            // ->take(10)->get();

            $data=DB::table('vc_movimiento')->where(function($query)use($text){
                $query->where('nro_ticket', 'like', '%'.$text.'%');
            })
            ->select('nro_ticket')
            ->where('estado','Activo')
            ->distinct()
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
        $transaction=DB::transaction(function() use($request){ 
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
                ->where('id_chofer','!=',auth()->user()->id_persona)
                ->first();
                if(!is_null($existe_ticket)){
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>'El número de ticket ya se encuentra asociado a otra salida'
                    ]);
                }

                $fecha_salida=date('Y-m-d 00:00:00',strtotime($request->fecha_h_salida_patio));
                $fecha_llega=date('Y-m-d 23:59:59',strtotime($request->fecha_h_llegada_patio));


                //validar que la fecha de salida este dentro del rango de despacho ticket
                $valida_rango=Ticket::where('numero_ticket',$request->n_ticket)
                ->where('estado','A')
                ->whereBetween('f_despacho', [$fecha_salida, $fecha_llega])
                ->first(); 

                if(is_null($valida_rango)){
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>'La fecha de despacho del ticket, esta fuera del rango de fecha del movimiento ingresado'
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
                        'mensaje'=>'El chofer seleccionado no esta asociado al ticket. #'.$valida_rango->numero_ticket
                    ]);
                }
                
                if(strtotime($request->fecha_h_llegada_patio) <= strtotime($request->fecha_h_destino_salida)){
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>'La fecha hora llegada a patio no puede ser menor o igual a la fecha hora salida de destino.'
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

                // $guarda_movi->firmaconductor=$request->b64_firma;
            
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
                    // return response()->json([
                    //     'error'=>true,
                    //     'mensaje'=>'El vehículo se encuentra asociado a un movimiento en el rango de fecha seleccionadoxx'
                    // ]);
                }


                $verificaFecha2=Movimiento::where(function($c)use($hora_fecha_sale,$hora_fecha_llega) {
                    $c->where('fecha_hora_salida_patio', '<=', $hora_fecha_sale)
                    ->where('fecha_hora_llega_patio','>=', $hora_fecha_llega);
                })
            
                ->where('id_vehiculo',$guarda_movi->id_vehiculo)
                ->where('estado','!=','Eliminada')
                ->first();
                if(!is_null($verificaFecha)){
                    DB::Rollback();
                    // return response()->json([
                    //     'error'=>true,
                    //     'mensaje'=>'El vehículo se encuentra asociado a un movimiento en el rango de fecha seleccionado'
                    // ]);
                }
                
                $cod=null;
                $ultimoCod=Movimiento::where('estado','!=','Eliminada')
                ->get()->last();
            
                if(!is_null($ultimoCod)){

                    if(is_null($ultimoCod->codigo_orden)){
                        $codi=1;
                        $cod='HGNDC-'.date('Y').'-'.date('m').'-'.sprintf("%'.05d",$codi);
                    }else{
                        //si es enero y el primero del año, reseteamos a 1
                        $verifica_codigo=$ultimoCod->codigo_orden;
                        
                        $separa=explode('-', $verifica_codigo);
                    
                        $anio=$separa[1];
                        if(date('m')==1 && date('Y')!=$anio){
                            $codi=1;
                            $cod='HGNDC-'.date('Y').'-'.date('m').'-'.sprintf("%'.05d",$codi);
                        }else{
                            //continuamos la secuencia
                            $codigo=$ultimoCod->codigo_orden;  
                            $codigo=explode('-', $codigo);
                            $codigo=$codigo[3]+1;  
                            $cod='HGNDC-'.date('Y').'-'.date('m').'-'.sprintf("%'.05d",$codigo);
                        }
                    }
                  
    
                }else{
                    //si no existe
                    $codi=1;
                    $cod='HGNDC-'.date('Y').'-'.date('m').'-'.sprintf("%'.05d",$codi);
                }
                
                if(is_null($cod)){
                    DB::Rollback();
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>'No se pudo generar el codigo de orden'
                    ]);
                }
                    
                if($guarda_movi->save()){

                
                    $movim=Movimiento::where('idmovimiento',$guarda_movi->idmovimiento)->first();
                    $movim->codigo_orden=$cod;
                    $movim->save();

                    // $generaPdf=$this->reporteIndividual($movim->idmovimiento);

                    //verificamos si ya se registro el detalle despacho con ese ticket
                    $detalle=DB::table('vc_detalle_despacho')
                    ->where('num_factura_ticket',$movim->nro_ticket)
                    ->where('estado','Aprobado')
                    ->first();
                    if(!is_null($detalle)){
                        $actualizaOrden=$this->objDespacho->pdfOrden($detalle->idcabecera_despacho,$detalle->num_factura_ticket, $detalle->iddetalle_despacho, 'N');
                    }
                        
                    return response()->json([
                        'error'=>false,
                        'mensaje'=>'Información registrada exitosamente',
                        "idmovimiento"=>$movim->idmovimiento
                    ]);
                }else{
                    DB::Rollback();
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>'No se pudo registrar la información'
                    ]);
                }




            }catch (\Throwable $e) {
                DB::Rollback();    
                Log::error('MovimientoVehController => guardar => mensaje => '.$e->getMessage(). ' Linea => '.$e->getLine());
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'Ocurrió un error'
                ]);
                
            }
        });
        return $transaction;
    }

    public function eliminar($id){
        try{
            
            $mov=Movimiento::find($id);
            //verificamos que no se haya generado despacho
            $despacho=DetalleDespacho::where('num_factura_ticket',$mov->nro_ticket)
            ->where('estado','Aprobado')->first();
           
            if(!is_null($despacho)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'Ya existen despacho generado con esta orden'
                ]);
            }
            
            $mov->id_usuario_actualiza=auth()->user()->id;
            $mov->fecha_act=date('Y-m-d H:i:s');
            $mov->estado="Eliminada";
            if($mov->save()){
                //eliminamos el archivo
                Storage::disk('OrdenesCombustible')
                ->delete("movimiento_".$id.".pdf"); 

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

    public function editar($id){
        try{
            
            $mov=Movimiento::find($id);
             
            return response()->json([
                'error'=>false,
                'resultado'=>$mov
            ]);           
               
        }catch (\Throwable $e) {
            Log::error('MovimientoVehController => editar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

    public function actualizar(Request $request, $id){
        // dd($request->all());
        $messages = [
            
            'vehiculo_tarea.required' => 'Debe seleccionar el vehículo',
            'motivo.required' => 'Debe ingresar el motivo',           
        ];

        $rules = [
            'vehiculo_tarea' => "required",                      
            'motivo' =>"required|string|max:500",
                     
        ];

        $this->validate($request, $rules, $messages);
        $transaction=DB::transaction(function() use($request, $id){ 
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
                ->where('id_chofer','!=',auth()->user()->id_persona)
                ->where('idmovimiento','!=',$id)
                ->first();
                if(!is_null($existe_ticket)){
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>'El número de ticket ya se encuentra asociado a otra salida'
                    ]);
                }

                $fecha_salida=date('Y-m-d 00:00:00',strtotime($request->fecha_h_salida_patio));
                $fecha_llega=date('Y-m-d 23:59:59',strtotime($request->fecha_h_llegada_patio));

                //validar que la fecha de salida este dentro del rango de despacho ticket
                $valida_rango=Ticket::where('numero_ticket',$request->n_ticket)
                ->where('estado','A')
                ->whereBetween('f_despacho', [$fecha_salida, $fecha_llega])
                ->first(); 

                if(is_null($valida_rango)){
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>'La fecha de despacho del ticket, esta fuera del rango de fecha del movimiento ingresado'
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
                        'mensaje'=>'El chofer seleccionado no esta asociado al ticket. #'.$valida_rango->numero_ticket
                    ]);
                }
                
                if(strtotime($request->fecha_h_llegada_patio) <= strtotime($request->fecha_h_destino_salida)){
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>'La fecha hora llegada a patio no puede ser menor o igual a la fecha hora salida de destino.'
                    ]);
                }

                $actualiza_movi= Movimiento::find($id);
                $actualiza_movi->id_vehiculo=$request->vehiculo_tarea;
                $actualiza_movi->id_chofer=auth()->user()->id_persona;
                $actualiza_movi->motivo=$request->motivo;
                $actualiza_movi->persona_solicita=$request->solicitante;
                $actualiza_movi->id_area_solicita=$request->area_sol;
                $actualiza_movi->acompanante=$request->acompanante;
                $actualiza_movi->nro_ticket=$request->n_ticket;
                $actualiza_movi->tiene_novedad=$request->tiene_novedad;
                $actualiza_movi->novedad=$request->txt_novedad;
                $actualiza_movi->id_autorizado_salida=$request->autorizado;

                $actualiza_movi->lugar_salida_patio="Chone";
                $actualiza_movi->km_salida_patio=$request->km_salida_patio;
                $actualiza_movi->fecha_salida_patio=date('Y-m-d',strtotime($request->fecha_h_salida_patio));
                $actualiza_movi->hora_salida_patio=date('H:i:s',strtotime($request->fecha_h_salida_patio));
                $actualiza_movi->fecha_hora_salida_patio=date('Y-m-d H:i:s',strtotime($request->fecha_h_salida_patio));

                $actualiza_movi->lugar_llegada_destino=$request->l_destino_ll;
                $actualiza_movi->km_llegada_destino=$request->km_destino_ll;
                $actualiza_movi->fecha_llega_destino=date('Y-m-d',strtotime($request->fecha_h_destino));
                $actualiza_movi->hora_llega_destino=date('H:i:s',strtotime($request->fecha_h_destino));
                $actualiza_movi->fecha_hora_llega_destino=date('Y-m-d H:i:s',strtotime($request->fecha_h_destino));

                $actualiza_movi->km_salida_destino=$request->km_salida_dest;
                $actualiza_movi->fecha_salida_destino=date('Y-m-d',strtotime($request->fecha_h_destino_salida));
                $actualiza_movi->hora_salida_destino=date('H:i:s',strtotime($request->fecha_h_destino_salida));
                $actualiza_movi->fecha_hora_salida_destino=date('Y-m-d H:i:s',strtotime($request->fecha_h_destino_salida));

                $actualiza_movi->km_llegada_patio=$request->km_llegada_patio;
                $actualiza_movi->fecha_llega_patio=date('Y-m-d',strtotime($request->fecha_h_llegada_patio));
                $actualiza_movi->hora_llega_patio=date('H:i:s',strtotime($request->fecha_h_llegada_patio));
                $actualiza_movi->fecha_hora_llega_patio=date('Y-m-d H:i:s',strtotime($request->fecha_h_llegada_patio));

                // $guarda_movi->firmaconductor=$request->b64_firma;
            
                $actualiza_movi->kilometraje=$request->kilometraje;
                $actualiza_movi->horometro=$request->horometro;
                $actualiza_movi->km_hm_recorrido=$valorRecorrido;

                $actualiza_movi->idusuarioregistra=auth()->user()->id;
                $actualiza_movi->fecha_registro=date('Y-m-d H:i:s');
                $actualiza_movi->estado="Activo";


                $cod=null;
                $ultimoCod=Movimiento::where('estado','!=','Eliminada')
                ->get()->last();
            
                if(!is_null($ultimoCod)){

                    if(is_null($ultimoCod->codigo_orden)){
                        $codi=1;
                        $cod='HGNDC-'.date('Y').'-'.date('m').'-'.sprintf("%'.05d",$codi);
                    }else{
                        //si es enero y el primero del año, reseteamos a 1
                        $verifica_codigo=$ultimoCod->codigo_orden;
                        
                        $separa=explode('-', $verifica_codigo);
                    
                        $anio=$separa[1];
                        if(date('m')==1 && date('Y')!=$anio){
                            $codi=1;
                            $cod='HGNDC-'.date('Y').'-'.date('m').'-'.sprintf("%'.05d",$codi);
                        }else{
                            //continuamos la secuencia
                            $codigo=$ultimoCod->codigo_orden;  
                            $codigo=explode('-', $codigo);
                            $codigo=$codigo[3]+1;  
                            $cod='HGNDC-'.date('Y').'-'.date('m').'-'.sprintf("%'.05d",$codigo);
                        }
                    }
                  
    
                }else{
                    //si no existe
                    $codi=1;
                    $cod='HGNDC-'.date('Y').'-'.date('m').'-'.sprintf("%'.05d",$codi);
                }
                
                if(is_null($cod)){
                    DB::Rollback();
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>'No se pudo generar el codigo de orden'
                    ]);
                }
                    
                if($actualiza_movi->save()){

                
                    $movim=Movimiento::where('idmovimiento',$actualiza_movi->idmovimiento)->first();
                    $movim->codigo_orden=$cod;
                    $movim->save();

                    // $generaPdf=$this->reporteIndividual($movim->idmovimiento);

                    //verificamos si ya se registro el detalle despacho con ese ticket
                    $detalle=DB::table('vc_detalle_despacho')
                    ->where('num_factura_ticket',$movim->nro_ticket)
                    ->where('estado','Aprobado')
                    ->first();
                    if(!is_null($detalle)){
                        $actualizaOrden=$this->objDespacho->pdfOrden($detalle->idcabecera_despacho,$detalle->num_factura_ticket, $detalle->iddetalle_despacho, 'N');
                    }
                        
                    return response()->json([
                        'error'=>false,
                        'mensaje'=>'Información actualizada exitosamente',
                        "idmovimiento"=>$movim->idmovimiento
                    ]);
                }else{
                    DB::Rollback();
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>'No se pudo actualizar la información'
                    ]);
                }




            }catch (\Throwable $e) {
                DB::Rollback();    
                Log::error('MovimientoVehController => actualizar => mensaje => '.$e->getMessage(). ' Linea => '.$e->getLine());
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'Ocurrió un error'
                ]);
                
            }
        });
        return $transaction;
    }

}
