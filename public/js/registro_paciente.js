
function seleccProvReside(){
    
    $('#canton_res').find('option').remove().end();
    var idprovincia=$('#provincia_res').val();
    $('#canton_res').html('');
    $('#canton_res').append(`<option value=""></option>`);
    $("#canton_res").trigger("chosen:updated"); // actualizamos el combo 
    $.get('/obtener-canton-prov/'+idprovincia, function(data){
      
        $.each(data.canton_pr,function(i,item){
            $('#canton_res').append(`<option value="${item.idcanton}">${item.descripcion}</option>`).change();
        });
    })   
   
    $("#canton_res").trigger("chosen:updated"); // actualizamos el combo 
}

function seleccCantonReside(){
    
    $('#parroquia_res').find('option').remove().end();
    var idcanton=$('#canton_res').val();

    if(idcanton==null || idcanton==""){
        return
    }

    $('#parroquia_res').html('');
    $('#parroquia_res').append(`<option value=""></option>`);
    $("#parroquia_res").trigger("chosen:updated"); // actualizamos el combo 
    $.get('/obtener-parroquia-canton/'+idcanton, function(data){
      
        $.each(data.parroquia_canton,function(i,item){
            $('#parroquia_res').append(`<option value="${item.idparroquia}">${item.descripcion}</option>`).change();
        });
    })   
   
    $("#parroquia_res").trigger("chosen:updated"); // actualizamos el combo 
}

function limpiarCampos(){
    $('#cedula_pac').val('')
    $('#nombres').val('')
    $('#apellidos').val('')
    $('#genero').val('').trigger('change.select2')
    $('#identidad_genero').val('').trigger('change.select2')
    $('#direccion_domiciliaria').val('')
    $('#provincia_res').val('').trigger('change.select2')
    $('#provincia_nac').val('').trigger('change.select2')

    $('#canton_res').val('').trigger('change.select2')
    $('#parroquia_res').val('').trigger('change.select2')

    $('#fecha_nac').val('')
    $('#cedula_rep_afil').val('')

    $('#name_rep_afil').val('')
    $('#parentesco_rep_afil').val('').trigger('change.select2')
    $('#parentesco').val('')
    $('#seguro1').val('').trigger('change.select2')

    $('#seguro2').val('').trigger('change.select2')
    $('#zona').val('').trigger('change.select2')
    $('#nombre_padre').val('')
    $('#nombre_madre').val('')

    $('#lugar_naci').val('')
    $('#discapacidad').val('').trigger('change.select2')
    $('#tipo_disc').val('').trigger('change.select2')
    $('#porce_dis').val('')

    $('#estado_civil').val('').trigger('change.select2')

    $('#nivel_inst').val('').trigger('change.select2')

    $('#grado_cultural').val('').trigger('change.select2')
    $('#orientacion').val('').trigger('change.select2')
    $('#nacionalidad').val('')

    $('#ocupacion').val('')
    $('#lugar_empleo').val('')
    $('#llamar_emerg').val('')
    $('#telefono').val('')

    $('#direccion').val('')
    $('#email').val('')

    $('html,body').animate({scrollTop:$('#content_consulta').offset().top},400);
}


