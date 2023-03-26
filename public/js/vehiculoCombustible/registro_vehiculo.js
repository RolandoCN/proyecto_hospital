function mayus(e) {
    e.value = e.value.toUpperCase();
}

$("#form_registro_veh").submit(function(e){
    e.preventDefault();
    
    //validamos los campos obligatorios
    let codigo=$('#codigo').val()
    let placa=$('#placa').val()
    let descripcion=$('#descripcion').val()
    let marcacombo=$('#marcacombo').val()
    let tipousocombo=$('#tipousocombo').val()
    let fabricacion=$('#fabricacion').val()
    let cmb_tipocombustible=$('#cmb_tipocombustible').val()
    let cmb_tipomedicion=$('#cmb_tipomedicion').val()
    let departamento=$('#departamento').val()
    let chasis=$('#chasis').val()
    let modelo=$('#modelo').val()
    let estado_veh=$('#estado_veh').val()

    if(codigo=="" || codigo==null){
        alertNotificar("Ingrese el código institucional del vehículo","error")
        $('#codigo').focus()
        return
    } 

    
    if(placa=="" || placa==null){
        alertNotificar("Ingrese la placa del vehículo","error")
        $('#placa').focus()
        return
    } 

    
    if(descripcion=="" || descripcion==null){
        alertNotificar("Ingrese la descripción del vehículo","error")
        $('#descripcion').focus()
        return
    } 

    
    if(marcacombo=="" || marcacombo==null){
        alertNotificar("Seleccione la marca del vehículo","error")
        return
    } 

    if(modelo=="" || modelo==null){
        alertNotificar("Ingrese el modelo del vehículo","error")
        $('#modelo').focus()
        return
    } 

    
    // if(tipousocombo=="" || tipousocombo==null){
    //     alertNotificar("Seleccione el tipo de uso del vehículo","error")
    //     return
    // } 

    if(chasis=="" || chasis==null){
        alertNotificar("Ingrese el chasis del vehículo","error")
        $('#chasis').focus()
        return
    } 

    
    if(fabricacion=="" || fabricacion==null){
        alertNotificar("Ingrese el año de fabricación del vehículo","error")
        $('#fabricacion').focus()
        return
    } 

    
    if(cmb_tipocombustible=="" || cmb_tipocombustible==null){
        alertNotificar("Seleccione el tipo de combustible del vehículo","error")
        return
    } 

    if(cmb_tipomedicion=="" || cmb_tipomedicion==null){
        alertNotificar("Seleccione el tipo de medicion del vehículo","error")
        return
    } 

    if(estado_veh=="" || estado_veh==null){
        alertNotificar("Seleccione el estado del vehículo","error")
        return
    } 

    // if(departamento=="" || departamento==null){
    //     alertNotificar("Seleccione el departamento","error")
    //     return
    // } 
    
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
        url_form="guardar-vehiculo"
    }else{
        tipo="PUT"
        url_form="actualizar-vehiculo/"+IdVehicEditar
    }
  
    var FrmData=$("#form_registro_veh").serialize();
    $.ajax({
            
        type: tipo,
        url: url_form,
        method: tipo,             
		data: FrmData,      
		
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
            llenar_tabla_vehiculo()
                            
        }, error:function (data) {

            // vistacargando("");
            alertNotificar('Ocurrió un error','error');
        }
    });
})

function limpiarCampos(){
    $('#codigo').val('')
    $('#placa').val('')
    $('#estado_veh').val('')
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

function llenar_tabla_vehiculo(){
    var num_col = $("#tabla_vehiculo thead tr th").length; //obtenemos el numero de columnas de la tabla
	$("#tabla_vehiculo tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center><span class="spinner-border" role="status" aria-hidden="true"></span><b> Obteniendo información</b></center></td></tr>`);
   
    
    $.get("listado-vehiculo/", function(data){
      
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            $("#tabla_vehiculo tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
            return;   
        }
        if(data.error==false){
            
            if(data.resultado.length <= 0){
                $("#tabla_vehiculo tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
                alertNotificar("No se encontró datos","error");
                return;  
            }
         
            $('#tabla_vehiculo').DataTable({
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
                    { "width": "35%", "targets": 1 },
                    { "width": "15%", "targets": 2 },
                    { "width": "20%", "targets": 3 },
                    { "width": "10%", "targets": 4 },
                   
                   
                ],
                data: data.resultado,
                columns:[
                        {data: "codigo_institucion"},
                        {data: "descripcion" },
                        {data: "placa"},
                        // {data: "tipo_uso.detalle"},
                        {data: "estado_vehiculo"},
                        {data: "placa"},
                ],    
                "rowCallback": function( row, data ) {
                    $('td', row).eq(4).html(`
                                  
                                            <button type="button" class="btn btn-primary btn-xs" onclick="editarVehi(${data.id_vehiculo})">Editar</button>
                                                                                
                                            <a onclick="btn_eliminar_veh(${data.id_vehiculo})" class="btn btn-danger btn-xs"> Eliminar </a>
                                       
                                    
                    `); 
                }             
            });
        }
    }).fail(function(){
        $("#tabla_vehiculo tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });


}

$('.collapse-link').click();
$('.datatable_wrapper').children('.row').css('overflow','inherit !important');

$('.table-responsive').css({'padding-top':'12px','padding-bottom':'12px', 'border':'0', 'overflow-x':'inherit'});



function editarVehi(id_vehiculo){
    $.get("editar-vehiculo/"+id_vehiculo, function(data){
      
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            return;   
        }

        $('#codigo').val(data.resultado.codigo_institucion)
        $('#placa').val(data.resultado.placa)
        $('#descripcion').val(data.resultado.descripcion)
        $('#estado_veh').val(data.resultado.estado_vehiculo).trigger('change.select2')

        $('#marcacombo').val(data.resultado.id_marca).trigger('change.select2')
        $('#tipousocombo').val(data.resultado.id_tipouso).trigger('change.select2')

        $('#fabricacion').val(data.resultado.anio_fabricacion)

        $('#cmb_tipocombustible').val(data.resultado.id_tipocombustible).trigger('change.select2')
        $('#cmb_tipomedicion').val(data.resultado.id_tipomedicion).trigger('change.select2')

        $('#chasis').val(data.resultado.num_chasis)
        $('#modelo').val(data.resultado.modelo)
        $('#departamento').val(data.resultado.id_departamento).trigger('change.select2')

        visualizarForm('E')
        globalThis.IdVehicEditar=id_vehiculo



       
    }).fail(function(){
       
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });
}

function visualizarForm(tipo){
    $('#form_ing').show(200)
    $('#listado_veh').hide(200)
    globalThis.AccionForm="";
    if(tipo=='N'){
        $('#titulo_form').html("Registro Vehículo")
        $('#nombre_btn_form').html('Registrar')
        AccionForm="R"
    }else{
        $('#titulo_form').html("Actualización Vehículo")
        $('#nombre_btn_form').html('Actualizar')
        AccionForm="E"
    }
}

function visualizarListado(){
    $('#form_ing').hide(200)
    $('#listado_veh').show(200)
    limpiarCampos()
}

function btn_eliminar_veh(id_vehiculo){
    if(confirm('¿Quiere eliminar el registro?')){
        $.get("eliminar-vehiculo/"+id_vehiculo, function(data){
          
            if(data.error==true){
                alertNotificar(data.mensaje,"error");
                return;   
            }
    
            alertNotificar(data.mensaje,"success");
            llenar_tabla_vehiculo()
           
        }).fail(function(){
           
            alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
        });
    }
   
}