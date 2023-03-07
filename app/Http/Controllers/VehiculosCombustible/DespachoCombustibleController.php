<?php

namespace App\Http\Controllers\VehiculosCombustible;
use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\VehiculoCombustible\Vehiculo;
use App\Models\VehiculoCombustible\Tarea;
use App\Models\VehiculoCombustible\TipoMedicion;
use App\Models\VehiculoCombustible\Movimiento;
use App\Models\VehiculoCombustible\Gasolinera;
use App\Models\VehiculoCombustible\CabeceraDespacho;
use \Log;
use Illuminate\Http\Request;

class DespachoCombustibleController extends Controller
{


    public function index(){
        $persona=Persona::all();
        $gasolinera=Gasolinera::all();
      
        return view('combustible.despacho_comb',[
            "persona"=>$persona,
            "gasolinera"=>$gasolinera
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

    public function tareaVehiculo($id){
        try{
            $tarea=Tarea::where('estado','Pendiente')
            ->where('id_vehiculo', $id)
            ->where('fecha_inicio','>=',date('Y-m-d'))
            ->get();

            $medicion=Vehiculo::with('TipoMedicion')->where('id_vehiculo',$id)->first();

            $ultimoKm_Hm=Movimiento::where('id_vehiculo',$id)
            ->get()->last();
            
            return response()->json([
                'error'=>false,
                'resultado'=>$tarea,
                'medicion'=>$medicion,
                'ultimoKm_Hm'=>$ultimoKm_Hm
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


    public function eliminar($id){
        try{
            $mov=Movimiento::find($id);
            $mov->id_usuario_actualiza=auth()->user()->id;
            $mov->fecha_act=date('Y-m-d H:i:s');
            $mov->estado="Eliminada";
            if($mov->save()){
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
            Log::error('DespachoCombustibleController => eliminar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

}
