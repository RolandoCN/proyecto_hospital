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
use \Log;
use DB;
use PDF;
use Illuminate\Http\Request;

class DespachoCombustibleController extends Controller
{


    public function index(){
        $persona=Persona::all();
        $gasolinera=Gasolinera::all();
        $vehiculo=Vehiculo::where('estado','A')->get();
        $tipo_comb=TipoCombustible::all();
        return view('combustible.despacho_comb',[
            "persona"=>$persona,
            "gasolinera"=>$gasolinera,
            "vehiculo"=>$vehiculo,
            "tipo_comb"=>$tipo_comb
        ]);
    }


    public function listar(){
        try{
            $cab=CabeceraDespacho::with('gasolinera')->where('estado','!=','Eliminada')->get();
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

    public function detallePrecioComb($idVeh, $idGasol){
        try{

            $datoVeh=Vehiculo::where('id_vehiculo', $idVeh)
            ->first();
            
            $precioCombGas=DB::table('vc_gasolinera_comb')->where('id_gasolinera',$idGasol)
            ->where('id_tipocombustible', $datoVeh->id_tipocombustible)
            ->first();

            $tipoMed=DB::table('vc_tipomedicion')->where('id_tipomedicion',$datoVeh->id_tipomedicion)
            ->first();

            return response()->json([
                'error'=>false,
                'idTipoCom'=>$datoVeh->id_tipocombustible,
                'precioCombGas'=>$precioCombGas->precio_x_galon,
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
            return response()->json([
                'error'=>false,
                'precioCombGas'=>$precioCombGas->precio_x_galon
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
        try{

        
            $guarda_det_des=new DetalleDespacho();
            $guarda_det_des->id_vehiculo=$request->vehiculo_id;
            $guarda_det_des->idcabecera_despacho=$request->idcabeceradespacho;
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
            Log::error('DespachoCombustibleController => guardarDetalle => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
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
            Log::error('DespachoCombustibleController => guardarDetalle => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function eliminarDetalle($id){
        try{
            $elim_detalle=DetalleDespacho::find($id);
            $elim_detalle->idusuarioelimina=auth()->user()->id;
            $elim_detalle->fecha_eliminacion=date('Y-m-d H:i:s');
            $elim_detalle->estado="Eliminado";
            if($elim_detalle->save()){
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

    public function listarTareaVeh($idVeh, $fecha){
        try{
            $fechaDesp=date('Y-m-d', strtotime($fecha));
           
            $bucartarea=Tarea::where('id_vehiculo',$idVeh)
            ->where('estado','!=','Eliminada')
            ->where(function($query)use($fechaDesp){
                $query->Where('fecha_inicio','<=',$fechaDesp)
                ->Where('fecha_fin','>=',$fechaDesp);
            })
            ->get();
            return response()->json([
                'error'=>false,
                'resultado'=>$bucartarea
            ]);
        }catch (\Throwable $e) {
            Log::error('DespachoCombustibleController => listarTareaVeh => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
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

            $detalle = DetalleDespacho::with('vehiculo','tipocombustible','cabecera','chofer')
            ->where('idcabecera_despacho',$idCab)->where('estado','Aprobado')
            ->orderBy('fecha_hora_despacho', 'desc')->get();
            
            if(sizeof($detalle)<=0){
              return back()->with(['mensajePInfoDespacho'=>'Aún no se han registrado despacho','estadoP'=>'danger']);
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


    }

}
