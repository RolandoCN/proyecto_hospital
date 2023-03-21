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
            Gestión Entrada-Salida
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
                                <th>Chofer</th>
                                <th>Fecha Evento</th>
                                <th>Evento</th>
                                <th>Firma</th>
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
                        <div id="msmDetalledos"></div>

                        <div class="form-group">
                          
                            <label for="inputPassword3" class="col-sm-2 control-label">Vehículo</label>
                            <div class="col-sm-4">
                                <select data-placeholder="Seleccione Un Vehículo" style="width: 100%;" class="form-control select2" name="vehiculo_tarea" id="vehiculo_tarea" onchange="cargartarea()">
                                
                                    @foreach ($vehiculo as $dato)
                                        <option value="{{ $dato->id_vehiculo }}" >{{ $dato->descripcion }} {{ $dato->codigo_institucion }} [{{ $dato->placa }}] </option>
                                    @endforeach
                                </select>
                            
                            </div>

                            <label for="inputPassword3" class="col-sm-2 control-label">Chofer</label>
                            <div class="col-sm-4">
                                <select data-placeholder="Seleccione Un Chofer" style="width: 100%;" class="form-control select2" name="chofer" id="chofer" >
                                
                                    @foreach ($persona as $dato)
                                        <option value="{{ $dato->idpersona}}" >{{ $dato->nombres }} {{ $dato->apellidos }}</option>
                                    @endforeach
                                </select>
                            
                            
                            </div>

                        </div>

                        {{-- <div id="table_dato_salida" class="form-group " style="margin-bottom: 0px; display: none">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="descripcion_p"><span class="required"></span>
                            </label>
                            <div id="table_dato_salida" class="panel  col-md-8 col-sm-12 col-xs-12 form-group"style="margin-left: 0px;margin-right: 0px;padding: 0px">
                                <div class="panel-heading" role="tab" id="HeadingTwo" style="background: blue;margin-right: 10px; margin-left: 10px">
                                    <center>
                                        <h4 class="panel-title "style="color: white">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#Accordion" href="#CollapseTwo" aria-expanded="false" aria-controls="CollapseTwo">
                                            Detalles de tareas
                                            </a>
                                        </h4>
                                    </center>
                                </div>
            
                                <div id="CollapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="HeadingTwo">
                                <div class="table-responsive col-md-12 col-sm-12 col-xs-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead style="background-color:blue">
                                                <tr>
                                                    <th scope="col" style="width: 20%;text-align: center;color: white">#</th>
                                                    <th scope="col" style="width: 80%;text-align: center;color: white">Tarea</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_dato_salida">
                                            </tbody>
                                        </table>  
                                </div>
                                </div>
                            </div>
                        </div> --}}
                        
                        <div class="form-group">
                          
                            <label for="inputPassword3" class="col-sm-3 control-label">Chofer</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Seleccione Un Chofer" style="width: 100%;" class="form-control select2" name="chofer" id="chofer" >
                                
                                    @foreach ($persona as $dato)
                                        <option value="{{ $dato->idpersona}}" >{{ $dato->nombres }} {{ $dato->apellidos }}</option>
                                    @endforeach
                                </select>
                            
                            
                            </div>

                        </div>

                        <div class="form-group" id="km_txt" style="display:none">
                            <label for="inputPassword3" class="col-sm-3 control-label">Kilometraje</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" placeholder="Ingrese una descripción" id="kilometraje" name="kilometraje">
                            </div>
                            
                        </div>

                        <div class="form-group" id="hm_txt" style="display:none">
                            <label for="inputPassword3" class="col-sm-3 control-label">Horómetro</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" placeholder="Ingrese una descripción" id="horometro" name="horometro">
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Entrada/Salida</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Seleccione Un Opción" class="form-control select2" style="width: 100%;" name="entrada_salida" id="entrada_salida">
                                    <option selected="selected" value="Entrada">Entrada</option>
                                    <option value="Salida">Salida</option>
                                  
                                  
                                </select>
                            </div>
                            
                        </div>

                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-3 control-label">Observacion</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" placeholder="Ingrese la observacion" name="observacion" id="observacion"></textarea>
                            </div>

                           
                        </div>


                        <div id="content_firma" class="form-group">
                            <label class="control-label col-md-3 col-sm-2 col-xs-12" for="icono_gestione"></label>
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <div id="signArea" style="margin: 0; margin-bottom: 20px; width: fit-content;">
                                    <h2 class="tag-ingo"> Firma</h2>
                                    <div class="sig sigWrapper" style="height:auto; border:1px solid #000;">
                                        <div class="typed"></div>
                                        <canvas class="sign-pad" id="sign-pad" width="350%" height="200"></canvas>
                                        
                                    </div>
                                    <button type="button" class="btn btn-default btn-sm" style="margin-top: 10px;" type="button" onclick="limpiarSingArea()"><i class="fa fa-eraser"></i> Limpiar</button>
                                </div>
                            </div>
                            <div id="preview_firma" class="col-md-4 col-sm-4 col-xs-12" style="display: none;">
                                <h2 class="tag-ingo">Firma Actual</h2>
                                <img id="img_preview_firma" class="preview_firma" src="" alt="">
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

    <script src="{{asset('canvasLibrary/js/numeric-1.2.6.min.js')}}"></script> 
    <script src="{{asset('canvasLibrary/js/bezier.js')}}"></script>
    <script src="{{asset('canvasLibrary/js/jquery.signaturepad.js')}}"></script> 
    <script type='text/javascript' src="{{asset('canvasLibrary/js/html2canvas.js')}}"></script>

    <script src="{{ asset('js/vehiculoCombustible/movimiento.js?v='.rand())}}"></script>

    <script>
        llenar_tabla_tarea()
        limpiarCampos()
    </script>


@endsection
