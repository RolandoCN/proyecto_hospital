$("#form_gaso_comb").submit(function(e){
    e.preventDefault();
    
    //validamos los campos obligatorios
    let gasolinera=$('#gasolinera').val()
    let combustible=$('#combustible').val()
    let precio=$('#precio').val()
   
    if(gasolinera=="" || gasolinera==null){
        alertNotificar("Debe seleccionar la gasolinera","error")
        return
    } 

    if(combustible=="" || combustible==null){
        alertNotificar("Debe seleccionar el combustible","error")
        return
    } 

    if(precio=="" || precio==null){
        alertNotificar("Debe ingresar el precio","error")
        $('#precio').focus()
        return
    } 

    if(precio<=0){
        alertNotificar("El precio debe ser mayor a cero","error")
        $('#precio').focus()
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
        url_form="guardar-gasolinera-combustible"
    }else{
        tipo="PUT"
        url_form="actualizar-gasolinera-combustible/"+idGasCombusEditar
    }
  
    var FrmData=$("#form_gaso_comb").serialize();
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
            $('#listado_gaso_comb').show(200)
            llenar_tabla_gaso_comb()
                            
        }, error:function (data) {
            console.log(data)

            // vistacargando("");
            alertNotificar('Ocurrió un error','error');
        }
    });
})

function limpiarCampos(){
    $('#gasolinera').val('').trigger('change.select2')
    $('#combustible').val('').trigger('change.select2')
    $('#precio').val('')
}

function llenar_tabla_gaso_comb(){
    var num_col = $("#tabla_gaso_comb thead tr th").length; //obtenemos el numero de columnas de la tabla
	$("#tabla_gaso_comb tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center><span class="spinner-border" role="status" aria-hidden="true"></span><b> Obteniendo información</b></center></td></tr>`);
   
    
    $.get("listado-gasolinera-combustible/", function(data){
      
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            $("#tabla_gaso_comb tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
            return;   
        }
        if(data.error==false){
            
            if(data.resultado.length <= 0){
                $("#tabla_gaso_comb tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
                alertNotificar("No se encontró datos","error");
                return;  
            }
         
            $('#tabla_gaso_comb').DataTable({
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
                    { "width": "30%", "targets": 1 },
                    { "width": "20%", "targets": 2 },
                    { "width": "20%", "targets": 3 },
                    { "width": "20%", "targets": 4 },
                   
                ],
                data: data.resultado,
                columns:[
                        {data: "idgasolinera_comb"},
                        {data: "gasolinera.descripcion" },
                        {data: "combustible.detalle"},
                        {data: "precio_x_galon"},
                        {data: "idgasolinera_comb"},
                ],    
                "rowCallback": function( row, data, index ) {
                    $('td', row).eq(0).html(index+1)
                    $('td', row).eq(4).html(`
                                  
                                            <button type="button" class="btn btn-primary btn-xs" onclick="editarGasoliComb(${data.idgasolinera_comb  })">Editar</button>
                                                                                
                                            <a onclick="eliminarGasoliComb(${data.idgasolinera_comb  })" class="btn btn-danger btn-xs"> Eliminar </a>
                                       
                                    
                    `); 
                }             
            });
        }
    }).fail(function(){
        $("#tabla_gaso_comb tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });


}

$('.collapse-link').click();
$('.datatable_wrapper').children('.row').css('overflow','inherit !important');

$('.table-responsive').css({'padding-top':'12px','padding-bottom':'12px', 'border':'0', 'overflow-x':'inherit'});



function editarGasoliComb(idgasolinera_comb ){
    $.get("editar-gasolinera-combustible/"+idgasolinera_comb , function(data){
      
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            return;   
        }
        if(data.resultado==null){
            alertNotificar("La persona ya no se puede editar","error");
            return;   
        }


        $('#gasolinera').val(data.resultado.id_gasolinera).trigger('change.select2')
        $('#combustible').val(data.resultado.id_tipocombustible).trigger('change.select2')
        $('#precio').val(data.resultado.precio_x_galon).trigger('change.select2')
      
        visualizarForm('E')
        globalThis.idGasCombusEditar=idgasolinera_comb 

       
    }).fail(function(){
       
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });
}

function visualizarForm(tipo){
    $('#form_ing').show(200)
    $('#listado_gaso_comb').hide(200)
    globalThis.AccionForm="";
    if(tipo=='N'){
        $('#titulo_form').html("Registro Gestión Menú")
        $('#nombre_btn_form').html('Registrar')
        AccionForm="R"
    }else{
        $('#titulo_form').html("Actualizar Gestión Menú")
        $('#nombre_btn_form').html('Actualizar')
        AccionForm="E"
    }
}

function visualizarListado(){
    $('#form_ing').hide(200)
    $('#listado_gaso_comb').show(200)
    limpiarCampos()
}

function eliminarGasoliComb(idgasolinera_comb ){
    if(confirm('¿Quiere eliminar el registro?')){
        $.get("eliminar-gasolinera-combustible/"+idgasolinera_comb , function(data){
          
            if(data.error==true){
                alertNotificar(data.mensaje,"error");
                return;   
            }
    
            alertNotificar(data.mensaje,"success");
            llenar_tabla_gaso_comb()
           
        }).fail(function(){
           
            alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
        });
    }
   
}