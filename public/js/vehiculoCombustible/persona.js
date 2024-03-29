

$("#form_registro_persona").submit(function(e){
    e.preventDefault();
    
    //validamos los campos obligatorios
    let cedula=$('#cedula_persona').val()
    let nombres=$('#nombres').val()
    let apellidos=$('#apellidos').val()
    let telefono=$('#telefono').val()
        
    if(cedula=="" || cedula==null){
        alertNotificar("Debe ingresar la cédula","error")
        $('#cedula_persona').focus()
        return
    } 

    if(nombres=="" || nombres==null){
        alertNotificar("Ingrese los nombres","error")
        $('#nombres').focus()
        return
    } 

    if(apellidos=="" || apellidos==null){
        alertNotificar("Ingrese los apellidos","error")
        $('#apellidos').focus()
        return
    } 

    if(telefono=="" || telefono==null){
        alertNotificar("Ingrese el telefono","error")
        $('#telefono').focus()
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
        url_form="guardar-persona"
    }else{
        tipo="PUT"
        url_form="actualizar-persona/"+idPersonaEditar
    }
    vistacargando("m","Espere por favor")
    var FrmData=$("#form_registro_persona").serialize();
   
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
            $('#listado_persona').show(200)
            llenar_tabla_persona()
                            
        }, error:function (data) {
           
            vistacargando("");
            alertNotificar('Ocurrió un error','error');
        }
    });
})

function limpiarCampos(){
    $('#cedula_persona').val('')
    $('#nombres').val('')
    $('#apellidos').val('')
    $('#telefono').val('')
}

function llenar_tabla_persona(){
    var num_col = $("#tabla_persona thead tr th").length; //obtenemos el numero de columnas de la tabla
	$("#tabla_persona tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center><span class="spinner-border" role="status" aria-hidden="true"></span><b> Obteniendo información</b></center></td></tr>`);
   
    
    $.get("listado-persona/", function(data){
             
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            $("#tabla_persona tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
            return;   
        }
        if(data.error==false){
            
            if(data.resultado.length <= 0){
                $("#tabla_persona tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
                alertNotificar("No se encontró datos","error");
                return;  
            }
         
            $('#tabla_persona').DataTable({
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
                    { "width": "20%", "targets": 1 },
                    { "width": "20%", "targets": 2 },
                    { "width": "10%", "targets": 3 },
                    { "width": "15%", "targets": 4 },
                    { "width": "15%", "targets": 5 },
                   
                ],
                data: data.resultado,
                columns:[
                        {data: "cedula"},
                        {data: "nombres" },
                        {data: "apellidos"},
                        {data: "telefono"},
                        {data: "telefono"},
                        {data: "telefono"},
                ],    
                "rowCallback": function( row, data ) {

                    if(data.firma_persona==null){
                        
                        $('td',row).eq(4).html('<span">  &nbsp; &nbsp;&nbsp;</span>'); 
                    }
                    else{
                        $('td',row).eq(4).html(`<img src='data:image/png;base64,${data.firma_persona}') class="img_firma">`);
                    } 

                    $('td', row).eq(5).html(`

                                            <button type="button" class="btn btn-success btn-xs" onclick="firmaPersona('${data.idpersona}','${data.cedula}','${data.nombres}','${data.apellidos}')">Firmar</button>
                                            
                                            <button type="button" class="btn btn-primary btn-xs" onclick="editarPersona(${data.idpersona })">Editar</button>
                                                                                
                                            <a onclick="btn_eliminar_tarea(${data.idpersona })" class="btn btn-danger btn-xs"> Eliminar </a>
                                       
                                    
                    `); 
                }             
            });
        }
    }).fail(function(){
        $("#tabla_persona tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center>No se encontraron datos</center></td></tr>`);
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });


}

$('.collapse-link').click();
$('.datatable_wrapper').children('.row').css('overflow','inherit !important');

$('.table-responsive').css({'padding-top':'12px','padding-bottom':'12px', 'border':'0', 'overflow-x':'inherit'});

function firmaPersona(id, cedula, nombres, apellidos){
  
    $('#cedula_modal').html(cedula)
    $('#persona_modal').html(nombres +" "+apellidos)
    $('#idPersonaFirma').val(id)
    $('#FirmaPersona').modal('show')

    $('#signArea').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:190});
    $('#signArea_edit').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:190});
    limpiarSingArea()
}

