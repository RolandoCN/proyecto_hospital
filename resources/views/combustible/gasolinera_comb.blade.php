@extends('layouts.app')

@section('content')

    
    <section class="content-header">
        <h1>
            Gestión Gasolinera Combustible
        </h1>

    </section>

    <section class="content" id="content_form">

        <div class="box" id="listado_gaso_comb">
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
                    <table id="tabla_gaso_comb" width="100%"class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Gasolinera</th>
                                <th>Combustible</th>
                                <th>Precio Galón</th>
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
            <form class="form-horizontal" id="form_gaso_comb" autocomplete="off" method="post"
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

                            <label for="inputPassword3" class="col-sm-3 control-label">Gasolinera</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Seleccione Una Gasolinera" style="width: 100%;" class="form-control select2" name="gasolinera" id="gasolinera" >
                                
                                    @foreach ($gasolinera as $dato)
                                        <option value=""></option>
                                        <option value="{{ $dato->id_gasolinera}}" >{{ $dato->descripcion }} </option>
                                    @endforeach
                                </select>
                               
                            </div>
                           
                        </div>


                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-3 control-label">Combustible</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Seleccione Un Combustible" style="width: 100%;" class="form-control select2" name="combustible" id="combustible" >
                                
                                    @foreach ($combustible as $dato)
                                        <option value=""></option>
                                        <option value="{{ $dato->id_tipocombustible }}" >{{ $dato->detalle }} </option>
                                    @endforeach
                                </select>
                            </div>
                           
                        </div>

                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-3 control-label">Precio Por Galón </label>
                            <div class="col-sm-8">
                                <input type="number" minlength="1" step="0.0001" pattern="^([0-9]{1,8}(\.[0-9]{1,4})?)$" title="Sólo se acepta valores númericos de máximos 8 digitos enteros y 4 decimales. (Separe los decimales con el punto)"onKeyPress="if(this.value.length==13) return false;" class="form-control" id="precio" name="precio" placeholder="Precio por Galón">
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

    <script src="{{ asset('js/vehiculoCombustible/gasolineraCombustible.js?v='.rand())}}"></script>

    <script>
        llenar_tabla_gaso_comb()
    </script>


@endsection
