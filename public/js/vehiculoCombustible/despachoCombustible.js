globalThis.EdicionDetalle="N"
function mayus(e) {
    e.value = e.value.toUpperCase();
}

$("#frm_registro_cab_desp").submit(function(e){
    e.preventDefault();
    
    //validamos los campos obligatorios
    let gasolinera=$('#cmb_gasolinera').val()
    let fecha_desp=$('#fecha_desp').val()
        
    
    if(gasolinera=="" || gasolinera==null){
        alertNotificar("Seleccione la gasolinera","error")
        return
    } 

    if(fecha_desp=="" || fecha_desp==null){
        alertNotificar("Seleccione la fecha ","error")
        $('#fecha_desp').focus()
        return
    } 

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //comprobamos si es registro o edicion
    let tipo=""
    let url_form=""
    if(AccionForm=="R"){
        tipo="POST"
        url_form="/guardar-cab-despacho"
    }else{
        tipo="PUT"
        url_form="/actualizar-tarea/"+IdTareaEditar
    }
  
    var FrmData=$("#frm_registro_cab_desp").serialize();
    console.log(FrmData)
    $.ajax({
            
        type: tipo,
        url: url_form,
        method: tipo,             
		data: FrmData,      
		
        processData:false, 

        success: function(data){
            console.log(data)
            // vistacargando("");                
            if(data.error==true){
                alertNotificar(data.mensaje,'error');
                return;                      
            }
            limpiarCampos()
            alertNotificar(data.mensaje,"success");
            $('#form_ing').hide(200)
            $('#listado_veh').show(200)
            llenar_tabla_tarea()
                            
        }, error:function (data) {
            console.log(data)

            // vistacargando("");
            alertNotificar('Ocurrió un error','error');
        }
    });
})

function limpiarCampos(){
    $('#codigo').val('')
    $('#placa').val('')
    $('#descripcion').val('')
    $('#marcacombo').val('').trigger('change.select2')
    $('#tipousocombo').val('').trigger('change.select2')
    $('#fabricacion').val('')
    $('#cmb_tipocombustible').val('').trigger('change.select2')
    $('#cmb_tipomedicion').val('').trigger('change.select2')
    $('#chasis').val('')
    $('#modelo').val('')
    $('#departamento').val('').trigger('change.select2')
}

