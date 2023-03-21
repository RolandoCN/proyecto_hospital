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
                    style="height:35px;text-align: center;background-color: #D3D3D3;border-top: 0px;border-bottom: 0px;border-color:#D3D3D3"colspan="8"
                    width="100%">HOJA DE RUTA PARA LICENCIA DE SERVICIOS INSTITUCIONALES</th>

            </tr>
           
        </tbody>
    </table>
    <br>

    <table class="ltable" style="width:99%;table-layout: fixed;  ">
        <tbody style="font-size:11px">
            <tr style="border-color:blue">
                <th style="height:35px;text-align: left;border-color:black; "colspan="9"
                width="60%">Lugar Y  Fecha</th>
                <th style="height:35px;text-align: left;border-color:black"colspan="7"
                width="40%">Número de Movilización</th>
            </tr>

            <tr style="border-color:blue">
                <th style="height:35px;text-align: left;border-color:black; "colspan="9"
                width="60%">Entidad solicitante</th>
                <th style="height:35px;text-align: left;border-color:black"colspan="7" rowspan="2"
                width="40%">Objetivo de la comisión</th>
            </tr>

            <tr style="border-color:blue">
                <th style="height:35px;text-align: left;border-color:black; "colspan="9"
                width="60%">Funcionario</th>
              
            </tr>

            <tr style="border-color:blue">
                <th style="height:35px;text-align: left;border-color:black; "colspan="9"
                width="60%">Vehículo</th>
                <th style="height:35px;text-align: left;border-color:black"colspan="7" rowspan="2"
                width="40%">Nombre Conductor</th>
            </tr>

            <tr style="border-color:blue">
                <th style="height:35px;text-align: left;border-color:black; "colspan="3"
                width="60%">Número</th>

                <th style="height:35px;text-align: left;border-color:black; "colspan="3"
                width="60%">Marca</th>

                <th style="height:35px;text-align: left;border-color:black; "colspan="3"
                width="60%">Placa</th>
                
               
            </tr>

        </tbody>
       
    </table>
    <br>    
    <table class="ltable" style="width:99%;table-layout: fixed;  ">
        <thead style="font-size:11px">
            <tr style="border-color:blue">
                <th style="height:35px;text-align: left;border-color:black; "colspan="8"
                width="35%">Salida</th>
                <th style="height:35px;text-align: left;border-color:black"colspan="8"
                width="35%">Llegada</th>
                <th style="height:35px;text-align: left;border-color:black"colspan="7"
                width="30%">Provisión de Combustibles y Lubricantes</th>
            </tr>
        </thead>

        <tbody style="font-size:11px">
            <tr style="border-color:blue">
                <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                width="35%">Lugar</th>

                <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                width="35%">Fecha</th>

                <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                width="35%">Hora</th>

                <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                width="35%">Km</th>


                <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                width="35%">Lugar</th>

                <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                width="35%">Fecha</th>

                <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                width="35%">Hora</th>

                <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                width="35%">Km</th>

                
                <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                width="35%">Lugar</th>

                <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                width="35%">Fecha</th>

                <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                width="35%">Hora</th>

                <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                width="35%">Km</th>
            </tr>

            <tr>
                @foreach($datos as $e=>$item)
                    <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                    width="35%"></th>

                    <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                    width="35%"></th>

                    <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                    width="35%"></th>

                    <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                    width="35%"></th>


                    <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                    width="35%"></th>

                    <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                    width="35%"></th>

                    <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                    width="35%"></th>

                    <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                    width="35%"></th>

                    
                    <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                    width="35%"></th>

                    <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                    width="35%"></th>

                    <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                    width="35%"></th>

                    <th style="height:35px;text-align: left;border-color:black; "colspan="2"
                    width="35%"></th>
                @endforeach
            </tr>
        </tbody>

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
