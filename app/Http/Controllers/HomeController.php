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

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(auth()->guest()){
            // goto RETORNARHOME;
            return redirect('/login');
        }
        return view('home');
    }

    public function test(){
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
        // dd($request->all());
        try{

            $guarda_pac=new Paciente();
            $guarda_pac->cedula=$request->cedula_pac;
            $guarda_pac->nombres=$request->nombres;
            $guarda_pac->apellidos=$request->apellidos;
            $guarda_pac->genero=$request->genero;
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
                return back()->with('creado','El paciente ha sido registrado');
            }else{
                return back()->withInput()->with(['mensajePaciente'=>'No se pudo registrar la información','estadoP'=>'danger']); 
            }




        }catch (\Throwable $e) {
            Log::error('TestController => guardar => mensaje => '.$e->getMessage());
            return back()->withInput()->with(['mensajePaciente'=>'Ocurrió un error','estadoP'=>'danger']);
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
            $actualiza_pac->genero=$request->genero;
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
