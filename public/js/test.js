function validaForm(){
    let nombre=$('#nombre').val()
    let apellido=$('#apellidos').val()

    if(nombre=="" || nombre==null){
        alert("Ingrese un nombre")
        $('#nombre').focus()
        return false
    }

    if(apellido=="" || apellido==null){
        alert("Ingrese un apellido")
        $('#apellidos').focus()
        return false
    }


    return true
}