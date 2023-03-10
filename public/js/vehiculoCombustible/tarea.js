function mayus(e) {
    e.value = e.value.toUpperCase();
}

$("#form_registro_tarea").submit(function(e){
    e.preventDefault();
    
    //validamos los campos obligatorios
    let vehiculo=$('#vehiculo_tarea').val()
    let choferSalvo=$('#choferSalvo').val()
    let fecha_ini=$('#fecha_ini').val()
    let fecha_fin=$('#fecha_fin').val()
    let motivo=$('#motivo').val()
        
    
    if(vehiculo=="" || vehiculo==null){
        alertNotificar("Seleccione el vehículo","error")
        return
    } 

    if(choferSalvo=="" || choferSalvo==null){
        alertNotificar("Seleccione el chofer","error")
        return
    } 

    if(fecha_ini=="" || fecha_ini==null){
        alertNotificar("Seleccione la fecha de inicio","error")
        $('#fecha_ini').focus()
        return
    } 

    if(fecha_fin!=""){
        if(fecha_ini > fecha_fin){
            alertNotificar("La fecha de inicio debe ser menor a la fecha final","error")
            $('#fecha_ini').focus()
            return
        } 
    }
       

    if(motivo=="" || motivo==null){
        alertNotificar("Ingrese el motivo","error")
        $('#motivo').focus()
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
        url_form="/guardar-tarea"
    }else{
        tipo="PUT"
        url_form="/actualizar-tarea/"+IdTareaEditar
    }
  
    var FrmData=$("#form_registro_tarea").serialize();
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
   
    $('#vehiculo_tarea').val('').trigger('change.select2')
    $('#choferSalvo').val('').trigger('change.select2')
    $('#fecha_ini').val('')
    $('#fecha_fin').val('')
    $('#motivo').val('')
}

function llenar_tabla_tarea(){
    var num_col = $("#tabla_tarea thead tr th").length; //obtenemos el numero de columnas de la tabla
	$("#tabla_tarea tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center><span class="spinner-border" role="status" aria-hidden="true"></span><b> Obteniendo información</b></center></td></tr>`);
   
    
    $.get("/listado-tarea/", function(data){
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
                    { "width": "10%", "targets": 0 },
                    { "width": "30%", "targets": 1 },
                    { "width": "10%", "targets": 2 },
                    { "width": "25%", "targets": 3 },
                    { "width": "15%", "targets": 4 },
                   
                ],
                data: data.resultado,
                columns:[
                        {data: "vehiculo.placa"},
                        {data: "motivo" },
                        {data: "fecha_solicitud"},
                        {data: "estado"},
                        {data: "estado"},
                ],    
                "rowCallback": function( row, data ) {
                    $('td', row).eq(4).html(`
                                  
                                            <button type="button" class="btn btn-primary btn-xs" onclick="editarTarea(${data.id_tarea })">Editar</button>
                                                                                
                                            <a onclick="btn_eliminar_tarea(${data.id_tarea })" class="btn btn-danger btn-xs"> Eliminar </a>
                                       
                                    
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



function editarTarea(id_tarea){
    $.get("/editar-tarea/"+id_tarea, function(data){
        console.log(data)
      
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            return;   
        }
        if(data.resultado==null){
            alertNotificar("La tarea ya no se puede editar","error");
            return;   
        }
        //si no ingreso fecha de fin de la tarea manadamos limpio el input
        if(data.resultado.fecha_fin_ing=="N"){
            $('#fecha_fin').val('')
        }else{
            $('#fecha_fin').val(data.resultado.fecha_fin)
        }
      
        $('#vehiculo_tarea').val(data.resultado.id_vehiculo).trigger('change.select2')
        $('#choferSalvo').val(data.resultado.id_chofer).trigger('change.select2')
        $('#fecha_ini').val(data.resultado.fecha_inicio)
       
        $('#motivo').val(data.resultado.motivo)
       

        visualizarForm('E')
        globalThis.IdTareaEditar=id_tarea



       
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

function btn_eliminar_tarea(id_tarea){
    if(confirm('¿Quiere eliminar el registro?')){
        $.get("/eliminar-tarea/"+id_tarea, function(data){
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