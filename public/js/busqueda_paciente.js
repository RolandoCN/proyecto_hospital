//Busqueda del paciente por cedula o nombre
$('#cmb_paciente').select2({
    placeholder: 'Seleccione una opción',
    ajax: {
    url: '/buscarPaciente',
    dataType: 'json',
    delay: 250,
    processResults: function (data) {
        return {
        results:  $.map(data, function (item) {
                return {
                    text: item.cedula+" - "+item.nombres+" "+item.apellidos,
                    id: item.idpaciente
                }
            })
        };
    },
    cache: true
    }
});

function buscarPaciente(){
    let idPac=$('#cmb_paciente').val()
    if(idPac==""){ return }

    $('#pac_body').html('');
	$('#table_paciente').DataTable().destroy();
	$('#table_paciente tbody').empty(); 
    
    limpiarCampos()

    $.get('/info-paciente/'+idPac, function(data){
        if(data.error==true){
			$("#table_paciente tbody").html('');
			$("#table_paciente tbody").html(`<tr><td colspan="${num_col}">No existen registros</td></tr>`);
			alertNotificar(data.mensaje,"error");
			return;   
		}
		if(data.error==false){
			if(data.paciente.length==0){
				$("#table_paciente tbody").html('');
				$("#table_paciente tbody").html(`<tr><td colspan="${num_col}">No existen registros</td></tr>`);
				alertNotificar("No se encontró información","error");
				return;
			}
			
			$("#table_paciente tbody").html('');
			console.log(data)
           
			$.each(data.paciente,function(i, item){
                console.log(item)
				$('#table_paciente').append(`<tr>
											<td>${item.idpaciente}</td>
                                            <td>${item.cedula}</td>
											<td>${item.nombres} ${item.apellidos}</td>
											<td>${item.fecha_nacimiento}</td>
											<td>
												<center>
													<button type="button" class="btn btn-sm btn-info" onclick="actualizarPac('${item.idpaciente}')">Actualizar</button>  
												</center>
											</td>
										</tr>`);
			})
				
		  
			cargar_estilos_datatable('table_paciente');
		}
    })  

}

function cargar_estilos_datatable(idtabla){
	$("#"+idtabla).DataTable({
		'paging'      : true,
		'searching'   : true,
		'ordering'    : true,
		'info'        : true,
		'autoWidth'   : true,
		"destroy":true,
		pageLength: 10,
		sInfoFiltered:false,
		language: {
				url: '/json/datatables/spanish.json',
		},
	}); 
	$('.collapse-link').click();
	$('.datatable_wrapper').children('.row').css('overflow','inherit !important');

	$('.table-responsive').css({'padding-top':'12px','padding-bottom':'12px', 'border':'0', 'overflow-x':'inherit'});	
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
    }else if(dis=="No"){
        $('#tipo_disc').append(`<option value="Ninguna">Ninguna</option>`).change();
    }
    
   
   
    $("#tipo_disc").trigger("chosen:updated"); // actualizamos el combo 
}

function actualizarPac(idPac){
    $.get('/info-paciente/'+idPac, function(data){
        $('#data_form_actualizar').show(200)
        $('#content_consulta').hide(200)

        $('#cedula_pac').val(data.paciente[0].cedula)
        $('#nombres').val(data.paciente[0].nombres)
        $('#apellidos').val(data.paciente[0].apellidos)
        $('#genero').val(data.paciente[0].sexo).trigger('change.select2')
        $('#identidad_genero').val(data.paciente[0].identidad_genero).trigger('change.select2')
        $('#direccion_domiciliaria').val(data.paciente[0].direccion_domiciliaria)

        $('#provincia_res').val(data.paciente[0].idprovincia_reside).trigger('change.select2')
        globalThis.EditPacCanton=data.paciente[0].idcanton_reside
        globalThis.EditPacParroquia=data.paciente[0].id_parroquia_reside

        $('#provincia_nac').val(data.paciente[0].id_provincia_nacimiento).trigger('change.select2')

        $('#fecha_nac').val(data.paciente[0].fecha_nacimiento)
        $('#cedula_rep_afil').val(data.paciente[0].cedula_rep_afiliado)

        $('#name_rep_afil').val(data.paciente[0].nombre_rep_afiliado)
        $('#parentesco_rep_afil').val(data.paciente[0].parentesco_rep).trigger('change.select2')
        $('#parentesco').val(data.paciente[0].parentesco)
        $('#orientacion').val(data.paciente[0].orientacion).trigger('change.select2')
        $('#seguro1').val(data.paciente[0].seguro1).trigger('change.select2')

        $('#seguro2').val(data.paciente[0].seguro2).trigger('change.select2')
        $('#zona').val(data.paciente[0].zona).trigger('change.select2')
        $('#nombre_padre').val(data.paciente[0].nombre_padre)
        $('#nombre_madre').val(data.paciente[0].nombre_madre)

        $('#lugar_naci').val(data.paciente[0].lugar_nacimiento)
        $('#discapacidad').val(data.paciente[0].discapacidad).trigger('change.select2')
        $('#tipo_disc').val(data.paciente[0].tipo_discapacidad).trigger('change.select2')
        $('#porce_dis').val(data.paciente[0].porcentaje_disc)

        $('#estado_civil').val(data.paciente[0].estado_civil).trigger('change.select2')

        $('#nivel_inst').val(data.paciente[0].idnivel_instruccion).trigger('change.select2')

        $('#grado_cultural').val(data.paciente[0].idgrado_cultural).trigger('change.select2')
        $('#nacionalidad').val(data.paciente[0].nacionalidad)

        $('#ocupacion').val(data.paciente[0].ocupacion)
        $('#lugar_empleo').val(data.paciente[0].lugar_empleo)
        $('#llamar_emerg').val(data.paciente[0].llamar_en_emergencia)
        $('#telefono').val(data.paciente[0].telefono)

        $('#direccion').val(data.paciente[0].direccion)
        $('#email').val(data.paciente[0].correo_elec)

        globalThis.idPacEditar=data.paciente[0].idpaciente 
        calcularEdad()
        seleccDisc()
       
    })

}


