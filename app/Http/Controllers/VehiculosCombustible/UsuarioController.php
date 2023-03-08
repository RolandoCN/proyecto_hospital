<?php

namespace App\Http\Controllers\VehiculosCombustible;
use App\Http\Controllers\Controller;
use App\Models\VehiculoCombustible\Perfil;
use App\Models\Persona;
use App\Models\User;
use App\Models\VehiculoCombustible\Tarea;
use App\Models\VehiculoCombustible\UsuarioPerfil;
use \Log;
use DB;
use Hash;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(){
        $persona=Persona::where('estado','A')->get();
        $perfil=Perfil::where('estado','A')->get();
        return view('combustible.usuario',[
            "persona"=>$persona,
            "perfil"=>$perfil
        ]);
    }


    public function listar(){
        try{
            $usuario=User::with('persona','perfil')->where('estado','!=','I')->get();
            return response()->json([
                'error'=>false,
                'resultado'=>$usuario
            ]);
        }catch (\Throwable $e) {
            Log::error('UsuarioController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function editar($id){
        try{
          
            $usuario=User::with('perfil')->where('estado','A')
            ->where('id', $id)
            ->first();
                        
            return response()->json([
                'error'=>false,
                'resultado'=>$usuario
            ]);
        }catch (\Throwable $e) {
            Log::error('UsuarioController => editar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }
    

    public function guardar(Request $request){
        
        $messages = [
            'idpersona.required' => 'Debe seleccionar la persona',
            'idperfil.required' => 'Debe seleccionar el perfil',           
        ];
           

        $rules = [
            'idpersona' =>"required",
            'idperfil' =>"required",
                 
        ];

        $this->validate($request, $rules, $messages);

        $transaction=DB::transaction(function() use($request){
            try{
                $pers=Persona::where('idpersona', $request->idpersona)
                ->where('estado','A')->first();
                if(is_null($pers)){
                    return response()->json([
                        'error'=>true,
                        'mensaje'=>'La persona ya no se encuentra activa'
                    ]);
                }

                $numero_ident_pers=$pers->cedula;

                //validar que exista
                $existe_user=User::where('tx_login', $numero_ident_pers)
                ->whereIn('estado',['A','I'])
                ->first();

                if(!is_null($existe_user)){
                    if($existe_user->estado=='A'){
                                        
                        return response()->json([
                            "error"=>true,
                            "mensaje"=>"La persona seleccionada ya tiene asignada una cuenta de usuario"
                        ]);
                    }else{

                        $existe_user->id_persona=$request->idpersona;
                        $existe_user->tx_login=$numero_ident_pers;
                        $existe_user->password=Hash::make($numero_ident_pers);
                        $existe_user->estado='A';
                        $existe_user->id_creadopor=auth()->user()->id;
                        $existe_user->fe_creacion=date('Y-m-d H:i:s');

                        $existe_user->save();

                        $UsuarioPerfil=UsuarioPerfil::where('id_usuario',$existe_user->id)->first();
                        if(!is_null($UsuarioPerfil)){

                            $UsuarioPerfil->id_perfil=$request->idperfil;

                            if($UsuarioPerfil->save()){
                                return response()->json([
                                    "error"=>false,
                                    "mensaje"=>"Cuenta creada exitosamente"
                                ]);
                            }else{
                                DB::Rollback();
                                return response()->json([
                                    "error"=>true,
                                    "mensaje"=>"No se pudo registrar la cuenta"
                                ]);
                            }
                        }
                        else{
                            
                            $UsuarioPerfil=new UsuarioPerfil();
                            $UsuarioPerfil->id_usuario=$existe_user->id;
                            $UsuarioPerfil->id_perfil=$request->idperfil;
                        
                            if($UsuarioPerfil->save()){
                                return response()->json([
                                    "error"=>false,
                                    "mensaje"=>"Cuenta creada exitosamente"
                                ]);
                            }else{
                                DB::Rollback();
                                return response()->json([
                                    "error"=>true,
                                    "mensaje"=>"No se pudo registrar la cuenta"
                                ]);
                            }
                        }
                    }
                }

                $Usuario=new User();
                $Usuario->id_persona=$request->idpersona;
                $Usuario->tx_login=$numero_ident_pers;
                $Usuario->password=Hash::make($numero_ident_pers);
                $Usuario->estado='A';
                $Usuario->id_creadopor=auth()->user()->id;
                $Usuario->fe_creacion=date('Y-m-d H:i:s');
                if($Usuario->save()){
                    $UsuarioPerfil=new UsuarioPerfil();
                    $UsuarioPerfil->id_usuario=$Usuario->id;
                    $UsuarioPerfil->id_perfil=$request->idperfil;
                
                    if($UsuarioPerfil->save()){
                        return response()->json([
                            "error"=>false,
                            "mensaje"=>"Cuenta creada exitosamente"
                        ]);
                    }else{
                        DB::Rollback();
                        return response()->json([
                            "error"=>true,
                            "mensaje"=>"No se pudo registrar la cuenta"
                        ]);
                    }

                }else{
                    DB::Rollback();
                    return response()->json([
                        "error"=>true,
                        "mensaje"=>"No se pudo registrar la información del usuario"
                    ]);
                }

            }catch (\Throwable $e) {
                DB::Rollback();
                Log::error('UsuarioController => guardar => mensaje => '.$e->getMessage().' linea => '.$e->getLine());
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'Ocurrió un error'
                ]);
                
            }
        });
        return $transaction;
    }


    public function actualizar(Request $request, $id){
       
    
        $messages = [
            'idperfil.required' => 'Debe seleccionar el perfil',           
        ];
           

        $rules = [
            'idperfil' =>"required",
                 
        ];


        $this->validate($request, $rules, $messages);
        try{
            $user=User::find($id);
            $UsuarioPerfil= UsuarioPerfil::where('id_usuario',$id)->first();
            $UsuarioPerfil->id_perfil=$request->idperfil;
        
            if($UsuarioPerfil->save()){
                return response()->json([
                    "error"=>false,
                    "mensaje"=>"Información actualizada exitosamente"
                ]);
            }else{
                return response()->json([
                    "error"=>true,
                    "mensaje"=>"No se pudo actualizar la información"
                ]);
            }

        }catch (\Throwable $e) {
            Log::error('UsuarioController => actualizar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

    public function eliminar($id){
        $transaction=DB::transaction(function() use($id){
            try{
            
                $usuario=User::where('id',$id)->first();
                $usuario->estado='I';
                $usuario->id_actualizado=auth()->user()->id;
                $usuario->fe_actualiza=date('Y-m-d H:i:s');
                $usuario->save();

                $UsuarioPerfil=UsuarioPerfil::where('id_usuario',$usuario->id)->first();
               
                //obtenemos el id del usuario logueado (si es el mismo al que se va eliminar lo mandamos al login)
                if(auth()->user()->id == $UsuarioPerfil->id_usuario){
                    $desloguear="S";
                }else{
                    $desloguear="N";
                }

                if($UsuarioPerfil->delete()){
                    return response()->json([
                        "error"=>false,
                        "mensaje"=>"Información eliminada exitosamente",
                        "desloguear"=>$desloguear
                    ]);
                }else{
                    return response()->json([
                        "error"=>true,
                        "mensaje"=>"No se pudo eliminar la información"
                    ]);
                }
                
            }catch (\Throwable $e) {
                DB::Rollback();
                Log::error('UsuarioController => eliminar => mensaje => '.$e->getMessage());
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'Ocurrió un error, intentelo más tarde'
                ]);
                
            }
        });
        return $transaction;
    }

}
