@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Formulario del Paciente
        </h1>

    </section>

    <section class="content">

        <form class="form-horizontal" onsubmit="return validaForm()" autocomplete="off" method="post"
            action="{{ url('/persona/add') }}">
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
                            <input type="text" class="form-control" id="nombre" placeholder="Cedula">
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Nombres</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Apellidos">
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Apellidos</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Apellidos">
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Género</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Género">
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Dirección</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Dirección">
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Provincia (Reside)</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Provincia">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Cantón (Reside)</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Cantón">
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Parroquia (Reside)</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Parroquia">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Provincia (Nacimiento)</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Cantón">
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Fecha Nacimiento</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="apellidos" placeholder="Parroquia">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Cédula Repr/Afiliado</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Cedula Rep/Afiliado">
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Nombre Rep/Afiliado</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Repres/Afiliado">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Parentesco Representante</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Parentesco">
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Orientación</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Orientación">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Seguro 1</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" style="width: 100%;">
                                <option selected="selected">Ministerio de Salud Pública</option>
                                <option>Seguro General</option>
                                <option>Seguro Campesino</option>
                                <option>ISSFA</option>
                                <option>ISSPO</option>
                                <option>Otro</option>
                             
                            </select>
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Seguro 2</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" style="width: 100%;">
                                <option selected="selected">Ninguno</option>
                                <option>Privado</option>
                            </select>
                               
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Zona</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Zona">
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
                            <input type="text" class="form-control" id="apellidos" placeholder="Nombre Padre">
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Nombre del Madre</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Nombre de la Madre">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Lugar Nacimiento</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Lugar Nacimiento">
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Discapacidad</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Discapacidad">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Tipo Discapacidad</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" style="width: 100%;">
                                <option selected="selected">Ninguna</option>
                                <option>Mental</option>
                                <option>Auditiva</option>
                                <option>Física</option>
                                <option>Visual</option>
                                <option>Otro</option>
                             
                            </select>
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Porcent. Discapacidad</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Porcent. Discapacidad">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Estado Civil</label>
                        <div class="col-sm-4">
                            {{-- <input type="text" class="form-control" id="apellidos" placeholder="Estado Civil">
                             --}}
                            <select class="form-control select2" style="width: 100%;">
                                <option selected="selected">Soltero/a</option>
                                <option>Casado/a</option>
                                <option>Divorciado/a</option>
                                <option>Union Libre</option>
                                <option>Viudo/a</option>
                              
                            </select>
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Nivel Instruccion</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" style="width: 100%;">
                                <option selected="selected">Edu Básica (Jovenes y Adultos)</option>
                                <option>Inicial</option>
                                <option>Edu Básica (Preparatoria)</option>
                                <option>Edu Básica (Elemental y Media)</option>
                                <option>Edu Básica (Superior)</option>
                                <option>Superior Técnico Superior</option>
                                <option>Superior 3er Nivel de Grado</option>
                                <option>Superior 4to Nivel de Grado</option>
                                <option>Ninguno</option>
                                <option>Se ignora</option>
                            </select>
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Grado Cultural</label>
                        <div class="col-sm-4">
                            <select class="form-control select2" style="width: 100%;">
                                <option selected="selected">Montuvio</option>
                                <option>Blanco</option>
                                <option>Mestizo</option>
                                <option>Mulato</option>
                                <option>Indigena</option>
                                <option>AfroEcuatoriano</option>
                                <option>Otro</option>
                              
                            </select>
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Nacionalidad</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Nacionalidad">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Ocupación</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Ocupación">
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Lugar Donde Trabaja</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Lugar Trabajo">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">En caso necesario, llamar a:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="LLamar a en caso necesario">
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Parentesco</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Parentesco">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Teléfono:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Teléfono">
                        </div>

                        <label for="inputPassword3" class="col-sm-2 control-label">Direccción</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Direccción">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="inputPassword3" class="col-sm-2 control-label">Correo Electrónico:</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="apellidos" placeholder="Correo Electrónico">
                        </div>

                       

                    </div>

                </div>
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <div class="form-group">
                        <div class="col-sm-12 text-center" >
                            <button type="submit" class="btn btn-success btn-sm">Guardar</button>

                            <button type="button" class="btn btn-danger btn-sm">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>



    </section>

    <script src="/js/test.js"></script>
@endsection