function seleccProvResideEdit(){
    
    $('#canton_res').find('option').remove().end();
    var idprovincia=$('#provincia_res').val();

    $('#canton_res').html('');
    $('#canton_res').append(`<option class="cmb_arriendo" value=""></option>`);
    $("#canton_res").trigger("chosen:updated"); // actualizamos el combo 
    $.get('/obtener-canton-prov/'+idprovincia, function(data){
       
       
        $.each(data.canton_pr,function(i,item){
            if(EditPacCanton==item.idcanton){
                $('#canton_res').append(`<option class="cmb_arriendo" selected="selected" value="${item.idcanton}">${item.descripcion}</option>`).change();
            }else{
                $('#canton_res').append(`<option class="cmb_arriendo" value="${item.idcanton}">${item.descripcion}</option>`).change();
            }
           
        });
    })   
   
    $("#canton_res").trigger("chosen:updated"); // actualizamos el combo 
}

function seleccCantonResideEdit(){
    
    $('#parroquia_res').find('option').remove().end();
    var idcanton=$('#canton_res').val();

    if(idcanton==null || idcanton==""){
        return
    }

    $('#parroquia_res').html('');
    $('#parroquia_res').append(`<option class="cmb_arriendo" value=""></option>`);
    $("#parroquia_res").trigger("chosen:updated"); // actualizamos el combo 
    $.get('/obtener-parroquia-canton/'+idcanton, function(data){
      
        $.each(data.parroquia_canton,function(i,item){
            if(EditPacParroquia==item.idparroquia){
                $('#parroquia_res').append(`<option class="cmb_arriendo" selected="selected" value="${item.idparroquia}">${item.descripcion}</option>`).change();
            }else{
                $('#parroquia_res').append(`<option class="cmb_arriendo" value="${item.idparroquia}">${item.descripcion}</option>`).change();
            }
           
        });
    })   
   
    $("#parroquia_res").trigger("chosen:updated"); // actualizamos el combo 
}


$("#form_actualiza_pac").submit(function(e){
    e.preventDefault();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
       
    // var FrmData = new FormData(this);
    var FrmData=$("#form_actualiza_pac").serialize();
    console.log(FrmData)
    $.ajax({
     
        type: "PUT",
        url: '/actualiza-paciente/'+idPacEditar,
        method: 'PUT',             
		data: FrmData,               
		
        processData:false, 

        success: function(data){
            console.log(data)
            // vistacargando("");                
            if(data.error==true){
                alertNotificar(data.mensaje,'error');
                return;                      
            }
          
            alertNotificar(data.mensaje,"success");
            $('#data_form_actualizar').hide(200)
            $('#content_consulta').show(200)
            $('html,body').animate({scrollTop:$('#content_consulta').offset().top},400);
                            
        }, error:function (data) {
            // vistacargando("");
            alertNotificar('Ocurrió un error','error');
        }
    });
        
})

function cancelar(){
    $('#data_form_actualizar').hide(200)
    $('#content_consulta').show(200)

    $('html,body').animate({scrollTop:$('#content_consulta').offset().top},400);
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