$("#form_registro").submit(function(e){
    e.preventDefault();
    // ocultarError('I')
    ocultarError1()
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  
    var FrmData=$("#form_registro").serialize();
    console.log(FrmData)
    $.ajax({
     
        type: "POST",
        url: '/guardar-generarf1',
        method: 'POST',             
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
            $('#data_form_actualizar').hide(200)
            $('#content_consulta').show(200)
                            
        }, error:function (data) {
            console.log(data)

            if(data.status==422){
              
                errore=data.responseJSON.errors

                if(errore.cedula){
                  
                    $('#error_cedula').show()
                    $('#txt_error_cedula').html(errore.cedula)
                  
                    
                }

                if(errore.nombres){
                    $('#error_nombre').show()
                    $('#txt_error_nombre').html(errore.nombres)
                   
                }
                if(errore.apellidos){
                    $('#error_apellidos').show()
                    $('#txt_error_apellidos').html(errore.apellidos)
                   
                }
                if(errore.sexo){
                    $('#error_sexo').show()
                    $('#txt_error_sexo').html(errore.sexo)
                   
                }
                if(errore.identidad_genero){
                    $('#error_ident_gen').show()
                    $('#txt_error_ident_gen').html(errore.identidad_genero)
                   
                }
                if(errore.direccion_domiciliaria){
                   
                    $('#error_direccion').show()
                    $('#txt_error_direccion').html(errore.direccion_domiciliaria)
                   
                }

                if(errore.idprovincia_reside){
                   
                    $('#error_prov_res').show()
                    $('#txt_error_prov_res').html(errore.idprovincia_reside)
                 
                }
                if(errore.idcanton_reside){
                    $('#error_canton_res').show()
                    $('#txt_error_canton_res').html(errore.idcanton_reside)
                   
                }
                if(errore.id_parroquia_reside){
                    $('#error_parroquia_res').show()
                    $('#txt_error_parroquia_res').html(errore.id_parroquia_reside)
                    
                }
                if(errore.id_provincia_nacimiento){
                    $('#error_provincia_nac').show()
                    $('#txt_error_provincia_nac').html(errore.id_provincia_nacimiento)
                   
                }
                if(errore.fecha_nacimiento){
                    $('#error_fecha_nac').show()
                    $('#txt_error_fecha_nac').html(errore.fecha_nacimiento)
                  
                }
                if(errore.cedula_rep_afiliado){
                    $('#error_cedula_rep_afil').show()
                    $('#txt_error_cedula_rep_afil').html(errore.cedula_rep_afiliado)
                   
                }

                if(errore.nombre_rep_afiliado){
                    $('#error_name_rep_afil').show()
                    $('#txt_error_name_rep_afil').html(errore.nombre_rep_afiliado)
                   
                }

                if(errore.parentesco_rep){
                    $('#error_parentesco_rep_afil').show()
                    $('#txt_error_parentesco_rep_afil').html(errore.parentesco_rep)
                  
                }
                if(errore.orientacion){
                    $('#error_orientacion').show()
                    $('#txt_error_orientacion').html(errore.orientacion)
                    
                }
                if(errore.seguro1){
                    $('#error_seguro1').show()
                    $('#txt_error_seguro1').html(errore.seguro1)
                   
                }
                if(errore.seguro2){
                    $('#error_seguro2').show()
                    $('#txt_error_seguro2').html(errore.seguro2)
                  
                }
                if(errore.zona){
                    $('#error_zona').show()
                    $('#txt_error_zona').html(errore.zona)
                   
                }

                if(errore.nombre_padre){
                    $('#error_nombre_padre').show()
                    $('#txt_error_nombre_padre').html(errore.nombre_padre)
                   
                }

                if(errore.nombre_madre){
                    $('#error_nombre_madre').show()
                    $('#txt_error_nombre_madre').html(errore.nombre_madre)
                   
                }

                if(errore.nombre_madre){
                    $('#error_nombre_madre').show()
                    $('#txt_error_nombre_madre').html(errore.nombre_madre)
                   
                }

                if(errore.lugar_nacimiento){
                    $('#error_lugar_naci').show()
                    $('#txt_error_lugar_naci').html(errore.lugar_nacimiento)
                   
                }

                // ocultarError('R')
                alertNotificar('Complete todos los campos obligatorios del formulario','error');
                $('html,body').animate({scrollTop:$('#content_consulta').offset().top},400);
                return
            }
            // vistacargando("");
            alertNotificar('Ocurrió un error','error');
        }
    });
})

function ocultarError1(){
    $('#error_cedula').hide()
    $('#error_nombre').hide()
    $('#error_apellidos').hide()
    $('#error_sexo').hide()
    $('#error_ident_gen').hide()
    $('#error_direccion').hide()
    $('#error_prov_res').hide()
    $('#error_canton_res').hide()
    $('#error_canton_res').hide()
    $('#error_parroquia_res').hide()
    $('#error_provincia_nac').hide()
    $('#error_fecha_nac').hide()
    $('#error_cedula_rep_afil').hide()
    $('#error_name_rep_afil').hide()
    $('#error_parentesco_rep_afil').hide()
    $('#error_orientacion').hide()
    $('#error_seguro1').hide()
    $('#error_seguro2').hide()
    $('#error_zona').hide()
    $('#error_nombre_padre').hide()
    $('#error_lugar_naci').hide()
}

