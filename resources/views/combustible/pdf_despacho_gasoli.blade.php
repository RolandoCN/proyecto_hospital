<!DOCTYPE html>
<html>

<head>
    <title></title>

    <link rel="stylesheet" type="text/css" href="css/estilotablaUsoSuelo.css">
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

        footer {
            position: fixed;
            bottom: -10px;
            left: 0px;
            right: 0px;
            height: 50px;

            /** Extra personal styles **/
            background-color: #F5F0EF;
            color: black;
            text-align: center;
            line-height: 20px;
        }
        .ltable
        {
            border-collapse: collapse;
            /* font-family: sans-serif; */
        }

        .td_qr {
            border-bottom: 0px;
            border-left: 0px;
            border-top: 0px;
            border-right: 0px;
            background-color: #F5F0EF;
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
                    width="100%"> INFORMACIÓN DEL DESPACHO</th>

            </tr>
            <tr>
                <td style="height:40px;font-size: 12px;border-top: 0px;border-right: 0px;border-color: #D3D3D3"
                    colspan="4" class="pad"><b>Gasolinera:</b> {{ $datos->gasolinera->descripcion }}</td>

                <td style="height:40px;font-size: 12px;border-top: 0px;border-left: : 0px;border-color: #D3D3D3"colspan="4"
                    class="pad"><b>Fecha:</b> {{ $fecha }}</td>

            </tr>

        </tbody>
    </table>



    @if (count($detalle) > 0)
        <table class="ltable" style="width:99%;table-layout: fixed;padding-bottom:60px">
            <tbody class="fuenteSubtitulo">


                <tr style="font-size: 11px">

                    <td style="text-align:center; background-color: #D3D3D3;border-top: 0px;border-right: 0px;border-color: #D3D3D3; "
                        class="pad"  colspan="1"><b># Despacho </b> 
                    </td>

                    <td style="text-align:center;background-color: #D3D3D3;border-top: 0px;border-right: 0px;border-color: #D3D3D3"
                        colspan="2" class="pad"><b>Fecha-Hora</b>
                    </td>

                    <td style="text-align:center;background-color: #D3D3D3;border-top: 0px;border-right: 0px;border-left: 0px;border-color: #D3D3D3"colspan="3"
                        class="pad"><b>Conductor</b>
                    </td>

                    <td style="text-align:center;background-color: #D3D3D3;border-top: 0px;border-right: 0px;border-left: 0px;border-color: #D3D3D3"colspan="2"
                        class="pad"><b>Número Ticket</b>
                    </td>

                    <td style="text-align:center;background-color: #D3D3D3;border-top: 0px;border-right: 0px;border-left: 0px;border-color: #D3D3D3"
                        colspan="3" class="pad"><b>Vehículo</b>
                    </td>
                                      
                    <td style="text-align:#D3D3D3;background-color: #D3D3D3;border-top: 0px;border-right: 0px;border-left: 0px;border-color: #D3D3D3"
                        colspan="1" class="pad"><b>Combustible</b>
                    </td>

                    <td style="text-align:center;background-color: #D3D3D3;border-top: 0px;border-right: 0px;border-left: 0px;border-color: #D3D3D3"
                        colspan="1" class="pad"><b>Galones</b>
                    </td>

                    <td style="text-align:center;background-color: #D3D3D3;border-top: 0px;border-right: 0px;border-left: 0px;border-color: #D3D3D3"
                        colspan="1" class="pad"><b>P. Unitario</b>
                    </td>

                    <td style="text-align:center;background-color: #D3D3D3;border-top: 0px;border-left: 0px;border-color: #D3D3D3"
                        colspan="1" class="pad"><b>SubTotal</b>
                    </td>

                </tr>
                @php
                    $total_parcial1 = 0;
                    $tt = 0;
                @endphp
                @foreach ($detalle as $dato)
                    <tr style="font-size: 10px">
                        <td align="center" style="border-color: #D3D3D3; border:0px"colspan="1" class="pad "  colspan="1">
                            {{ $dato->iddetalle_despacho }}
                        </td>

                        <td  align="center" style="border-color: #D3D3D3" colspan="2" class="pad">
                            {{ $dato->ticket->f_despacho }}
                        </td>

                        <td style="border-color: #D3D3D3" colspan="3" class="pad">
                            {{ $dato->chofer->nombres }} {{ $dato->chofer->apellidos }}
                        </td>

                        <td align="center" style="border-color: #D3D3D3"colspan="2" class="pad">
                           {{ $dato->num_factura_ticket}}
                                
                        </td>

                        <td  align="center"style="border-color: #D3D3D3" colspan="3" class="pad">
                            {{ $dato->vehiculo['descripcion'] . ' ' .$dato->vehiculo['codigo_institucion'].' ['. $dato->vehiculo['placa'].']' }}
                        </td>
                        
               
                        <td style=";border-color: #D3D3D3" colspan="1" class="pad">
                            {{ $dato->tipocombustible['detalle'] }}</td>
                        <td style="text-align:right;border-color: #D3D3D3" colspan="1" class="pad">
                        {{ $dato->galones }}</td>
                        <td style="text-align:right;border-color: #D3D3D3" colspan="1" class="pad">
                            {{ $dato->precio_unitario }}</td>
                        <td style="text-align:right;border-color:#D3D3D3" colspan="1" class="pad">{{ $dato->total }}
                        </td>
                    </tr>
                @php
                    $total_parcial1 += $dato->total;
                    $tt = $total_parcial1;
                @endphp
                @endforeach
                <tr>
                    <td style="text-align:right;font-size: 12px;background-color: #D3D3D3;border-color: #D3D3D3" colspan="14"
                        class="pad"><b>Total</b></td>
                    <td style="text-align:right;font-size: 12px;background-color: #D3D3D3;border-color: #D3D3D3" colspan="1"
                        class="pad"><b>${{ number_format($tt, 2) }}</b></td>

                </tr>

            </tbody>
        </table>
    @endif



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
