@extends('layouts.app')

@section('content')

    <style>
       
    </style>
    <section class="content-header">
        <h1>
            Gestión Reportes Ordenes
        </h1>

    </section>

    <section class="content" id="content_form">

        <div class="box" id="listado_formulario">

            <div class="box-header with-border">
                <h3 class="box-title" id="titulo_form"> Buscar Ordenes</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                    
                </div>
            </div>
            
            <div class="box-body">

                <div id="form_ing">
                    <form class="form-horizontal" id="form_reporte" autocomplete="off" method="post"
                    action="">
                     {{ csrf_field() }}
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
                            <div class="col-sm-12 col-md-offset-3" >
                            
                                <button type="submit" class="btn btn-success btn-sm">
                                Buscar
                                </button>
                              
                            </div>
                        </div>
                    </form>
                    <hr>
                </div>

                <div class="table-responsive" >
                    <table id="tabla_formulario" width="100%"class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Vehículo</th>
                               
                                <th>Nro Ticket</th>
                                <th>Chofer</th>
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


        <div class="modal fade" id="documentopdf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
           
                    <div class="modal-body">
                        <span style="font-size: 150%; color: green" class="fa fa-file"></span> <label id="titulo" class="modal-title" style="font-size: 130%; color: black ;">ORDEN</label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span style="font-size: 35px"aria-hidden="true">&times;</span>
                        </button>
                        <br><br>
                        <div class="row">
                            <div class="col-sm-12 col-xs-11 "style="height: auto ">
                                <iframe width="100%" height="500" frameborder="0"id="iframePdf"></iframe>
                                <p style="color: #747373;font-size:15px"></p>
                            </div>
                        </div>
                    </div>
      
                    <div class="modal-footer"> 
                        <center>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-mail-reply-all"></i> Salir</button>  
                            <a href=""id="vinculo"><button  type="button" id="descargar"class="btn btn-primary"><i class="fa fa-mail"></i> Descargar</button> </a>                                 
                        </center>               
                    </div>
      
                </div>
            </div>
        </div>

    </section>

@endsection
@section('scripts')

    <script src="{{ asset('js/vehiculoCombustible/reportesOrdenes.js?v='.rand())}}"></script>


    <script>
        // llenar_tabla_reportes()
    </script>

@endsection
