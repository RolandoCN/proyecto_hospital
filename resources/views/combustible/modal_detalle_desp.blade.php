

<div class="modal fade_ detalle_class"  id="DetalleDespacho" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">INFORMACIÓN DEL DETALLE DESPACHO</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="nav nav-pills nav-stacked"style="margin-left:0px">
                            <li style="border-color: white"><a><i class="fa fa-home text-red"></i> <b class="text-black" style="font-weight: 650 !important">Gasolinera</b>: <span id="gasolineramodalapr"></span></a></li>

                            <li style="border-color: white"><a><i class="fa fa-car text-green"></i> <b class="text-black" style="font-weight: 650 !important">Vehículo</b>: <span id="vehiculomodalapr"></span></a></li>

                           

                            <li style="border-color: white"><a><i class="fa fa-flask text-black"></i> <b class="text-black" style="font-weight: 650 !important">Galones</b>: <span id="galonesmodalapr"></span></a></li>


                            <li style="border-color: white"><a><i class="fa fa-money text-red"></i> <b class="text-black" style="font-weight: 650 !important">Total</b>: <span id="totalmodalapr"></span></a></li>

                            {{-- <li style="border-color: white"><a><i class="fa fa-tasks text-green"></i> <b class="text-black" style="font-weight: 650 !important">Tareas</b>: 
                                <span id="tar">
                                    <ul id="tareamodalapr"></ul>
                                </span></a>
                            </li> --}}

                           
                        </ul>
                    </div>     
                    <div class="col-md-6">
                        <ul class="nav nav-pills nav-stacked" style="margin-left:12px">
                            <li style="border-color: white"><a><i class="fa fa-keyboard-o text-red"></i> <b class="text-black" style="font-weight: 650 !important">Fecha :</b> <span id="fechadespachomodalapr"></span></a></li>

                          
                            <li style="border-color: white"><a><i class="fa fa-tint text-blue"></i> <b class="text-black" style="font-weight: 650 !important">Combustible</b>: <span id="combustiblemodalapr"></span></a></li>

                            <li style="border-color: white"><a><i class="fa fa-money text-black"></i> <b class="text-black" style="font-weight: 650 !important">Precio Unitario</b>: <span id="preciounitmodal"></span></a></li>
                            
                            <li style="border-color: white"><a><i class="fa fa-user text-user"></i> <b class="text-black" style="font-weight: 650 !important">Conductor</b>: <span id="conductormodalapr"></span></a></li>

                           
                        </ul>
                    </div> 
                    <div class="col-md-12">
                        <form name="firma" id="firma_aprobacion">
                          
                            <input type="hidden" name="iddespachoApr"id="iddespachoApr">

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
        
                   
                            <div class="rowx">
                                
                                <div class="col-xs-12 col-sm-12">
                                    <center>
                                     
                                        <button type="submit"id="aprob"   class="btn btn-success"><i class="fa fa-thumbs-up"></i> Aprobar</button>

                                        <button type="button"id="reprob" onclick="cambiarestado()" class="btn btn-danger"><i class="fa fa-thumbs-down"></i> Reprobar</button>

                                        <button type="button" class="btn btn-warning" data-dismiss="modal" value="Cancel" id="cerra_modal"><i class="fa fa-times"></i> Cerrar</button>
                                    
                                    </center>
                                </div>

                            </div>
                             
                        </form>
                    </div> 

                </div>

               
            </div>
         
        </div>

    </div>

</div>
