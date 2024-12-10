<?php

namespace App\Http\Controllers\VehiculosCombustible;
use App\Http\Controllers\Controller;
use App\Models\VehiculoCombustible\TipoCombustible;
use App\Models\VehiculoCombustible\Vehiculo;
use App\Models\VehiculoCombustible\Gasolinera;
use App\Models\VehiculoCombustible\Movimiento;
use App\Models\VehiculoCombustible\DetalleDespacho;
use App\Models\VehiculoCombustible\Ticket;
use \Log;
use DB;
use Illuminate\Http\Request;

class TicketController extends Controller
{

    public function listado(){
        $tipo_combust=TipoCombustible::where('estado','A')->get();
        $gasolinera=Gasolinera::where('estado','A')->get();
        $vehiculo=Vehiculo::where('estado','A')
        ->where('estado_vehiculo','Operativo')
        ->get();

        return view('combustible.listado_ticket',[
            "gasolinera"=>$gasolinera,
            "tipo_combust"=>$tipo_combust,
            "vehiculo"=>$vehiculo,
        ]);
    }

    public function listarGeneral(){
        try{
            $ticket=Ticket::with('vehiculo', 'gasolinera', 'combustible', 'chofer')
            ->where('estado','A')
            ->get();
           
            return response()->json([
                'error'=>false,
                'resultado'=>$ticket
            ]);
        }catch (\Throwable $e) {
            Log::error('TicketController => listarGeneral => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function listarFechaTicket($desde, $hasta){
        try{
            $desde = date('Y-m-d 00:00:00', strtotime($desde));
            $hasta = date('Y-m-d 23:59:59', strtotime($hasta));
            $ticket=Ticket::with('vehiculo', 'gasolinera', 'combustible', 'chofer')
            ->where('estado','A')
            ->whereBetween('f_despacho', [$desde, $hasta])
            ->get();
           
            return response()->json([
                'error'=>false,
                'resultado'=>$ticket
            ]);
        }catch (\Throwable $e) {
            Log::error('TicketController => listarGeneral => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function index(){
       
        $tipo_combust=TipoCombustible::where('estado','A')->get();
        $gasolinera=Gasolinera::where('estado','A')->get();
        $vehiculo=Vehiculo::where('estado','A')
        ->where('estado_vehiculo','Operativo')
        ->get();

        return view('combustible.ticket',[
            "gasolinera"=>$gasolinera,
            "tipo_combust"=>$tipo_combust,
            "vehiculo"=>$vehiculo,
        ]);
    }

    public function buscaTicketChofer(Request $request){
      
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $text=mb_strtoupper($search);
            $data=Ticket::where(function($query)use($text){
                $query->where('numero_ticket', 'like', '%'.$text.'%');
            })
            ->where('idchofer', auth()->user()->id_persona) 
            ->where('estado', 'A')
            ->take(10)->get();
        }
       
        return response()->json($data);

    }

    public function infoTicketChofer($num){
        $data=Ticket::where('numero_ticket',$num)
        ->where('idchofer', auth()->user()->id_persona) 
        ->where('estado', 'A')
        ->first();
        if(is_null($data)){
            return response()->json([
                'error'=>true,
                'mensaje'=>'No se encontro información'
            ]);
        }

        return response()->json([
            'error'=>false,
            'resultado'=>$data
        ]);
    }
    public function listar(){
        try{
            $ticket=Ticket::with('vehiculo', 'gasolinera', 'combustible', 'chofer')
            ->where('estado','A')
            ->where('idchofer', auth()->user()->id_persona)
            ->get();
           
            return response()->json([
                'error'=>false,
                'resultado'=>$ticket
            ]);
        }catch (\Throwable $e) {
            Log::error('TicketController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function editar($id){
        try{
            $ticket=Ticket::where('estado','A')
            ->where('id', $id)
            ->first();
            return response()->json([
                'error'=>false,
                'resultado'=>$ticket
            ]);
        }catch (\Throwable $e) {
            Log::error('TicketController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function infoVehiCombustible($idveh){
        try{
            $vehiculoCom=Vehiculo::where('id_vehiculo',$idveh)
            ->first();
            return response()->json([
                'error'=>false,
                'resultado'=>$vehiculoCom
            ]);
        }catch (\Throwable $e) {
            Log::error('TicketController => infoVehiCombustible => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }
    

    public function guardar(Request $request){
   
        $messages = [
            'numero_ticket.required' => 'Debe ingresar el numero de ticket',
            'total.required' => 'Debe ingresar el total',
            'cmb_tipocombustible.required' => 'Debe seleccionar el tipo combustible',
            'cmb_gasolinera.required' => 'Debe seleccionar la gasolinera',
        ];
           

        $rules = [
            'numero_ticket' =>"required|string|max:30",
            'total' =>"required|string|max:32",
            'cmb_tipocombustible' => "required",
            'cmb_gasolinera' => "required",
           
        ];

        $this->validate($request, $rules, $messages);
        try{

            $guarda_ticket=new Ticket();
            $guarda_ticket->numero_ticket=$request->numero_ticket;
            $guarda_ticket->id_vehiculo=$request->id_vehiculo;
            $guarda_ticket->id_gasolinera=$request->cmb_gasolinera;
            $guarda_ticket->id_tipocombustible =$request->cmb_tipocombustible;
            $guarda_ticket->total=$request->total;
            $guarda_ticket->f_despacho=$request->f_despacho;
            $guarda_ticket->idchofer=auth()->user()->id_persona;
            $guarda_ticket->fecha_registro=date('Y-m-d H:i:s');
            $guarda_ticket->estado="A";
           
            //comprobamos si no existe otra ticket con el mismo numero
            $ticket_existe=Ticket::where('numero_ticket',$guarda_ticket->numero_ticket)
            ->where('estado','A')->first();
            if(!is_null($ticket_existe)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El ticket ingresado ya existe'
                ]);
            }

            if($guarda_ticket->save()){
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
            Log::error('TicketController => guardar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }


    public function actualizar(Request $request, $id){
       
       
        $messages = [
            'numero_ticket.required' => 'Debe ingresar el numero de ticket',
            'total.required' => 'Debe ingresar el total',
            'cmb_tipocombustible.required' => 'Debe seleccionar el tipo combustible',
            'cmb_gasolinera.required' => 'Debe seleccionar la gasolinera',
        ];
            

        $rules = [
            'numero_ticket' =>"required|string|max:30",
            'total' =>"required|string|max:32",
            'cmb_tipocombustible' => "required",
            'cmb_gasolinera' => "required",
            
        ];
    
        $this->validate($request, $rules, $messages);
        try{
    
            $actualiza_ticket= Ticket::find($id);
            $actualiza_ticket->numero_ticket=$request->numero_ticket;
            $actualiza_ticket->id_vehiculo=$request->id_vehiculo;
            $actualiza_ticket->id_gasolinera=$request->cmb_gasolinera;
            $actualiza_ticket->id_tipocombustible =$request->cmb_tipocombustible;
            $actualiza_ticket->total=$request->total;
            $actualiza_ticket->f_despacho=$request->f_despacho;
            $actualiza_ticket->idchofer=auth()->user()->id_persona;
            $actualiza_ticket->fecha_actualizacion=date('Y-m-d H:i:s');
            $actualiza_ticket->estado="A";
            
            //comprobamos si no existe otra ticket con el mismo numero
            $ticket_existe=Ticket::where('numero_ticket',$actualiza_ticket->numero_ticket)
            ->where('estado','A')
            ->where('id','!=',$id)
            ->first();
            if(!is_null($ticket_existe)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El ticket ingresado ya existe'
                ]);
            }
            
            $consulta_ticket_ingresado=Ticket::find($id);
            $numero_ticket_ingresado=$consulta_ticket_ingresado->numero_ticket;

            $veri_Movimiento=Movimiento::where('nro_ticket',$numero_ticket_ingresado)
            ->where('estado','!=', 'Eliminada')
            ->first();
            if(!is_null($veri_Movimiento)){
                // return response()->json([
                //     'error'=>true,
                //     'mensaje'=>'El ticket #'.$numero_ticket_ingresado. ' está asociado a una orden de salida y no se puede actualizar'
                // ]);

                $veri_Movimiento->estado="Eliminada";
                $veri_Movimiento->id_usuario_actualiza=auth()->user()->id;
                $veri_Movimiento->fecha_act=date('Y-m-d H:i:s');
                $veri_Movimiento->save();
            }

            $veri_Despacho=DetalleDespacho::where('num_factura_ticket',$numero_ticket_ingresado)
            ->where('estado','!=', 'Eliminado')
            ->first();
            if(!is_null($veri_Despacho)){
                // return response()->json([
                //     'error'=>true,
                //     'mensaje'=>'El ticket #'.$numero_ticket_ingresado. ' está asociado a un despacho de combustible y no se puede actualizar'
                // ]);
                $veri_Despacho->estado='Eliminado';
                $veri_Despacho->save();
            }
           
            if($actualiza_ticket->save()){
                return response()->json([
                    'error'=>false,
                    'mensaje'=>'Información actualizada exitosamente'
                ]);
            }else{
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'No se pudo actualizar la información'
                ]);
            }
    
    
        }catch (\Throwable $e) {
            Log::error('TicketController => actualizar => mensaje => '.$e->getMessage().' => Linea => '.$e->getLine());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

    public function eliminar($id){
        try{
            $ticket_elim=Ticket::find($id);
            //verificamos que no este asociado a un  movimiento y despacho en estado activo
          
            $veri_Movimiento=DB::table('vc_movimiento')
            ->where('nro_ticket',$ticket_elim->numero_ticket)
            ->where('estado','!=', 'Eliminada')
            ->first();
            if(!is_null($veri_Movimiento)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El ticket está asociado a una orden de salida y no se puede eliminar'
                ]);
            }

            $veri_Despacho=DB::table('vc_detalle_despacho')
            ->where('num_factura_ticket',$ticket_elim->numero_ticket)
            ->where('estado','!=', 'Eliminado')
            ->first();
            if(!is_null($veri_Despacho)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El ticket está asociado a un despacho de combustible y no se puede eliminar'
                ]);
            }
            
            $ticket_elim->fecha_actualizacion=date('Y-m-d H:i:s');
            $ticket_elim->estado="I";
            if($ticket_elim->save()){
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
            Log::error('TicketController => eliminar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

}