function limpiarSingArea(){
    $('#signArea').signaturePad().clearCanvas();
}

$("#firma_persona").submit(function(e){
    e.preventDefault();
        
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //comprobamos si es registro o edicion
    let tipo="POST"
    let url_form="guardar-firma-persona"
 
    var FrmData = new FormData(this);
    vistacargando("m", "Espere por favor")
    
    html2canvas([document.getElementById('sign-pad')], {
        onrendered: function (canvas) {
            var canvas_img_data = canvas.toDataURL('image/png');
            var img_data = canvas_img_data.replace(/^data:image\/(png|jpg);base64,/, "");
            if($( "p" ).hasClass("error")){
                alertNotificar("Debes firmar para poder registrar", "error")
                return
            }

            FrmData.append("b64_firma",img_data);

            $.ajax({
                    
                type: tipo,
                url: url_form,
                method: tipo,             
                data: FrmData,
                dataType: 'json',
                contentType:false,
                cache:false,
                processData:false,
            

                success: function(data){
                    vistacargando("");                
                    if(data.error==true){
                        alertNotificar(data.mensaje,'error');
                        return;                      
                    }
                  
                    alertNotificar(data.mensaje,"success");
                    $('#cerra_modal').click()
                    limpiarSingArea()
                    llenar_tabla_persona()
                                    
                }, error:function (data) {

                    vistacargando("");
                    alertNotificar('Ocurrió un error','error');
                }
            });
        }
    })
})



function editarPersona(idpersona){
    vistacargando("m","Espere por favor")
    $.get("editar-persona/"+idpersona, function(data){
        vistacargando("")
      
        if(data.error==true){
            alertNotificar(data.mensaje,"error");
            return;   
        }
        if(data.resultado==null){
            alertNotificar("La persona ya no se puede editar","error");
            return;   
        }


        $('#cedula_persona').val(data.resultado.cedula)
        $('#nombres').val(data.resultado.nombres)
        $('#apellidos').val(data.resultado.apellidos)
        $('#telefono').val(data.resultado.telefono)
       

        visualizarForm('E')
        globalThis.idPersonaEditar=idpersona



       
    }).fail(function(){
        vistacargando("")
        alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
    });
}

function visualizarForm(tipo){
    $('#form_ing').show(200)
    $('#listado_persona').hide(200)
    globalThis.AccionForm="";
    if(tipo=='N'){
        $('#titulo_form').html("Registro Persona")
        $('#nombre_btn_form').html('Registrar')
        AccionForm="R"
    }else{
        $('#titulo_form').html("Actualización Persona")
        $('#nombre_btn_form').html('Actualizar')
        AccionForm="E"
    }
}

function visualizarListado(){
    $('#form_ing').hide(200)
    $('#listado_persona').show(200)
    limpiarCampos()
}

function btn_eliminar_tarea(idpersona){
    if(confirm('¿Quiere eliminar el registro?')){
        vistacargando("m","Espere por favor")
        $.get("eliminar-persona/"+idpersona, function(data){
            vistacargando("")
          
            if(data.error==true){
                alertNotificar(data.mensaje,"error");
                return;   
            }
    
            alertNotificar(data.mensaje,"success");
            llenar_tabla_persona()
           
        }).fail(function(){
            vistacargando("")
            alertNotificar("Se produjo un error, por favor intentelo más tarde","error");  
        });
    }
   
}