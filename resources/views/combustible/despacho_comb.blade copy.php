@extends('layouts.app')

@section('content')
    <style>
        a:hover,
        a:active,
        a:focus {
            outline: none;
            text-decoration: none;
            color: #f4f4f4;
        }

        .img_firma {
            width: 90px;
        }

        .select2-container .select2-selection--single{
            height: 34px;
        }

        .box-header2 {
            color: #444;
            display: block;
            padding: 10px;
            position: relative;
            text-align: center;
        }
    </style>
    <link rel="stylesheet" href="{{asset('plugins/sweetalert/sweetalert.css')}}">

    @if(session()->has('mensajePInfoDespacho'))
        <input type="hidden" name="errorReporte" id="errorReporte" value="{{session('mensajePInfoDespacho')}}">
    @endif

    <section class="content-header">
        <h1>
            Gestión Despacho Combustible
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
                                <th>Gasolinera</th>
                                <th>Fecha </th>

                                <th style="min-width: 30%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="2">
                                    <center>No hay Datos Disponibles
                                </td>
                            </tr>

                        </tbody>

                    </table>
                </div>

            </div>

        </div>

        <div id="form_ing" style="display:none">

            <form class="form-horizontal" id="frm_registro_cab_desp" autocomplete="off" method="post" action="">
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

                            <label for="inputPassword3" class="col-sm-3 control-label">Gasolinera</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Seleccione Una Gasolinera" style="width: 100%;"
                                    class="form-control select2" name="cmb_gasolinera" id="cmb_gasolinera" ">
                                    
                                        @foreach ($gasolinera as $dato)
                                            <option value="{{ $dato->id_gasolinera }}">{{ $dato->descripcion }} </option>
                                        @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Fecha</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" placeholder="Ingrese una descripción"
                                    id="fecha_desp" name="fecha_desp">
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="col-sm-12 text-center">

                                <button type="submit" class="btn btn-success btn-sm">
                                    <span id="nombre_btn_form"></span>
                                </button>
                                <button type="button" onclick="visualizarListado()"
                                    class="btn btn-danger btn-sm">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <div id="secc_detalle" style="display:none">

            <button type="button" onclick="volverListado()" class="btn btn-primary btn-sm">Regresar</button>
            <div class="box box-primary" style="margin-top: 12px">
                <div class="box-header with-border">
                    <h3 class="box-title text-center">Cabecera</h3>
                </div>


            
                <div class="box-body">

                    <div class="col-md-6">
                        <ul class="nav nav-pills nav-stacked"style="margin-left:0px">
                            <li style="border-color: white"><a><i class="fa fa-home text-red"></i> <b class="text-black" style="font-weight: 650 !important">Gasolinera</b>: <span  id="gasolinera_cab"></span></a></li>
                           
                        </ul>
                    </div>     
                    <div class="col-md-6">
                        <ul class="nav nav-pills nav-stacked" style="margin-left:12px">
                            <li style="border-color: white"><a><i class="fa fa-keyboard-o text-red"></i> <b class="text-black" style="font-weight: 650 !important">Fecha:</b> <span  id="fecha_cab"></span></a></li>
                            
                        </ul>
                    </div>  
                </div>

            </div>


            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title text-center">Detalle</h3>
                </div>


                <form role="form" id="form_idDetalleDesp">
                    <div class="box-body">

                     
                        <input type="hidden" name="idcabeceradespacho"id="idcabeceradespacho">

                        <div class="form-group col-md-6">
                            <label for="exampleInputEmail1" class="">Vehiculo</label>
                            <select data-placeholder="Seleccione Un Vehículo" style="width: 100%;" class="form-control select2" name="vehiculo_id" id="vehiculo_id" onchange="capturaDatosVeh()">
                                
                                @foreach ($vehiculo as $dato)
                                    <option value="{{ $dato->id_vehiculo }}" >{{ $dato->descripcion }} {{ $dato->codigo_institucion }} [{{ $dato->placa }}] </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group col-md-6 col-sm-12 col-xs-12">
                            <label for="inputPlaca">Kilometraje</label>
                            <input type="text" readonly class="form-control"  id="kilometrajemodal" name="kilometrajemodal">
                          </div>
  
                          <div class="form-group col-md-6 col-sm-12 col-xs-12">
                            <label for="inputDescripcion">Horometraje</label>
                            <input type="text" readonly class="form-control" id="horometrajemodal" name="horometrajemodal">
                          </div>
                          <div class="form-group col-md-6 col-sm-12 col-xs-12">
                              <div class="chosen-select-conten">
                                  <label for="inputfabtricacion">Conductor</label>
                                  <select data-placeholder="Seleccione Un Conductor" style="width: 100%;" class="form-control select2" name="chofer_id" id="chofer_id" >
                                
                                        @foreach ($persona as $dato)
                                            <option value=""></option>
                                            <option value="{{ $dato->idpersona }}" >{{ $dato->nombres }} {{ $dato->apellidos }}</option>
                                        @endforeach
                                    </select>
                                
                              </div>
                          </div>
  
                          <div class="form-group col-md-6 col-sm-12 col-xs-12">
                            <label for="inputPlaca">N° Factura/Ticket</label>
                            <input type="text"  class="form-control"  id="facturamodal" name="facturamodal">
                          </div>
  
                          
                           <div class="form-group col-md-6 col-sm-12 col-xs-12">
                            <label for="inputModelo">Total</label>
                            <input type="number" class="floatNumberField form-control"step="0.01" min="0" onkeyup="calculartotal(this,0)" class="form-control" name="totalmodal" id="totalmodal">
                          </div>
                          
                          <div class="form-group col-md-6 col-sm-12 col-xs-12">
                              <div class="chosen-select-conten">
                                    <label for="inputfabtricacion">Combustible</label>
                                    <select data-placeholder="Seleccione Un Combustible" style="width: 100%;" class="form-control select2" name="combustible_id" id="combustible_id" onchange="precioCombgas()" >
                                
                                        @foreach ($tipo_comb as $dato)
                                            <option value=""></option>
                                            <option value="{{ $dato->id_tipocombustible }}" >{{ $dato->detalle }}</option>
                                        @endforeach
                                    </select>
                              </div>
                          </div>
  
                          <div class="form-group col-md-6 col-sm-12 col-xs-12">
                            <label for="inputModelo">Precio Unitario</label>
                            <input type="text" class="form-control" readonly name="preciounitariomodal" id="preciounitariomodal">
                          </div>
  
                          <div class="form-group col-md-6 col-sm-12 col-xs-12">
                            <label for="inputModelo">Galones</label>
                            <input type="text" readonly class="form-control" name="galonesmodal" id="galonesmodal">
                          </div>
                        


                        
                    </div>

                    <div class="box-footer" style="text-align:center">
                        <button type="submit" class="btn btn-primary"> <span id="titulo_btn_detalle_form">Guardar</span></button>

                        <button type="button" class="btn btn-danger canc_detalle" style="display:none"> 
                            <span onclick="limpiarCamposDetalle()">Cancelar</span>
                        </button>

                    </div>
                </form>
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title text-center">Listado</h3>
                </div>


            
                <div class="box-body">

                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-sm-12">
                                <table style="color: black" width="100%" id="tabla_listado_desp" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width:8%;">Orden Despacho</th>

                                            <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 12%;">Vehiculo</th>
                                            
                                            <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 12%;">Combustible</th>

                                            

                                            <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 13%;">Total</th>

                                            <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 10%;">Fecha despacho</th>

                                            <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 12%;">Firma</th>

                                            <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending" aria-sort="descending" style="width: 13%;">Estado</th>

                                            <th  class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 20%;"></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                                           
                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>
                     </div>
                    
                </div>
               
            </div>

        </div>

        @include('combustible.modal_detalle_desp')
    </section>
    


    
@endsection
@section('scripts')
    <script src="{{asset('plugins/sweetalert/sweetalert.js')}}"></script>
    <script src="{{ asset('canvasLibrary/js/numeric-1.2.6.min.js') }}"></script>
    <script src="{{ asset('canvasLibrary/js/bezier.js') }}"></script>
    <script src="{{ asset('canvasLibrary/js/jquery.signaturepad.js') }}"></script>
    <script type='text/javascript' src="{{ asset('canvasLibrary/js/html2canvas.js') }}"></script>
    <script src="{{ asset('js/vehiculoCombustible/despachoCombustible.js?v='.rand())}}"></script>

    <script>
        llenar_tabla_tarea()
        limpiarCampos()
        let error=$('#errorReporte').val()
        if(error){
            alertNotificar(error, "error")
        }
    </script>
@endsection
