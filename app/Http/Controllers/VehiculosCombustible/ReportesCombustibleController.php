<?php

namespace App\Http\Controllers\VehiculosCombustible;
use App\Http\Controllers\Controller;
use App\Models\VehiculoCombustible\ReportesFormularios;
use App\Models\VehiculoCombustible\DetalleDespacho;
use App\Models\VehiculoCombustible\OrdenesCombustible;
use \Log;
use Illuminate\Http\Request;
use DB;
use PDF;
use Storage;
class ReportesCombustibleController extends Controller
{
      
    //vista para generar despacho por departamento con los formularios
    public function index(){
        try{
            $departamento=DB::table('vc_departamento')
            ->join('vc_vehiculo','vc_vehiculo.id_departamento','=','vc_departamento.iddepartamento')
            ->where('vc_vehiculo.estado','A')
            ->distinct('vc_departamento.iddepartamento')
            ->select('vc_departamento.iddepartamento as id','vc_departamento.descripcion as departamento')
            ->get();
            
            return view('combustible.vistaDespachoFormulario',[
                "departamento"=>$departamento
            ]);
        } catch (\Throwable $e) {
            Log::error(__CLASS__." => ".__FUNCTION__." => Mensaje =>".$e->getMessage());
            return back();
        }
    }

    public function listado(){
        try{
            $reportes=ReportesFormularios::where('estado','!=','Elimininado')->get();
            return response()->json([
                'error'=>false,
                'resultado'=>$reportes
            ]);
        }catch (\Throwable $e) {
            Log::error('MenuController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    //consulta y genera el documento
    public function guardar(Request $request){
        $transaction=DB::transaction(function() use ($request){
            try{
                $fecha_ini=$request->fecha_ini;
                $fecha_fin=$request->fecha_fin;
                $departamento=$request->departamento;
                        
                if($fecha_ini==null || $fecha_fin==null){  
                
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>'No se pudo acceder la información, complete todos los datos del formulario'
                    ]);                             
                    
                }

                $detalle = DetalleDespacho::with('vehiculo','tipocombustible','cabecera','chofer')
                ->whereHas('vehiculo', function ($query) use ($departamento){
                    $query->whereIn('id_departamento',$departamento);
                })
                ->whereBetween('fecha_cabecera_despacho', [$fecha_ini, $fecha_fin])->where('estado','Aprobado')
                ->orderBy('id_vehiculo', 'asc')->get();

                if(sizeof($detalle)==0){
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>"No se encontró datos, con la información seleccionada" 
                    ]);
                }

                #agrupamos los despachos por departamento
                $lista_final_agrupada=[];
                foreach ($detalle as $key => $item){                
                    if(!isset($lista_final_agrupada[$item->vehiculo->id_departamento])) {
                        $lista_final_agrupada[$item->vehiculo->id_departamento]=array($item);
                
                    }else{
                        array_push($lista_final_agrupada[$item->vehiculo->id_departamento], $item);
                    }
                }

               
                if($request->formulario=="F6"){
                   
                    $nombrePDF="despachoCombustibleDepartamento6_".date('YmdHis').".pdf";// $nombrePDF  
                 
                    //creamos el objeto
                    
                    $crearpdf=PDF::loadView('combustible.reportes.reporteForm6',['datos'=>$lista_final_agrupada,'detalle'=>$detale=[],'desde'=>$fecha_ini,'hasta'=>$fecha_fin]);
                    $crearpdf->setPaper("A4", "landscape");
        
                    $estadoarch = $crearpdf->stream();

                        
                    //lo guardamos en el disco temporal
                    Storage::disk('public')->put(str_replace("", "",$nombrePDF), $estadoarch);
                    $exists_destino = Storage::disk('public')->exists($nombrePDF); 
                    if($exists_destino){    
                        $fecha_ini=date('d-m-Y',strtotime($fecha_ini));
                        $fecha_fin=date('d-m-Y',strtotime($fecha_fin));
                        $guadarDatos=new ReportesFormularios();
                        $guadarDatos->formulario="Formulario 6";
                        $guadarDatos->fecha_generacion=date('Y-m-d H:i:s');
                        $guadarDatos->usuario_genera= auth()->user()->id;    
                        $guadarDatos->descripcion="Informe General desde ".$fecha_ini. " hasta ".$fecha_fin;
                        $guadarDatos->ruta=$nombrePDF;
                        $guadarDatos->estado="Generado";
                        $guadarDatos->save(); 

                        return response()->json([
                            'error'=>false,
                            'mensaje'=>"Informe creado exitosamente"
                        ]);

                    }else{
                        return response()->json([
                            'error'=>true,
                            'mensaje'=>'No se pudo crear el documento'
                        ]);
                    }
                }else{
                   
                    $nombrePDF="despachoCombustibleDepartamento7_".date('YmdHis').".pdf";// $nombrePDF  
                                 
                    // enviamos a la vista para crear el documento que los datos repsectivos
                    $crearpdf=PDF::loadView('combustible.reportes.reporteForm7',['datos'=>$lista_final_agrupada,'detalle'=>$detale=[],'desde'=>$fecha_ini,'hasta'=>$fecha_fin]);
                    $crearpdf->setPaper("A4", "landscape");

                    // return $crearpdf->stream("asa.pdf");

                    $estadoarch = $crearpdf->stream();
                        
                    //lo guardamos en el disco temporal
                    Storage::disk('public')->put(str_replace("", "",$nombrePDF), $estadoarch);
                    $exists_destino = Storage::disk('public')->exists($nombrePDF); 
                    if($exists_destino){    
                        $fecha_ini=date('d-m-Y',strtotime($fecha_ini));
                        $fecha_fin=date('d-m-Y',strtotime($fecha_fin));
                        $guadarDatos=new ReportesFormularios();
                        $guadarDatos->formulario="Formulario 7";
                        $guadarDatos->fecha_generacion=date('Y-m-d H:i:s');
                        $guadarDatos->usuario_genera= auth()->user()->id;    
                        $guadarDatos->descripcion="Informe Resumido desde ".$fecha_ini. " hasta ".$fecha_fin;
                        $guadarDatos->ruta=$nombrePDF;
                        $guadarDatos->estado="Generado";
                        $guadarDatos->save(); 

                        return response()->json([
                            'error'=>false,
                            'mensaje'=>"Informe creado exitosamente"
                        ]);

                    }else{
                        return response()->json([
                            'error'=>true,
                            'mensaje'=>'No se pudo crear el documento'
                        ]);
                    }

                }


            }catch (\Throwable $e) {
                DB::rollback();
                Log::error(__CLASS__." => ".__FUNCTION__." => Mensaje =>".$e->getMessage()." Linea ".$e->getLine());           
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'Ocurrió un error, intentelo más tarde'
                ]);
            }    
        });
        return ($transaction);           
        
    }

    public function descargar($id){
        try{

            $archivo=ReportesFormularios::where('id_reportes_formulario',$id)->first();     
            if($archivo != []){
                $exists = Storage::disk('public')->exists($archivo->ruta);       
                if($exists){
                    return Storage::disk('public')->download($archivo->ruta);
                }else{
                    abort(404);
                }
            }else{
                abort(404);
            }

        } catch (\Throwable $e) {
            Log::error(__CLASS__." => ".__FUNCTION__." => Mensaje =>".$e->getMessage());
            return back();
        }
    }

    public function vistaOrdenes(){
        return view('combustible.reportes.vistaOrdenes');
    }

    public function listadoOrden(){
        try{
            $ordenes=OrdenesCombustible::where('estado','!=','Elimininado')->get();
            return response()->json([
                'error'=>false,
                'resultado'=>$ordenes
            ]);
        }catch (\Throwable $e) {
            Log::error('MenuController => listadoOrden => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    //buscar ordenes despacho en rango de fecha
    public function buscarOrden(Request $request){
    
        try{

            $desde=$request->fecha_ini;
            $hasta=$request->fecha_fin;

            $detalle = DetalleDespacho::with('vehiculo','tipocombustible','cabecera','chofer')
            ->whereBetween('fecha_cabecera_despacho', [$desde, $hasta])->where('estado','Aprobado')
            ->orderBy('id_vehiculo', 'asc')->get();
            

            return response()->json([
                'error'=>false,
                'data'=>$detalle
            ]);

        }catch (\Throwable $e) {
            
            Log::error(__CLASS__." => ".__FUNCTION__." => Mensaje =>".$e->getMessage()." Linea ".$e->getLine());           
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
        }    
       
    }

    public function pdfOrden($id, $nro){
       
        $detalle = DetalleDespacho::with('vehiculo','tipocombustible','cabecera','chofer')
        ->where('estado','Aprobado')
        ->where('idcabecera_despacho',$id)
        ->orderBy('id_vehiculo', 'asc')->get();

        $fechaw=$detalle[0]->fecha_cabecera_despacho;
        setlocale(LC_ALL,"es_ES@euro","es_ES","esp"); //IDIOMA ESPAÑOL
        $fecha= $fechaw;
        $fecha = strftime("%d de %B de %Y", strtotime($fecha));
        $movimiento=DB::table('vc_movimiento')
        ->where('estado','!=','Eliminada')
        ->where('nro_ticket',$nro)
        ->first();

        $responsable=DB::table('vc_responsable_servicios')
        ->first();

        $crearpdf=PDF::loadView('combustible.reportes.reporteOrden',['datos'=>$detalle, "movimiento"=>$movimiento,"fecha"=>$fecha, "responsable"=>$responsable]);
        $crearpdf->setPaper("A4", "portrait");
        
        $nombrePDF="orden_".$movimiento->idmovimiento.".pdf";
        Storage::disk('public')->put(str_replace("", "",$nombrePDF), $estadoarch);
        $exists_destino = Storage::disk('public')->exists($nombrePDF); 
        if($exists_destino){   
            return [
                'error'=>false,
                'pdf'=>$nombrePDF
            ];
        }else{
            return [
                'error'=>true,
                'mensaje'=>'No se pudo crear el documento'
            ];
        }

    }

    public function vistaConsolidado(){
        $desde="2024-03-01";
        $hasta="2024-03-31";

        $desde = date('Y-m-d 00:00:00', strtotime($desde));
        $hasta = date('Y-m-d 23:59:59', strtotime($hasta));

        $despacho_veh=\DB::table('vc_ticket as t')
        ->leftJoin('vc_tipocombustible as c', 'c.id_tipocombustible', 't.id_tipocombustible')
        ->leftJoin('vc_detalle_despacho as m', 'm.num_factura_ticket', 't.numero_ticket')
        ->where('t.estado','A')
        ->where('m.estado','Aprobado')
        ->whereBetween('f_despacho', [$desde, $hasta])
        // ->where('t.id_vehiculo',13)

        // ->select(DB::raw('t.id_vehiculo, sum(total) as total, c.detalle as combustible'))
        // ->groupBy('t.id_vehiculo', 'combustible') 

        // ->select(DB::raw('t.id_vehiculo, sum(total) as total, c.detalle as combustible'),'t.numero_ticket')
        // ->groupBy('id_vehiculo', 'combustible','t.numero_ticket') 

        ->select(DB::raw('t.id_vehiculo, sum(t.total) as total, c.detalle as combustible'))
        ->groupBy('id_vehiculo', 'combustible') 
        // ->distinct('t.numero_ticket')
        ->get(); 

        // dd($despacho_veh);

        // $super="";
        // $diesel="";
        // $eco="";
        // foreach($despacho_veh as $desp){
        //     $tipo=$desp->combustible;
        //     switch ($tipo) { 
        //     case 'Diesel': 
        //         $super=$desp->total;
        //         break; 
        //     case 'Super':     
        //         $diesel=$desp->total;
        //         break;
            
        //     case 'Eco':     
        //         $eco=$desp->total;
        //         break;
        //     }
        // }
        
        // dd("super => ".$super. " .diesel => ".$diesel. " eco => ".$eco);

        return view ('combustible.reportes.vistaConsolidado');
    }

     //buscar ordenes despacho en rango de fecha
    public function listarConsolidado($desde, $hasta){
    
        try{
            $desde = date('Y-m-d 00:00:00', strtotime($desde));
            $hasta = date('Y-m-d 23:59:59', strtotime($hasta));
            $detalle = DetalleDespacho::with('vehiculo','tipocombustible','movimiento','chofer','ticket')
            ->whereHas('ticket', function ($query) use ($desde, $hasta){
                $query->whereBetween('f_despacho', [$desde, $hasta]);
            })
            // ->whereBetween('fecha_cabecera_despacho', [$desde, $hasta])
            ->where('estado','Aprobado')
            ->orderBy('id_vehiculo', 'asc')->get();
            
            return response()->json([
                'error'=>false,
                'resultado'=>$detalle
            ]);

        }catch (\Throwable $e) {
            
            Log::error(__CLASS__." => ".__FUNCTION__." => Mensaje =>".$e->getMessage()." Linea ".$e->getLine());           
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
        }    
       
    }

    public function pdfConsolidado($desde, $hasta){
    
        try{
            $desde = date('Y-m-d 00:00:00', strtotime($desde));
            $hasta = date('Y-m-d 23:59:59', strtotime($hasta));

            $detalle = DetalleDespacho::with('vehiculo','tipocombustible','movimiento','chofer', 'ticket')
            ->whereHas('ticket', function ($query) use ($desde, $hasta){
                $query->whereBetween('f_despacho', [$desde, $hasta]);
            })
            // ->whereBetween('fecha_cabecera_despacho', [$desde, $hasta])
            ->where('estado','Aprobado')
            ->orderBy('fecha_cabecera_despacho', 'asc')->get();
            // dd($detalle);

            $data_vehiculo=DB::table('vc_vehiculo as v')
            ->leftJoin('vc_tipocombustible as c', 'c.id_tipocombustible', 'v.id_tipocombustible')
            ->leftJoin('vc_marca as m', 'm.id_marca', 'v.id_marca')
            ->select('v.id_vehiculo', 'm.detalle as marca', 'v.descripcion as tipo', 'c.detalle as combustible', 'v.estado as estado', 'v.estado_vehiculo as estado_vehiculo','v.codigo_institucion')
            ->where('v.estado','A')
            ->get();

            $responsable=DB::table('vc_responsable_servicios')
            ->first();

            $crearpdf=PDF::loadView('combustible.reportes.reporteConsolidado',['datos'=>$detalle, "desde"=>$desde, "hasta"=>$hasta, "data_vehiculo"=>$data_vehiculo,'responsable'=>$responsable]);
            $crearpdf->setPaper("A4", "landscape");

            $estadoarch =$crearpdf->stream();

            $nombrePDF="Consolidado.pdf";
            Storage::disk('public')->put(str_replace("", "",$nombrePDF), $estadoarch);
            $exists_destino = Storage::disk('public')->exists($nombrePDF); 
            if($exists_destino){   
               
                return [
                    'error'=>false,
                    'pdf'=>$nombrePDF
                ];
            }else{
                return [
                    'error'=>true,
                    'mensaje'=>'No se pudo crear el documento'
                ];
            }
           

        }catch (\Throwable $e) {
            
            Log::error(__CLASS__." => ".__FUNCTION__." => Mensaje =>".$e->getMessage()." Linea ".$e->getLine());           
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
        }    
       
    }

    //funcion que permite visualizar un documento selecciondo
    public function visualizarDocumentoOrden($documentName){
        try {
            //obtenemos la extension
            $info = new \SplFileInfo($documentName);
            $extension = $info->getExtension();
            if($extension!= "pdf" && $extension!="PDF"){
                return \Storage::disk('OrdenesCombustible')->download($documentName);
            }else{
                // obtenemos el documento del disco en base 64
                $documentEncode= base64_encode(\Storage::disk('OrdenesCombustible')->get($documentName));
                return view("vistaPrevia")->with([
                    "documentName"=>$documentName,
                    "documentEncode"=>$documentEncode
                ]);        
            }            
        }   catch (\Throwable $e) {
            Log::error(__CLASS__." => ".__FUNCTION__." => Mensaje =>".$e->getMessage()." Linea ".$e->getLine()); 
            abort("404");            
        }

    }

    public function descargarOrden($archivo)
    {
        
        $exists = Storage::disk('OrdenesCombustible')
        ->exists($archivo);   
     
        if($exists){
            return Storage::disk('OrdenesCombustible')->download($archivo);
           
        }else{
            return back()->with(['error'=>'No se pudo descargar el archivo','estadoP'=>'danger']);
        }
      
    }

    

}


