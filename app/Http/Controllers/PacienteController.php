<?php

namespace App\Http\Controllers;
use App\Models\Provincia;
use App\Models\GradoCultural;
use App\Models\NivelInstruccion;
use App\Models\Canton;
use App\Models\Parroquia;
use App\Models\Paciente;
use \Log;
use Illuminate\Http\Request;

class PacienteController extends Controller
{


    public function index(){
        $provincia=Provincia::all();
        $grado=GradoCultural::all();
        $nivel=NivelInstruccion::all();
        return view('paciente.registro',[
            "provincia"=>$provincia,
            "grado"=>$grado,
            "nivel"=>$nivel
        ]);
    }

    public function obtenerCantones($idprov){
        $canton_pr=Canton::where('idprovincia', $idprov)->get();
        return response()->json([
            'error'=>false,
            'canton_pr'=>$canton_pr
        ]);
    }

    public function obtenerParroquias($idcanton){
        $parroquia_canton=Parroquia::where('idcanton', $idcanton)->get();
        return response()->json([
            'error'=>false,
            'parroquia_canton'=>$parroquia_canton
        ]);
    }

    public function guardar(Request $request){

        $messages = [
            'cedula.required' => 'Debe ingresar la cédula',
            'nombres.required' => 'Debe ingresar los nombres',
            'apellidos.required' => 'Debe ingresar los apellidos',
            'sexo.required' => 'Debe seleccionar el sexo',
            'identidad_genero.required' => 'Debe seleccionar la identidad de género',
            'direccion_domiciliaria.required' => 'Debe ingresar la dirección',
            'idprovincia_reside.required' => 'Debe seleccionar la provincia',
            'idcanton_reside.required' => 'Debe seleccionar el cantón',
            'id_parroquia_reside.required' => 'Debe seleccionar la parroquia',
            'id_provincia_nacimiento.required' => 'Debe seleccionar la provincia nacimiento',
            'fecha_nacimiento.required' => 'Debe seleccionar la fecha de nacimiento',
            'cedula_rep_afiliado.required' => 'Debe ingresar la cedula afiliado',
            'nombre_rep_afiliado.required' => 'Debe ingresar el nombre del representante',
            'parentesco_rep.required' => 'Debe seleccionar el parentesco del representante',
            'orientacion.required' => 'Debe seleccionar la orientación',
            'seguro1.required' => 'Debe seleccionar el tipo seguro 1',
            'seguro2.required' => 'Debe seleccionar el tipo seguro 2',
            'zona.required' => 'Debe seleccionar la zona',
        ];

        $rules = [
            'cedula' =>"required|string|max:10",
            'nombres' =>"required|string|max:100",
            'apellidos' => "required|string|max:100",
            'sexo' => "required",
            'identidad_genero' => "required",
            'direccion_domiciliaria' =>"required|string|max:100",
            'idprovincia_reside' => "required",
            'idcanton_reside' => "required",
            'id_parroquia_reside' => "required",
            'id_provincia_nacimiento' => "required",
            'fecha_nacimiento' => "required",
            'cedula_rep_afiliado' => "required|string|max:10",
            'nombre_rep_afiliado' => "required|string|max:100",
            'parentesco_rep' => "required",
            'orientacion' => "required",
            'seguro1' => "required",
            'seguro2' => "required",
            'zona' => "required",
        ];

        $this->validate($request, $rules, $messages);
        try{

            $guarda_pac=new Paciente();
            $guarda_pac->cedula=$request->cedula_pac;
            $guarda_pac->nombres=$request->nombres;
            $guarda_pac->apellidos=$request->apellidos;
            $guarda_pac->sexo=$request->genero;
            $guarda_pac->identidad_genero=$request->identidad_genero;
            $guarda_pac->direccion_domiciliaria=$request->direccion_domiciliaria;
            $guarda_pac->idprovincia_reside=$request->provincia_res;
            $guarda_pac->idcanton_reside=$request->canton_res;
            $guarda_pac->id_parroquia_reside=$request->parroquia_res;
            $guarda_pac->id_provincia_nacimiento=$request->provincia_nac;
            $guarda_pac->fecha_nacimiento=$request->fecha_nac;
            $guarda_pac->cedula_rep_afiliado=$request->cedula_rep_afil;
            $guarda_pac->nombre_rep_afiliado=$request->name_rep_afil;
            $guarda_pac->parentesco_rep=$request->parentesco_rep_afil;
            $guarda_pac->orientacion=$request->orientacion;
            $guarda_pac->seguro1=$request->seguro1;
            $guarda_pac->seguro2=$request->seguro2;
            $guarda_pac->zona=$request->zona;
            $guarda_pac->nombre_padre=$request->nombre_padre;
            $guarda_pac->nombre_madre=$request->nombre_madre;
            $guarda_pac->lugar_nacimiento=$request->lugar_naci;
            $guarda_pac->discapacidad=$request->discapacidad;
            $guarda_pac->tipo_discapacidad=$request->tipo_disc;
            $guarda_pac->porcentaje_disc=$request->porce_dis;
            $guarda_pac->estado_civil=$request->estado_civil;
            $guarda_pac->idnivel_instruccion=$request->nivel_inst;
            $guarda_pac->idgrado_cultural=$request->grado_cultural;
            $guarda_pac->nacionalidad=$request->nacionalidad;
            $guarda_pac->ocupacion=$request->ocupacion;
            $guarda_pac->lugar_empleo=$request->lugar_empleo;
            $guarda_pac->llamar_en_emergencia=$request->llamar_emerg;
            $guarda_pac->parentesco=$request->parentesco;            
            $guarda_pac->telefono=$request->telefono;
            $guarda_pac->direccion=$request->direccion;
            $guarda_pac->correo_elec=$request->email;
            $guarda_pac->save();

            if($guarda_pac->save()){
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
            Log::error('TestController => guardar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function guardarGeneraraF1(Request $request){
        $messages = [
            'cedula.required' => 'Debe ingresar la cédula',
            'nombres.required' => 'Debe ingresar los nombres',
            'apellidos.required' => 'Debe ingresar los apellidos',
            'sexo.required' => 'Debe seleccionar el sexo',
            'identidad_genero.required' => 'Debe seleccionar la identidad de género',
            'direccion_domiciliaria.required' => 'Debe ingresar la dirección',
            'idprovincia_reside.required' => 'Debe seleccionar la provincia',
            'idcanton_reside.required' => 'Debe seleccionar el cantón',
            'id_parroquia_reside.required' => 'Debe seleccionar la parroquia',
            'id_provincia_nacimiento.required' => 'Debe seleccionar la provincia nacimiento',
            'fecha_nacimiento.required' => 'Debe seleccionar la fecha de nacimiento',
            'cedula_rep_afiliado.required' => 'Debe ingresar la cedula afiliado',
            'nombre_rep_afiliado.required' => 'Debe ingresar el nombre del representante',
            'parentesco_rep.required' => 'Debe seleccionar el parentesco del representante',
            'orientacion.required' => 'Debe seleccionar la orientación',
            'seguro1.required' => 'Debe seleccionar el tipo seguro 1',
            'seguro2.required' => 'Debe seleccionar el tipo seguro 2',
            'zona.required' => 'Debe seleccionar la zona',

            'nombre_padre.required' => 'Debe ingresar el nombre del padre',
            'nombre_madre.required' => 'Debe ingresar el nombre de la madre',
            'lugar_nacimiento.required' => 'Debe ingresar el lugar de nacimiento',
        ];

        $rules = [
            'cedula' =>"required|string|max:10",
            'nombres' =>"required|string|max:100",
            'apellidos' => "required|string|max:100",
            'sexo' => "required",
            'identidad_genero' => "required",
            'direccion_domiciliaria' =>"required|string|max:100",
            'idprovincia_reside' => "required",
            'idcanton_reside' => "required",
            'id_parroquia_reside' => "required",
            'id_provincia_nacimiento' => "required",
            'fecha_nacimiento' => "required",
            'cedula_rep_afiliado' => "required|string|max:10",
            'nombre_rep_afiliado' => "required|string|max:100",
            'parentesco_rep' => "required",
            'orientacion' => "required",
            'seguro1' => "required",
            'seguro2' => "required",
            'zona' => "required",

            'nombre_padre' => "required|string|max:100",
            'nombre_madre' => "required|string|max:100",
            'lugar_nacimiento' => "required|string|max:100",
        ];

        $this->validate($request, $rules, $messages);
        try{

            $guarda_pac=new Paciente();
            $guarda_pac->cedula=$request->cedula_pac;
            $guarda_pac->nombres=$request->nombres;
            $guarda_pac->apellidos=$request->apellidos;
            $guarda_pac->sexo=$request->genero;
            $guarda_pac->identidad_genero=$request->identidad_genero;
            $guarda_pac->direccion_domiciliaria=$request->direccion_domiciliaria;
            $guarda_pac->idprovincia_reside=$request->provincia_res;
            $guarda_pac->idcanton_reside=$request->canton_res;
            $guarda_pac->id_parroquia_reside=$request->parroquia_res;
            $guarda_pac->id_provincia_nacimiento=$request->provincia_nac;
            $guarda_pac->fecha_nacimiento=$request->fecha_nac;
            $guarda_pac->cedula_rep_afiliado=$request->cedula_rep_afil;
            $guarda_pac->nombre_rep_afiliado=$request->name_rep_afil;
            $guarda_pac->parentesco_rep=$request->parentesco_rep_afil;
            $guarda_pac->orientacion=$request->orientacion;
            $guarda_pac->seguro1=$request->seguro1;
            $guarda_pac->seguro2=$request->seguro2;
            $guarda_pac->zona=$request->zona;

            $guarda_pac->nombre_padre=$request->nombre_padre;
            $guarda_pac->nombre_madre=$request->nombre_madre;
            $guarda_pac->lugar_nacimiento=$request->lugar_naci;
            $guarda_pac->discapacidad=$request->discapacidad;
            $guarda_pac->tipo_discapacidad=$request->tipo_disc;
            $guarda_pac->porcentaje_disc=$request->porce_dis;
            $guarda_pac->estado_civil=$request->estado_civil;
            $guarda_pac->idnivel_instruccion=$request->nivel_inst;
            $guarda_pac->idgrado_cultural=$request->grado_cultural;
            $guarda_pac->nacionalidad=$request->nacionalidad;
            $guarda_pac->ocupacion=$request->ocupacion;
            $guarda_pac->lugar_empleo=$request->lugar_empleo;
            $guarda_pac->llamar_en_emergencia=$request->llamar_emerg;
            $guarda_pac->parentesco=$request->parentesco;            
            $guarda_pac->telefono=$request->telefono;
            $guarda_pac->direccion=$request->direccion;
            $guarda_pac->correo_elec=$request->email;
            $guarda_pac->formulario1_gen="Si";
            $guarda_pac->save();

            if($guarda_pac->save()){
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
            Log::error('TestController => guardar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function busqueda(){
        $provincia=Provincia::all();
        $grado=GradoCultural::all();
        $nivel=NivelInstruccion::all();
        return view('paciente.busqueda',[
            "provincia"=>$provincia,
            "grado"=>$grado,
            "nivel"=>$nivel
        ]);
        // return view('paciente.busqueda');
    }

    public function busquedaPaciente(Request $request){

        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $text=mb_strtoupper($search);
            $data=Paciente::where(function($query)use($text){
                $query->where('nombres', 'like', '%'.$text.'%')
                ->orWhere('apellidos', 'like', '%'.$text.'%')
                ->orWhere('cedula', 'like', '%'.$text.'%');
            })
            ->take(10)->get();
        }
        
        return response()->json($data);

    }
    public function infoPaciente($idpac){
        $paciente=Paciente::where('idpaciente', $idpac)->get();
        return response()->json([
            'error'=>false,
            'paciente'=>$paciente
        ]);
    }

    public function actualiza(Request $request, $id){
        // return $request->all();
        try{

            $actualiza_pac= Paciente::find($id);
            $actualiza_pac->cedula=$request->cedula_pac;
            $actualiza_pac->nombres=$request->nombres;
            $actualiza_pac->apellidos=$request->apellidos;
            $actualiza_pac->sexo=$request->genero;
            $actualiza_pac->identidad_genero=$request->identidad_genero;
            $actualiza_pac->direccion_domiciliaria=$request->direccion_domiciliaria;
            $actualiza_pac->idprovincia_reside=$request->provincia_res;
            $actualiza_pac->idcanton_reside=$request->canton_res;
            $actualiza_pac->id_parroquia_reside=$request->parroquia_res;
            $actualiza_pac->id_provincia_nacimiento=$request->provincia_nac;
            $actualiza_pac->fecha_nacimiento=$request->fecha_nac;
            $actualiza_pac->cedula_rep_afiliado=$request->cedula_rep_afil;
            $actualiza_pac->nombre_rep_afiliado=$request->name_rep_afil;
            $actualiza_pac->parentesco_rep=$request->parentesco_rep_afil;
            $actualiza_pac->orientacion=$request->orientacion;
            $actualiza_pac->seguro1=$request->seguro1;
            $actualiza_pac->seguro2=$request->seguro2;
            $actualiza_pac->zona=$request->zona;
            $actualiza_pac->nombre_padre=$request->nombre_padre;
            $actualiza_pac->nombre_madre=$request->nombre_madre;
            $actualiza_pac->lugar_nacimiento=$request->lugar_naci;
            $actualiza_pac->discapacidad=$request->discapacidad;
            $actualiza_pac->tipo_discapacidad=$request->tipo_disc;
            $actualiza_pac->porcentaje_disc=$request->porce_dis;
            $actualiza_pac->estado_civil=$request->estado_civil;
            $actualiza_pac->idnivel_instruccion=$request->nivel_inst;
            $actualiza_pac->idgrado_cultural=$request->grado_cultural;
            $actualiza_pac->nacionalidad=$request->nacionalidad;
            $actualiza_pac->ocupacion=$request->ocupacion;
            $actualiza_pac->lugar_empleo=$request->lugar_empleo;
            $actualiza_pac->llamar_en_emergencia=$request->llamar_emerg;
            $actualiza_pac->parentesco=$request->parentesco;
            
            $actualiza_pac->telefono=$request->telefono;
            $actualiza_pac->direccion=$request->direccion;
            $actualiza_pac->correo_elec=$request->email;
            $actualiza_pac->save();

            if($actualiza_pac->save()){
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
            Log::error('TestController => actualiza => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrrió un error, intentelo más tarde'
            ]);
        }
    }
}
