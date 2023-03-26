

$("#form_ticket").submit(function(e){
    e.preventDefault();
    
    //validamos los campos obligatorios
    let numero_ticket=$('#numero_ticket').val()
    let id_vehiculo=$('#id_vehiculo').val()
    let cmb_gasolinera=$('#cmb_gasolinera').val()
    let cmb_tipocombustible=$('#cmb_tipocombustible').val()
    let total=$('#total').val()
    let f_despacho=$('#f_despacho').val()

    if(numero_ticket=="" || numero_ticket==null){
        alertNotificar("Debe ingresar el número de ticket","error")
        $('#numero_ticket').focus()
        return
    } 

    if(id_vehiculo=="" || id_vehiculo==null){
        alertNotificar("Debe seleccionar el vehículo","error")
       
        return
    } 

    if(cmb_gasolinera=="" || cmb_gasolinera==null){
        alertNotificar("Debe seleccionar la gasolinera","error")
        
        return
    } 

    if(cmb_tipocombustible=="" || cmb_tipocombustible==null){
        alertNotificar("Debe seleccionar el tipo combustible","error")
        return
    } 

    if(total=="" || total==null){
        alertNotificar("Debe ingresar el total","error")
        $('#total').focus()
        return
    } 

    if(total<=0){
        alertNotificar("El total debe ser mayor a cero","error")
        $('#total').focus()
        return
    } 

    if(f_despacho=="" || f_despacho==null){
        alertNotificar("Debe ingresar la fecha de despacho","error")
        $('#f_despacho').focus()
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
        url_form="guardar-ticket"
    }else{
        tipo="PUT"
        url_form="actualizar-ticket/"+idTicketEditar
    }
    vistacargando("m","Espere por favor")
    var FrmData=$("#form_ticket").serialize();
   
    $.ajax({
            
        type: tipo,
        url: url_form,
        method: tipo,             
		data: FrmData,      
		
        processData:false, 

        success: function(data){
           
            vistacargando("");                
            if(data.error==true){
                alertNotificar(data.mensaje,'error');
                return;                      
            }
            limpiarCampos()
            alertNotificar(data.mensaje,"success");
            $('#form_ing').hide(200)
            $('#listado_ticket').show(200)
            llenar_tabla_ticket()
                            
        }, error:function (data) {
           
            vistacargando("");
            alertNotificar('Ocurrió un error','error');
        }
    });
})

function limpiarCampos(){
    $('#numero_ticket').val('')
    $('#id_vehiculo').val('').trigger('change.select2')
    $('#cmb_gasolinera').val('').trigger('change.select2')
    $('#cmb_tipocombustible').val('').trigger('change.select2')
    $('#total').val('')
    $('#f_despacho').val('')
}

function llenar_tabla_ticket(){
    var num_col = $("#tabla_ticket thead tr th").length; //obtenemos el numero de columnas de la tabla
	$("#tabla_ticket tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center><span class="spinner-border" role="status" aria-hidden="true"></span><b> Obteniendo información</b></center></td></tr>`);
   
    
    $.get("obtener-ticket/", function(data){
        console.log(data)
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
                                  
                                            <button type="button" class="btn btn-primary btn-xs" onclick="editarTicket(${data.id})">Editar</button>
                                                                                
                                            <a onclick="eliminarTicket(${data.id })" class="btn btn-danger btn-xs"> Eliminar </a>
                                       
                                    
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



function editarTicket(idticket){
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
        $('#titulo_form').html("Actualización Ticket")
        $('#nombre_btn_form').html('Actualizar')
        AccionForm="E"
    }
}

function visualizarListado(){
    $('#form_ing').hide(200)
    $('#listado_ticket').show(200)
    limpiarCampos()
}

function eliminarTicket(idticket){
    if(confirm('¿Quiere eliminar el registro?')){
        vistacargando("m","Espere por favor")
        $.get("eliminar-ticket/"+idticket, function(data){
            vistacargando("")
          
            if(data.error==true){
                alertNotificar(data.mensaje,"error");
                return;   
            }
    
            alertNotificar(data.mensaje,"success");
            llenar_tabla_ticket()
           
        }).fail(function(){
            vistacargando("")
            alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
        });
    }
   
}