function llenar_tabla_tarea(){
    var num_col = $("#tabla_tarea thead tr th").length; //obtenemos el numero de columnas de la tabla
	$("#tabla_tarea tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center><span class="spinner-border" role="status" aria-hidden="true"></span><b> Obteniendo información</b></center></td></tr>`);
   
    
    $.get("/listado-desp/", function(data){
        console.log(data)
      
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            $("#tabla_tarea tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
            return;   
        }
        if(data.error==false){
            
            if(data.resultado.length <= 0){
                $("#tabla_tarea tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
                alertNotificar("No se encontró datos","error");
                return;  
            }
         
            $('#tabla_tarea').DataTable({
                "destroy":true,
                pageLength: 10,
                autoWidth : true,
                order: [[ 1, "desc" ]],
                sInfoFiltered:false,
                language: {
                    url: '/json/datatables/spanish.json',
                },
                columnDefs: [
                    { "width": "40%", "targets": 0 },
                    { "width": "30%", "targets": 1 },
                    { "width": "30%", "targets": 2 },
                   
                   
                ],
                data: data.resultado,
                columns:[
                        {data: "gasolinera.descripcion"},
                        {data: "fecha" },
                        {data: "fecha"},
                      
                ],    
                "rowCallback": function( row, data ) {
                    $('td', row).eq(2).html(`
                                            <a onclick="detalle_despacho('${data.idcabecera_despacho}','${data.id_gasolinera}','${data.gasolinera.descripcion}','${data.fecha}')" class="btn btn-primary btn-xs"> Detalle </a>  

                                            <a onclick="imprimir_desp(${data.idcabecera_despacho })" class="btn btn-success btn-xs"> Imprimir </a>
                                                            
                                            <a onclick="eliminar_cabecera_desp(${data.idcabecera_despacho })" class="btn btn-danger btn-xs"> Eliminar </a>
                                       
                                    
                    `); 
                }             
            });
        }
    }).fail(function(){
        $("#tabla_tarea tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });


}

$('.collapse-link').click();
$('.datatable_wrapper').children('.row').css('overflow','inherit !important');

$('.table-responsive').css({'padding-top':'12px','padding-bottom':'12px', 'border':'0', 'overflow-x':'inherit'});



function editarTarea(idcabecera_despacho){
    $.get("/editar-tarea/"+idcabecera_despacho, function(data){
        console.log(data)
      
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            return;   
        }
        if(data.resultado==null){
            alertNotificar("La tarea ya no se puede editar","error");
            return;   
        }

      
        $('#vehiculo_tarea').val(data.resultado.id_vehiculo).trigger('change.select2')
        $('#choferSalvo').val(data.resultado.id_chofer).trigger('change.select2')
        $('#fecha_ini').val(data.resultado.fecha_inicio)
        $('#motivo').val(data.resultado.motivo)
       

        visualizarForm('E')
        globalThis.IdTareaEditar=idcabecera_despacho



       
    }).fail(function(){
       
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });
}

function visualizarForm(tipo){
    $('#form_ing').show(200)
    $('#listado_veh').hide(200)
    globalThis.AccionForm="";
    if(tipo=='N'){
        $('#titulo_form').html("Registro Tarea")
        $('#nombre_btn_form').html('Registrar')
        AccionForm="R"
    }else{
        $('#titulo_form').html("Actualización Tarea")
        $('#nombre_btn_form').html('Actualizar')
        AccionForm="E"
    }
}

function visualizarListado(){
    $('#form_ing').hide(200)
    $('#listado_veh').show(200)
    limpiarCampos()
}

function imprimir_desp(idCab){
    window.location.href='/despacho-pdf/'+idCab
}

function eliminar_cabecera_desp(idcabecera_despacho){
    if(confirm('¿Quiere eliminar el registro?')){
        $.get("/eliminar-tarea/"+idcabecera_despacho, function(data){
            console.log(data)
          
            if(data.error==true){
                alertNotificar(data.mensaje,"error");
                return;   
            }
    
            alertNotificar(data.mensaje,"success");
            llenar_tabla_tarea()
           
        }).fail(function(){
           
            alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
        });
    }
   
}

function detalle_despacho(idcabecera_despacho, idGasolinera, Gasolinera, FechaCab){
  
    $('#secc_detalle').show(200)
    $('#listado_veh').hide(200)

    $('#titulo_btn_detalle_form').html('Guardar')

    globalThis.idCabeceraDes=idcabecera_despacho
    globalThis.idGasolinearaDes=idGasolinera

    $('#vehiculo_id').val('').trigger('change.select2')
    globalThis.AccionFormDetalle="R"
    limpiarCamposDetalle()

    cargartablaDetalle()

    $('#gasolinera_cab').html(Gasolinera)
    $('#fecha_cab').html(FechaCab)
}

function volverListado(){
    $('#secc_detalle').hide(200)
    $('#listado_veh').show(200)
}

function capturaDatosVeh(){
    let idVeh=$('#vehiculo_id').val()
    //si viene del boton edit no mandamos a consultar
    if(EdicionDetalle=="S"){
        return
    }
    
    if(idVeh=="" || idVeh==undefined){return}

    $.get("/precio-detalle-comb/"+idVeh+"/"+idGasolinearaDes, function(data){
        console.log(data)
      
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            return;   
        }

        if(data.precioCombGas==null){
            var preciogas="";
            $('#totalmodal').attr('readonly','readonly');
            alertNotificar("La gasolinera no tiene asignado un precio para el tipo de combustible","info")
           
        }
        else{
            var preciogas=data.precioCombGas
            $('#totalmodal').attr('readonly',false);
        
        }      
       
        $('#preciounitariomodal').val(preciogas);

        if(data.idTipoCom!=null){
            $('#combustible_id').val(data.idTipoCom).trigger('change.select2')
        }
        
        $('#kilometrajemodal').attr('readonly',true);
        $('#kilometrajemodal').val('');

        $('#horometrajemodal').attr('readonly',true);
        $('#horometrajemodal').val('');
        
        //tipo medicion
        if(data.tipoMed!=null){
            globalThis.Km_Hm_Veh_Sel=""
            if(data.tipoMed.detalle=="Kilometraje"){
              
                $('#kilometrajemodal').attr('readonly',false);
                $('#kilometrajemodal').val('');
                Km_Hm_Veh_Sel="kilometrajemodal"
            }else{
                $('#horometrajemodal').attr('readonly',false);
                $('#horometrajemodal').val('');
                Km_Hm_Veh_Sel="horometrajemodal"
            }
        }
            
                
    }).fail(function(){
       
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });
}

globalThis.PrecioUnitGas=0;

function precioCombgas(){
    //si viene del boton edit no mandamos a consultar
    if(EdicionDetalle=="S"){
        EdicionDetalle="N"
        return
    }
    let idTipoCom=$('#combustible_id').val()
    if(idTipoCom=="" || idTipoCom==undefined){return}
    $.get("/precio-comb-gas/"+idTipoCom+"/"+idGasolinearaDes, function(data){
        console.log(data)
      
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            return;   
        }

        if(data.precioCombGas==null){
            var preciogas="";
            $('#totalmodal').attr('readonly','readonly');
            alertNotificar("La gasolinera no tiene asignado un precio para el tipo de combustible","info")
           
        }
        else{
            var preciogas=data.precioCombGas
            $('#totalmodal').attr('readonly',false);
        
        }      
      
        $('#preciounitariomodal').val(preciogas);
        PrecioUnitGas=preciogas;

        $('#galonesmodal').val('');  
        $('#totalmodal').val('');  

       
                
    }).fail(function(){
       
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });
}
globalThis.TotalGlobalGalones=0
function calculartotal(input){
    var galones=0; 
  
    var totalp = $(input).val();
    var preciouni=$('#preciounitariomodal').val();

    if(preciouni==""){
        alertNotificar("Debe seleccionar el tipo de combustible", "error")
        return
    }


    var galones=totalp/preciouni;
  
    if(totalp==0){

        // $('#preciounitariomodal').val('');
        $('#galonesmodal').val('');  

    }else{

        var galonestotal=(galones-0).toFixed(2);
        $('#preciounitariomodal').val(preciouni);
        $('#galonesmodal').val(galonestotal);
        TotalGlobalGalones=galonestotal
    }
    

}

function cargartablaDetalle(){
  
    $.get("/detalle-listado-des/"+idCabeceraDes, function (resultado) {    
        console.log(resultado); 

        var idtabla = "tabla_listado_desp";
        $(`#${idtabla}`).DataTable({
            dom: ""
            +"<'row' <'form-inline' <'col-sm-12 inputsearch'f>>>"
            +"<rt>"
            +"<'row'<'form-inline'"
            +" <'col-sm-6 col-md-6 col-lg-6'l>"
            +"<'col-sm-6 col-md-6 col-lg-6'p>>>",
            "destroy":true,
            "order": [[ 4, "desc" ]],
            pageLength: 10,
            sInfoFiltered:false,
            language: {
                lengthMenu: "Mostrar _MENU_ registros por pagina",
                zeroRecords: "No se encontraron resultados en su busqueda",
                searchPlaceholder: "Buscar registros",
                info: "Mostrando registros de _START_ al _END_ de un total de  _TOTAL_ registros",
                infoEmpty: "No existen registros",
                infoFiltered: "(filtrado de un total de _MAX_ registros)",
                search: "Buscar:",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                },
            },
            
            data: resultado.resultado,
            
            columns:[
                {data: "iddetalle_despacho" },
                {data: "vehiculo.placa" },
                {data: "tipocombustible.detalle" },
                {data: "total" },
                {data: "fecha_hora_despacho" },
                {data: "fecha_hora_despacho" },
                {data: "estado" },
                {data: "estado" },                              
                
                //{data: "tramite.asunto" }
            ],
            "rowCallback": function( row, data, index ){
                
                $('td', row).eq(1).html(data.vehiculo.descripcion+" "+data.vehiculo.codigo_institucion);

                $('td', row).eq(7).html(`
                    <button type="button" onclick="editar_detalle('${data.iddetalle_despacho}')" data-toggle="tooltip" data-original-title="Ver detalle" class="btn btn-sm btn-info btn_icon" style="margin-bottom: 0;"><i class="fa fa-edit"></i></button>
                    
                    <button type="button" onclick="ver_detalledes('${data.iddetalle_despacho}')" data-toggle="tooltip" data-original-title="Ver detalle" class="btn btn-sm btn-success btn_icon" style="margin-bottom: 0;"><i class="fa fa-eye"></i></button>
                    
                    <button type="button" onclick="eliminardetalle('${data.iddetalle_despacho}')"data-toggle="tooltip" data-original-title="Ver detalle"  class="btn btn-sm btn-danger btn_icon" style="margin-bottom: 0;" ><i class="fa fa-remove"></i></button>

                    `);

                if(data.estado=='Aprobado'){
                        
                    $('td',row).eq(6).html('<span style="min-width: 90px !important;font-size: 12px" class="label label-success estado_validado"> Aprobado &nbsp; &nbsp;&nbsp;</span>');
                    
                }
                else{
                            
                    $('td',row).eq(6).html('<span style="min-width: 90px !important;font-size: 12px" class="label label-danger estado_validado"> No aprobado &nbsp;</span>');

                } 

                if(data.firma_conductor==null){
                    $('td',row).eq(5).html('<span"> Sin Firmar &nbsp; &nbsp;&nbsp;</span>'); 
                }
                else{
                    $('td',row).eq(5).html(`<img src='data:image/png;base64,${data.firma_conductor}') class="img_firma">`);
                } 
            } 



        }); 

    });                               
                  

}

function editar_detalle(id){
    
      
    $.get("/detalle-desp/editar/"+id, function (data) {
        console.log(data);

        if(data.error==true){
            alertNotificar(data.mensaje,"error")
            return
        }
        
        EdicionDetalle="S"
        $('#titulo_btn_detalle_form').html('Actualizar')
    
        $('#facturamodal').val(data.resultado.num_factura_ticket);
       
        $('#galonesmodal').val(data.resultado.galones);
        TotalGlobalGalones=data.resultado.galones
        $('#preciounitariomodal').val(data.resultado.precio_unitario);
        PrecioUnitGas=data.resultado.precio_unitario
        $('#totalmodal').val(data.resultado.total);
   

        $('#vehiculo_id').val(data.resultado.id_vehiculo).trigger('change.select2')

        $('#chofer_id').val(data.resultado.idconductor).trigger('change.select2')
        $('#combustible_id').val(data.resultado.id_tipocombustible).trigger('change.select2')
        
        
        if(data.resultado.horometraje!=null){
            $('#horometrajemodal').attr('readonly',false);
            $('#horometrajemodal').val(data.resultado.horometraje);
            Km_Hm_Veh_Sel="horometrajemodal"
        }
        else{
            $('#horometrajemodal').attr('readonly',true);
            $('#horometrajemodal').val('');              
        }
        if(data.resultado.kilometraje!=null){
            $('#kilometrajemodal').attr('readonly',false);
            $('#kilometrajemodal').val(data.resultado.kilometraje);
            Km_Hm_Veh_Sel="kilometrajemodal"
        }
        else{
            
            $('#kilometrajemodal').attr('readonly',true);
            $('#kilometrajemodal').val('');
        }

        AccionFormDetalle="E"
        globalThis.IdDetalleEditar=id
        
    });
      
    $('.canc_detalle').show()

    

}

$("#form_idDetalleDesp").submit(function(e){
    e.preventDefault();
    
    //validamos los campos obligatorios
    let vehiculo=$('#vehiculo_id').val()
    let chofer=$('#chofer_id').val()

    let facturamodal=$('#facturamodal').val()
    let totalmodal=$('#totalmodal').val()
    let combustible_id=$('#combustible_id').val()
    let preciounitariomodal=$('#preciounitariomodal').val()
    let galones_total=$('#galonesmodal').val()
    let km_in=$('#kilometrajemodal').val();
    let Hm_in=$('#horometrajemodal').val();
       
    if(vehiculo=="" || vehiculo==null){
        alertNotificar("Seleccione un vehículo","error")
        return
    } 

    if(Km_Hm_Veh_Sel=="kilometrajemodal"){
        if(km_in==""){
            alertNotificar("Ingrese el Kilometraje ","error")
            $('#kilometrajemodal').focus()
                return
        }
    }else{
        if(Hm_in==""){
            alertNotificar("Ingrese el Horometro ","error")
            $('#horometrajemodal').focus()
            return
        }
    }

    if(chofer=="" || chofer==null){
        alertNotificar("Seleccione un chofer ","error")
        return
    } 

    if(facturamodal=="" || facturamodal==null){
        alertNotificar("Ingrese el número de factura o ticket ","error")
        $('#facturamodal').focus()
        return
    } 

    if(totalmodal=="" || totalmodal==null){
        alertNotificar("Ingrese el total ","error")
        $('#totalmodal').focus()
        return
    } 


    if(combustible_id=="" || combustible_id==null){
        alertNotificar("Seleccione el tipo combustible ","error")
        return
    } 

    if(parseFloat(preciounitariomodal)!=parseFloat(PrecioUnitGas)){
        alertNotificar("El precio del valor unitario ha sido modificado manualmente, vuelva a ingresar el total","error")
        $('#preciounitariomodal').val(PrecioUnitGas)
        $('#totalmodal').val('')
        $('#galonesmodal').val('')
        return
    }

    if(parseFloat(galones_total)!=parseFloat(TotalGlobalGalones)){
        alertNotificar("El total de galones ha sido modificado manualmente, vuelva a ingresar el total para realizar el cálculo","error")
        $('#preciounitariomodal').val(PrecioUnitGas)
        $('#totalmodal').val('')
        $('#galonesmodal').val('')
        return
    }

    $('#idcabeceradespacho').val(idCabeceraDes)
    

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //comprobamos si es registro o edicion
    let tipo=""
    let url_form=""
    if(AccionFormDetalle=="R"){
        tipo="POST"
        url_form="/guardar-detalle-desp"
    }else{
        tipo="PUT"
        url_form="/actualizar-detalle-desp/"+IdDetalleEditar
    }
  
    var FrmData=$("#form_idDetalleDesp").serialize();
   
    console.log(FrmData)
    $.ajax({
            
        type: tipo,
        url: url_form,
        method: tipo,             
		data: FrmData,      
		
        processData:false, 

        success: function(data){
            console.log(data)
            // vistacargando("");                
            if(data.error==true){
                alertNotificar(data.mensaje,'error');
                return;                      
            }
            limpiarCamposDetalle()
            alertNotificar(data.mensaje,"success");
            cargartablaDetalle()

            //preguntamos si desea firmar y aprobar el despacho generado/actualizado
            swal({
                title: "¿Desea aprobar el despacho?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Si, continuar",
                cancelButtonText: "No, cancelar",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm) {
                if (isConfirm) { 
                   //mostramos el resumen en la modal y la seccion para que firme
                   ver_detalledes(data.id_despacho)
                }
                sweetAlert.close();   // ocultamos la ventana de pregunta
            }); 
                            
        }, error:function (data) {
            console.log(data)

            // vistacargando("");
            alertNotificar('Ocurrió un error','error');
        }
    });
})


function limpiarCamposDetalle(){
    $('#vehiculo_id').val('').trigger('change.select2')
    $('#chofer_id').val('').trigger('change.select2')

    $('#facturamodal').val('')
    $('#totalmodal').val('')
    $('#combustible_id').val('').trigger('change.select2')
    $('#preciounitariomodal').val('')
    $('#galonesmodal').val('')
    $('#kilometrajemodal').val('');
    $('#horometrajemodal').val('');

    $('.canc_detalle').hide()

    AccionFormDetalle="R"
    $('#titulo_btn_detalle_form').html('Guardar')

}

function eliminardetalle(idesp){

    swal({
        title: "¿Desea eliminar el despacho?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Si, continuar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function(isConfirm) {
        if (isConfirm) { 
            $.get("/eliminar-detalle-desp/"+idesp, function(data){
                console.log(data)
              
                if(data.error==true){
                    alertNotificar(data.mensaje,"error");
                    return;   
                }
        
                alertNotificar(data.mensaje,"success");
                cargartablaDetalle()
                limpiarCamposDetalle()
               
            }).fail(function(){
               
                alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
            });
        }
        sweetAlert.close();   // ocultamos la ventana de pregunta
    }); 
    
}
function limpiarCamposDetalleTxt(){
   
    $('#fechadespachomodalapr').html('');   
    $('#gasolineramodalapr').html('');
    $('#vehiculomodalapr').html('');
    $('#totalmodalapr').html('');
    $('#kilometrajemodalapr').html('');
    $('#horometromodalapr').html('');
    $('#combustiblemodalapr').html('');  
    $('#galonesmodalapr').html('');
    $('#preciounitmodal').html('');
    $('#conductormodalapr').html('');
    $('#fechaaprmodalapr').html('');   
   
    
}

function ver_detalledes(id){
    
    $.get("/detalle-desp/editar/"+id, function (data) {
        console.log(data);

        if(data.error==true){
            alertNotificar(data.mensaje,"error")
            return
        }
        console.log(data)
        
        $('#DetalleDespacho').modal('show')


        $('#fechadespachomodalapr').html(data.resultado.fecha_hora_despacho);   
        $('#gasolineramodalapr').html(data.resultado.cabecera.gasolinera.descripcion);
        $('#vehiculomodalapr').html(data.resultado.vehiculo.descripcion+" "+data.resultado.vehiculo.codigo_institucion+ " ["+data.resultado.vehiculo.placa+"]");
        $('#totalmodalapr').html(data.resultado.total);
        $('#kilometrajemodalapr').html(data.resultado.kilometraje);
        $('#horometromodalapr').html(data.resultado.horometraje);
        $('#combustiblemodalapr').html(data.resultado.tipocombustible.detalle);  
        $('#galonesmodalapr').html(data.resultado.galones);
        $('#preciounitmodal').html(data.resultado.precio_unitario);
        $('#conductormodalapr').html(data.resultado.chofer.nombres+" "+data.resultado.chofer.apellidos);
        $('#fechaaprmodalapr').html(data.resultado.fecha_hora_aprobacion);   
      
        alert(data.resultado.estado)
        if(data.resultado.estado=="Aprobado"){
            $('#reprob').hide();
            $('#aprob').hide();
            $('#content_firma').hide();
        }
        else{
            $('#aprob').show();
            $('#content_firma').show();
            $('#reprob').hide();
        }
        $('#iddespachoApr').val(id)
        cargartareadetalle(data.resultado.id_vehiculo,data.resultado.fecha_hora_despacho);
        
        $('#signArea').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:190});
        $('#signArea_edit').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:190});

        limpiarSingArea()
      

    }).fail(function(){
               
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });

}

