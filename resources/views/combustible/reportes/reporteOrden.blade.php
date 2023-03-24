<!DOCTYPE html>
<html>

<head>
    <title></title>

    {{-- <link rel="stylesheet" type="text/css" href="css/estilotablaUsoSuelo.css"> --}}
    <style type="text/css">
        @page {
            margin-top: 8em;
            margin-left: 3em;
            margin-right: 3em;
            margin-bottom: 5em;
        }

        header {
            position: fixed;
            top: -100px;
            left: 0px;
            right: 0px;
            background-color: white;
            height: 60px;
            margin-right: 99px
        }

        td, th /* Asigna un borde a las etiquetas td Y th */
        {
            border: 1px solid black;
        }

        .td_qr {
            border-bottom: 0px;
            border-left: 0px;
            border-top: 0px;
            border-right: 0px;
            background-color: #F5F0EF;
        }
        .ltable
        {
            border-collapse: collapse;
            font-family: sans-serif;
        }
    </style>
    <style type="text/css">
        .preview_firma {
            width: 156px;
            border: solid 1px #000;
        }

        .img_firma {
            width: 80px;
        }

        .btn_azul {
            color: #fff;
            background-color: #337ab7;
            border-color: #2e6da4;
        }
    </style>


</head>

<body>

    <header>
        <table class="ltable " width="112.5%">

            <tr>
                <td height="50px" colspan="3" style="border: 0px;" align="left">
                    <img src="logo.jpg" width="300px" height="80px">
                </td>
                
            </tr>


        </table>

    </header>

    <br>
    <table class="ltable" style="width:99%;table-layout: fixed;  ">
        <tbody class="fuenteSubtitulo">
            <tr style="font-size: 13px" class="fuenteSubtitulo ">
                <th class="pad"
                    style="height:25px;text-align: center;background-color: #D3D3D3;border-top: 0px;border-bottom: 0px;border-color:#D3D3D3"colspan="8"
                    width="100%">ORDEN DE PEDIDO DE COMBUSTIBLE Nº {{$movimiento->codigo_orden}}</th>

            </tr>

            <tr style="font-size: 12px" class="fuenteSubtitulo ">
                <td class="pad"
                    style="height:20px;text-align: left;border-top: 0px;border-bottom: 0px;border-color:white"colspan="8"
                    width="100%"><b></b></td>

            </tr>

            <tr style="font-size: 12px" class="fuenteSubtitulo ">
                <td class="pad"
                    style="height:20px;text-align: right;border-top: 0px;border-bottom: 0px;border-color:white"colspan="8"
                    width="100%">Chone, {{$fecha}}</td>

            </tr>

            <tr style="font-size: 12px" class="fuenteSubtitulo ">
                <td class="pad"
                    style="height:20px;text-align: left;border-top: 0px;border-bottom: 0px;border-color:white"colspan="8"
                    width="100%"><b>Sirvase a entregar al SR: </b>{{$datos[0]->chofer->nombres}}
                    {{$datos[0]->chofer->apellidos}}
                </td>

              
            </tr>

            <tr style="font-size: 12px" class="fuenteSubtitulo ">
                <td class="pad"
                    style="height:20px;text-align: left;border-top: 0px;border-bottom: 0px;border-color:white"colspan="4"
                    width="100%"><b>Para el carro N°</b> {{$datos[0]->vehiculo->descripcion}}
                    {{$datos[0]->vehiculo->codigo_institucion}}
                </td>
                <td class="pad"
                    style="height:20px;text-align: left;border-top: 0px;border-bottom: 0px;border-color:white"colspan="4"
                    width="100%"><b>Marca: </b> {{$datos[0]->vehiculo->marca->detalle}}
                </td>


            </tr>

            <tr style="font-size: 12px" class="fuenteSubtitulo ">
                <td class="pad"
                    style="height:30px;text-align: center;border-top: 0px;border-bottom: 0px;border-color:white"colspan="4"
                    width="100%"><b>TIPO COMBUSTIBLE</b>
                </td>

                <td class="pad"
                    style="height:30px;text-align: center;border-top: 0px;border-bottom: 0px;border-color:white"colspan="4"
                    width="100%"><b>VALOR</b>
                </td>
               
            </tr>

            <tr style="font-size: 12px">   
                <td class="pad"
                    style="height:20px;text-align: center;border-top: 0px;border-bottom: 0px;border-color:white"colspan="4"
                    width="100%">{{$datos[0]->tipocombustible->detalle}}
                </td>

                <td class="pad"
                    style="height:20px;text-align: center;border-top: 0px;border-bottom: 0px;border-color:white"colspan="4"
                    width="100%">{{$datos[0]->total}}
                </td>
            </tr>

            <tr style="font-size: 12px">   
                <td class="pad"
                    style="height:20px;text-align: center;border-top: 0px;border-bottom: 0px;border-color:white"colspan="8"
                    width="100%"><b>MOTIVO DE LA SALIDA</b>
                </td>
             
            </tr>

            <tr style="font-size: 12px">   
                <td class="pad"
                    style="height:20px;text-align: left;border-top: 0px;border-bottom: 0px;border-color:white"colspan="8"
                    width="100%">
                    <li style="margin-left:12px">{{$movimiento->motivo}}</li>
                </td>
             
            </tr>

            <tr style="font-size: 12px">   
                <td class="pad"
                    style="height:20px;text-align: left;border-top: 0px;border-bottom: 0px;border-color:white"colspan="8"
                    width="100%"><b>Acompañante: </b>{{$movimiento->acompanante}}
                </td>
             
            </tr>

           
        </tbody>
    </table>

    <table width="100%">
        <tr style="font-size:11px">
            
            <td width="35%" style="text-align: center; border:0px">
                
            </td>
            <td width="30%" style="border:0px">
            
                <br><br><br><br><br>
                    
                <hr style="color:black !important">
                <p style="text-align: center">Responsable de Servicios Institucionales <br>
                    
                </p>
            </td>
            <td width="35%" style="border:0px">
               
            </td>
            
        </tr>
    </table>
    
    <br>

    <table class="ltable" style="width:99%;table-layout: fixed; margin-top:42px ">
        <tbody class="fuenteSubtitulo">
            <tr style="font-size: 13px" class="fuenteSubtitulo ">
                <th class="pad"
                    style="height:25px;text-align: center;background-color: #D3D3D3;border-top: 0px;border-bottom: 0px;border-color:#D3D3D3"colspan="8"
                    width="100%">ORDEN DE SALIDA DE VEHÍCULOS Nº {{$movimiento->codigo_orden}}</th>

            </tr>

            <tr style="font-size: 12px" class="fuenteSubtitulo ">
                <td class="pad"
                    style="height:20px;text-align: left;border-top: 0px;border-bottom: 0px;border-color:white"colspan="8"
                    width="100%"><b></b></td>

            </tr>

            <tr style="font-size: 12px" class="fuenteSubtitulo ">
                <td class="pad"
                    style="height:20px;text-align: LEFT;border-top: 0px;border-bottom: 0px;border-color:white"colspan="4"
                    width="100%"><b>Salida del Carro N°:  </b> {{$datos[0]->vehiculo->descripcion}}
                    {{$datos[0]->vehiculo->codigo_institucion}}</td>

                <td class="pad"
                style="height:20px;text-align: LEFT;border-top: 0px;border-bottom: 0px;border-color:white"colspan="4"
                width="100%"> <b> Marca: </b> {{$datos[0]->vehiculo->marca->detalle}}</td>

            </tr>

            <tr style="font-size: 12px" class="fuenteSubtitulo ">
                <td class="pad"
                    style="height:20px;text-align: left;border-top: 0px;border-bottom: 0px;border-color:white"colspan="4"
                    width="100%"><b>Para viajar a: </b>{{$movimiento->lugar_llegada_destino}}
                </td>

                <td class="pad"
                style="height:20px;text-align: left;border-top: 0px;border-bottom: 0px;border-color:white"colspan="4"
                width="100%"><b>Solicitado por:</b> {{$movimiento->area}}
               
            </td>

              
            </tr>
            <tr style="font-size: 12px">   
                <td class="pad"
                    style="height:20px;text-align: left;border-top: 0px;border-bottom: 0px;border-color:white"colspan="8"
                    width="100%">
                    
                </td>
             
            </tr>
              
            <tr style="font-size: 12px">   
                <td class="pad"
                    style="height:20px;text-align: center;border-top: 0px;border-bottom: 0px;border-color:white"colspan="8"
                    width="100%"><b>COMISIÓN</b>
                </td>
             
            </tr>

            <tr style="font-size: 12px">   
                <td class="pad"
                    style="height:20px;text-align: left;border-top: 0px;border-bottom: 0px;border-color:white"colspan="8"
                    width="100%">
                    <li style="margin-left:12px">{{$movimiento->motivo}}</li>
                </td>
             
            </tr>

            <tr style="font-size: 12px" class="fuenteSubtitulo ">
                <td class="pad"
                    style="height:20px;text-align: left;border-top: 0px;border-bottom: 0px;border-color:white"colspan="8"
                    width="100%"><b>Acompañante:</b> {{$movimiento->acompanante}}
                   
                </td>
               

            </tr>

            
            <tr style="font-size: 12px" class="fuenteSubtitulo ">
                <td class="pad"
                    style="height:20px;text-align: left;border-top: 0px;border-bottom: 0px;border-color:white"colspan="4"
                    width="100%"><b>Chofer: </b>{{$datos[0]->chofer->nombres}}
                    {{$datos[0]->chofer->apellidos}}
                </td>

                <td class="pad"
                style="height:20px;text-align: left;border-top: 0px;border-bottom: 0px;border-color:white"colspan="4"
                width="100%"><b>Fecha de la salida:</b> {{$movimiento->fecha_salida_patio}}
               
            </td>

            <tr style="font-size: 12px" class="fuenteSubtitulo ">
                <td class="pad"
                    style="height:20px;text-align: left;border-top: 0px;border-bottom: 0px;border-color:white"colspan="4"
                    width="100%"><b>Autorizado por: </b> {{ $movimiento->abreviacion_titulo }} {{ $movimiento->autorizador }}
                </td>

                <td class="pad"
                style="height:20px;text-align: left;border-top: 0px;border-bottom: 0px;border-color:white"colspan="4"
                width="100%"><b>Lugar y fecha:</b> Chone, {{$fecha}}
               
            </td>

            
        </tbody>
    </table>

    <table width="100%" style="margin-top:22px">
        <tr style="font-size:11px">
            <td width="10%" style="border:0px"></td>
            <td width="25%" style="text-align: center; border:0px">
                <br><br><br><br><br>
                <hr style="color:black !important">
                <p style="text-align: center">Autorizado por <br>
                    {{ $movimiento->abreviacion_titulo }} {{ $movimiento->autorizador }}
                </p>
            </td>
            <td width="30%" style="border:0px">
            
            
            </td>
            <td width="25%" style="border:0px">
                <br><br><br><br><br>
                    
                <hr style="color:black !important">
                <p style="text-align: center">Conductor <br>
                    {{ $movimiento->nombres }}  {{ $movimiento->apellidos }}
                </p>
            </td>
            <td width="10%" style="border:0px"></td>
        </tr>
    </table>


  



    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(370, 560, "Pág $PAGE_NUM de $PAGE_COUNT", $font, 7);
            ');
        }
      </script>
</body>

</html>
