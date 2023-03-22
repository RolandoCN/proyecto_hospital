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

                // dd($lista_final_agrupada);
               
                if($request->formulario=="F6"){
                   
                    $nombrePDF="despachoCombustibleDepartamento6_".date('YmdHis').".pdf";// $nombrePDF  
                 
                    //creamos el objeto
                    // $pdf=new PDF();
                    //habilitamos la opcion php para mostrar la paginacion
                    // $crearpdf=$pdf::setOptions(['isPhpEnabled'=>true]);
                    // enviamos a la vista para crear el documento que los datos repsectivos
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
                   
                    //creamos el objeto
                    // $pdf=new PDF();
                    //habilitamos la opcion php para mostrar la paginacion
                    // $crearpdf=$pdf::setOptions(['isPhpEnabled'=>true]);
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

    //consulta y genera el documento
    public function guardarOrden(Request $request){
      
        $transaction=DB::transaction(function() use ($request){
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
        ->where('estado','!=','Eliminado')
        // ->where('id_vehiculo',$id)
        ->where('nro_ticket',$nro)
        ->first();
        // dd($movimiento);

        $crearpdf=PDF::loadView('combustible.reportes.reporteOrden',['datos'=>$detalle, "movimiento"=>$movimiento,"fecha"=>$fecha]);
        $crearpdf->setPaper("A4", "portrait");
        
        // $nombrePDF=
        return $crearpdf->stream("xx.pdf");
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

        $estadoarch = $crearpdf->stream();
    }
}