function cargartareadetalle(idvehi,fecha){
    $('#tareamodalapr').html('');
    
    $.get("/listar-tarea-veh/"+idvehi+'/'+fecha, function (data) {
        
        if(data.error == true){
            alertNotificar(data.mensaje,'error');
        }
        else{
               
            if(data.resultado.length===0){            
            $('#tareamodalapr').html('');
            $("#table_dato_salida").show(700);
                $('#tareamodalapr').append(
                `<li>
                    Sin tareas que mostrar
                </li>`);
            }
            else{
                var items=[];
                $.each(data.resultado, function(i,item){
                    $('#tareamodalapr').html('');   
                    $("#table_dato_salida").show(700);
                   
                    items.push($('<li/>').text(item.motivo));
                }); 
                $('#tareamodalapr').append.apply($('#tareamodalapr'), items);
            }
        
        }
    
    });
 
 }


function limpiarSingArea(){
    $('#signArea').signaturePad().clearCanvas();
}


$("#firma_aprobacion").submit(function(e){
    e.preventDefault();
   
    
   
    
    //validamos los campos obligatorios
    let iddetalledes=$('#iddespachoApr').val()
    if(iddetalledes=="" || iddetalledes==null){
        alertNotificar("No se pudo acceder al identificador del detalle","error")
        return
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var FrmData = new FormData(this);

    console.log(FrmData)
   
    // var iddetale = $("#iddetallef").val();

    html2canvas([document.getElementById('sign-pad')], {
        onrendered: function (canvas) {
            var canvas_img_data = canvas.toDataURL('image/png');
            var img_data = canvas_img_data.replace(/^data:image\/(png|jpg);base64,/, "");
           
            if($( "p" ).hasClass("error")){
                alertNotificar("Debes firmar para poder aprobar el despacho", "error")
                return
            }

            FrmData.append("b64_firma",img_data);
            $.ajax({
                    
                type: 'POST',
                url: '/aprobar-despacho-firma',
                method: 'POST',             
                data: FrmData,
                dataType: 'json',
                contentType:false,
                cache:false,
                processData:false,

                success: function(data){

                   
                    console.log(data)
                    // vistacargando("");                
                    if(data.error==true){
                        alertNotificar(data.mensaje,'error');
                        return;                      
                    }
                    limpiarCamposDetalle()
                    limpiarCamposDetalleTxt()
                    alertNotificar(data.mensaje,"success");
                    cargartablaDetalle()
                 
                    $('#cerra_modal').click();
                    
                                    
                }, error:function (data) {
                    console.log(data)

                    // vistacargando("");
                    alertNotificar('Ocurrió un error','error');
                }
            });
        }
    })
})

