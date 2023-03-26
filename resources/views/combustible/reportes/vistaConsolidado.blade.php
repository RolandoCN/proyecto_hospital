@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Reporte Consolidado
        </h1>

    </section>

    <section class="content" id="arriba">

        <div id="content_consulta-" >
            <div class="box ">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <span id='cabecera_txt'>Buscar</span>
                        <button id="cabecera_btn" style="display:none" class="btn btn-danger btn-xs" onclick="cancelar()">Volver</button>
                    </h3>
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
                                        
                                            <button type="button" onclick="buscarDespachos()" class="btn btn-success btn-sm">
                                                Buscar
                                            </button>
                                          
                                        </div>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>

                     
                    </div>
                </div>

                <div class="box-body" id="listado_turno" style="display: none"  >
                    <div class="row">
                        <div class="col-md-12" style="text-align:center; margin-bottom:20px">
                            <button type="button" onclick="generarReporteCons()" class="btn btn-primary">Descargar</button>

                            <button type="button" onclick="visualizarListado()" class="btn btn-danger">Volver</button>

                        </div>
                      
                        <div class="col-md-12">
                            <table class="table table-bordered table-hover text-center" id="tabla_consolidado">
                                <thead class="th">
                                <tr>
                                    <th>Fecha</th>
                                    <th>N° Ticket</th>
                                    <th>Vehículo</th>
                                    <th>Chofer</th>
                                    <th>Autorizado</th>
                                    <th>Total</th>
                                   
                                   
                                </tr>
                                </thead>
            
                                <tbody style="font-weight: normal" id="pac_body">
                                    <tr>
                                    <td colspan="6">Ningún dato disponible en esta tabla</td>
                                    </tr>
                                </tbody>
            
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>
@endsection
@section('scripts')

{{-- <script src="/js/vehiculoCombustible/reporte_consolidado.js"></script> --}}

<script src="{{ asset('js/vehiculoCombustible/reporte_consolidado.js?v='.rand())}}"></script>
    
<script>

 
    
</script>
@endsection