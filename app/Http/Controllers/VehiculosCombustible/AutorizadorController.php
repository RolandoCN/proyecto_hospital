<?php

namespace App\Http\Controllers\VehiculosCombustible;
use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\VehiculoCombustible\Vehiculo;
use App\Models\VehiculoCombustible\Autoriza;
use \Log;
use DB;
use Illuminate\Http\Request;

class AutorizadorController extends Controller
{
    
  
    public function index(){
      
        return view('combustible.autorizador');
    }


    public function listar(){
        try{
            $persona_aut=Autoriza::where('estado','!=','I')->get();
            return response()->json([
                'error'=>false,
                'resultado'=>$persona_aut
            ]);
        }catch (\Throwable $e) {
            Log::error('AutorizadorController => listar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }

    public function editar($id){
        try{
            $persona_au=Autoriza::where('estado','A')
            ->where('id_autorizado_salida', $id)
            ->first();
            
            return response()->json([
                'error'=>false,
                'resultado'=>$persona_au
            ]);
        }catch (\Throwable $e) {
            Log::error('AutorizadorController => editar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }
    

    public function guardar(Request $request){
        
        $messages = [
            'cedula.required' => 'Debe ingresar la cédula',  
            'nombres.required' => 'Debe ingresar los nombres',           
            'apellidos.required' => 'Debe ingresar los apellidos',  
          
        ];
           

        $rules = [
            'cedula' =>"required|string|max:10",
            'nombres' =>"required|string|max:100",
      
        ];

        $this->validate($request, $rules, $messages);
        try{

            $validaCedula=validarCedula($request->cedula);
            if($validaCedula==false){
                return response()->json([
                    "error"=>true,
                    "mensaje"=>"El numero de identificacion ingresado no es valido"
                ]);
            }          

            $guarda_autorizador=new Autoriza();
            $guarda_autorizador->cedula=$request->cedula;
            $guarda_autorizador->nombres=$request->nombres;
            $guarda_autorizador->telefono=$request->telefono;
            $guarda_autorizador->abreviacion_titulo=$request->abreviacion_titulo;
            $guarda_autorizador->email=$request->email;
            $guarda_autorizador->estado="A";
            $guarda_autorizador->estado_autoriza=$request->estado_autoriza;

            //validar que la cedula no se repita
            $valida_cedula=Autoriza::where('cedula', $guarda_autorizador->cedula)
            ->where('estado','A')
            ->first();

            if(!is_null($valida_cedula)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El número de cédula ya existe, en otra persona'
                ]);
            }

            //validar que la cedula no se email
            $valida_cedula=Autoriza::where('email', $guarda_autorizador->email)
            ->where('estado','A')
            ->first();

            if(!is_null($valida_cedula)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El email ya existe, en otra persona'
                ]);
            }

           
            if($guarda_autorizador->save()){
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
            Log::error('AutorizadorController => guardar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error'
            ]);
            
        }
    }


    public function actualizar(Request $request, $id){
       
    
        $messages = [
            'cedula.required' => 'Debe ingresar la cédula',  
            'nombres.required' => 'Debe ingresar los nombres',           

        ];
            

        $rules = [
            'cedula' =>"required|string|max:10",
            'nombres' =>"required|string|max:100",
                       
        ];

        $this->validate($request, $rules, $messages);
        try{

            $validaCedula=validarCedula($request->cedula);
            if($validaCedula==false){
                return response()->json([
                    "error"=>true,
                    "mensaje"=>"El numero de identificacion ingresado no es valido"
                ]);
            } 
            
           
            $actualiza_autorizador= Autoriza::find($id);
            $actualiza_autorizador->cedula=$request->cedula;
            $actualiza_autorizador->nombres=$request->nombres;
            $actualiza_autorizador->telefono=$request->telefono;
            $actualiza_autorizador->abreviacion_titulo=$request->abreviacion_titulo;
            $actualiza_autorizador->estado="A";
            $actualiza_autorizador->estado_autoriza=$request->estado_autoriza;
            $actualiza_autorizador->email=$request->email;

            //validar que la cedula no se repita
            $valida_cedula=Autoriza::where('cedula', $actualiza_autorizador->cedula)
            ->where('estado','A')
            ->where('id_autorizado_salida','!=', $id)
            ->first();

            if(!is_null($valida_cedula)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'El número de cédula ya existe, en otra persona'
                ]);
            }

            
            if($actualiza_autorizador->save()){
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
            Log::error('AutorizadorController => actualizar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

    
    public function subirP12(Request $request){
       
        try{

            $messages = [
                'idpersona.required' => 'No se pudo obtener la informacion de la empresa',
                'contrasena.required' => 'Debe ingresar la contraseña',
                'p12.required' => 'Debe subir el archivo'
            ];

            $rules = [
                'idpersona' =>"required",
                'contrasena' =>"required|max:50",
                'p12' =>"required"
            ];

            $this->validate($request, $rules, $messages);

            //verificamos si no se le a cargado un archivo previamente
            $data_firma=Autoriza::where('id_autorizado_salida',$request->idpersona)->first();
        
            $archivo = $request->p12;
        
            $extension = pathinfo($archivo->getClientOriginalName(), PATHINFO_EXTENSION);
            $nombre_arch= pathinfo($archivo->getClientOriginalName(),PATHINFO_FILENAME);

            $arch_tamanio= $_FILES['p12']['size'];           
            $arch_subido= fopen($_FILES['p12']['tmp_name'], 'r');           
            $binario_arch=fread($arch_subido, $arch_tamanio);

            $nombre_arch_certifi =$nombre_arch.".".$extension;
                      
            if(!isset($request->contrasena)){
                $mensajeError="Faltan datos por ingresar"; goto RETORNARERROR;
            }
            
            if($extension != "p12"){
                $mensajeError="El archivo del certificado debe ser formato .p12"; goto RETORNARERROR;
            }

            $datos_certificado = $this->obtenerInformacionCertificado(file_get_contents($archivo), $request->contrasena);
        
            if(sizeof($datos_certificado)==0){
                $mensajeError="Archivo o contraseña incorrectos"; goto RETORNARERROR;
            }
        
            //verificamos si el archivo esta exirado
            $fecha_actual = date('Y-m-d H:m:s');
            $datos_certificado["fecha_hasta"];
            if(strtotime($datos_certificado["fecha_hasta"]) <= strtotime($fecha_actual)){
                $mensajeError="El certificado cargado ya expiró"; goto RETORNARERROR;
            }   
                        
            $data_firma->archivo_certificado = $nombre_arch_certifi;
            $data_firma->valido_arch_desde = date(env('FORMATO_FECHA'), strtotime($datos_certificado["fecha_de"]));
            $data_firma->valido_arch_hasta = date(env('FORMATO_FECHA'), strtotime($datos_certificado["fecha_hasta"]));
            $data_firma->password_certif = base64_encode($request->contrasena);
            //$data_firma->base_firma = base64_encode($binario_arch);//
            $data_firma->save();

            \Storage::disk('public')->put($nombre_arch.".".$extension,  \File::get($archivo));

            return response()->json([
                'mensaje' => "Configuración realizada exitosamente",
                'error' => false
            ]);

           


            RETORNARERROR:
            return response()->json([
                'mensaje' => $mensajeError,
                'error' => true
            ]);
        
        }catch (\Throwable $e) {
            Log::error(__CLASS__." => ".__FUNCTION__." => Mensaje =>".$e->getMessage());
            return response()->json([
                "error"=>true,
                "mensaje"=>'Ocurrió un error, intentelo más tarde'
            ]);
        }
    }

     // recibe la ruta de un certificado.p12 y la contraseña y retorna la informacion del propietario de la firma
     public function obtenerInformacionCertificado($archivo_p12, $clave){

        try {
            $datos_certificado = [];
            $informacion_archivo = array();
    
            if (openssl_pkcs12_read($archivo_p12, $informacion_archivo, $clave)) {
                if (isset($informacion_archivo['cert'])) {
                    openssl_x509_export($informacion_archivo['cert'], $informacion_archivo);   
                    $informacion_archivo = openssl_x509_parse($informacion_archivo);
                    $datos_certificado["fecha_de"] = date('Y-m-d H:i:s', $informacion_archivo['validFrom_time_t']);
                    $datos_certificado["fecha_hasta"] = date('Y-m-d H:i:s', $informacion_archivo['validTo_time_t']);
                    $datos_certificado["propietario"] = $informacion_archivo["subject"]["CN"];
                }
            }
    
            return $datos_certificado;

        }catch(\Throwable $th){
            return $th->getMessage();;
        }

    }

    public function eliminar($id){
        try{
            
            //verificamos que no este asociado a un movimiento 
          
            $veri_Movimiento=DB::table('vc_movimiento')
            ->where('id_autorizado_salida',$id)
            ->where('estado','=', 'Activo')
            ->first();
            if(!is_null($veri_Movimiento)){
                return response()->json([
                    'error'=>true,
                    'mensaje'=>'La persona está asociado a un ingreso/salida de vehículo y no se puede eliminar'
                ]);
            }

            $persona_aut=Autoriza::find($id);
            $persona_aut->estado="I";
            if($persona_aut->save()){
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
            Log::error('AutorizadorController => eliminar => mensaje => '.$e->getMessage());
            return response()->json([
                'error'=>true,
                'mensaje'=>'Ocurrió un error, intentelo más tarde'
            ]);
            
        }
    }

}
