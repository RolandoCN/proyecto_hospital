<?php

namespace App\Http\Controllers\VehiculosCombustible;
use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\VehiculoCombustible\Vehiculo;
use App\Models\VehiculoCombustible\Tarea;
use App\Models\VehiculoCombustible\DetalleDespacho;
use App\Models\VehiculoCombustible\TipoCombustible;
use App\Models\VehiculoCombustible\Movimiento;
use App\Models\VehiculoCombustible\Gasolinera;
use App\Models\VehiculoCombustible\CabeceraDespacho;
use App\Models\VehiculoCombustible\TareaDetalleDespacho;
use App\Models\VehiculoCombustible\MovimientoDetalleDespacho;
use App\Models\User;
use App\Models\VehiculoCombustible\Ticket;
use \Log;
use DB;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\VehiculosCombustible\TareasController;

use League\Flysystem\Sftp\SftpAdapter;


class JobController extends Controller
{
    public function __construct() {
        try {       
            $this->objTareas = new TareasController();
                           
        } catch (\Throwable $e) {
            Log::error(__CLASS__." => ".__FUNCTION__." => Mensaje =>".$e->getMessage());
        }
    }

   
    public function guardarCabecera(){
      
        try{
            $guarda_cabec_des=new CabeceraDespacho();
            $guarda_cabec_des->id_gasolinera=6;
            $guarda_cabec_des->fecha=date('Y-m-d');
            $guarda_cabec_des->fecha_registro=date('Y-m-d H:i:s');
            $guarda_cabec_des->estado="Activo";

            //validar no se repita
            $valida_exis=CabeceraDespacho::where('id_gasolinera',$guarda_cabec_des->id_gasolinera)
            ->where('fecha',$guarda_cabec_des->fecha)
            ->where('estado','Activo')
            ->first();
           
            if(!is_null($valida_exis)){
                
                Log::error('La información ya existe');   
                return 'La información ya existe'; 
                
            }
           
            if($guarda_cabec_des->save()){
               
                Log::info('Información registrada exitosamente');   
                return 'Información registrada exitosamente'; 

            }else{
                Log::error('No se pudo registrar la información');   
                return 'No se pudo registrar la información'; 
            }


        }catch (\Throwable $e) {
            Log::error('JobController => guardar => mensaje => '.$e->getMessage());
            Log::error('Ocurrio un error');   
            return 'Ocurrio un error'; 
            
        }
    }

