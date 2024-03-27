function mayus(e) {
    e.value = e.value.toUpperCase();
}

function validaValoresKmSalidaPatio(){
    let km_salida_patio= $('#km_salida_patio').val()
    if(parseFloat(km_salida_patio) < parseFloat(ValorMinimoKm_Hm)){
        // alertNotificar("El kilometraje de salida no puede ser menor a "+ValorMinimoKm_Hm,"error")
        // $('#km_salida_patio').focus()
        // return
    }
}
function lugardestino(){
    let lugar_destino=$('#l_destino_ll').val()
    $('#l_sal_destino').val(lugar_destino)
}

function kmdestino(){
    let km_destino=$('#km_destino_ll').val()
    $('#km_salida_dest').val(km_destino)
}

function validaValoresKmDest(){
    let km_destino_ll= $('#km_destino_ll').val()
    let km_salida_patio= $('#km_salida_patio').val()
    if(parseFloat(km_destino_ll) <= parseFloat(km_salida_patio)){
        alertNotificar("El kilometraje de destino no puede ser menor o igual al kilometraje salida patio","error")
        $('#km_destino_ll').focus()
        return
    }
}

function validaFechaHoraDestino(){
    let fecha_salida_patio=$('#fecha_h_salida_patio').val()
    let fecha_h_destino=$('#fecha_h_destino').val()

    if(fecha_h_destino <= fecha_salida_patio){
        alertNotificar("La fecha hora destino no puede ser menor o igual a la fecha hora salida de patio","error")
        $('#fecha_h_destino').focus()
        return
    }
}

function validaFechaHoraLlegadaPatio(){
    let fecha_h_destino_salida=$('#fecha_h_destino_salida').val()
    let fecha_h_llegada_patio=$('#fecha_h_llegada_patio').val()

    if(fecha_h_llegada_patio <= fecha_h_destino_salida){
        // alertNotificar("La fecha hora llegada a patio no puede ser menor o igual a la fecha hora salida de destino","error")
        // $('#fecha_h_llegada_patio').focus()
        return
    }
}

function validaFechaHoraSalidaDestino(){
    let fecha_h_destino=$('#fecha_h_destino').val()
    let fecha_h_destino_salida=$('#fecha_h_destino_salida').val()

    if(fecha_h_destino_salida <= fecha_h_destino){
        alertNotificar("La fecha hora salida destino no puede ser menor a la fecha hora destino llegada","error")
        $('#fecha_h_destino_salida').focus()
        return
    }
}

function validaValoresKmLlegadaPatio(){
    let km_destino_ll= $('#km_destino_ll').val()
    let km_llegada_patio= $('#km_llegada_patio').val()
    if(parseFloat(km_llegada_patio) <= parseFloat(km_destino_ll)){
        alertNotificar("El kilometraje de llegada a patio no puede ser menor o igual al kilometraje salida destino","error")
        $('#km_llegada_patio').focus()
        return
    }
}

