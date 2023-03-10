<?php

namespace App\Http\Controllers\VehiculosCombustible;
use App\Http\Controllers\Controller;
use App\Models\VehiculoCombustible\Gasolinera;
use App\Models\VehiculoCombustible\TipoCombustible;
use App\Models\VehiculoCombustible\GasolineraCombustible;
use \Log;
use Illuminate\Http\Request;

class GasolineraCombustibleController extends Controller
{
    public function index(){
        $gasolinera=Gasolinera::where('estado','A')->get();
        $combustible=TipoCombustible::where('estado','A')->get();
        return view('combustible.gasolinera_comb',[
            "gasolinera"=>$gasolinera,
            "combustible"=>$combustible
        ]);
    }


    public function listar(){
        try{
            $gas_comb=GasolineraCombustible::with('gasolinera','combustible')
            ->where('estado','!=','I')->get();
            return response()->json([
                'error'=>false,
                'resultado'=>$gas_comb
            ]);
        }catch (\Throwable $e) {
            Log::error('GasolineraCombustibleController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function editar($id){
        try{
            $gas_comb=GasolineraCombustible::where('estado','A')
            ->where('idgasolinera_comb', $id)
            ->first();
            
            return response()->json([
                'error'=>false,
                'resultado'=>$gas_comb
            ]);
        }catch (\Throwable $e) {
            Log::error('GasolineraCombustibleController => editar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }
    

    public function guardar(Request $request){
        
        $messages = [
            'gasolinera.required' => 'Debe seleccionar la gasolinera',  
            'combustible.required' => 'Debe seleccionar el combustible',   
            'precio.required' => 'Debe ingresar el precio',           
        ];
           

        $rules = [
            'gasolinera' =>"required",
            'combustible' =>"required",
            'precio' =>"required",
        ];

        $this->validate($request, $rules, $messages);
        try{

            $guarda_gasoli_comb=new GasolineraCombustible();
            $guarda_gasoli_comb->id_gasolinera=$request->gasolinera;
            $guarda_gasoli_comb->id_tipocombustible=$request->combustible;
            $guarda_gasoli_comb->precio_x_galon=$request->precio;
            $guarda_gasoli_comb->id_usuario_reg=auth()->user()->id;
            $guarda_gasoli_comb->fecha_reg=date('Y-m-d H:i:s');
            $guarda_gasoli_comb->estado="A";

            //validar que la gasolinera-combustible no se repita
            $valida_gestion_menu=GasolineraCombustible::where('id_gasolinera', $guarda_gasoli_comb->id_gasolinera )
            ->where('id_tipocombustible',$guarda_gasoli_comb->id_tipocombustible)
            ->where('estado','A')
            ->first();

            if(!is_null($valida_gestion_menu)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La información ya existe'
                ]);
            }

           
            if($guarda_gasoli_comb->save()){
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
            Log::error('GasolineraCombustibleController => guardar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }


    public function actualizar(Request $request, $id){
       
        $messages = [
            'gasolinera.required' => 'Debe seleccionar la gasolinera',  
            'combustible.required' => 'Debe seleccionar el combustible', 
            'precio.required' => 'Debe ingresar el precio',          
        ];
           

        $rules = [
            'gasolinera' =>"required",
            'combustible' =>"required",
            'precio' =>"required",
        ];

        $this->validate($request, $rules, $messages);
        try{

            $actualiza_gasoli_comb= GasolineraCombustible::find($id);
            $actualiza_gasoli_comb->id_gasolinera=$request->gasolinera;
            $actualiza_gasoli_comb->id_tipocombustible=$request->combustible;
            $actualiza_gasoli_comb->precio_x_galon=$request->precio;
            $actualiza_gasoli_comb->id_usuario_act=auth()->user()->id;
            $actualiza_gasoli_comb->fecha_actualiza=date('Y-m-d H:i:s');
            $actualiza_gasoli_comb->estado="A";

            //validar que la gasolinera-comb no se repita
            $valida_gestion_menu=GasolineraCombustible::where('id_gasolinera', $actualiza_gasoli_comb->id_gasolinera )
            ->where('id_tipocombustible',$actualiza_gasoli_comb->id_tipocombustible)
            ->where('estado','A')
            ->where('idgasolinera_comb','!=',$id)
            ->first();

            if(!is_null($valida_gestion_menu)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La información ya existe'
                ]);
            }

           
            if($actualiza_gasoli_comb->save()){
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
            Log::error('GasolineraCombustibleController => actualizar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

    public function eliminar($id){
        try{
            $gas_comb=GasolineraCombustible::find($id);
            $gas_comb->id_usuario_act=auth()->user()->id;
            $gas_comb->fecha_actualiza=date('Y-m-d H:i:s');
            $gas_comb->estado="I";
            if($gas_comb->save()){
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
            Log::error('GasolineraCombustibleController => eliminar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

}
