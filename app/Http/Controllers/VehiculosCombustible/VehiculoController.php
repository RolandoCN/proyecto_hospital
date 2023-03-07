<?php

namespace App\Http\Controllers\VehiculosCombustible;
use App\Http\Controllers\Controller;
use App\Models\VehiculoCombustible\Marca;
use App\Models\VehiculoCombustible\TipoCombustible;
use App\Models\VehiculoCombustible\TipoMedicion;
use App\Models\VehiculoCombustible\TipoUso;
use App\Models\VehiculoCombustible\Departamento;
use App\Models\VehiculoCombustible\Vehiculo;
use \Log;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{


    public function index(){
        $marca=Marca::all();
        $tipo_combust=TipoCombustible::all();
        $tipo_medic=TipoMedicion::all();
        $tipo_uso=TipoUso::all();
        $departamento=Departamento::all();
        return view('combustible.vehiculo',[
            "marca"=>$marca,
            "tipo_combust"=>$tipo_combust,
            "tipo_medic"=>$tipo_medic,
            "tipo_uso"=>$tipo_uso,
            "departamento"=>$departamento
        ]);
    }


    public function listar(){
        try{
            $veh=Vehiculo::with('tipoUso')->where('estado','A')->get();
            return response()->json([
                'error'=>false,
                'resultado'=>$veh
            ]);
        }catch (\Throwable $e) {
            Log::error('VehiculoController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function editar($id){
        try{
            $veh=Vehiculo::where('estado','A')
            ->where('id_vehiculo', $id)
            ->first();
            return response()->json([
                'error'=>false,
                'resultado'=>$veh
            ]);
        }catch (\Throwable $e) {
            Log::error('VehiculoController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }
    

    public function guardar(Request $request){
   
        $messages = [
            'codigo.required' => 'Debe ingresar el código',
            'placa.required' => 'Debe ingresar la placa',
            'descripcion.required' => 'Debe ingresar la descripción',
            'cmb_marca.required' => 'Debe seleccionar la marca',
            'modelo.required' => 'Debe ingresar el modelo',
            'cmb_tipouso.required' => 'Debe seleccionar tipo uso',
            'chasis.required' => 'Debe ingresar el chasis',
            'fabricacion.required' => 'Debe ingresar el año de fabricacion',
            'cmb_tipocombustible.required' => 'Debe seleccionar el tipo combustible',
            'cmb_tipomedicion.required' => 'Debe seleccionar el tipo medición',
            'departamento.required' => 'Debe seleccionar el departamento',
        ];
           

        $rules = [
            'codigo' =>"required|string|max:30",
            'placa' =>"required|string|max:12",
            'descripcion' => "required|string|max:100",
            'cmb_marca' => "required",
            'modelo' => "required|string|max:100",
            'cmb_tipouso' => "required",
            'chasis' =>"required|string|max:45",
            'fabricacion' => "required",
            'cmb_tipocombustible' => "required",
            'cmb_tipomedicion' => "required",
            'departamento' => "required",
           
        ];

        $this->validate($request, $rules, $messages);
        try{

            $guarda_veh=new Vehiculo();
            $guarda_veh->codigo_institucion=$request->codigo;
            $guarda_veh->placa=$request->placa;
            $guarda_veh->descripcion=$request->descripcion;
            $guarda_veh->id_marca=$request->cmb_marca;
            $guarda_veh->modelo=$request->modelo;
            $guarda_veh->id_tipouso=$request->cmb_tipouso;
            $guarda_veh->num_chasis=$request->chasis;
            $guarda_veh->anio_fabricacion=$request->fabricacion;
            $guarda_veh->id_tipocombustible=$request->cmb_tipocombustible;
            $guarda_veh->capacidad_galon=$request->capacidad;
            $guarda_veh->id_tipomedicion=$request->cmb_tipomedicion;
            $guarda_veh->id_departamento=$request->departamento;
            $guarda_veh->usuariocrea=auth()->user()->id;
            $guarda_veh->fecha_registro=date('Y-m-d H:i:s');
            $guarda_veh->estado="A";
           
            //comprobamos si no existe otra placa con el mismo dato
            $placaexiste=Vehiculo::where('placa',$guarda_veh->placa)
            ->where('estado','A')->first();
            if(!is_null($placaexiste)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La placa ingresada ya existe'
                ]);
            }

            //comprobamos si no existe otro codigo con el mismo dato
            $codigosexiste=Vehiculo::where('codigo_institucion',$guarda_veh->codigo_institucion)
            ->where('estado','A')->first();
            if(!is_null($codigosexiste)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El código ingresado ya existe'
                ]);
            }

            //comprobamos si no existe otro chasis con el mismo dato
            $numchasisexiste=Vehiculo::where('num_chasis',$guarda_veh->num_chasis)
            ->where('estado','A')->first();
            if(!is_null($numchasisexiste)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El número de chasis ingresado ya existe'
                ]);
            }

            if($guarda_veh->save()){
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
            Log::error('VehiculoController => guardar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }


    public function actualizar(Request $request, $id){
       
        $messages = [
            'codigo.required' => 'Debe ingresar el código',
            'placa.required' => 'Debe ingresar la placa',
            'descripcion.required' => 'Debe ingresar la descripción',
            'cmb_marca.required' => 'Debe seleccionar la marca',
            'modelo.required' => 'Debe ingresar el modelo',
            'cmb_tipouso.required' => 'Debe seleccionar tipo uso',
            'chasis.required' => 'Debe ingresar el chasis',
            'fabricacion.required' => 'Debe ingresar el año de fabricacion',
            'cmb_tipocombustible.required' => 'Debe seleccionar el tipo combustible',
            'cmb_tipomedicion.required' => 'Debe seleccionar el tipo medición',
            'departamento.required' => 'Debe seleccionar el departamento',
        ];
           

        $rules = [
            'codigo' =>"required|string|max:30",
            'placa' =>"required|string|max:12",
            'descripcion' => "required|string|max:100",
            'cmb_marca' => "required",
            'modelo' => "required|string|max:100",
            'cmb_tipouso' => "required",
            'chasis' =>"required|string|max:45",
            'fabricacion' => "required",
            'cmb_tipocombustible' => "required",
            'cmb_tipomedicion' => "required",
            'departamento' => "required",
           
        ];

        $this->validate($request, $rules, $messages);
        try{

            $guarda_veh= Vehiculo::find($id);
            $guarda_veh->codigo_institucion=$request->codigo;
            $guarda_veh->placa=$request->placa;
            $guarda_veh->descripcion=$request->descripcion;
            $guarda_veh->id_marca=$request->cmb_marca;
            $guarda_veh->modelo=$request->modelo;
            $guarda_veh->id_tipouso=$request->cmb_tipouso;
            $guarda_veh->num_chasis=$request->chasis;
            $guarda_veh->anio_fabricacion=$request->fabricacion;
            $guarda_veh->id_tipocombustible=$request->cmb_tipocombustible;
            $guarda_veh->capacidad_galon=$request->capacidad;
            $guarda_veh->id_tipomedicion=$request->cmb_tipomedicion;
            $guarda_veh->id_departamento=$request->departamento;
            $guarda_veh->usuario_actualiza=auth()->user()->id;
            $guarda_veh->fecha_actualizacion=date('Y-m-d H:i:s');
           
            //comprobamos si no existe otra placa con el mismo dato
            $placaexiste=Vehiculo::where('placa',$guarda_veh->placa)
            ->where('estado','A')
            ->where('id_vehiculo','!=',$id)
            ->first();
            if(!is_null($placaexiste)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La placa ingresada ya existe, para otro vehículo'
                ]);
            }

            //comprobamos si no existe otro codigo con el mismo dato
            $codigosexiste=Vehiculo::where('codigo_institucion',$guarda_veh->codigo_institucion)
            ->where('estado','A')
            ->where('id_vehiculo','!=',$id)
            ->first();
            if(!is_null($codigosexiste)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El código ingresado ya existe, para otro vehículo'
                ]);
            }

            //comprobamos si no existe otro chasis con el mismo dato
            $numchasisexiste=Vehiculo::where('num_chasis',$guarda_veh->num_chasis)
            ->where('estado','A')
            ->where('id_vehiculo','!=',$id)
            ->first();
            if(!is_null($numchasisexiste)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El número de chasis ingresado ya existe, para otro vehículo'
                ]);
            }

            if($guarda_veh->save()){
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
            Log::error('VehiculoController => actualizar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

    public function eliminar($id){
        try{
            $veh=Vehiculo::find($id);
            $veh->usuario_actualiza=auth()->user()->id;
            $veh->fecha_actualizacion=date('Y-m-d H:i:s');
            $veh->estado="I";
            if($veh->save()){
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
            Log::error('VehiculoController => eliminar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

}