$("#form_registro_tarea").submit(function(e){
    e.preventDefault();
    
    //validamos los campos obligatorios
    let vehiculo=$('#vehiculo_tarea').val()
    let chofer=$('#chofer').val()
    let kilometraje=$('#kilometraje').val()
    let horometro=$('#horometro').val()
    let n_ticket=$('#n_ticket').val()
    
    let l_salida_patio=$('#l_salida_patio').val()
    let fecha_h_salida_patio=$('#fecha_h_salida_patio').val()
    let km_salida_patio=$('#km_salida_patio').val()
    let l_destino_ll=$('#l_destino_ll').val()
    let fecha_h_destino=$('#fecha_h_destino').val()
    let km_destino_ll=$('#km_destino_ll').val()
    let l_sal_destino=$('#l_sal_destino').val()
    let fecha_h_destino_salida=$('#fecha_h_destino_salida').val()
    let km_salida_dest=$('#km_salida_dest').val()
    let l_llegada_pat=$('#l_llegada_pat').val()
    let fecha_h_llegada_patio=$('#fecha_h_llegada_patio').val()
    let km_llegada_patio=$('#km_llegada_patio').val()
    let motivo=$('#motivo').val()
    let acompanante=$('#acompanante').val()
    
    let solicitante=$('#solicitante').val()
    let area_sol=$('#area_sol').val()
    let tiene_novedad=$('#tiene_novedad').val()
    let txt_novedad= $('#txt_novedad').val()

    let autorizado=$('#autorizado').val()

    if(n_ticket=="" || n_ticket==null){
        alertNotificar("Ingrese el número de ticket","error")
        $('#n_ticket').focus()
        return
    } 
    
    if(vehiculo=="" || vehiculo==null){
        alertNotificar("Seleccione el vehículo","error")
        return
    } 

   


    if(fecha_h_salida_patio=="" || fecha_h_salida_patio==null){
        alertNotificar("Ingrese la fecha salida patio","error")
        $('#fecha_h_salida_patio').focus()
        return
    } 

    if(km_salida_patio=="" || km_salida_patio==null){
        alertNotificar("Ingrese el kilometraje salida patio","error")
        $('#km_salida_patio').focus()
        return
    } 

    if(l_destino_ll=="" || l_destino_ll==null){
        alertNotificar("Ingrese el lugar destino","error")
        $('#l_destino_ll').focus()
        return
    } 

    if(fecha_h_destino=="" || fecha_h_destino==null){
        alertNotificar("Ingrese la fecha de llegada destino","error")
        $('#fecha_h_destino').focus()
        return
    } 

    if(km_destino_ll=="" || km_destino_ll==null){
        alertNotificar("Ingrese el kilometraje de llegada al destino","error")
        $('#km_destino_ll').focus()
        return
    } 

    if(parseFloat(km_destino_ll) <= parseFloat(km_salida_patio)){
        alertNotificar("El kilometraje de destino no puede ser menor al kilometraje salida patio","error")
        $('#km_destino_ll').focus()
        return
    }

    if(fecha_h_destino <= fecha_h_salida_patio){
        alertNotificar("La fecha hora destino no puede ser menor o igual a la fecha hora salida de patio","error")
        $('#fecha_h_destino').focus()
        return
    }

    if(fecha_h_llegada_patio <= fecha_h_destino_salida){
        // alertNotificar("La fecha hora llegada a patio no puede ser menor o igual a la fecha hora salida de destino","error")
        // $('#fecha_h_llegada_patio').focus()
        return
    }

    if(fecha_h_destino_salida <= fecha_h_destino){
        alertNotificar("La fecha hora salida destino no puede ser menor a la fecha hora destino llegada","error")
        $('#fecha_h_destino_salida').focus()
        return
    }

    if(parseFloat(km_llegada_patio) <= parseFloat(km_destino_ll)){
        alertNotificar("El kilometraje de llegada a patio no puede ser menor o igual al kilometraje salida destino","error")
        $('#km_llegada_patio').focus()
        return
    }


    if(fecha_h_destino_salida=="" || fecha_h_destino_salida==null){
        alertNotificar("Ingrese la fecha de salida destino","error")
        $('#fecha_h_destino_salida').focus()
        return
    } 

   
    if(fecha_h_llegada_patio=="" || fecha_h_llegada_patio==null){
        alertNotificar("Ingrese la fecha llegada a patio","error")
        $('#fecha_h_llegada_patio').focus()
        return
    }

    if(km_llegada_patio=="" || km_llegada_patio==null){
        alertNotificar("Ingrese el kilomentraje llegada a patio","error")
        $('#km_llegada_patio').focus()
        return
    }

    if(motivo=="" || motivo==null){
        alertNotificar("Ingrese el motivo","error")
        $('#motivo').focus()
        return
    }

    if(acompanante=="" || acompanante==null){
        alertNotificar("Ingrese el acompanante","error")
        $('#acompanante').focus()
        return
    }

    if(parseFloat(km_destino_ll) <= parseFloat(km_salida_patio)){
        alertNotificar("El kilometraje de destino no puede ser menor o igual al kilometraje salida patio","error")
        $('#km_destino_ll').focus()
        return
    }

    if(solicitante=="" || solicitante==null){
        alertNotificar("Ingrese el funcionario solicitante","error")
        $('#solicitante').focus()
        return
    } 

    if(area_sol=="" || area_sol==null){
        alertNotificar("Seleccione el área solicitante'","error")

        return
    } 
    

    if(autorizado=="" || autorizado==null){
        alertNotificar("Seleccione la persona que autoriza'","error")
     
        return
    } 


    if(tiene_novedad=="Si"){
        if(txt_novedad =="" || txt_novedad==null){
            alertNotificar("Ingrese la novedad'","error")
            $('#txt_novedad').focus()
            return
        }
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
        url_form="guardar-movimiento"
    }else{
        tipo="PUT"
        url_form="actualizar-tarea/"+IdTareaEditar
    }

    var FrmData = new FormData(this);

  
    $.ajax({
            
        type: tipo,
        url: url_form,
        method: tipo,             
        data: FrmData,
        dataType: 'json',
        contentType:false,
        cache:false,
        processData:false,
    

        success: function(data){
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

            reporte_movimiento(data.idmovimiento)
            // limpiarSingArea()
                            
        }, error:function (data) {

            // vistacargando("");
            alertNotificar('Ocurrió un error','error');
        }
    });
       
})

