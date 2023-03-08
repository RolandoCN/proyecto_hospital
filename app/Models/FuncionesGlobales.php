<?php
    
    function listarMenuSession(){
    
        $lista_modulo = array(); // iniciamos la variable de retorno como un arreglo vacio
        if(auth()->guest()){ // si no hay usuarios logueados no retornamos nada en el menu
            goto FINALM;
        }


        $idperfil=auth()->user()->perfil->id_perfil;
        $perfil=App\Models\VehiculoCombustible\Perfil::where('id_perfil',$idperfil)->where('estado','A')->first();

        //si no tiene un perfil activo mandamos el menu vacio
        if(is_null($perfil)){
            goto FINALM;
        }
        
        
        $gestiones_listado= App\Models\VehiculoCombustible\GestionMenu::select('id_gestion','estado')
        ->groupBy('id_gestion','estado')
        ->where('estado','A')
        ->get();

            
        $lista=[];
        foreach($gestiones_listado as $key=> $dataGestion){
            $consultaAcceso=App\Models\VehiculoCombustible\PerfilAcceso::with('menu')
            ->where('id_perfil',$idperfil)
            ->where('id_gestion', $dataGestion->id_gestion)
            ->get();

            if(!is_null($consultaAcceso)){
                $nombreGestion=App\Models\VehiculoCombustible\Gestion::where('id_gestion', $dataGestion->id_gestion)->first();

                array_push($lista_modulo,["gestion"=>$nombreGestion->descripcion,"icono"=>$nombreGestion->icono, "rutas"=>$consultaAcceso]);
            }
        }


           
        // goto FINALM;
        FINALM:
      
        return $lista_modulo; // retornamos el menu solo con las gestiones que le pertenecen al usuario

    }