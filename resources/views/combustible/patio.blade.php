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
                                <th style="text-align: center">Detalle</th>
                                {{-- <th style="text-align: center">Chofer</th> --}}
                                <th style="text-align: center">Patio</th>
                                <th style="text-align: center">Destino</th>
                                <th>Firma</th>
                                <th style="min-width: 10%">Opciones</th>
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
           
            <form role="form" id="form_registro_tarea" autocomplete="off" method="post"
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

                        <div class="col-md-4">

                            <div class="form-group "> 
                                <label for="inputPassword3" >Nro Ticket</label>                            
                                {{-- <input type="number" maxlength="20" onKeyPress="if(this.value.length==20) return false;"  class="form-control" id="n_ticket" name="n_ticket" placeholder="Ingrese el número de ticket">                                     --}}

                                <select data-placeholder="Seleccione Un Número Factura/Ticket" style="width: 100%;" class="form-control select2" name="n_ticket" id="n_ticket" onchange="cargaInfoTicket()"></select>

                            </div>

                            <div class="form-group " >
                                <label for="inputPassword3" >Lugar Salida Patio</label>                              
                                <input type="text" readonly class="form-control" placeholder="Chone" id="l_salida_patio"  maxlength="100" onKeyPress="if(this.value.length==100) return false;" name="l_salida_patio">                            
                            </div>

                            <div class="form-group " >
                                <label for="inputPassword3" >Lugar Destino</label>                              
                                <input type="text" class="form-control" maxlength="100" onKeyPress="if(this.value.length==100) return false;" placeholder="Ingrese lugar destino" id="l_destino_ll" name="l_destino_ll" onkeyup="lugardestino()">                            
                            </div>

                            <div class="form-group " >
                                <label for="inputPassword3" >Lugar Salida Destino</label>                              
                                <input type="text" maxlength="100" onKeyPress="if(this.value.length==100) return false;"  readonly class="form-control" placeholder="Ingrese lugar salida destino" id="l_sal_destino" name="l_sal_destino" >                            
                            </div>

                            <div class="form-group " >
                                <label for="inputPassword3" >Lugar Llegada Patio</label>                              
                                <input type="text" maxlength="100" onKeyPress="if(this.value.length==100) return false;"  class="form-control" readonly placeholder="Chone" id="l_llegada_pat" name="l_llegada_pat" >                            
                            </div>

                            <div class="form-group " >
                                <label for="inputPassword3" >Motivo</label>                              
                                <textarea class="form-control" placeholder="Ingrese el motivo" id="motivo" maxlength="200" onKeyPress="if(this.value.length==200) return false;"  name="motivo"></textarea>                          
                            </div>
                            <div class="form-group" >
                                <label for="inputPassword3" >Área Solicita</label>                            
                                <select data-placeholder="Seleccione Una Opción" style="width: 100%;" class="form-control select2"  name="area_sol" id="area_sol" >
                                    
                                    @foreach ($area as $dato)
                                        <option value=""></option>
                                        <option value="{{ $dato->id_area   }}" >{{ $dato->descripcion }} </option>
                                    @endforeach
                                </select>                            
                            </div>

                            <div class="form-group" id="div_novedad" style="display:none">
                                <label for="inputPassword3" >Novedad</label>
                                <textarea class="form-control" placeholder="Ingrese la novedad" id="txt_novedad" name="txt_novedad"> </textarea>                
                            </div>

                        </div>   

                        <div class="col-md-4">


                            <div class="form-group ">
                                <label for="exampleInputEmail1" class="">Vehiculo</label>
                                <select data-placeholder="Seleccione Un Vehículo" style="width: 100%;" class="form-control select2" name="vehiculo_tarea" id="vehiculo_tarea" onchange="cargartarea()" >
                                    
                                    @foreach ($vehiculo as $dato)
                                        <option value="{{ $dato->id_vehiculo }}" >{{ $dato->descripcion }} {{ $dato->codigo_institucion }} [{{ $dato->placa }}] </option>
                                    @endforeach
                                </select>
                            </div>
                            
                           
                            <div class="form-group">
                                <label for="inputPassword3" >Fecha Hora Salida Patio</label>
                                <input type="datetime-local" class="form-control"id="fecha_h_salida_patio" name="fecha_h_salida_patio" >                            
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" >Fecha Hora Destino</label>
                                <input type="datetime-local" class="form-control" id="fecha_h_destino" name="fecha_h_destino" onblur="validaFechaHoraDestino()">                     
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" >Fecha Hora Salida Destino</label>
                                <input type="datetime-local" class="form-control" id="fecha_h_destino_salida" name="fecha_h_destino_salida"  onblur="validaFechaHoraSalidaDestino()">                     
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" >Fecha Hora Llegada Patio</label>
                                <input type="datetime-local" class="form-control" id="fecha_h_llegada_patio" name="fecha_h_llegada_patio" onblur="validaFechaHoraLlegadaPatio()">                     
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" >Acompañante</label>
                                <textarea class="form-control" placeholder="Ingrese nombre acompañante" id="acompanante" maxlength="200" onKeyPress="if(this.value.length==200) return false;"  name="acompanante">  </textarea>                
                            </div>


                            <div class="form-group" >
                                <label for="inputPassword3" >Autorizado Por</label>                            
                                <select data-placeholder="Seleccione Una Opción" style="width: 100%;" class="form-control select2"  name="autorizado" id="autorizado" >
                                    
                                    @foreach ($autorizado as $dato)
                                        <option value=""></option>
                                        <option value="{{ $dato->id_autorizado_salida  }}" >{{ $dato->nombres }} </option>
                                    @endforeach
                                </select>                            
                            </div>

                           
                            

                        </div>

                        <div class="col-md-4">

                            <div class="form-group" >
                                <label for="inputPassword3" >Chofer</label>                            
                                <select data-placeholder="Seleccione Un Chofer" style="width: 100%;" class="form-control select2" name="chofer" id="chofer" disabled>
                                    @foreach ($persona as $dato)
                                        <option value="{{ $dato->idpersona}}" >{{ $dato->nombres }} {{ $dato->apellidos }}</option>
                                    @endforeach
                                </select>                            
                            </div>


                           

                            <div class="form-group ">
                                <label for="inputPassword3" >Kilometraje Salida Patio</label>
                                <input type="number" class="form-control"id="km_salida_patio" name="km_salida_patio" onblur="validaValoresKmSalidaPatio()" placeholder="Ingrese el kilometraje salida del patio">
                            </div>

                            <div class="form-group ">
                                <label for="inputPassword3" >Kilometraje Destino</label>
                                <input type="number" maxlength="10" onKeyPress="if(this.value.length==10) return false;"  class="form-control"id="km_destino_ll" name="km_destino_ll" placeholder="Ingrese el kilometraje destino" onkeyup="kmdestino()" onblur="validaValoresKmDest()">
                            </div>

                            <div class="form-group ">
                                <label for="inputPassword3" >Kilometraje Salida Destino</label>
                                <input type="number" maxlength="10" onKeyPress="if(this.value.length==10) return false;"  readonly class="form-control"id="km_salida_dest" name="km_salida_dest" placeholder="Ingrese el kilometraje de salida destino">
                            </div>

                            <div class="form-group ">
                                <label for="inputPassword3" >Kilometraje LLegada Patio</label>
                                <input type="number" maxlength="10" onKeyPress="if(this.value.length==10) return false;" class="form-control"id="km_llegada_patio" name="km_llegada_patio" placeholder="Ingrese el kilometraje de llegada patio"  onblur="validaValoresKmLlegadaPatio()" >
                            </div>

                            <div class="form-group ">
                                <label for="inputPassword3" >Solicitado Por</label>
                                <textarea  maxlength="100" onKeyPress="if(this.value.length==100) return false;" class="form-control"id="solicitante" name="solicitante" placeholder="Ingrese el funcionario solicitante"  ></textarea>
                            </div>

                            <div class="form-group" >
                                <label for="inputPassword3" >Presenta Novedad</label>                            
                                <select data-placeholder="Seleccione Una Opción" style="width: 100%;" class="form-control select2" onchange="cambiaNovedad()" name="tiene_novedad" id="tiene_novedad" >
                                    
                                    <option value="No" selected>No</option>
                                    <option value="Si">Si</option>
                                </select>                            
                            </div>

                        </div>


                        <hr>
                        <div class="col-sm-12 text-center" >
                            <input type="hidden" id="idchofer_loguea" name="idchofer" value="{{auth()->user()->id_persona}}">
                            <button type="submit" class="btn btn-success btn-sm">
                                <span id="nombre_btn_form"></span>
                            </button>
                            <button type="button" onclick="visualizarListado()" class="btn btn-danger btn-sm">Cancelar</button>
                        </div>
                        
                        
                        
                    </div>

                </div>

            
            </form>
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

    <script src="{{ asset('js/vehiculoCombustible/movimiento.js?v='.rand())}}"></script>

    <script>
        //cargamos el datos del chofer el usuario logueado
       
        llenar_tabla_tarea()
        limpiarCampos()
    </script>


@endsection