    //funcion ejecutada via clic
    public function guardarDetalleDespachoManual($fecha){
                       
        $transaction=DB::transaction(function() use($fecha){
            try{
               
                $fechaDesp=date('Y-m-d', strtotime($fecha));
                $movimientoHoy=Movimiento::whereDate('fecha_salida_patio',$fechaDesp)
                ->where('estado','Activo')->get();
                $contador=0;
                if(sizeof($movimientoHoy)==0){
                    return [
                        'error'=>false,
                        'mensaje'=>'No existen tickets con despachos del dia '.date('d-m-Y')
                    ];
                }

                $data_cabecera=CabeceraDespacho::whereDate('fecha',$fechaDesp)->first();
                $idcabecera=$data_cabecera->idcabecera_despacho;
                $fecha_cabecera=$data_cabecera->fecha;

                //elimino los detalles asociados a esa cabecera
                $eliminaDetalles=DetalleDespacho::where('idcabecera_despacho',$idcabecera)->delete();
                
                foreach($movimientoHoy as $data){
                    // $data_cabecera=CabeceraDespacho::whereDate('fecha',$fechaDesp)->first();                   
                    // $fecha_cabecera=$data_cabecera->fecha;

                    $ticket=Ticket::where('numero_ticket',$data->nro_ticket)
                    ->where('estado','A')
                    ->first();

                    $gasoli_comb=DB::table('vc_gasolinera_comb')
                    ->where('id_gasolinera',$ticket->id_gasolinera)
                    ->where('id_tipocombustible',$ticket->id_tipocombustible)
                    ->where('estado','A')
                    ->select('precio_x_galon')
                    ->first();
                    if(is_null($gasoli_comb)){
                        // dd($ticket);
                    }
                    $galones=$ticket->total / $gasoli_comb->precio_x_galon;
                    
                    $guarda_det_des=new DetalleDespacho();
                    $guarda_det_des->id_vehiculo=$data->id_vehiculo;
                    $guarda_det_des->idcabecera_despacho=$idcabecera;
                    $guarda_det_des->fecha_cabecera_despacho=$fecha_cabecera;
                  
                    $guarda_det_des->id_tipocombustible=$ticket->id_tipocombustible;
                    $guarda_det_des->galones=number_format(($galones),2,'.', '');;
                    $guarda_det_des->precio_unitario=$gasoli_comb->precio_x_galon;
                    $guarda_det_des->total=$ticket->total;
                    $guarda_det_des->idconductor=$data->id_chofer;
                    $guarda_det_des->fecha_hora_despacho=$ticket->f_despacho;
                    $guarda_det_des->estado="Aprobado";
                    $guarda_det_des->num_factura_ticket=$data->nro_ticket;

                    $ver=DetalleDespacho::where('num_factura_ticket',$guarda_det_des->num_factura_ticket)
                    ->where('estado','!=',"Eliminado")->first();
                    
                    if(is_null($ver)){
                        if($guarda_det_des->save()){
                            //generamos el documento
                            $genera=$this->pdfOrden($guarda_det_des->idcabecera_despacho,$guarda_det_des->num_factura_ticket, $guarda_det_des->iddetalle_despacho, 'N');
    
                            $contador=$contador+1;
                        }
                    }

                    // $genera=$this->pdfOrden($guarda_det_des->idcabecera_despacho,$guarda_det_des->num_factura_ticket, $guarda_det_des->iddetalle_despacho, 'N');

                    $contador=$contador+1;
                    log::info($guarda_det_des->num_factura_ticket);
                }
                if($contador>0){
                    return [
                        'error'=>false,
                        'mensaje'=>'Despacho actualizado exitosamente del dia '.date('d-m-Y')
                    ];
                }else{
                    return [
                        'error'=>true,
                        'mensaje'=>'No se pudo actualizar los despachos del dia '.date('d-m-Y')
                    ];
                }

            }catch (\Throwable $e) {
                DB::Rollback();
                Log::error('JobController => guardarDetalleDespachoManual => mensaje => '.$e->getMessage().' Linea '.$e->getLine());
                return [
                    'error'=>true,
                    'mensaje'=>'Ocurrió un error'
                ];
                
            }
        });
        return $transaction;
    }

