@extends('layouts.app')

@section('content')
    <style>
        a:hover, a:active, a:focus {
            outline: none;
            text-decoration: none;
            color: #f4f4f4;
        }
        .img_firma{
            width: 90px;
        }
    </style>
    
    <section class="content-header">
        <h1>
            Listado Salidas Vehicular
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

            <div class="box-body" id="content_consulta">
                <div class="row">

                    <div class="col-md-12">
                        <form id="frm_buscarPersona" class="form-horizontal" action="" autocomplete="off">
                            {{ csrf_field() }}
                            <div class="box-body">

                                <div class="form-group">
                                    <label for="inputEmail3" id="label_crit" class="col-sm-2 control-label" >Fecha Inicio:</label>
                                    
                                    <div class="col-sm-10" style="font-weight: normal;">                     
                                        <input type="date"  class="form-control" id="fecha_ini"  name="fecha_ini" >
                                    </div>
                                            
                                </div>

                                <div class="form-group">
                                    <label for="inputEmail3" id="label_crit" class="col-sm-2 control-label" >Fecha Fin:</label>
                                    
                                    <div class="col-sm-10" style="font-weight: normal;">                     
                                        <input type="date"  class="form-control" id="fecha_fin"  name="fecha_fin" >
                                    </div>
                                            
                                </div>

                              

                                <div class="form-group">
                                    <div class="col-sm-12 col-md-offset-2" >
                                    
                                        <button type="button" onclick="buscarSalidas()" class="btn btn-success btn-sm">
                                            Regresar
                                        </button>
                                      
                                    </div>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="box-body" id="listado" style="display: none">
                <div class="col-sm-12 " >                                    
                    <center><button type="button" onclick="regresarBusqueda()" class="btn btn-primary btn-sm">
                        Buscar
                    </button></center>                  
                </div>
                <div class="table-responsive">
                    <table id="tabla_salidas" width="100%"class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Vehículo</th>
                                <th style="text-align: center">Chofer</th>
                                <th style="text-align: center">Patio</th>
                                <th style="text-align: center">Destino</th>
                                <th>N° Ticket</th>
                                <th style="min-width: 10%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6"><center>No hay Datos Disponibles</td>
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
                        <span style="font-size: 150%; color: green" class="fa fa-file"></span> <label id="titulo" class="modal-title" style="font-size: 130%; color: black ;">RUTAS</label>
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

    <script src="{{asset('canvasLibrary/js/numeric-1.2.6.min.js')}}"></script> 
    <script src="{{asset('canvasLibrary/js/bezier.js')}}"></script>
    <script src="{{asset('canvasLibrary/js/jquery.signaturepad.js')}}"></script> 
    <script type='text/javascript' src="{{asset('canvasLibrary/js/html2canvas.js')}}"></script>

    <script src="{{ asset('js/vehiculoCombustible/salidas.js?v='.rand())}}"></script>

    <script>
        //cargamos el datos del chofer el usuario logueado
       
        // llenar_tabla_salidas()
        limpiarCampos()
    </script>


@endsection
