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
use \Log;
use DB;
use PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\VehiculosCombustible\TareasController;

class DespachoCombustibleController extends Controller
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
        $gasolinera=Gasolinera::where('estado','A')->get();
        $vehiculo=Vehiculo::where('estado','A')->get();
        $tipo_comb=TipoCombustible::where('estado','A')->get();
        return view('combustible.despacho_comb',[
            "persona"=>$persona,
            "gasolinera"=>$gasolinera,
            "vehiculo"=>$vehiculo,
            "tipo_comb"=>$tipo_comb
        ]);
    }


    public function listar(){
        try{
            //comprobamos si hay tareas sin fecha final y actualizamos el estado en caso d q tenga fecha fin menor a la actual
            $comprobar=$this->objTareas->actualizaTarea();
            
            $cab=CabeceraDespacho::with('gasolinera')->where('estado','!=','Eliminado')->get();
            return response()->json([
                'error'=>false,
                'resultado'=>$cab
            ]);
        }catch (\Throwable $e) {
            Log::error('DespachoCombustibleController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function guardarCabecera(Request $request){
      
        $messages = [
            
            'cmb_gasolinera.required' => 'Debe seleccionar la gasolinera',
            'fecha_desp.required' => 'Debe seleccionar la fecha',
                 
        ];
           

        $rules = [
            'cmb_gasolinera' => "required",
            'fecha_desp' => "required",
                                
        ];

        $this->validate($request, $rules, $messages);
        try{

            $guarda_cabec_des=new CabeceraDespacho();
            $guarda_cabec_des->id_gasolinera=$request->cmb_gasolinera;
            $guarda_cabec_des->fecha=$request->fecha_desp;

            $guarda_cabec_des->idusuarioregistra=auth()->user()->id;
            $guarda_cabec_des->fecha_registro=date('Y-m-d H:i:s');
            $guarda_cabec_des->estado="Activo";

            //validar no se repita
            $valida_exis=CabeceraDespacho::where('id_gasolinera',$guarda_cabec_des->id_gasolinera)
            ->where('fecha',$guarda_cabec_des->fecha)
            ->where('estado','Activo')
            ->first();
           
            if(!is_null($valida_exis)){
            
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La información ya existe'
                ]);
                
            }
           
            if($guarda_cabec_des->save()){
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
            Log::error('DespachoCombustibleController => guardar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function editarCabecera($idCab){
        try{
            $detalleDespEdit=CabeceraDespacho::where('idcabecera_despacho', $idCab)
            ->where('estado','!=','Eliminado')->first();
            return response()->json([
                'error'=>false,
                'resultado'=>$detalleDespEdit
            ]);
        }catch (\Throwable $e) {
            Log::error('DespachoCombustibleController => editarCabecera => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function actualizarCabecera(Request $request, $id){
      
        $messages = [
            
            'cmb_gasolinera.required' => 'Debe seleccionar la gasolinera',
            'fecha_desp.required' => 'Debe seleccionar la fecha',
                 
        ];
           

        $rules = [
            'cmb_gasolinera' => "required",
            'fecha_desp' => "required",
                                
        ];

        $this->validate($request, $rules, $messages);
        try{

            //si ya se han realizado despacho no dejamos actualizar
            $tiene_despacho_act=DB::table('vc_detalle_despacho')
            ->where('idcabecera_despacho',$id)
            ->where('estado','!=','Eliminado')
            ->first();
            if(!is_null($tiene_despacho_act)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'No se puede actualizar, ya que tiene despachos asociados'
                ]);
            }


            $actualiza_cabec_des= CabeceraDespacho::find($id);
            $actualiza_cabec_des->id_gasolinera=$request->cmb_gasolinera;
            $actualiza_cabec_des->fecha=$request->fecha_desp;
            $actualiza_cabec_des->idusuario_act=auth()->user()->id;
            $actualiza_cabec_des->fecha_actualiza=date('Y-m-d H:i:s');
            $actualiza_cabec_des->estado="Activo";

            //validar no se repita
            $valida_exis=CabeceraDespacho::where('id_gasolinera',$actualiza_cabec_des->id_gasolinera)
            ->where('fecha',$actualiza_cabec_des->fecha)
            ->where('estado','Activo')
            ->where('idcabecera_despacho','!=', $id)
            ->first();
           
            if(!is_null($valida_exis)){
            
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La información ya existe'
                ]);
                
            }
           
            if($actualiza_cabec_des->save()){
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
            Log::error('DespachoCombustibleController => guardar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function eliminarCabecera($id){
        try{
            //verificamos que no tenga detalle en estado activo
            $veri_detalle=DB::table('vc_detalle_despacho')
            ->where('idcabecera_despacho',$id)
            ->where('estado','!=','Eliminado')
            ->first();
            if(!is_null($veri_detalle)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'No se puede eliminar, ya que tiene despachos asociados'
                ]);
            }

            $elim_cabecera=CabeceraDespacho::find($id);
            $elim_cabecera->idusuario_act=auth()->user()->id;
            $elim_cabecera->fecha_actualiza=date('Y-m-d H:i:s');
            $elim_cabecera->estado="Eliminado";
            if($elim_cabecera->save()){
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
            Log::error('DespachoCombustibleController => eliminarDetalle => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

    public function detallePrecioComb($idVeh, $idGasol){
        try{

            $datoVeh=Vehiculo::where('id_vehiculo', $idVeh)
            ->first();
            
            $precioCombGas=DB::table('vc_gasolinera_comb')->where('id_gasolinera',$idGasol)
            ->where('id_tipocombustible', $datoVeh->id_tipocombustible)
            ->first();
            if(is_null($precioCombGas)){
                $precioCom=null;
            }else{
                $precioCom=$precioCombGas->precio_x_galon;
            }

            $tipoMed=DB::table('vc_tipomedicion')->where('id_tipomedicion',$datoVeh->id_tipomedicion)
            ->first();

            return response()->json([
                'error'=>false,
                'idTipoCom'=>$datoVeh->id_tipocombustible,
                'precioCombGas'=>$precioCom,
                'tipoMed'=>$tipoMed
            ]);
            
        }catch (\Throwable $e) {
            Log::error('DespachoCombustibleController => detallePrecioComb => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function PrecioCombGaso($idTipoComb, $idGasol){
        try{

            $precioCombGas=DB::table('vc_gasolinera_comb')
            ->where('id_gasolinera',$idGasol)
            ->where('id_tipocombustible', $idTipoComb)
            ->first();
            if(is_null($precioCombGas)){
                $precioCom=null;
            }else{
                $precioCom=$precioCombGas->precio_x_galon;
            }
            return response()->json([
                'error'=>false,
                'precioCombGas'=>$precioCom
            ]);
            
        }catch (\Throwable $e) {
            Log::error('DespachoCombustibleController => PrecioCombGaso => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }


    public function guardarDetalle(Request $request){
            
        $messages = [
            
            'idcabeceradespacho.required' => 'Debe seleccionar la cabecera del despacho',
            'vehiculo_id.required' => 'Debe seleccionar el vehículo',
            'chofer_id.required' => 'Debe seleccionar el chofer',
        ];
           

        $rules = [
            'idcabeceradespacho' => "required",
            'vehiculo_id' => "required",
            'chofer_id' => "required",
                                
        ];

        $this->validate($request, $rules, $messages);
        $transaction=DB::transaction(function() use($request){
            try{

                $data_cabecera=CabeceraDespacho::find($request->idcabeceradespacho);
                $fecha_cabecera=$data_cabecera->fecha;

                              
                $guarda_det_des=new DetalleDespacho();
                $guarda_det_des->id_vehiculo=$request->vehiculo_id;
                $guarda_det_des->idcabecera_despacho=$request->idcabeceradespacho;
                $guarda_det_des->fecha_cabecera_despacho=$fecha_cabecera;
                $guarda_det_des->kilometraje=$request->kilometrajemodal;
                $guarda_det_des->horometraje=$request->horometrajemodal;
                $guarda_det_des->id_tipocombustible=$request->combustible_id;
                $guarda_det_des->galones=$request->galonesmodal;
                $guarda_det_des->precio_unitario=$request->preciounitariomodal;
                $guarda_det_des->total=$request->totalmodal;
                $guarda_det_des->idconductor=$request->chofer_id;
                $guarda_det_des->fecha_hora_despacho=date('Y-m-d H:i:s');
                $guarda_det_des->estado="No aprobado";
                $guarda_det_des->num_factura_ticket=$request->facturamodal;
                $guarda_det_des->idusuarioregistra=auth()->User()->id;

                //validar no se repita
                $ver=DetalleDespacho::where('idcabecera_despacho',$guarda_det_des->idcabecera_despacho)
                ->where('num_factura_ticket',$guarda_det_des->num_factura_ticket)
                ->where('estado','!=',"Eliminado")->first();
                if(!is_null($ver)){
                return response()->json([
                        'error'=>true,
                        'mensaje'=>'El número de factura-ticket ya está ingresado'
                    ]);
                }
            
                if($guarda_det_des->save()){
                    return response()->json([
                        'error'=>false,
                        'mensaje'=>'Información registrada exitosamente',
                        'id_despacho'=>$guarda_det_des->iddetalle_despacho 
                    ]);
                }else{
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>'No se pudo registrar la información'
                    ]);
                }




            }catch (\Throwable $e) {
                DB::Rollback();
                Log::error('DespachoCombustibleController => guardarDetalle => mensaje => '.$e->getMessage()).' Linea '.$e->getLine();
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'Ocurrió un error'
                ]);
                
            }
        });
        return $transaction;
    }

    public function listarDetalleDesp($idCab){
        try{
            $detalleDesp=DetalleDespacho::with('vehiculo','tipocombustible')
            ->where('idcabecera_despacho', $idCab)
            ->where('estado','!=','Eliminado')->get();
            return response()->json([
                'error'=>false,
                'resultado'=>$detalleDesp
            ]);
        }catch (\Throwable $e) {
            Log::error('DespachoCombustibleController => listarDetalleDesp => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function editarDetalle($idDet){
        try{
            $detalleDespEdit=DetalleDespacho::with('vehiculo','tipocombustible','cabecera','chofer')
            
            ->where('iddetalle_despacho', $idDet)
            ->where('estado','!=','Eliminado')->first();
            return response()->json([
                'error'=>false,
                'resultado'=>$detalleDespEdit
            ]);
        }catch (\Throwable $e) {
            Log::error('DespachoCombustibleController => listarDetalleDesp => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function actualizarDetalle(Request $request, $id){
            
        $messages = [
            
            'idcabeceradespacho.required' => 'Debe seleccionar la cabecera del despacho',
            'vehiculo_id.required' => 'Debe seleccionar el vehículo',
            'chofer_id.required' => 'Debe seleccionar el chofer',
        ];
           

        $rules = [
            'idcabeceradespacho' => "required",
            'vehiculo_id' => "required",
            'chofer_id' => "required",
                                
        ];

        $this->validate($request, $rules, $messages);
        $transaction=DB::transaction(function() use($request, $id){
            try{

                $actualiza_detalle= DetalleDespacho::find($id);
                $actualiza_detalle->id_vehiculo=$request->vehiculo_id;
                $actualiza_detalle->idcabecera_despacho=$request->idcabeceradespacho;
                $actualiza_detalle->kilometraje=$request->kilometrajemodal;
                $actualiza_detalle->horometraje=$request->horometrajemodal;
                $actualiza_detalle->id_tipocombustible=$request->combustible_id;
                $actualiza_detalle->galones=$request->galonesmodal;
                $actualiza_detalle->precio_unitario=$request->preciounitariomodal;
                $actualiza_detalle->total=$request->totalmodal;
                $actualiza_detalle->idconductor=$request->chofer_id;
                $actualiza_detalle->fecha_hora_despacho=date('Y-m-d H:i:s');
                $actualiza_detalle->estado="No aprobado";
                $actualiza_detalle->firma_conductor=null;
                $actualiza_detalle->num_factura_ticket=$request->facturamodal;
                $actualiza_detalle->idusuarioregistra=auth()->User()->id;

                //validar no se repita
                $ver=DetalleDespacho::where('idcabecera_despacho',$actualiza_detalle->idcabecera_despacho)
                ->where('num_factura_ticket',$actualiza_detalle->num_factura_ticket)
                ->where('iddetalle_despacho','!=',$id)
                ->where('estado','!=',"Eliminado")->first();
                if(!is_null($ver)){
                return response()->json([
                        'error'=>true,
                        'mensaje'=>'El número de factura-ticket ya está ingresado'
                    ]);
                }
            
                if($actualiza_detalle->save()){

                    $fecha_cabecera=$actualiza_detalle->fecha_cabecera_despacho;
                    //eliminamos la informacion de las tareasDetalle
                    $eliminaTareaDetalle=TareaDetalleDespacho::where('iddetalle_despacho', $id);
                    $eliminaTareaDetalle->delete();

                    // buscamos las tareas asociadas a ese vehiculo ese dia asi como la informacion de los movimientos
                    $tareas=$this->listarTareaVeh($request->vehiculo_id,$fecha_cabecera,'S');  
                    if($tareas['error']==true){
                        DB::Rollback();
                        return response()->json([
                            'error'=>true,
                            'mensaje'=>'No se pudo registrar la información, ocurrió un error al obtener las tareas'
                        ]);
                    }else{
                        if(sizeof($tareas['resultado'])>0){
                            foreach($tareas['resultado'] as $tarea){
                                $tareaDespacho= new TareaDetalleDespacho();
                                $tareaDespacho->id_tarea=$tarea->id_tarea;
                                $tareaDespacho->iddetalle_despacho=$actualiza_detalle->iddetalle_despacho;
                                $tareaDespacho->save();
                            }  
                        }    
                    }
                    //eliminamos la informacion de las movimiemtoDetalle
                    $eliminaMovimDetalle=MovimientoDetalleDespacho::where('iddetalle_despacho', $id);
                    $eliminaMovimDetalle->delete();
                    
                    $listaMov=$this->listarMovimientoVeh($request->vehiculo_id, $fecha_cabecera);
                    if($listaMov['error']==true){
                        DB::Rollback();
                        return response()->json([
                            'error'=>true,
                            'mensaje'=>'No se pudo registrar la información, ocurrió un error al obtener los movimientos de los vehículos'
                        ]);
                    }else{
                        if(sizeof($listaMov['resultado'])>0){
                            foreach($listaMov['resultado'] as $movi){
                                $movimientoDespacho= new MovimientoDetalleDespacho();
                                $movimientoDespacho->id_movimiento=$movi->idmovimiento;
                                $movimientoDespacho->iddetalle_despacho=$actualiza_detalle->iddetalle_despacho;
                                $movimientoDespacho->save();
                            }  
                        }    
                    }

                    return response()->json([
                        'error'=>false,
                        'mensaje'=>'Información actualizada exitosamente',
                        'id_despacho'=>$actualiza_detalle->iddetalle_despacho 
                    
                    ]);
                }else{
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>'No se pudo actualizar la información'
                    ]);
                }

            }catch (\Throwable $e) {
                DB::Rollback();
                Log::error('DespachoCombustibleController => guardarDetalle => mensaje => '.$e->getMessage());
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'Ocurrió un error'
                ]);
                
            }
        });
        return $transaction;
    }

    public function eliminarDetalle($id){
        $transaction=DB::transaction(function() use($id){
            try{
                $elim_detalle=DetalleDespacho::find($id);
                $elim_detalle->idusuarioelimina=auth()->user()->id;
                $elim_detalle->fecha_eliminacion=date('Y-m-d H:i:s');
                $elim_detalle->estado="Eliminado";
                if($elim_detalle->save()){

                    //eliminamos la informacion de las tareasDetalle
                    $eliminaTareaDetalle=TareaDetalleDespacho::where('iddetalle_despacho', $id);
                    $eliminaTareaDetalle->delete();

                    //eliminamos la informacion de las movimiemtoDetalle
                    $eliminaMovimDetalle=MovimientoDetalleDespacho::where('iddetalle_despacho', $id);
                    $eliminaMovimDetalle->delete();

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
                Log::error('DespachoCombustibleController => eliminarDetalle => mensaje => '.$e->getMessage());
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'Ocurrió un error, intentelo más tarde'
                ]);
                
            }
        });
        return $transaction;
    }

    public function listarTareaVeh($idVeh, $fecha, $interno=null){
        try{
            $fechaDesp=date('Y-m-d', strtotime($fecha));
           
            $bucartarea=Tarea::where('id_vehiculo',$idVeh)
            ->where('estado','!=','Eliminada')
            ->where(function($query)use($fechaDesp){
                $query->WhereDate('fecha_inicio','<=',$fechaDesp)
                ->WhereDate('fecha_fin','>=',$fechaDesp);
            })
            ->get();
            if($interno==null){
                return response()->json([
                    'error'=>false,
                    'resultado'=>$bucartarea
                ]);
            }else{
                return [
                    'error'=>false,
                    'resultado'=>$bucartarea
                ];
            }
                
        }catch (\Throwable $e) {
            Log::error('DespachoCombustibleController => listarTareaVeh => mensaje => '.$e->getMessage());
            if($interno==null){
                return response()->json([
                    'error'=>false,
                    'resultado'=>$bucartarea
                ]);
            }else{
                return [
                    'error'=>false,
                    'resultado'=>$bucartarea
                ];
            }
            
        }
    }

    public function listarMovimientoVeh($idVeh, $fecha){
        try{
            $fechaDesp=date('Y-m-d', strtotime($fecha));
           
            $bucarMovimiento=Movimiento::where('id_vehiculo',$idVeh)
            ->where('estado','!=','Eliminada')
            ->whereDate('fecha_registro', $fechaDesp)
            ->get();
          
            return [
                'error'=>false,
                'resultado'=>$bucarMovimiento
            ];
            
                
        }catch (\Throwable $e) {
            Log::error('DespachoCombustibleController => listarMovimientoVeh => mensaje => '.$e->getMessage());
           
            return [
                'error'=>false,
                'resultado'=>$bucartarea
            ];
            
            
        }
    }

    public function aprobarDespacho(Request $request){
        try{
            if(is_null($request->b64_firma)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'Debe firmar para poder aporbar el despacho'
                ]);
            }

           $aprobar_detalle_desp=DetalleDespacho::where('iddetalle_despacho',$request->iddespachoApr)
           ->first();
           $aprobar_detalle_desp->idusuarioactualiza=auth()->user()->id;
           $aprobar_detalle_desp->fecha_aprobacion=date('Y-m-d H:i:s');
           $aprobar_detalle_desp->fecha_actualiza=date('Y-m-d H:i:s');
           $aprobar_detalle_desp->estado="Aprobado";
           $aprobar_detalle_desp->firma_conductor=$request->b64_firma;
           $aprobar_detalle_desp->save();

           return response()->json([
            'error'=>false,
            'mensaje'=>'El detalle del despacho ha sido aprobado exitosamente'
        ]);

        }catch (\Throwable $e) {
            Log::error('DespachoCombustibleController => aprobarDespacho => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function despachoPdfGasolinera($idCab){
            try{
                $detalle = DetalleDespacho::with('vehiculo','tipocombustible','cabecera','chofer')
                ->where('idcabecera_despacho',$idCab)->where('estado','Aprobado')
                ->orderBy('fecha_hora_despacho', 'desc')->get();

                if(sizeof($detalle)<=0){
                    return back()->with(['mensajePInfoDespacho'=>'Aún no se han registrado despachos aprobados','estadoP'=>'danger']);
                }
                
                $datos=CabeceraDespacho::with('gasolinera')->where('idcabecera_despacho',$idCab)->first();
                $fechaw=$datos->fecha;
                setlocale(LC_ALL,"es_ES@euro","es_ES","esp"); //IDIOMA ESPAÑOL
                        $fecha= $fechaw;
                        $fecha = strftime("%d de %B de %Y", strtotime($fecha));

                
                $nombre="despacho"; 
                
                //creamos el objeto
                $pdf=new PDF();
                //habilitamos la opcion php para mostrar la paginacion
                $crearpdf=$pdf::setOptions(['isPhpEnabled'=>true]);
            // enviamos a la vista para crear el documento que los datos repsectivos
                $crearpdf->loadView('combustible.pdf_despacho_gasoli',['datos'=>$datos,'detalle'=>$detalle,'fecha'=>$fecha]);
                $crearpdf->setPaper("A4", "landscape");

                return $crearpdf->download($nombre."_".date('YmdHis').'.pdf');
            
            }catch (\Throwable $e) {
                Log::error('DespachoCombustibleController => despachoPdfGasolinera => mensaje => '.$e->getMessage());
                return back()->with(['mensajePInfoDespacho'=>'Ocurrió un error, intentelo más tarde','estadoP'=>'danger']);
                
            }

    }

}