    //funcion ejecutada via job (busca los tickets del dia anterior)
    public function guardarDetalleDespachoJob($fecha=null){
       
        $transaction=DB::transaction(function() use($fecha){
            try{
                $fecha = date('Y-m-d');
                $fecha = date("Y-m-d", strtotime('-1 day', strtotime($fecha)));
                $fechaDesp=$fecha;
              
                $movimientoHoy=Movimiento::whereDate('fecha_salida_patio',$fechaDesp)
                ->where('estado','Activo')->get();
                $contador=0;
                if(sizeof($movimientoHoy)==0){
                    Log::error('No existen tickets con despachos del dia '.$fecha);   
                    return 'No existen tickets con despachos del dia '.$fecha;
                }

                $data_cabecera=CabeceraDespacho::whereDate('fecha',$fechaDesp)->first();
                $idcabecera=$data_cabecera->idcabecera_despacho;
                $fecha_cabecera=$data_cabecera->fecha;

                //elimino los detalles asociados a esa cabecera
                $eliminaDetalles=DetalleDespacho::where('idcabecera_despacho',$idcabecera)->delete();

                foreach($movimientoHoy as $data){
                    // $data_cabecera=CabeceraDespacho::whereDate('fecha',$fechaDesp)->first();                   
                    // $fecha_cabecera=$data_cabecera->fecha;

                    $ticket=Ticket::where('numero_ticket',$data->nro_ticket)
                    >where('estado','A')
                    ->first();

                    $gasoli_comb=DB::table('vc_gasolinera_comb')
                    ->where('id_gasolinera',$ticket->id_gasolinera)
                    ->where('id_tipocombustible',$ticket->id_tipocombustible)
                    ->where('estado','A')
                    ->select('precio_x_galon')
                    ->first();

                    $galones=$ticket->total / $gasoli_comb->precio_x_galon;

                                        
                    $guarda_det_des=new DetalleDespacho();
                    $guarda_det_des->id_vehiculo=$data->id_vehiculo;
                    $guarda_det_des->idcabecera_despacho=$idcabecera;
                    $guarda_det_des->fecha_cabecera_despacho=$fecha_cabecera;
                  
                    $guarda_det_des->id_tipocombustible=$ticket->id_tipocombustible;
                    $guarda_det_des->galones=number_format(($galones),2,'.', '');;
                    $guarda_det_des->precio_unitario=$gasoli_comb->precio_x_galon;
                    $guarda_det_des->total=$ticket->total;
                    $guarda_det_des->idconductor=$data->id_chofer;
                    $guarda_det_des->fecha_hora_despacho=$ticket->f_despacho;
                    $guarda_det_des->estado="Aprobado";
                    $guarda_det_des->num_factura_ticket=$data->nro_ticket;

                    $ver=DetalleDespacho::where('num_factura_ticket',$guarda_det_des->num_factura_ticket)
                    ->where('estado','!=',"Eliminado")->first();
                    
                    
                    if(is_null($ver)){
                        if($guarda_det_des->save()){
                            //generamos el documento
                            $genera=$this->pdfOrden($guarda_det_des->idcabecera_despacho,$guarda_det_des->num_factura_ticket, $guarda_det_des->iddetalle_despacho, 'N');
    
                            $contador=$contador+1;
                        }
                    }
                }
                if($contador>0){
                    Log::info('Despacho actualizado exitosamente del dia '.$fecha);   
                    return 'Despacho actualizado exitosamente del dia '.$fecha;
                }else{
                    Log::error('No se pudo actualizar los despachos del dia '.$fecha);   
                    return 'No se pudo actualizar los despachos del dia '.$fecha;
                }

            }catch (\Throwable $e) {
                DB::Rollback();
                Log::error('JobController => guardarDetallexx => mensaje => '.$e->getMessage().' Linea '.$e->getLine());
                Log::error('Ocurrió un error');   
                return 'Ocurrió un error';
                
            }
        });
        return $transaction;
    }

   
    public function pdfOrden($id, $nro, $iddet, $tipo){

        try{
           
            $movimiento=DB::table('vc_movimiento as m')
            ->leftJoin('vc_autorizado_salida as a', 'a.id_autorizado_salida', 'm.id_autorizado_salida')
            ->leftJoin('persona as p', 'p.idpersona', 'm.id_chofer')
            ->where('m.estado','!=','Eliminado')
            ->where('nro_ticket',$nro)
            ->select('*', 'a.abreviacion_titulo', 'a.nombres as autorizador', 'p.nombres', 'p.apellidos')
            ->get();

            $nombrePDF="orden_".$movimiento[0]->idmovimiento.".pdf";
           

            $act_det_des=DetalleDespacho::find($iddet);
            $act_det_des->pdf_orden=$nombrePDF;
            $act_det_des->save();
            
            $detalle = DetalleDespacho::with('vehiculo','tipocombustible','cabecera','chofer')
            ->where('estado','Aprobado')
            ->where('idcabecera_despacho',$id)
            ->orderBy('id_vehiculo', 'asc')->get();


            $fechaw=$detalle[0]->fecha_cabecera_despacho;
            setlocale(LC_ALL,"es_ES@euro","es_ES","esp"); //IDIOMA ESPAÑOL
            $fecha= $fechaw;
            $fecha = strftime("%d de %B de %Y", strtotime($fecha));

            $responsable=DB::table('vc_responsable_servicios')
            ->first(); 
 
            $crearpdf=PDF::loadView('combustible.reportes.reporteOrden',['datos'=>$detalle, "movimientodata"=>$movimiento,"fecha"=>$fecha, "responsable"=>$responsable]);

            // return $crearpdf->stream("a.pdf");

            $crearpdf->setPaper("A4", "portrait");
            $estadoarch = $crearpdf->stream();

             
            // $exists_destino = Storage::disk('public')->exists($nombrePDF);
            $exists_destino = Storage::disk('OrdenesCombustible')->exists($nombrePDF);
            
            if($exists_destino){   
                Storage::disk('OrdenesCombustible')->delete($nombrePDF);
            }

            Storage::disk('OrdenesCombustible')->put(str_replace("", "",$nombrePDF), $estadoarch);
            $exists_destino = Storage::disk('OrdenesCombustible')->exists($nombrePDF); 
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
            Log::error('JobController => pdfOrden => mensaje => '.$e->getMessage().' Linea => '.$e->getLine());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }

       
    }

 
}
