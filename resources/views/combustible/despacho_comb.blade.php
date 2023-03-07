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
                                <td colspan="2"><center>No hay Datos Disponibles</td>
                            </tr>
                            
                        </tbody>
                      
                    </table>  
                  </div>    

                
            </div>

        </div>


        <div id="form_ing" style="display:none">
           
            <form class="form-horizontal" id="frm_registro_cab_desp" autocomplete="off" method="post"
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
                          
                            <label for="inputPassword3" class="col-sm-3 control-label">Gasolinera</label>
                            <div class="col-sm-8">
                                <select data-placeholder="Seleccione Un Vehículo" style="width: 100%;" class="form-control select2" name="cmb_gasolinera" id="cmb_gasolinera" ">
                                
                                    @foreach ($gasolinera as $dato)
                                        <option value="{{ $dato->id_gasolinera }}" >{{ $dato->descripcion }} </option>
                                    @endforeach
                                </select>
                            
                            
                            </div>

                        </div>

                     
                        <div class="form-group" >
                            <label for="inputPassword3" class="col-sm-3 control-label">Fecha</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" placeholder="Ingrese una descripción" id="fecha_desp" name="fecha_desp">
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
    <script src="/js/vehiculoCombustible/despachoCombustible.js"></script>

    <script>
        llenar_tabla_tarea()
        limpiarCampos()
    </script>


@endsection
