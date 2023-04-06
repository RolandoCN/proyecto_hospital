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
            Gestión Persona
        </h1>

    </section>

    <section class="content" id="content_form">

        <div class="box" id="listado_persona">
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
                    <table id="tabla_persona" width="100%"class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Cédula</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Teléfono</th>
                                <th>Firma</th>
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
            <form class="form-horizontal" id="form_registro_persona" autocomplete="off" method="post"
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
                            <label for="inputPassword3" class="col-sm-3 control-label">Cédula</label>
                            <div class="col-sm-8">
                                <input type="number" minlength="1" maxlength="10" onKeyPress="if(this.value.length==10) return false;"  class="form-control" id="cedula_persona" name="cedula_persona" placeholder="Cedula">
                                <span class="invalid-feedback" role="alert" style="color:red; display:none
                                " id="error_cedula">
                                    <strong id="txt_error_cedula"></strong>
                                </span>
                            </div>
                            
                        </div>

                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-3 control-label">Nombres</label>
                            <div class="col-sm-8">
                                <input type="text" minlength="1" maxlength="100" onKeyPress="if(this.value.length==100) return false;" class="form-control" id="nombres" name="nombres" placeholder="Nombres">
                                <span class="invalid-feedback" role="alert" style="color:red; display:none
                                " id="error_nombres">
                                    <strong id="txt_error_nombres"></strong>
                                </span>
                            </div>
                           
                        </div>


                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-3 control-label">Apellidos</label>
                            <div class="col-sm-8">
                                <input type="text" minlength="1" maxlength="100" onKeyPress="if(this.value.length==100) return false;"  class="form-control" id="apellidos" name="apellidos" placeholder="Apellidos">
                                <span class="invalid-feedback" role="alert" style="color:red; display:none
                                " id="error_apellidos">
                                    <strong id="txt_error_apellidos"></strong>
                                </span>
                            </div>
                           
                        </div>

                        <div class="form-group">

                            <label for="inputPassword3" class="col-sm-3 control-label">Teléfono</label>
                            <div class="col-sm-8">
                                <input type="number" minlength="1" maxlength="10" onKeyPress="if(this.value.length==10) return false;"  class="form-control" id="telefono" name="telefono" placeholder="Teléfono">
                                <span class="invalid-feedback" role="alert" style="color:red; display:none
                                " id="error_telefono">
                                    <strong id="txt_error_telefonos"></strong>
                                </span>
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

        <div class="modal fade_ detalle_class"  id="FirmaPersona" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">REGISTRO DE FIRMA</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            
                            <div class="col-md-6">
                                <ul class="nav nav-pills nav-stacked" style="margin-left:12px">
                                    <li style="border-color: white"><a><i class="fa fa-keyboard-o text-red"></i> <b class="text-black" style="font-weight: 650 !important">Cedula :</b> <span id="cedula_modal"></span></a></li>
        
                                </ul>
                            </div> 

                            <div class="col-md-6">
                                <ul class="nav nav-pills nav-stacked" style="margin-left:12px">
                                    <li style="border-color: white"><a><i class="fa fa-user text-red"></i> <b class="text-black" style="font-weight: 650 !important">Nombres :</b> <span id="persona_modal"></span></a></li>
        
                                </ul>
                            </div> 

                            <div class="col-md-12">
                                <form name="firma_persona" id="firma_persona">
                                  
                                    <input type="hidden" name="idPersonaFirma"id="idPersonaFirma">
        
                                    <div id="content_firma" class="form-group">
                                        <label class="control-label col-md-3 col-sm-2 col-xs-12" for="icono_gestione"></label>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div id="signArea" style="margin: 0; margin-bottom: 20px; width: fit-content;">
                                                <h2 class="tag-ingo"> Firma</h2>
                                                <div class="sig sigWrapper" style="height:auto; border:1px solid #000;">
                                                    <div class="typed"></div>
                                                    <canvas class="sign-pad" id="sign-pad" width="300%" height="200"></canvas>
                                                    
                                                </div>
                                                <button type="button" class="btn btn-default btn-sm" style="margin-top: 10px;" type="button" onclick="limpiarSingArea()"><i class="fa fa-eraser"></i> Limpiar</button>
                                            </div>
                                        </div>
                                        <div id="preview_firma" class="col-md-4 col-sm-4 col-xs-12" style="display: none;">
                                            <h2 class="tag-ingo">Firma Actual</h2>
                                            <img id="img_preview_firma" class="preview_firma" src="" alt="">
                                        </div>
                            
                                    </div>
                
                                    <div class="col-xs-12 col-sm-12">
                                        <center>
                                            
                                            <button type="submit"id="aprob" class="btn btn-success"><i class="fa fa-thumbs-up"></i> Guardar</button>
    
                                            <button type="button" class="btn btn-warning" data-dismiss="modal" value="Cancel" id="cerra_modal"><i class="fa fa-times"></i> Cerrar</button>
                                        
                                        </center>
                                    </div>
        
                                  
                                </form>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>

@endsection
@section('scripts')

    <script src="{{ asset('js/vehiculoCombustible/persona.js?v='.rand())}}"></script>
    <script src="{{ asset('canvasLibrary/js/numeric-1.2.6.min.js') }}"></script>
    <script src="{{ asset('canvasLibrary/js/bezier.js') }}"></script>
    <script src="{{ asset('canvasLibrary/js/jquery.signaturepad.js') }}"></script>
    <script type='text/javascript' src="{{ asset('canvasLibrary/js/html2canvas.js') }}"></script>
    <script>
        llenar_tabla_persona()
        limpiarCampos()
    </script>


@endsection
