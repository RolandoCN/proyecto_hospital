@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Formulario del Paciente
        </h1>

    </section>

    <section class="content" id="content_consulta">

        <form class="form-horizontal" id="form_registro" autocomplete="off" method="post"
            action="{{ url('/guardar-paciente') }}">
            {{ csrf_field() }}
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Datos Generales del Paciente</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        
                    </div>
                </div>
                <div class="box-body">

                  

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Cedula</label>
                        <div class="col-sm-4">
                            <input type="number" maxlength="10"  class="form-control" id="cedula_pac" name="cedula_pac" placeholder="Cedula">
                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_cedula">
                                <strong id="txt_error_cedula"></strong>
                            </span>
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Nombres</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="nombres" name="nombres" placeholder="Nombres"  maxlength="10" onKeyPress="if(this.value.length==10) return false;">
                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_nombre">
                                <strong id="txt_error_nombre"></strong>
                            </span>
                           
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Apellidos</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos"  name="apellidos" placeholder="Apellidos" maxlength="100">
                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_apellidos">
                                <strong id="txt_error_apellidos"></strong>
                            </span>
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Sexo</label>
                        <div class="col-sm-4">
                            <select data-placeholder="Seleccione Un Sexo" class="form-control select2" style="width: 100%;" name="genero" id="genero">
                                <option selected="selected" value="Hombre">Hombre</option>
                                <option value="Mujer">Mujer</option>
                                <option value="Indeterminado">Indeterminado</option>
                              
                            </select>
                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_sexo">
                                <strong id="txt_error_sexo"></strong>
                            </span>
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Identidad del Género</label>
                        <div class="col-sm-4">
                            <select data-placeholder="Seleccione Una Identidad" class="form-control select2" style="width: 100%;" name="identidad_genero" id="identidad_genero">
                                <option selected="selected" value="Ninguno">Ninguno</option>
                                <option value="TransMasculino">TransMasculino</option>
                                <option value="TransFemenino">TransFemenino</option>
                              
                            </select>

                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_ident_gen">
                                <strong id="txt_error_ident_gen"></strong>
                            </span>
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Dirección</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="direccion_domiciliaria" id="direccion_domiciliaria" placeholder="Dirección" maxlength="100">
                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_direccion">
                                <strong id="txt_error_direccion"></strong>
                            </span>
                        </div>

                        

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Provincia (Reside)</label>
                        <div class="col-sm-4" >
                            <select data-placeholder="Seleccione Un Provincia" style="width: 100%;" class="form-control select2" name="provincia_res" id="provincia_res" onchange="seleccProvReside()">
                               
                                @foreach ($provincia as $dato)
                                    <option value="{{ $dato->idprovincia }}" >{{ $dato->descripcion }}</option>
                                @endforeach
                            </select>

                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_prov_res">
                                <strong id="txt_error_prov_res"></strong>
                            </span>
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Cantón (Reside)</label>
                        <div class="col-sm-4">
                            <select data-placeholder="Seleccione Un Canton" style="width: 100%;" class="form-control select2" name="canton_res" id="canton_res" onchange="seleccCantonReside()">
                             
                            </select>

                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_canton_res">
                                <strong id="txt_error_canton_res"></strong>
                            </span>
                        </div>

                       

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Parroquia (Reside)</label>
                        <div class="col-sm-4">
                            <select data-placeholder="Seleccione Un Parroquia" style="width: 100%;" class="form-control select2" name="parroquia_res" id="parroquia_res" >
                             
                            </select>

                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_parroquia_res">
                                <strong id="txt_error_parroquia_res"></strong>
                            </span>

                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Provincia (Nacimiento)</label>
                        <div class="col-sm-4">
                            <select data-placeholder="Seleccione Un Provincia" style="width: 100%;" class="form-control select2" id="provincia_nac" name="provincia_nac">
                                                                   
                                @foreach ($provincia as $dato)
                                    <option value="{{ $dato->idprovincia }}" {{ (old("provincia_nac") == $dato->id ? "selected":"") }}>{{ $dato->descripcion }}</option>
                                @endforeach
                            </select>

                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_provincia_nac">
                                <strong id="txt_error_provincia_nac"></strong>
                            </span>

                        </div>

                        

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Fecha Nacimiento</label>
                        <div class="col-sm-4">
                            <input type="date" onchange="calcularEdad()" class="form-control" id="fecha_nac" name="fecha_nac" placeholder="Parroquia">

                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_fecha_nac">
                                <strong id="txt_error_fecha_nac"></strong>
                            </span>
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Edad (Años)</label>
                        <div class="col-sm-4">
                            <input type="number" readonly class="form-control" id="edad" name="edad" placeholder="Edad">
                        </div>

                       

                    </div>

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Cédula Repr/Afiliado</label>
                        <div class="col-sm-4">
                            <input type="number" maxlength="10" class="form-control" id="cedula_rep_afil" name="cedula_rep_afil" placeholder="Cedula Rep/Afiliado">

                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_cedula_rep_afil">
                                <strong id="txt_error_cedula_rep_afil"></strong>
                            </span>

                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Nombre Rep/Afiliado</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="name_rep_afil" name="name_rep_afil" placeholder="Repres/Afiliado">

                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_name_rep_afil">
                                <strong id="txt_error_name_rep_afil"></strong>
                            </span>

                        </div>

                    </div>


                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Parentesco Representante</label>
                        <div class="col-sm-4">
                            <select data-placeholder="Seleccione Un Parentesco" class="form-control select2" style="width: 100%;" name="parentesco_rep_afil" id="parentesco_rep_afil">
                                <option selected="selected" value="Titular">Titular</option>
                                <option value="Conyuge">Conyuge</option>
                                                             
                            </select>

                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_parentesco_rep_afil">
                                <strong id="txt_error_parentesco_rep_afil"></strong>
                            </span>

                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Orientación</label>
                        <div class="col-sm-4">
                            <select data-placeholder="Seleccione Una Opción" class="form-control select2" style="width: 100%;" name="orientacion" id="orientacion">
                                <option selected="selected" value="Ninguno">Ninguno</option>
                                <option value="Otro">Otro</option>
                              
                            </select>
                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_orientacion">
                                <strong id="txt_error_orientacion"></strong>
                            </span>
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Seguro 1</label>
                        <div class="col-sm-4">
                            <select data-placeholder="Seleccione Un Opción" class="form-control select2" style="width: 100%;" name="seguro1" id="seguro1">
                              
                                <option selected="selected"value="Ministerio de Salud Pública">Ministerio de Salud Pública</option>
                                <option value="Seguro General">Seguro General</option>
                                <option value="Seguro Campesino">Seguro Campesino</option>
                                <option value="ISSFA">ISSFA</option>
                                <option value="ISSPO">ISSPO</option>
                                <option value="Otro">Otro</option>
                             
                            </select>

                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_seguro1">
                                <strong id="txt_error_seguro1"></strong>
                            </span>
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Seguro 2</label>
                        <div class="col-sm-4">
                            <select data-placeholder="Seleccione Un Opción" class="form-control select2" style="width: 100%;"  name="seguro2" id="seguro2">
                                <option selected="selected" value="Ninguno">Ninguno</option>
                                <option value="Privado">Privado</option>
                            </select>

                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_seguro2">
                                <strong id="txt_error_seguro2"></strong>
                            </span>
                               
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Zona</label>
                        <div class="col-sm-4">
                            <select data-placeholder="Seleccione Un Zona" class="form-control select2" style="width: 100%;"  name="zona" id="zona">
                                <option selected="selected" value="Urbano">Urbano</option>
                                <option value="Rural">Rural</option>
                            </select>

                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_zona">
                                <strong id="txt_error_zona"></strong>
                            </span>

                        </div>


                    </div>

                    <!-- <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                                </div>
                            </div> -->


                </div>

            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Datos Adicionales del Paciente</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                       
                    </div>
                </div>
                <div class="box-body">
                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Nombre del Padre</label>
                        <div class="col-sm-4">
                            <input type="text" maxlength="100" class="form-control" name="nombre_padre"  id="nombre_padre" placeholder="Nombre Padre">

                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_nombre_padre">
                                <strong id="txt_error_nombre_padre"></strong>
                            </span>

                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Nombre del Madre</label>
                        <div class="col-sm-4">
                            <input type="text" maxlength="100" class="form-control" name="nombre_madre"  id="nombre_madre" placeholder="Nombre de la Madre">

                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_nombre_madre">
                                <strong id="txt_error_nombre_madre"></strong>
                            </span>

                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Lugar Nacimiento</label>
                        <div class="col-sm-4">
                            <input type="text" maxlength="100" class="form-control" name="lugar_naci" id="lugar_naci" placeholder="Lugar Nacimiento">

                            <span class="invalid-feedback" role="alert" style="color:red; display:none
                            " id="error_lugar_naci">
                                <strong id="txt_error_lugar_naci"></strong>
                            </span>

                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Discapacidad</label>
                        <div class="col-sm-4">
                            <select data-placeholder="Seleccione Una Opción" class="form-control select2" style="width: 100%;"  name="discapacidad" id="discapacidad" onchange="seleccDisc()">
                                <option selected="selected" value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Tipo Discapacidad</label>
                        <div class="col-sm-4">
                            <select data-placeholder="Seleccione Un Tipo" class="form-control select2" style="width: 100%;" name="tipo_disc" id="tipo_disc">
                                {{-- <option selected="selected" value="Ninguna">Ninguna</option>
                                <option value="Mental">Mental</option>
                                <option value="Auditiva">Auditiva</option>
                                <option value="Física">Física</option>
                                <option value="Visual">Visual</option>
                                <option value="Otro">Otro</option> --}}
                             
                            </select>
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Porcent. Discapacidad</label>
                        <div class="col-sm-4">
                            <input type="number" step="0.01" class="form-control" id="porce_dis" name="porce_dis" placeholder="Porcent. Discapacidad">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Estado Civil</label>
                        <div class="col-sm-4">
                           
                            <select data-placeholder="Seleccione Un Estado" class="form-control select2" style="width: 100%;" name="estado_civil" id="estado_civil">
                                <option selected="selected">Soltero/a</option>
                                <option>Casado/a</option>
                                <option>Divorciado/a</option>
                                <option>Union Libre</option>
                                <option>Viudo/a</option>
                              
                            </select>
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Nivel Instruccion</label>
                        <div class="col-sm-4">
                          
                            <select data-placeholder="Seleccione Un Nivel" data-placeholder="Seleccione Un Nivel" style="width: 100%;" class="form-control select2" id="nivel_inst" name="nivel_inst">
                                
                                @foreach ($nivel as $dato)
                                    <option value="{{ $dato->id }}" {{ (old("nivel_inst") == $dato->id ? "selected":"") }}>{{ $dato->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Grado Cultural</label>
                        <div class="col-sm-4">
                           
                            <select data-placeholder="Seleccione Un Grado" data-placeholder="Seleccione Un Grado" style="width: 100%;" class="form-control select2" id="grado_cultural" name="grado_cultural">
                                                                   
                                @foreach ($grado as $dato)
                                    <option value="{{ $dato->id }}" {{ (old("grado_cultural") == $dato->id ? "selected":"") }}>{{ $dato->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Nacionalidad</label>
                        <div class="col-sm-4">
                            <input type="text" maxlength="50" class="form-control" name="nacionalidad" id="nacionalidad" placeholder="Nacionalidad">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Ocupación</label>
                        <div class="col-sm-4">
                            <input type="text" maxlength="100" class="form-control" id="ocupacion" name="ocupacion" placeholder="Ocupación">
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Lugar Donde Trabaja</label>
                        <div class="col-sm-4">
                            <input type="text" maxlength="200" class="form-control" id="lugar_empleo" name="lugar_empleo" placeholder="Lugar Trabajo">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">En caso necesario, llamar a:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="llamar_emerg" name="llamar_emerg" placeholder="LLamar a en caso necesario" maxlength="300">
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Parentesco</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="parentesco" name="parentesco" placeholder="Parentesco" maxlength="15">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Teléfono:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono" maxlength="15">
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Direccción</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Direccción" maxlength="100">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Correo Electrónico:</label>
                        <div class="col-sm-4">
                            <input type="email" maxlength="100" class="form-control" id="email"  name="email" placeholder="Correo Electrónico">
                        </div>

                       

                    </div>

                </div>
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <div class="form-group">
                        <div class="col-sm-12 text-center" >
                           
                            <button type="button" onclick="guardarInfoGenPac()" class="btn btn-info btn-sm">Guardar</button>
                            <button type="submit" class="btn btn-success btn-sm">Generar Form 1</button>
                            <button type="button" onclick="limpiarCampos()" class="btn btn-danger btn-sm">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>



    </section>

    {{-- <script src="/js/registro_paciente.js"></script> --}}

    <script>
       
       

    </script>
@endsection
@section('scripts')

    <script src="/js/registro_paciente.js"></script>

    <script>
        //provincia cargada al iniciiar
        
        // seleccProvReside()
        // seleccDisc()
        limpiarCampos()
    </script>
{{-- <script>
     
    let error=$('#error').val()
    let creado=$('#creado').val()
    // alert(error)
   
    if(error!=undefined){
       
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");
        // return
    }

    
    
    else if(creado!=undefined){
        // alert(creado)
        alertNotificar(creado,"success");
        // return
    }
</script> --}}

@endsection
