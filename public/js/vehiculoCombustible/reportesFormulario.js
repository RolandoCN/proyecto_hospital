

$("#form_reporte").submit(function(e){
    e.preventDefault();
    
    //validamos los campos obligatorios
    let fecha_ini=$('#fecha_ini').val()
    let fecha_fin=$('#fecha_fin').val()
    let departamento=$('#departamento').val()
    let formulario=$('#formulario').val()

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

    if(formulario=="" || formulario==null){
        alertNotificar("Debe seleccionar el formulario","error")
        return
    } 

    if(departamento=="" || departamento==null){
        alertNotificar("Debe seleccionar el/los departamentos","error")
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
        url_form="/guardar-reportes"
    }else{
        tipo="PUT"
        url_form="/actualizar-rol/"+idRolEditar
    }
  
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
            limpiarCampos()
            alertNotificar(data.mensaje,"success");
            $('#form_ing').hide(200)
            $('#listado_formulario').show(200)
            llenar_tabla_reportes()
                            
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
    $('#formulario').val('').trigger('change.select2')
    // $('#departamento').val('').trigger('change.select2')
    $("#departamento").val([]).change();
    // $("#departamento").select2({
    //     placeholder: "Select a customer",
    //     initSelection: function(element, callback) {                   
    //     }
    // });
}
 
function llenar_tabla_reportes(){
    var num_col = $("#tabla_formulario thead tr th").length; //obtenemos el numero de columnas de la tabla
	$("#tabla_formulario tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center><span class="spinner-border" role="status" aria-hidden="true"></span><b> Obteniendo información</b></center></td></tr>`);
   
    
    $.get("/listado-reportes/", function(data){
        console.log(data)
      
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            $("#tabla_formulario tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
            return;   
        }
        if(data.error==false){
            
            if(data.resultado.length <= 0){
                $("#tabla_formulario tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
                alertNotificar("No se encontró datos","error");
                return;  
            }
         
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
                    { "width": "10%", "targets": 0 },
                    { "width": "20%", "targets": 1 },
                    { "width": "40%", "targets": 2 },
                    { "width": "20%", "targets": 3 },
                    { "width": "20%", "targets": 4 },
                   
                ],
                data: data.resultado,
                columns:[
                    {data: "id_reportes_formulario"},
                    {data: "formulario" },
                    {data: "descripcion"},
                    {data: "fecha_generacion"},
                    {data: "id_reportes_formulario"},
                    
                ],    
                "rowCallback": function( row, data, index ) {
                    $('td', row).eq(0).html(index+1)
                    $('td', row).eq(4).html(`
                                  
                                            <button type="button" class="btn btn-success btn-xs" onclick="reporteDescargar(${data.id_reportes_formulario })">Descargar</button>

                                       
                                    
                    `); 
                }             
            });
        }
    }).fail(function(){
        $("#tabla_formulario tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });


}

function reporteDescargar(id){
    window.location.href="/descargar-reporte-form/"+id
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

