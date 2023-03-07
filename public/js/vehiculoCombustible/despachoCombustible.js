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
                                                               
                                            <a onclick="btn_eliminar_tarea(${data.idcabecera_despacho })" class="btn btn-danger btn-xs"> Eliminar </a>
                                       
                                    
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

function btn_eliminar_tarea(idcabecera_despacho){
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