function mayus(e) {
    e.value = e.value.toUpperCase();
}

$("#form_registro_tarea").submit(function(e){
    e.preventDefault();
    
    //validamos los campos obligatorios
    let vehiculo=$('#vehiculo_tarea').val()
    let chofer=$('#chofer').val()
    let kilometraje=$('#kilometraje').val()
    let horometro=$('#horometro').val()
    let entrada_salida=$('#entrada_salida').val()
    let observacion=$('#observacion').val()
            
    
    if(vehiculo=="" || vehiculo==null){
        alertNotificar("Seleccione el vehículo","error")
        return
    } 

    if(chofer=="" || chofer==null){
        alertNotificar("Seleccione el chofer","error")
        return
    } 

    //comprobamos que el valor a ingresar d km o hm no sea menor al ultimo ingresado
    if(TipoMedi=="Kilometraje"){
        if(parseFloat(ValorMinimoKm_Hm)> parseFloat(kilometraje)){
            alertNotificar("El valor del kilometraje no puede ser menor a "+ValorMinimoKm_Hm,"error")
            $('#kilometraje').focus()
            return
        }

        if(kilometraje=="" || kilometraje==null || kilometraje<0){
            alertNotificar("Ingrese un valor de kilometraje válido","error")
            $('#kilometraje').focus()
            return
        } 

    }else{
        if(parseFloat(ValorMinimoKm_Hm)> parseFloat(horometro)){
            alertNotificar("El valor del Horometro no puede ser menor a "+ValorMinimoKm_Hm,"error")
            $('#horometro').focus()
            return
        }

        if(horometro=="" || horometro==null || horometro<0){
            alertNotificar("Ingrese un valor de kilometraje válido","error")
            $('#horometro').focus()
            return
        } 
    }
    
    if(entrada_salida=="" || entrada_salida==null){
        alertNotificar("Seleccione si es entrada o salida","error")
        $('#entrada_salida').focus()
        return
    } 


    if(observacion=="" || observacion==null){
        alertNotificar("Ingrese la observacion","error")
        $('#observacion').focus()
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
        url_form="/guardar-movimiento"
    }else{
        tipo="PUT"
        url_form="/actualizar-tarea/"+IdTareaEditar
    }

    var FrmData = new FormData(this);

    html2canvas([document.getElementById('sign-pad')], {
        onrendered: function (canvas) {
            var canvas_img_data = canvas.toDataURL('image/png');
            var img_data = canvas_img_data.replace(/^data:image\/(png|jpg);base64,/, "");
            if($( "p" ).hasClass("error")){
                alertNotificar("Debes firmar para poder aprobar la "+entrada_salida, "error")
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
                    limpiarSingArea()
                                    
                }, error:function (data) {

                    // vistacargando("");
                    alertNotificar('Ocurrió un error','error');
                }
            });
        }
    })
})

function limpiarCampos(){

    $('#kilometraje').val('')
    $('#horometro').val('')
    $('#entrada_salida').val('').trigger('change.select2')
    $('#vehiculo_tarea').val('').trigger('change.select2')
    $('#chofer').val('').trigger('change.select2')
   
    $('#observacion').val('')
    // limpiarSingArea()
    $("#table_dato_salida").hide();
    $('#tbody_dato_salida').html('');
    $('#tareasguard').val();

    $('#msmDetalledos').html('')
    $('#msmDetalledos').hide()
    
}