function limpiarCampos(){

    $('#kilometraje').val('')
    $('#horometro').val('')
    $('#entrada_salida').val('').trigger('change.select2')
    $('#vehiculo_tarea').val('').trigger('change.select2')
    $('#area_sol').val('').trigger('change.select2')
    $('#chofer').val('').trigger('change.select2')

    $('#autorizado').val('').trigger('change.select2')
    $('#n_ticket').val('').trigger('change.select2')
   
    $('#observacion').val('')
    // limpiarSingArea()
    $("#table_dato_salida").hide();
    $('#tbody_dato_salida').html('');
    $('#tareasguard').val();

    $('#msmDetalledos').html('')
    $('#msmDetalledos').hide()

    $('#n_ticket').val('')
    $('#fecha_h_salida_patio').val('')
    $('#km_salida_patio').val('')
    $('#l_destino_ll').val('')
    $('#fecha_h_destino').val('')

    $('#km_destino_ll').val('')
    $('#l_sal_destino').val('')
    $('#fecha_h_destino_salida').val('')
    $('#km_salida_dest').val('')

    $('#fecha_h_llegada_patio').val('')
    $('#km_llegada_patio').val('')
    $('#motivo').val('')
    $('#acompanante').val('')
    $('#solicitante').val('')
    $('#area_sol').val('')
    
    $('#div_novedad').hide()

    $('#tiene_novedad').val('No').trigger('change.select2')
    $('#txt_novedad').val('')
}

function cambiaNovedad(){
    let tiene_novedad=$('#tiene_novedad').val()
    if(tiene_novedad=="Si"){
        $('#div_novedad').show()
    }else{
        $('#div_novedad').hide()
        $('#txt_novedad').val('')
    }
    
}

