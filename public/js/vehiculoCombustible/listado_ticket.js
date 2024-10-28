function buscarTickets(){
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
    $('#desde_detalle').html('')
    $('#cantidad_detalle').html('')
    $('#hasta_detalle').html('')
    $('#total_detalle').html('')
    
    var num_col = $("#tabla_ticket thead tr th").length; //obtenemos el numero de columnas de la tabla
	$("#tabla_ticket tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center><span class="spinner-border" role="status" aria-hidden="true"></span><b> Obteniendo información</b></center></td></tr>`);

    
    vistacargando("m", "Espere por favor")
    let total_valor=0
    $.get("listado-ticket-fecha/"+desde+"/"+hasta, function(data){
        console.log(data)
        vistacargando("")
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            $("#tabla_ticket tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
            visualizarBusqueda()
            return;   
        }
        if(data.error==false){
            
            if(data.resultado.length <= 0){
               
                $("#tabla_ticket tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
                alertNotificar("No se encontró datos","error");
                visualizarBusqueda()
                return;  
            }
           
            $('#listado_tickets').show()
            $('#content_consulta').hide(200)
            $('#tabla_ticket').DataTable({
                "destroy":true,
                pageLength: 10,
                autoWidth : true,
                order: [[ 5, "desc" ]],
                sInfoFiltered:false,
                language: {
                    url: 'json/datatables/spanish.json',
                },
                columnDefs: [
                    { "width": "7%", "targets": 0 },
                    { "width": "13%", "targets": 1 },
                    { "width": "20%", "targets": 2 },
                    { "width": "23%", "targets": 3 },
                    { "width": "7%", "targets": 4 },
                    { "width": "10%", "targets": 5 },
                    { "width": "10%", "targets": 6, "className": "text-right" },
                    { "width": "10%", "targets": 7 },
                   
                ],
                data: data.resultado,
                columns:[
                        {data: "numero_ticket"},
                        {data: "numero_ticket" },
                        {data: "numero_ticket"},
                        {data: "gasolinera.descripcion"},
                        {data: "combustible.detalle"},
                        {data: "f_despacho"},
                        {data: "total"},
                        {data: "total"},
                ],    
                "rowCallback": function( row, data ) {
                    $('td', row).eq(1).html(data.vehiculo.codigo_institucion+" "+data.vehiculo.descripcion)
                    $('td', row).eq(2).html(data.chofer.nombres+" "+data.chofer.apellidos)
                    $('td', row).eq(7).html(`
                                  
                                            <button type="button" class="btn btn-primary btn-xs" onclick="Detalle(${data.id})">Detalle</button>
                                                                                
                                       
                                    
                    `); 
                }             
            });

            $(".box-title").html(`<button type="button" onclick="visualizarBusqueda()" class="btn btn-danger btn-sm">Atrás</button>`)
           
            $.each(data.resultado, function(i,item){
                total_valor=total_valor + Number(item.total)
            })
            console.log("TOTAL => "+total_valor)  

            $('#desde_detalle').html(desde)
            $('#cantidad_detalle').html(data.resultado.length)
            $('#hasta_detalle').html(hasta)
            $('#total_detalle').html(total_valor.toFixed(2))
        }
    }).fail(function(){
        vistacargando("")
        $("#tabla_ticket tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
        visualizarBusqueda()
    });         
                

}



function llenar_tabla_ticket(){
    var num_col = $("#tabla_ticket thead tr th").length; //obtenemos el numero de columnas de la tabla
	$("#tabla_ticket tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center><span class="spinner-border" role="status" aria-hidden="true"></span><b> Obteniendo información</b></center></td></tr>`);
   
    
    $.get("listado-ticket-todos/", function(data){
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            $("#tabla_ticket tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
            return;   
        }
        if(data.error==false){
            
            if(data.resultado.length <= 0){
                $("#tabla_ticket tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
                alertNotificar("No se encontró datos","error");
                return;  
            }
         
            $('#tabla_ticket').DataTable({
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
                    { "width": "20%", "targets": 2 },
                    { "width": "15%", "targets": 3 },
                    { "width": "10%", "targets": 4 },
                    { "width": "20%", "targets": 5 },
                    { "width": "10%", "targets": 6 },
                   
                ],
                data: data.resultado,
                columns:[
                        {data: "numero_ticket"},
                        {data: "numero_ticket" },
                        {data: "numero_ticket"},
                        {data: "gasolinera.descripcion"},
                        {data: "combustible.detalle"},
                        {data: "total"},
                        {data: "total"},
                ],    
                "rowCallback": function( row, data ) {
                    $('td', row).eq(1).html(data.vehiculo.codigo_institucion+" "+data.vehiculo.descripcion)
                    $('td', row).eq(2).html(data.chofer.nombres+" "+data.chofer.apellidos)
                    $('td', row).eq(6).html(`
                                  
                                            <button type="button" class="btn btn-primary btn-xs" onclick="Detalle(${data.id})">Detalle</button>
                                                                                
                                       
                                    
                    `); 
                }             
            });
        }
    }).fail(function(){
        $("#tabla_ticket tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });


}

$('.collapse-link').click();
$('.datatable_wrapper').children('.row').css('overflow','inherit !important');

$('.table-responsive').css({'padding-top':'12px','padding-bottom':'12px', 'border':'0', 'overflow-x':'inherit'});



function Detalle(idticket){
    vistacargando("m","Espere por favor")
    $.get("editar-ticket/"+idticket, function(data){
        vistacargando("")
      
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            return;   
        }
        if(data.resultado==null){
            alertNotificar("La informacion ya no se puede editar","error");
            return;   
        }


        $('#numero_ticket').val(data.resultado.numero_ticket)
        
        $('#id_vehiculo').val(data.resultado.id_vehiculo).trigger('change.select2')
        $('#cmb_gasolinera').val(data.resultado.id_gasolinera).trigger('change.select2')
        $('#cmb_tipocombustible').val(data.resultado.id_tipocombustible).trigger('change.select2')
        $('#total').val(data.resultado.total)
        $('#f_despacho').val(data.resultado.f_despacho)

        visualizarForm('E')
        globalThis.idTicketEditar=idticket



       
    }).fail(function(){
        vistacargando("")
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });
}

function visualizarForm(tipo){
    $('#form_ing').show(200)
    $('#listado_ticket').hide(200)
    globalThis.AccionForm="";
    if(tipo=='N'){
        $('#titulo_form').html("Registro Ticket")
        $('#nombre_btn_form').html('Registrar')
        AccionForm="R"
    }else{
        $('#titulo_form').html("Detalle Ticket")
        $('#nombre_btn_form').html('Actualizar')
        AccionForm="E"
    }
}

function visualizarBusqueda(){
    $('#listado_tickets').hide(200)
    $('#content_consulta').show(200)
    $('#fecha_ini').val('')
    $('#fecha_fin').val('')
    $(".box-title").html(`Buscar`)
}

function visualizarListado(){
    $('#form_ing').hide(200)
    $('#listado_ticket').show(200)
    limpiarCampos()
}

function limpiarCampos(){
    $('#numero_ticket').val('')
    $('#id_vehiculo').val('').trigger('change.select2')
    $('#cmb_gasolinera').val('').trigger('change.select2')
    $('#cmb_tipocombustible').val('').trigger('change.select2')
    $('#total').val('')
    $('#f_despacho').val('')
}

