@extends('layouts.app')

@section('content')

    <style>
       
    </style>
    <section class="content-header">
        <h1>
            Gestión Reportes Formularios
        </h1>

    </section>

    <section class="content" id="content_form">

        <div class="box" id="listado_formulario">
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
                    <table id="tabla_formulario" width="100%"class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Formulario</th>
                                <th>Descripción</th>
                                <th>Fecha Generado</th>
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
            <form class="form-horizontal" id="form_reporte" autocomplete="off" method="post"
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
                            <label for="inputPassword3" class="col-sm-3 control-label">Formato</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Seleccione Un Formulario" style="width: 100%;" class="form-control select2" name="formulario" id="formulario" >
                                
                                    <option value=""></option>
                                    <option value="F6" >Formulario 6</option>
                                    <option value="F7" >Formulario 7</option>
                                
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Departamento</label>
                            <div class="col-sm-8">
                                <select data-placeholder="" style="width: 100%;" class="form-control select2"  multiple="multiple" name="departamento[]" id="departamento" >
                                
                                    @foreach ($departamento as $dato)
                                        <option value=""></option>
                                        <option value="{{ $dato->id}}" >{{ $dato->departamento }}</option>
                                    @endforeach
                                </select>
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

@endsection
@section('scripts')

    <script src="{{ asset('js/vehiculoCombustible/reportesFormulario.js?v='.rand())}}"></script>


    <script>
        llenar_tabla_reportes()
    </script>

@endsection
