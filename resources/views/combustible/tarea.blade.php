@extends('layouts.app')

@section('content')

    
    <section class="content-header">
        <h1>
            Gestión Tareas Vehículos
        </h1>

    </section>


   

    <section class="content" id="content_form">

        <div class="box" id="listado_veh">
            <div class="box-header with-border">
                <h3 class="box-title">Listado </h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                    
                </div>

              
            </div>
            <div class="box-body">

                <div class="col-md-12" style="text-align:right; margin-bottom:20px; margin-top:10px">
                    <button type="button" onclick="visualizarForm('N')" class="btn btn-primary btn-sm">Nuevo</button>
                </div>

                <div class="table-responsive">
                    <table id="tabla_tarea" width="100%"class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Vehículo</th>
                                <th>Detalle</th>
                                <th>Fecha Solicitud</th>
                                <th>Estado</th>
                                <th style="min-width: 30%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5"><center>No hay Datos Disponibles</td>
                            </tr>
                            
                        </tbody>
                      
                    </table>  
                  </div>    

                
            </div>

        </div>


        <div id="form_ing" style="display:none">
            <form class="form-horizontal" id="form_registro_tarea" autocomplete="off" method="post"
                action="">
                {{ csrf_field() }}
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title" id="titulo_form"> </h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                            
                        </div>
                    </div>
                    <div class="box-body">

                        <div class="form-group">
                          
                            <label for="inputPassword3" class="col-sm-3 control-label">Vehículo</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Seleccione Un Vehículo" style="width: 100%;" class="form-control select2" name="vehiculo_tarea" id="vehiculo_tarea" >
                                
                                    @foreach ($vehiculo as $dato)
                                        <option value=""></option>
                                        <option value="{{ $dato->id_vehiculo }}" >{{ $dato->descripcion }} {{ $dato->codigo_institucion }} [{{ $dato->placa }}] </option>
                                    @endforeach
                                </select>
                            
                            
                            </div>

                        </div>

                        <div class="form-group">
                          
                            <label for="inputPassword3" class="col-sm-3 control-label">Chofer</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Seleccione Un Chofer" style="width: 100%;" class="form-control select2" name="choferSalvo" id="choferSalvo" >
                                
                                    @foreach ($persona as $dato)
                                        <option value=""></option>
                                        <option value="{{ $dato->idpersona}}" >{{ $dato->nombres }} {{ $dato->apellidos }}</option>
                                    @endforeach
                                </select>
                            
                            </div>

                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Fecha Inicio</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" placeholder="Ingrese una descripción" id="fecha_ini" name="fecha_ini">
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Fecha Fin</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" placeholder="Ingrese una fecha" id="fecha_fin" name="fecha_fin">
                            </div>
                            
                        </div>

                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-3 control-label">Motivo</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" placeholder="Ingrese el motivo" name="motivo" id="motivo"></textarea>
                            </div>

                           
                        </div>

                      
                        
                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12 text-center" >
                            
                                <button type="submit" class="btn btn-success btn-sm">
                                    <span id="nombre_btn_form"></span>
                                </button>
                                <button type="button" onclick="visualizarListado()" class="btn btn-danger btn-sm">Cancelar</button>
                            </div>
                        </div>

                        
                    </div>

                </div>

            
            </form>
        </div>


    </section>

    
    <script>
       
       

    </script>
@endsection
@section('scripts')

    <script src="{{ asset('js/vehiculoCombustible/tarea.js?v='.rand())}}"></script>

    <script>
        llenar_tabla_tarea()
        limpiarCampos()
    </script>


@endsection
