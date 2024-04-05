
function buscarSalidas(){
    let desde=$('#fecha_ini').val()
    let hasta=$('#fecha_fin').val()
    if(desde=="" || desde==null){
        alertNotificar("Seleccione la fecha de inicio", "error")
        $('#fecha_ini').focus()
        return
    }
    if(hasta=="" || hasta==null){
        alertNotificar("Seleccione la fecha final", "error")
        $('#hasta').focus()
        return
    }

    if(desde > hasta){
        alertNotificar("La fecha final debe ser superior o igual a la inicial", "error")
        $('#hasta').focus()
        return
    }
    
    var num_col = $("#tabla_consolidado thead tr th").length; //obtenemos el numero de columnas de la tabla
	$("#tabla_consolidado tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center><span class="spinner-border" role="status" aria-hidden="true"></span><b> Obteniendo información</b></center></td></tr>`);

    vistacargando("m","Espere por favor")
    $.get("obtener-salidas/"+desde+"/"+hasta, function(data){
        vistacargando("")
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            $("#tabla_salidas tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
            return;   
        }
        if(data.error==false){
            
            if(data.resultado.length <= 0){
                $("#tabla_salidas tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
                alertNotificar("No se encontró datos","error");
                return;  
            }
         
            $('#tabla_salidas').DataTable({
                "destroy":true,
                pageLength: 10,
                autoWidth : true,
                order: [[ 1, "desc" ]],
                sInfoFiltered:false,
                language: {
                    url: 'json/datatables/spanish.json',
                },
                columnDefs: [
                    { "width": "10%", "targets": 0 },
                    { "width": "15%", "targets": 1 },
                    { "width": "30%", "targets": 2 },
                    { "width": "30%", "targets": 3 },
                    { "width": "15%", "targets": 4 },
                    { "width": "10%", "targets": 5 },
                   
                ],
                data: data.resultado,
                columns:[
                        {data: "vehiculo.placa"},
                        {data: "chofer.nombres" },
                        {data: "chofer.apellidos"},
                        {data: "lugar_llegada_destino"},
                        {data: "nro_ticket"},
                        {data: "vehiculo.descripcion"},
                ],    
                "rowCallback": function( row, data ) {
                    $('td',row).eq(0).html(data.vehiculo.descripcion +" ["+data.vehiculo.placa+"]")
                    $('td',row).eq(1).html(data.chofer.nombres +" "+data.chofer.apellidos)

                    $('td',row).eq(2).html(`<li> <b>Lugar:</b> ${data.lugar_salida_patio} </li>
                                            <li> <b>Fecha Salida:</b> ${data.fecha_hora_salida_patio} </li>
                                            <li> <b>Km Salida:</b> ${data.km_salida_patio} </li>

                                            <li>  <b>Fecha Llegada:</b> ${data.fecha_hora_llega_patio}</li>
                                            <li>  <b>Km Llegada:</b> ${data.km_llegada_patio}</li>

                                            `)
                    $('td',row).eq(3).html(`<li> <b>Lugar:</b> ${data.lugar_llegada_destino} </li>

                                            <li>  <b>Fecha Llegada:</b> ${data.fecha_hora_llega_destino}</li>
                                            <li>  <b>Km Llegada:</b> ${data.km_llegada_destino}</li>
                                            <li> <b>Fecha Salida:</b> ${data.fecha_hora_salida_destino} </li>
                                            <li> <b>Km Salida:</b> ${data.km_salida_destino} </li>

                                           

                                            `)

                    $('td', row).eq(5).html(`
                                  
                                            <a onclick="reporte_movimiento(${data.idmovimiento })" class="btn btn-success btn-xs"
                                            style="margin-top:3px"> Reporte </a>
                                       
                                    
                    `); 
                }             
            });
            $('#content_consulta').hide()
            $('#listado').show()
        }
    }).fail(function(){
        vistacargando("")
        $("#tabla_salidas tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });   
       
}

function regresarBusqueda(){
    $('#content_consulta').show()
    $('#listado').hide()
}

function limpiarCampos(){

    $('#kilometraje').val('')
    $('#horometro').val('')
    $('#entrada_salida').val('').trigger('change.select2')
    $('#vehiculo_tarea').val('').trigger('change.select2')
    $('#chofer').val('').trigger('change.select2')

    $('#autorizado').val('').trigger('change.select2')
   
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
    
    $('#div_novedad').hide()

    $('#tiene_novedad').val('No').trigger('change.select2')
    $('#txt_novedad').val('')
}


function llenar_tabla_salidas(){
    var num_col = $("#tabla_salidas thead tr th").length; //obtenemos el numero de columnas de la tabla
	$("#tabla_salidas tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center><span class="spinner-border" role="status" aria-hidden="true"></span><b> Obteniendo información</b></center></td></tr>`);
   
    
    $.get("obtener-salidas/", function(data){
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            $("#tabla_salidas tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
            return;   
        }
        if(data.error==false){
            
            if(data.resultado.length <= 0){
                $("#tabla_salidas tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
                alertNotificar("No se encontró datos","error");
                return;  
            }
         
            $('#tabla_salidas').DataTable({
                "destroy":true,
                pageLength: 10,
                autoWidth : true,
                order: [[ 1, "desc" ]],
                sInfoFiltered:false,
                language: {
                    url: 'json/datatables/spanish.json',
                },
                columnDefs: [
                    { "width": "10%", "targets": 0 },
                    { "width": "15%", "targets": 1 },
                    { "width": "30%", "targets": 2 },
                    { "width": "30%", "targets": 3 },
                    { "width": "15%", "targets": 4 },
                    { "width": "10%", "targets": 5 },
                   
                ],
                data: data.resultado,
                columns:[
                        {data: "vehiculo.placa"},
                        {data: "fecha_registro" },
                        {data: "fecha_registro"},
                        {data: "fecha_registro"},
                        {data: "nro_ticket"},
                        {data: "fecha_registro"},
                ],    
                "rowCallback": function( row, data ) {
                    $('td',row).eq(0).html(data.vehiculo.descripcion +" ["+data.vehiculo.placa+"]")
                    $('td',row).eq(1).html(data.chofer.nombres +" "+data.chofer.apellidos)

                    $('td',row).eq(2).html(`<li> <b>Lugar:</b> ${data.lugar_salida_patio} </li>
                                            <li> <b>Fecha Salida:</b> ${data.fecha_hora_salida_patio} </li>
                                            <li> <b>Km Salida:</b> ${data.km_salida_patio} </li>

                                            <li>  <b>Fecha Llegada:</b> ${data.fecha_hora_llega_patio}</li>
                                            <li>  <b>Km Llegada:</b> ${data.km_llegada_patio}</li>

                                            `)
                    $('td',row).eq(3).html(`<li> <b>Lugar:</b> ${data.lugar_llegada_destino} </li>

                                            <li>  <b>Fecha Llegada:</b> ${data.fecha_hora_llega_destino}</li>
                                            <li>  <b>Km Llegada:</b> ${data.km_llegada_destino}</li>
                                            <li> <b>Fecha Salida:</b> ${data.fecha_hora_salida_destino} </li>
                                            <li> <b>Km Salida:</b> ${data.km_salida_destino} </li>

                                           

                                            `)


                    // if(data.firmaconductor==null){
                        
                    //     $('td',row).eq(4).html('<span"> Sin Firmar &nbsp; &nbsp;&nbsp;</span>'); 
                    // }
                    // else{
                    //         // estad="Atendido"
                    // $('td',row).eq(4).html(`<img src='data:image/png;base64,${data.firmaconductor}') class="img_firma">`);
                    // } 

                    // <a onclick="reporte_movimiento(${data.idmovimiento })" class="btn btn-success btn-xs"
                    // style="margin-top:3px"> Reporte </a>

                    $('td', row).eq(5).html(`
                                  
                                            <a onclick="verpdf(${data.idmovimiento })" class="btn btn-success btn-xs"
                                            style="margin-top:3px"> Reporte </a>
                                       
                                    
                    `); 
                }             
            });
        }
    }).fail(function(){
        $("#tabla_salidas tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
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
        vistacargando("")
        
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            return;   
        }
        verpdf(id)
     
   }).fail(function(){
       vistacargando("")
       alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
   });
}

//permite visualizarr el pdf de la emision en una modal
function verpdf(id){
    var nombre_pdf="movimiento_"+id+".pdf"; 
   
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
    
}

function visualizarListado(){
    $('#form_ing').hide(200)
    $('#listado_veh').show(200)
    limpiarCampos()
    limpiarSingArea()
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