function llenar_tabla_tarea(){
    var num_col = $("#tabla_tarea thead tr th").length; //obtenemos el numero de columnas de la tabla
	$("#tabla_tarea tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center><span class="spinner-border" role="status" aria-hidden="true"></span><b> Obteniendo información</b></center></td></tr>`);
   
    
    $.get("listado-movimiento/", function(data){
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
                    url: 'json/datatables/spanish.json',
                },
                columnDefs: [
                    { "width": "25%", "targets": 0 },
                    { "width": "30%", "targets": 1 },
                    { "width": "30%", "targets": 2 },
                    { "width": "15%", "targets": 3 },
                    { "width": "10%", "targets": 4 },
                   
                ],
                data: data.resultado,
                columns:[
                        {data: "vehiculo.placa"},
                        {data: "fecha_registro" },
                        {data: "fecha_registro"},
                        {data: "fecha_registro"},
                        {data: "fecha_registro"},
                       
                ],    
                "rowCallback": function( row, data ) {
                    // $('td',row).eq(0).html(data.vehiculo.descripcion +" ["+data.vehiculo.placa+"]")
                    // $('td',row).eq(1).html(data.chofer.nombres +" "+data.chofer.apellidos)

                    $('td',row).eq(0).html(`<li>
                                                <b>Vehiculo:</b> ${data.vehiculo.descripcion} ${data.vehiculo.codigo_institucion} [${data.vehiculo.placa}]  
                                            </li>
                                            <li> 
                                                <b>Chofer:</b> ${data.chofer.nombres} ${data.chofer.apellidos}
                                            </li>
                                            <li> <b>Número Ticket:</b> ${data.nro_ticket} </li>
                                            `)

                    $('td',row).eq(1).html(`<li> <b>Lugar:</b> ${data.lugar_salida_patio} </li>
                                            <li> <b>Fecha Salida:</b> ${data.fecha_hora_salida_patio} </li>
                                            <li> <b>Km Salida:</b> ${data.km_salida_patio} </li>

                                            <li>  <b>Fecha Llegada:</b> ${data.fecha_hora_llega_patio}</li>
                                            <li>  <b>Km Llegada:</b> ${data.km_llegada_patio}</li>

                                            `)
                    $('td',row).eq(2).html(`<li> <b>Lugar:</b> ${data.lugar_llegada_destino} </li>

                                            <li>  <b>Fecha Llegada:</b> ${data.fecha_hora_llega_destino}</li>
                                            <li>  <b>Km Llegada:</b> ${data.km_llegada_destino}</li>
                                            <li> <b>Fecha Salida:</b> ${data.fecha_hora_salida_destino} </li>
                                            <li> <b>Km Salida:</b> ${data.km_salida_destino} </li>

                                           

                                            `)


                    if(data.chofer.firma_persona==null){
                        
                        $('td',row).eq(3).html('<span"> Sin Firmar &nbsp; &nbsp;&nbsp;</span>'); 
                    }
                    else{
                    
                        $('td',row).eq(3).html(`<img src='data:image/png;base64,${data.chofer.firma_persona}') class="img_firma">`);
                    } 

                    $('td', row).eq(4).html(`
                                  
                                            
                                            <a onclick="btn_eliminar_movimi(${data.idmovimiento })" class="btn btn-danger btn-xs"> Eliminar </a><br>

                                            <a onclick="reporte_movimiento(${data.idmovimiento })" class="btn btn-success btn-xs"
                                            style="margin-top:3px"> Reporte </a>
                                       
                                    
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


function reporte_movimiento(id){
//    window.location.href='reporte-mov-ind/'+id
   vistacargando("m","Espere por favor")
   $.get("reporte-mov-ind/"+id, function(data){
        console.log(data)
        vistacargando("")
        
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            return;   
        }
        verpdf(data.pdf)
     
   }).fail(function(){
       vistacargando("")
       alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
   });
}

//permite visualizarr el pdf de la emision en una modal
function verpdf(nombre_pdf){
    // var nombre_pdf="movimiento_"+id+".pdf";
    // alert(nombre_pdf)
    var iframe=$('#iframePdf');
    iframe.attr("src", "visualizar-documento-orden/"+nombre_pdf);   
    $("#vinculo").attr("href", 'descargar-doc-orden/'+nombre_pdf);
    $("#documentopdf").modal("show");
    $('#titulo').html('Rutas');
}

//limpiamos los datos de la modal
$('#documentopdf').on('hidden.bs.modal', function (e) {            
    var iframe=$('#iframePdf');
    iframe.attr("src", null);

});

$('#descargar').click(function(){
    $('#documentopdf').modal("hide");
});

function visualizarForm(tipo){
    $('#chofer').val($('#idchofer_loguea').val()).trigger('change.select2')
    $('#form_ing').show(200)
    $('#listado_veh').hide(200)
    globalThis.AccionForm="";
    if(tipo=='N'){
        $('#titulo_form').html("Registro Movimiento")
        $('#nombre_btn_form').html('Registrar')
        AccionForm="R"
    }else{
        $('#titulo_form').html("Actualización Tarea")
        $('#nombre_btn_form').html('Actualizar')
        AccionForm="E"
    }
    $('#signArea').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:190});
    $('#signArea_edit').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:190});
}

function visualizarListado(){
    $('#form_ing').hide(200)
    $('#listado_veh').show(200)
    limpiarCampos()
    // limpiarSingArea()
}

function btn_eliminar_movimi(idmovimiento){
    if(confirm('¿Quiere eliminar el registro?')){
        $.get("eliminar-movimiento/"+idmovimiento, function(data){
          
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


function cargartarea(){

    let idveh=$('#vehiculo_tarea').val()
    if(idveh=="" || idveh==undefined){
        return
    }
    $('#tbody_dato_salida').html('');
    $('#tareasguard').val('');
    $.get("carga-tarea/"+idveh, function (data) {
        if(data.error == true){
            alertNotificar(data.mensaje,"error");
        }
        else{
        
            if(data.resultado.length===0){
                // $('#chofer').val('').trigger('change.select2')
                var tarea="no";
                // $('#tareasguard').val(''); 
              
                $("#table_dato_salida").show(700);

                
                $('#tbody_dato_salida').append(
                    `<tr>
                        <td colspan="2">
                            <center>Sin tareas que mostrar</center>
                           
                        </td>
                        
                    </tr>`);
                }
            else{
                var tarea="si";
                 

                //cargamos el chofer que se realizo la tarea
                $('#chofer').val(data.resultado[0].id_chofer).trigger('change.select2')
            } 
        
            $("#table_dato_salida").show(700);
            $.each(data.resultado, function(i,item){
            
                $('#tbody_dato_salida').append(
                    `<tr>
                        <td style="color:black" >
                            <input type='hidden' name='tareasguard[]' id='tareasguard' value='${item.id_tarea}'>
                            ${i+1}
                        </td>
                        <td style="color:black">${item.motivo}</td>
                        
                    </tr>`);

                // $('#tareasguard').val(item.id_tarea);
                
            
            }); 

            //km o hm
            globalThis.TipoMedi=""
            //ultimo km o hm
            globalThis.ValorMinimoKm_Hm=0;
            if(data.medicion.tipo_medicion.detalle=="Kilometraje"){
               
                $('#km_txt').show()
                $('#hm_txt').hide()
                $('#kilometraje').val('')
                $('#horometro').val('')
                TipoMedi="Kilometraje"
                var resp=0;
                if(data.ultimoKm_Hm!=null){

                    if(data.ultimoKm_Hm.km_llegada_patio==null){
                        var resp=0;
                                
                    }else{
                        var resp=data.ultimoKm_Hm.km_llegada_patio
                    } 
                }

            }else{
                
                $('#km_txt').hide()
                $('#hm_txt').show()
                $('#kilometraje').val('')
                $('#horometro').val('')
                TipoMedi="Horometro"
                var resp=0;
                if(data.ultimoKm_Hm!=null){
                    if(data.ultimoKm_Hm.horometro==null){
                        var resp=0;
                                
                    }else{
                        var resp=data.ultimoKm_Hm.horometro
                    } 
                }
                   
            }

           
          
            ValorMinimoKm_Hm=resp
            $('#msmDetalledos').html(`
                                        <div class="alert alert-info alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        El valor del último ${TipoMedi} fué ${resp}.
                                        </div>
                                    `);
            $('#msmDetalledos').show(200);
        }


    });


}

$(document).ready(function () {
    $('#signArea').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:190});
    $('#signArea_edit').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:190});
});
    

function limpiarSingArea(){
    $('#signArea').signaturePad().clearCanvas();
}

//Busqueda de persona por cedula o nombre
$('#n_ticket').select2({
   
    placeholder: 'Seleccione una opción',
    ajax: {
    url: 'buscar-ticket-persona',
    dataType: 'json',
    delay: 250,
    processResults: function (data) {
        return {
        results:  $.map(data, function (item) {
                return {
                    text: item.numero_ticket,
                    id: item.numero_ticket
                }
            })
        };
    },
    cache: true
    }
});

function cargaInfoTicket(){

    let n_ticket=$('#n_ticket').val()
    if(n_ticket=="" || n_ticket==null){return}
    vistacargando("m","Espere por favor")
    $.get("info-veh-ticket/"+n_ticket, function(data){
        vistacargando("")
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            return;   
        }
        $('#vehiculo_tarea').val(data.resultado.id_vehiculo).trigger('change.select2')
      
      
       
    }).fail(function(){
        vistacargando("")
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });
}