function llenar_tabla_tarea(){
    var num_col = $("#tabla_tarea thead tr th").length; //obtenemos el numero de columnas de la tabla
	$("#tabla_tarea tbody").html(`<tr><td colspan="${num_col}" style="padding:40px; 0px; font-size:20px;"><center><span class="spinner-border" role="status" aria-hidden="true"></span><b> Obteniendo información</b></center></td></tr>`);
   
    
    $.get("/listado-movimiento/", function(data){
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
                    url: 'json/datatables/spanish.json',
                },
                columnDefs: [
                    { "width": "25%", "targets": 0 },
                    { "width": "25%", "targets": 1 },
                    { "width": "10%", "targets": 2 },
                    { "width": "10%", "targets": 3 },
                    { "width": "20%", "targets": 4 },
                    { "width": "10%", "targets": 5 },
                   
                ],
                data: data.resultado,
                columns:[
                        {data: "vehiculo.placa"},
                        {data: "fecha_registro" },
                        {data: "fecha_registro"},
                        {data: "entrada_salida"},
                        {data: "entrada_salida"},
                        {data: "entrada_salida"},
                ],    
                "rowCallback": function( row, data ) {
                    $('td',row).eq(0).html(data.vehiculo.descripcion +" ["+data.vehiculo.placa+"]")
                    $('td',row).eq(1).html(data.chofer.nombres +" "+data.chofer.apellidos)
                    if(data.firmaconductor==null){
                        
                        $('td',row).eq(4).html('<span"> Sin Firmar &nbsp; &nbsp;&nbsp;</span>'); 
                    }
                    else{
                            // estad="Atendido"
                    $('td',row).eq(4).html(`<img src='data:image/png;base64,${data.firmaconductor}') class="img_firma">`);
                    } 

                    $('td', row).eq(5).html(`
                                  
                                            
                                            <a onclick="btn_eliminar_movimi(${data.idmovimiento })" class="btn btn-danger btn-xs"> Eliminar </a>
                                       
                                    
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



function visualizarForm(tipo){
    $('#form_ing').show(200)
    $('#listado_veh').hide(200)
    globalThis.AccionForm="";
    if(tipo=='N'){
        $('#titulo_form').html("Registro Movimiento")
        $('#nombre_btn_form').html('Registrar')
        AccionForm="R"
    }else{
        $('#titulo_form').html("Actualización Tarea")
        $('#nombre_btn_form').html('Actualizar')
        AccionForm="E"
    }
    $('#signArea').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:190});
    $('#signArea_edit').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:190});
}

function visualizarListado(){
    $('#form_ing').hide(200)
    $('#listado_veh').show(200)
    limpiarCampos()
    limpiarSingArea()
}

function btn_eliminar_movimi(idmovimiento){
    if(confirm('¿Quiere eliminar el registro?')){
        $.get("/eliminar-movimiento/"+idmovimiento, function(data){
          
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

function cargartarea(){

    let idveh=$('#vehiculo_tarea').val()
    if(idveh=="" || idveh==undefined){
        return
    }
    $('#tbody_dato_salida').html('');
    $('#tareasguard').val('');
    $.get("/carga-tarea/"+idveh, function (data) {
        if(data.error == true){
            alertNotificar(data.mensaje,"error");
        }
        else{
        
            if(data.resultado.length===0){
                $('#chofer').val('').trigger('change.select2')
                var tarea="no";
                // $('#tareasguard').val(''); 
              
                $("#table_dato_salida").show(700);

                
                $('#tbody_dato_salida').append(
                    `<tr>
                        <td colspan="2">
                            <center>Sin tareas que mostrar</center>
                           
                        </td>
                        
                    </tr>`);
                }
            else{
                var tarea="si";
                 

                //cargamos el chofer que se realizo la tarea
                $('#chofer').val(data.resultado[0].id_chofer).trigger('change.select2')
            } 
        
            $("#table_dato_salida").show(700);
            $.each(data.resultado, function(i,item){
                console.log(item.id_tarea);
            
                $('#tbody_dato_salida').append(
                    `<tr>
                        <td style="color:black" >
                            <input type='hidden' name='tareasguard[]' id='tareasguard' value='${item.id_tarea}'>
                            ${i+1}
                        </td>
                        <td style="color:black">${item.motivo}</td>
                        
                    </tr>`);

                // $('#tareasguard').val(item.id_tarea);
                
            
            }); 

            //km o hm
            globalThis.TipoMedi=""
            //ultimo km o hm
            globalThis.ValorMinimoKm_Hm=0;
            if(data.medicion.tipo_medicion.detalle=="Kilometraje"){
               
                $('#km_txt').show()
                $('#hm_txt').hide()
                $('#kilometraje').val('')
                $('#horometro').val('')
                TipoMedi="Kilometraje"
                var resp=0;
                if(data.ultimoKm_Hm!=null){

                    if(data.ultimoKm_Hm.kilometraje==null){
                        var resp=0;
                                
                    }else{
                        var resp=data.ultimoKm_Hm.kilometraje
                    } 
                }

            }else{
                
                $('#km_txt').hide()
                $('#hm_txt').show()
                $('#kilometraje').val('')
                $('#horometro').val('')
                TipoMedi="Horometro"
                var resp=0;
                if(data.ultimoKm_Hm!=null){
                    if(data.ultimoKm_Hm.horometro==null){
                        var resp=0;
                                
                    }else{
                        var resp=data.ultimoKm_Hm.horometro
                    } 
                }
                   
            }

           
          
            ValorMinimoKm_Hm=resp
            $('#msmDetalledos').html(`
                                        <div class="alert alert-info alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        El valor del último ${TipoMedi} fué ${resp}.
                                        </div>
                                    `);
            $('#msmDetalledos').show(200);
        }


    });


}

$(document).ready(function () {
    $('#signArea').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:190});
    $('#signArea_edit').signaturePad({drawOnly:true, drawBezierCurves:true, lineTop:190});
});
    

function limpiarSingArea(){
    $('#signArea').signaturePad().clearCanvas();
}
