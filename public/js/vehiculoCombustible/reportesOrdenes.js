

$("#form_reporte").submit(function(e){
    e.preventDefault();
    
    //validamos los campos obligatorios
    let fecha_ini=$('#fecha_ini').val()
    let fecha_fin=$('#fecha_fin').val()
   
    if(fecha_ini=="" || fecha_ini==null){
        alertNotificar("Debe seleccionar la fecha de inicio","error")
        $('#fecha_ini').focus()
        return
    } 

    if(fecha_fin=="" || fecha_fin==null){
        alertNotificar("Debe seleccionar la fecha de inicio","error")
        $('#fecha_ini').focus()
        return
    } 

    if(fecha_ini>fecha_fin){
        alertNotificar("La fecha de inicio debe ser mayor a la fecha final","error")
        $('#fecha_ini').focus()
        return
    } 


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //comprobamos si es registro o edicion
    let tipo="POST"
    let url_form="/guardar-ordenes"
    
    var FrmData=$("#form_reporte").serialize();
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
            // limpiarCampos()
            alertNotificar(data.mensaje,"success");
            // $('#form_ing').hide(200)
            // $('#listado_formulario').show(200)
            llenar_tabla_reportes(data.data)
                            
        }, error:function (data) {
            console.log(data)

            // vistacargando("");
            alertNotificar('Ocurrió un error','error');
        }
    });
})

function limpiarCampos(){
    $('#fecha_ini').val('')
    $('#fecha_fin').val('')
    
}

function llenar_tabla_reportes(data){
    console.log(data)
    var num_col = $("#tabla_formulario thead tr th").length; //obtenemos el numero de columnas de la tabla
	$("#tabla_formulario tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center><span class="spinner-border" role="status" aria-hidden="true"></span><b> Obteniendo información</b></center></td></tr>`);

    let desde=$('#fecha_ini').val()
    let hasta=$('#fecha_fin').val()
    alert(desde)
   
    $('#tabla_formulario').DataTable({
        "destroy":true,
        pageLength: 10,
        autoWidth : true,
        order: [[ 3, "desc" ]],
        sInfoFiltered:false,
        language: {
            url: '/json/datatables/spanish.json',
        },
        columnDefs: [
            { "width": "5%", "targets": 0 },
            { "width": "10%", "targets": 1 },
            { "width": "10%", "targets": 2 },
            { "width": "20%", "targets": 3 },
            { "width": "45%", "targets": 4 },
            { "width": "10%", "targets": 5 },
            
        ],
        data: data,
        columns:[
            {data: "estado"},
            {data: "estado" },
            {data: "estado"},
            {data: "num_factura_ticket"},
            {data: "num_factura_ticket"},
            {data: "estado"},
            
        ],    
        "rowCallback": function( row, data, index ) {
            $('td', row).eq(0).html(index+1)
            $('td', row).eq(1).html(desde)
            $('td', row).eq(2).html(hasta)
            $('td', row).eq(5).html(`
                            
                                    <button type="button" class="btn btn-success btn-xs" onclick="reporteDescargar('${data.idcabecera_despacho}','${data.num_factura_ticket}')">Descargar</button>

                                
                            
            `); 
        } 
    })            
         
        
    


}

function reporteDescargar(id, nroTicket){
    
    window.location.href="genera-orden-pdf/"+id+"/"+nroTicket
}

$('.collapse-link').click();
$('.datatable_wrapper').children('.row').css('overflow','inherit !important');

$('.table-responsive').css({'padding-top':'12px','padding-bottom':'12px', 'border':'0', 'overflow-x':'inherit'});


function visualizarForm(tipo){
    $('#form_ing').show(200)
    $('#listado_formulario').hide(200)
    limpiarCampos()
    globalThis.AccionForm="";
    if(tipo=='N'){
        $('#titulo_form').html("Registro Perfil")
        $('#nombre_btn_form').html('Registrar')
        AccionForm="R"
    }else{
        $('#titulo_form').html("Actualizar Perfil")
        $('#nombre_btn_form').html('Actualizar')
        AccionForm="E"
    }
}

function visualizarListado(){
    $('#form_ing').hide(200)
    $('#listado_formulario').show(200)
  
}

