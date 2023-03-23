@extends('layouts.app')

@section('content')

    
    <section class="content-header">
        <h1>
            Gestión Ticket
        </h1>

    </section>

    <section class="content" id="content_form">

        <div class="box" id="listado_ticket">
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
                    <table id="tabla_ticket" width="100%"class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Número Ticket</th>
                                <th>Vehículo</th>
                                <th>Chofer</th>
                                <th>Gasolinera</th>
                                <th>Combustible</th>
                                <th>Valor</th>
                                <th style="min-width: 30%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7"><center>No hay Datos Disponibles</td>
                            </tr>
                            
                        </tbody>
                      
                    </table>  
                  </div>    

                
            </div>

        </div>


        <div id="form_ing" style="display:none">
            <form class="form-horizontal" id="form_ticket" autocomplete="off" method="post"
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
                            <label for="inputPassword3" class="col-sm-3 control-label">Ticket</label>
                            <div class="col-sm-8">
                                <input type="number" minlength="1" maxlength="20" onKeyPress="if(this.value.length==20) return false;"  class="form-control" id="numero_ticket" name="numero_ticket" placeholder="Ticket">
                               
                            </div>
                            
                        </div>

                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-3 control-label">Vehiculo</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Seleccione Un Vehículo" style="width: 100%;" class="form-control select2" name="id_vehiculo" id="id_vehiculo">
                                    @foreach ($vehiculo as $dato)
                                        <option value=""></option>
                                        <option value="{{ $dato->id_vehiculo }}" >{{ $dato->descripcion }} {{ $dato->codigo_institucion }} [{{ $dato->placa }}] </option>
                                    @endforeach
                                </select>
                            </div>
                           
                        </div>

                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-3 control-label">Gasolinera</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Seleccione Un Gasolinera" style="width: 100%;" class="form-control select2" name="cmb_gasolinera" id="cmb_gasolinera">
                                    @foreach ($gasolinera as $dato)
                                        <option value=""></option>
                                        <option value="{{ $dato->id_gasolinera  }}" >{{ $dato->descripcion }} </option>
                                    @endforeach
                                </select>
                            </div>
                           
                        </div>

                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-3 control-label">Combustible</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Seleccione Un Combustible" style="width: 100%;" class="form-control select2" name="cmb_tipocombustible" id="cmb_tipocombustible">
                                    @foreach ($tipo_combust as $dato)
                                        <option value=""></option>
                                        <option value="{{ $dato->id_tipocombustible   }}" >{{ $dato->detalle }} </option>
                                    @endforeach
                                </select>
                            </div>
                           
                        </div>

                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-3 control-label">Total</label>
                            <div class="col-sm-8">
                                <input type="number" step="0.01" minlength="1" maxlength="10" onKeyPress="if(this.value.length==100) return false;"  class="form-control" id="total" name="total" placeholder="Total">
                               
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

    <script src="{{ asset('js/vehiculoCombustible/ticket.js?v='.rand())}}"></script>

    <script>
        llenar_tabla_ticket()
        limpiarCampos()
    </script>


@endsection