function ocultarError(estado){
   
    let time=""
    if(estado=="R"){
        time="7000"
    }else{
        time="7000"
    }
    setTimeout(() => {
        $('#error_cedula').hide()
        $('#error_nombre').hide()
        $('#error_apellidos').hide()
        $('#error_sexo').hide()
        $('#error_ident_gen').hide()
        $('#error_direccion').hide()
        $('#error_prov_res').hide()
        $('#error_canton_res').hide()
        $('#error_canton_res').hide()
        $('#error_parroquia_res').hide()
        $('#error_provincia_nac').hide()
        $('#error_fecha_nac').hide()
        $('#error_cedula_rep_afil').hide()
        $('#error_name_rep_afil').hide()
        $('#error_parentesco_rep_afil').hide()
        $('#error_orientacion').hide()
        $('#error_seguro1').hide()
        $('#error_seguro2').hide()
        $('#error_zona').hide()
        $('#error_nombre_padre').hide()
        $('#error_nombre_madre').hide()
        

    }, time)
}
function guardarInfoGenPac(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // ocultarError('I')
    ocultarError1()
    var FrmData=$("#form_registro").serialize();
    console.log(FrmData)
    $.ajax({
     
        type: "POST",
        url: '/guardar-paciente',
        method: 'POST',             
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
            $('#data_form_actualizar').hide(200)
            $('#content_consulta').show(200)
           
                            
        }, error:function (data) {
            let errore=[]
            // ocultarError('I')
            // vistacargando("");
            console.log(data)
            if(data.status==422){
              
                errore=data.responseJSON.errors

                if(errore.cedula){
                  
                    $('#error_cedula').show()
                    $('#txt_error_cedula').html(errore.cedula)
                  
                    
                }

                if(errore.nombres){
                    $('#error_nombre').show()
                    $('#txt_error_nombre').html(errore.nombres)
                   
                }
                if(errore.apellidos){
                    $('#error_apellidos').show()
                    $('#txt_error_apellidos').html(errore.apellidos)
                   
                }
                if(errore.sexo){
                    $('#error_sexo').show()
                    $('#txt_error_sexo').html(errore.sexo)
                   
                }
                if(errore.identidad_genero){
                    $('#error_ident_gen').show()
                    $('#txt_error_ident_gen').html(errore.identidad_genero)
                   
                }
                if(errore.direccion_domiciliaria){
                   
                    $('#error_direccion').show()
                    $('#txt_error_direccion').html(errore.direccion_domiciliaria)
                   
                }

                if(errore.idprovincia_reside){
                   
                    $('#error_prov_res').show()
                    $('#txt_error_prov_res').html(errore.idprovincia_reside)
                 
                }
                if(errore.idcanton_reside){
                    $('#error_canton_res').show()
                    $('#txt_error_canton_res').html(errore.idcanton_reside)
                   
                }
                if(errore.id_parroquia_reside){
                    $('#error_parroquia_res').show()
                    $('#txt_error_parroquia_res').html(errore.id_parroquia_reside)
                    
                }
                if(errore.id_provincia_nacimiento){
                    $('#error_provincia_nac').show()
                    $('#txt_error_provincia_nac').html(errore.id_provincia_nacimiento)
                   
                }
                if(errore.fecha_nacimiento){
                    $('#error_fecha_nac').show()
                    $('#txt_error_fecha_nac').html(errore.fecha_nacimiento)
                  
                }
                if(errore.cedula_rep_afiliado){
                    $('#error_cedula_rep_afil').show()
                    $('#txt_error_cedula_rep_afil').html(errore.cedula_rep_afiliado)
                   
                }

                if(errore.nombre_rep_afiliado){
                    $('#error_name_rep_afil').show()
                    $('#txt_error_name_rep_afil').html(errore.nombre_rep_afiliado)
                   
                }

                if(errore.parentesco_rep){
                    $('#error_parentesco_rep_afil').show()
                    $('#txt_error_parentesco_rep_afil').html(errore.parentesco_rep)
                  
                }
                if(errore.orientacion){
                    $('#error_orientacion').show()
                    $('#txt_error_orientacion').html(errore.orientacion)
                    
                }
                if(errore.seguro1){
                    $('#error_seguro1').show()
                    $('#txt_error_seguro1').html(errore.seguro1)
                   
                }
                if(errore.seguro2){
                    $('#error_seguro2').show()
                    $('#txt_error_seguro2').html(errore.seguro2)
                  
                }
                if(errore.zona){
                    $('#error_zona').show()
                    $('#txt_error_zona').html(errore.zona)
                   
                }
                // ocultarError('R')
                alertNotificar('Complete todos los campos obligatorios del formulario','error');
                $('html,body').animate({scrollTop:$('#content_consulta').offset().top},400);
                return
            }
            
            alertNotificar('Ocurrió un error','error');
        }
    });
    
            
}

function cancelar(){
    $('#data_form_actualizar').hide(200)
    $('#content_consulta').show(200)

    $('html,body').animate({scrollTop:$('#content_consulta').offset().top},400);
}

function seleccDisc(){
    let dis=$('#discapacidad').val()
    $('#tipo_disc').html('');
    $("#tipo_disc").trigger("chosen:updated"); // actualizamos el combo 

    if(dis=="Si"){
        $('#tipo_disc').append(`<option value="Mental">Mental</option>`).change();
        $('#tipo_disc').append(`<option value="Auditiva">Auditiva</option>`).change();
        $('#tipo_disc').append(`<option value="Física">Física</option>`).change();
        $('#tipo_disc').append(`<option value="Visual">Visual</option>`).change();
        $('#tipo_disc').append(`<option value="Otro">Otro</option>`).change();
    }else{
        $('#tipo_disc').append(`<option value="Ninguna">Ninguna</option>`).change();
    }
    
   
   
    $("#tipo_disc").trigger("chosen:updated"); // actualizamos el combo 
}

function calcularEdad() {
            
    fecha = $('#fecha_nac').val();
    var hoy = new Date();
    var cumpleanos = new Date(fecha);
    var edad = hoy.getFullYear() - cumpleanos.getFullYear();
    var m = hoy.getMonth() - cumpleanos.getMonth();

    if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
        edad--;
    }
    $('#edad').val(edad);
}