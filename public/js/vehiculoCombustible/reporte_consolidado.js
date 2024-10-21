

function buscarDespachos(){
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

    
    vistacargando("m", "Espere por favor")
    $.get("listado-consolidado/"+desde+"/"+hasta, function(data){
        console.log(data)
        vistacargando("")
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            $("#tabla_consolidado tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
            visualizarListado()
            return;   
        }
        if(data.error==false){
            
            if(data.resultado.length <= 0){
               
                $("#tabla_consolidado tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
                alertNotificar("No se encontró datos","error");
                visualizarListado()
                return;  
            }
            
            $('#listado_turno').show()
            $('#content_consulta').hide(200)
            $('#tabla_consolidado').DataTable({
                "destroy":true,
                pageLength: 10,
                autoWidth : true,
                order: [[ 0, "desc" ]],
                sInfoFiltered:false,
                language: {
                    url: 'json/datatables/spanish.json',
                },
                columnDefs: [
                    { "width": "10%", "targets": 0 },
                    { "width": "10%", "targets": 1 },
                    { "width": "20%", "targets": 2 },
                    { "width": "25%", "targets": 3 },
                    { "width": "20%", "targets": 4 },
                    { "width": "15%", "targets": 5 },
                 
                    
                ],
                data: data.resultado,
                columns:[
                    {data: "fecha_cabecera_despacho"},
                    {data: "num_factura_ticket"},
                    {data: "vehiculo.descripcion" },
                    {data: "num_factura_ticket"},
                    {data: "chofer.apellidos"},
                    {data: "total"},
                 
                    
                ],    
                "rowCallback": function( row, data, index ) {
                    $('td', row).eq(2).html(data.vehiculo.descripcion+" "+data.vehiculo.codigo_institucion)
                    $('td', row).eq(3).html(data.chofer.apellidos+" "+data.chofer.nombres)
                    $('td', row).eq(4).html(data.movimiento.autoriza.nombres)
                  
                } 
            })   
        }
    }).fail(function(){
        vistacargando("")
        $("#tabla_consolidado tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
        visualizarListado()
    });         
         
        
    


}

//permite visualizarr el pdf de la emision en una modal
function visualizarOrden(nombre_pdf){
    var iframe=$('#iframePdf');
    iframe.attr("src", "visualizar-documento/"+nombre_pdf);   
    $("#vinculo").attr("href", 'descargar-doc-elim/'+nombre_pdf);
    $("#documentopdf").modal("show");
    $('#titulo').html('Orden');
}

//limpiamos los datos de la modal
$('#documentopdf').on('hidden.bs.modal', function (e) {            
    var iframe=$('#iframePdf');
    iframe.attr("src", null);

});

$('#descargar').click(function(){
    $('#documentopdf').modal("hide");
});

function generarReporteCons(){
    let ini=$('#fecha_ini').val()
    let fin=$('#fecha_fin').val()

    
    vistacargando("m","Espere por favor")
    $.get("genera-consolidado-pdf/"+ini+"/"+fin, function(data){
        vistacargando("")
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            return;   
        }

        alertNotificar("El documento se descargará en unos segundos...","success");
        window.location.href="descargar-doc-elim/"+data.pdf
       
    }).fail(function(){
        vistacargando("")
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });

    
}

$('.collapse-link').click();
$('.datatable_wrapper').children('.row').css('overflow','inherit !important');

$('.table-responsive').css({'padding-top':'12px','padding-bottom':'12px', 'border':'0', 'overflow-x':'inherit'});

function visualizarListado(){
    $('#content_consulta').show()
    $('#listado_turno').hide()
  
}


