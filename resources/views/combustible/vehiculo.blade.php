@extends('layouts.app')

@section('content')

    
    <section class="content-header">
        <h1>
            Gestión Vehículo
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
                    <table id="tabla_vehiculo" width="100%"class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Placa</th>
                                <th>Uso</th>
                                <th>Estado</th>
                                <th style="min-width: 30%">Opciones</th>
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
            <form class="form-horizontal" id="form_registro_veh" autocomplete="off" method="post"
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
                            <label for="inputEmail3" class="col-sm-2 control-label">Código</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" placeholder="Ingrese el Código" required id="codigo" name="codigo">
                            
                            </div>

                            <label for="inputPassword3" class="col-sm-2 control-label">Placa</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="Ingrese la placa"maxlength="7" minlength="6" onkeyup="mayus(this);" requireed id="placa" name="placa">
                            
                            
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Descripción</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="Ingrese una descripción" id="descripcion" name="descripcion">
                            </div>

                            <label for="inputPassword3" class="col-sm-2 control-label">Marca</label>
                            <div class="col-sm-4">
                                <select data-placeholder="Seleccione Una Marca" style="width: 100%;" class="form-control select2" name="cmb_marca" id="marcacombo" >
                                
                                    @foreach ($marca as $dato)
                                        <option value="{{ $dato->id_marca }}" >{{ $dato->detalle }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-2 control-label">Modelo</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="Ingrese el modelo" name="modelo" id="modelo">
                            </div>

                            <label for="inputPassword3" class="col-sm-2 control-label">Tipo Uso</label>
                            <div class="col-sm-4">
                                <select data-placeholder="Seleccione Una Opción" style="width: 100%;" class="form-control select2" name="cmb_tipouso" id="tipousocombo" >
                                
                                    @foreach ($tipo_uso as $dato)
                                        <option value="{{ $dato->id_tipouso }}" >{{ $dato->detalle }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-2 control-label">Número de chasis</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" placeholder="Ingrese el número de chasis"name="chasis" id="chasis">
                            </div>

                            <label for="inputPassword3" class="col-sm-2 control-label">Año fabricación</label>
                            <div class="col-sm-4" >
                                <input type="number" max="{{date('Y')}}" placeholder="Ingrese el año de fabricación" min="1950"class="form-control" name="fabricacion" id="fabricacion">
                            </div>


                        </div>

                        <div class="form-group">

                            
                            <label for="inputPassword3" class="col-sm-2 control-label">Tipo Combustible</label>
                            <div class="col-sm-4">
                                <select data-placeholder="Seleccione Una Opción" style="width: 100%;" class="form-control select2" name="cmb_tipocombustible" id="cmb_tipocombustible" >
                                
                                    @foreach ($tipo_combust as $dato)
                                        <option value="{{ $dato->id_tipocombustible }}" >{{ $dato->detalle }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <label for="inputPassword3" class="col-sm-2 control-label">Capacidad Galon</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" placeholder="Ingrese Capacidad"name="capacidad" id="capacidad">

                            </div>

                        
                            

                        </div>

                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-2 control-label">Tipo Medición</label>
                            <div class="col-sm-4">
                                <select data-placeholder="Seleccione Una Opción" style="width: 100%;" class="form-control select2" id="cmb_tipomedicion"  name="cmb_tipomedicion" >
                                                                    
                                    @foreach ($tipo_medic as $dato)
                                        <option value="{{ $dato->id_tipomedicion }}">{{ $dato->detalle }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <label for="inputPassword3" class="col-sm-2 control-label">Estado</label>
                            <div class="col-sm-4">
                                <select data-placeholder="Seleccione Una Opción" style="width: 100%;" class="form-control select2" id="estado_veh"  name="estado_veh" >
                                                                    
                                    <option value="Bueno" selected>Bueno</option>
                                    <option value="Malo" >Malo</option>
        
                                </select>

                            </div>


                            {{-- <label for="inputPassword3" class="col-sm-2 control-label">Departamento</label>
                            <div class="col-sm-4">
                                <select data-placeholder="Seleccione Un Departamento" style="width: 100%;" class="form-control select2" name="departamento" id="departamento" >
                                
                                    @foreach ($departamento as $dato)
                                        <option value="{{ $dato->iddepartamento}}" >{{ $dato->descripcion }}</option>
                                    @endforeach
                                </select>
                            </div> --}}

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

    <script src="{{ asset('js/vehiculoCombustible/registro_vehiculo.js?v='.rand())}}"></script>

    <script>
        llenar_tabla_vehiculo()
        limpiarCampos()
    </script>


@endsection
