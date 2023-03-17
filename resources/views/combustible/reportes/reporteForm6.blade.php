<!DOCTYPE html>
<html>
<head>
  <title></title>

     <style type="text/css">
        @page {
            margin-top: 8em;
            margin-left: 3em;
            margin-right:3em;
            margin-bottom: 5em;
        }
        header { position: fixed;  top: -100px; left: 0px; right: 0px; background-color: white; height: 60px; margin-right: 99px}
       
        footer{
            position: fixed; 
            bottom: -10px; 
            left: 0px; 
            right: 0px;
            height: 50px;           

            /** Extra personal styles **/
            background-color:#F5F0EF;
            color: black;
            text-align: center;
            line-height: 20px;
        }
         .td_qr{
            border-bottom: 0px;
            border-left: 0px;
            border-top: 0px;
            border-right:0px;
            background-color: #F5F0EF;
        }

        .ltable
        {
            border-collapse: collapse;
            font-family: sans-serif;
        }
        td, th /* Asigna un borde a las etiquetas td Y th */
        {
            border: 1px solid white;
        }

        .sinbordeencabezado /* Asigna un borde a las etiquetas td Y th */
        {
            border: 0px solid black;
        }
        .fuenteSubtitulo{
            font-size: 12px;
        }
        .pad{
            padding-left:5px;
            padding-right:5px;
        }

        
     </style>
      <style type="text/css">
        .preview_firma{
            width: 156px;
            border: solid 1px #000;
        }
        .img_firma{
            width: 80px;
        }
        .btn_azul{
            color: #fff;
            background-color: #337ab7;
            border-color: #2e6da4;
        }

    </style>

  
</head>

<body>

  <header>
    <table class="ltable " width="112.5%"  >                
            <tr>
                <td height="50px" colspan="3" style="border: 0px;" align="left" >
                    <img src="logo.jpg" width="300px" height="80px"> 
                </td>
                
            </tr>             
        </table>
  </header>

   
    <div style="margin-bottom:30px; margin-top:12px;">

        <table class="ltable" style="" border="0" width="100%" style="padding-bottom:2px !important">
            @php
                //Leo cuantos elementos hay en el array
                $cont = count($datos);
                //Consulto las keys del array
                $keys = array_keys($datos);
                //Busco la ultima
                $ultima_key = $keys[$cont-1];

                $total_facturado=0;
            @endphp

            @if(isset($datos))
                @foreach($datos as $key=> $datodesp)
                    @foreach($datodesp as $e1=>$dato1)
                        @if($e1==0)
                            <tr style="font-size: 11px"  class="fuenteSubtitulo " style=""> 
                                <th colspan="11" style="border-color:white;height:35px;text-align: center;border:0 px" width="100%"  >FORMULARIO NRO. 006<br> INFORMACIÓN GENERAL DE COMBUSTIBLE  Y KILOMETRAJE<br>
                                INFORME DESDE {{date('d-m-Y',strtotime($desde))}} HASTA {{date('d-m-Y',strtotime($hasta))}} </th>
                            
                            </tr>
                             
                            <tr style="line-height: 20px">
                                <td  colspan="11" width="5%" style=" text-transform: uppercase;font-size: 11px !important;border-left:0px;border-right: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: center"  ><b>{{$dato1->vehiculo->departamento->descripcion}}</b></td>
                            </tr>
                        @endif
                    @endforeach
                    <tr style="font-size: 9px !important; background-color: #D3D3D3;line-height:10px; "> 
                       
                        <th width="5%" style="border: 0px; ;border-color: #D3D3D3; text-align: center"></th>
                        <th width="10%" style="border: 0px; ;border-color: #D3D3D3; text-align: center"></th>
                        <th width="15%" style="border-right: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: center"></th>
                    
                        <th width="20%" style="border: 0px; text-align: center"></th>
                        <th width="5%" style="border: 0px; text-align: center"></th>
                        <th width="5%" style="border: 0px; text-align: right"></th>
                        <th width="5%" style="border: 0px; text-align: center"></th>
                        <th width="8%" style="border: 0px; text-align: center"></th>

                        <th width="7%" style="border: 0px; text-align: center   ">KM</th>
                    
                        <th width="10%" style="border-left: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: right;"></th> 
                        <th width="10%" style="border-left: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: right;"></th> 
                    </tr>
                    <tr style="font-size: 9px !important; background-color: #D3D3D3;line-height:10px; "> 
                       
                        <th width="5%" style="border: 0px; ;border-color: #D3D3D3; text-align: center">FECHA</th>
                        <th width="10%" style="border: 0px; ;border-color: #D3D3D3; text-align: center">VEHICULO</th>
                        <th width="15%" style="border-right: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: center">CHOFER</th>
                    
                        <th width="20%" style="border: 0px; text-align: center">TAREA</th>
                        <th width="5%" style="border: 0px; text-align: center">COMBUSTIBLE</th>
                        <th width="7%" style="border: 0px; text-align: right">N° FACTURA</th>
                        <th width="5%" style="border: 0px; text-align: center">SALIDA</th>
                        <th width="5%" style="border: 0px; text-align: center">RETORNO</th>
                        <th width="8%" style="border: 0px; text-align: center">RECORRIDOS</th>
                        <th width="10%" style="border-left: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: right;">GALONES</th> 
                        <th width="10%" style="border-left: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: right;">VALOR</th> 
                    </tr>
            
                    <tbody>
                        @php
                            $total_final=0;
                            $total_galones=0;
                            $total_km_recorridos=0;
                           
                        @endphp
                        @if(isset($datodesp))
                            @foreach($datodesp as $e=>$dato)
                                <tr style="font-size: 9px !important;">                                    
                                  
                                    <td align="center" style="border-top: 0px;border-left: 0px; border-bottom: 0px;border-center:0px;border-right:0px;border-color: #D3D3D3">
                                       {{$dato->fecha_cabecera_despacho}}
                                    </td>
                                    
                                    <td align="center" style="border-top: 0px; border-bottom: 0px;border-left:0px;border-right:0px;border-color: #D3D3D3">
                                        {{$dato->vehiculo->descripcion}} {{$dato->vehiculo->codigo_institucion}}
                                    </td>

                                    <td align="center" style="border-top: 0px;border-bottom: 0px;border-left:0px; border-right:0px;border-color: #D3D3D3">
                                        {{$dato->chofer->nombres}}
                                    </td>
                                    
                                    <td align="left" style="border-top: 0px;border-bottom: 0px;border-right:0px;border-left:0px;border-color: #D3D3D3">
                                                                  
                    
                                        @php
                                        $tareasVeh=\DB::table('vc_tarea')
                                            ->WhereDate('fecha_inicio','<=',$dato->fecha_cabecera_despacho)
                                            ->WhereDate('fecha_fin','>=',$dato->fecha_cabecera_despacho)
                                            ->where('estado','!=','Eliminada')
                                            ->where('id_vehiculo',$dato->id_vehiculo)
                                            ->get()
                                        @endphp
                                        <ul style="margin-left: 0px">
                                        
                                                @foreach ($tareasVeh as $tarea)
                                                    <li style="margin-left: 0px">{{ $tarea->motivo }}</li>
                                                @endforeach
                                        
                                        </ul>
                                    
                                    </td>

                                    <td align="center" style="border :0px;border-color: #D3D3D3;text-transform: uppercase;">
                                        {{$dato->tipocombustible->detalle}}
                                    </td>

                                    <td align="right" style="border :0px;border-color: #D3D3D3">
                                        {{$dato->num_factura_ticket}}
                                    </td>

                                    <td align="center" style="border-top: 0px; border-bottom: 0px;border-left:0px;border-right:0px;border-color: #D3D3D3">
                                        @php
                                            $cont_fin=0;
                                            $km_salida=0;
                                       
                                            $movimVeh=\DB::table('vc_movimiento')
                                            ->WhereDate('fecha_registro','=',$dato->fecha_cabecera_despacho)
                                            ->where('estado','!=','Eliminada')
                                            ->where('id_vehiculo',$dato->id_vehiculo)
                                            ->get()
                                        @endphp

                                        @foreach($movimVeh as $movi) 
                                        
                                            @if($movi->entrada_salida=="Salida")
                                                @if(!is_null($movi->kilometraje))
                                                    <li style="margin-left: 0px">
                                                        {{$movi->kilometraje }}
                                                        @php
                                                            $cont_fin=$cont_fin+1;
                                                            $km_salida=$km_salida+$movi->kilometraje;
                                                        @endphp
                                                    </li>
                                                @else
                                                    <li style="margin-left: 0px">
                                                        {{$movi->horometro }}
                                                        @php
                                                            $cont_fin=$cont_fin+1;
                                                            $km_salida=$km_salida+$movi->horometro;
                                                        @endphp
                                                    </li>
                                                @endif
                                            @endif
                                        @endforeach

                                      
                                    </td>

                                    <td align="center" style="border-top: 0px; border-bottom: 0px;border-left:0px;border-right:0px;border-color: #D3D3D3">
                                        
                                            @php
                                                $cont_ini=0;
                                                $km_entrada=0;

                                                $movimVeh=\DB::table('vc_movimiento')
                                                ->WhereDate('fecha_registro','=',$dato->fecha_cabecera_despacho)
                                                ->where('estado','!=','Eliminada')
                                                ->where('id_vehiculo',$dato->id_vehiculo)
                                                ->get()
                                            @endphp

                                            @foreach($movimVeh as $movi) 
                                            
                                                @if($movi->entrada_salida=="Entrada")
                                                    @if(!is_null($movi->kilometraje))
                                                        <li style="margin-left: 0px">
                                                            {{$movi->kilometraje }}
                                                            @php
                                                                $cont_ini=$cont_ini+1;
                                                                $km_entrada=$km_entrada+$movi->kilometraje;
                                                            @endphp
                                                        </li>
                                                    @else
                                                        <li style="margin-left: 0px">
                                                            {{$movi->horometro }}
                                                            @php
                                                                $cont_ini=$cont_ini+1;
                                                                $km_entrada=$km_entrada+$movi->horometro;
                                                            @endphp
                                                        </li>
                                                    @endif
                                                @endif
                                            @endforeach
                                      
                                    
                                    </td>
                                
                                    <td align="center" style="border-top: 0px; border-bottom: 0px;border-left:0px;border-right:0px;border-color: #D3D3D3">

                                        @php
                                            $km_recorrido=0;

                                            $ValorRecorrido=\DB::table('vc_movimiento')
                                            ->WhereDate('fecha_registro','=',$dato->fecha_cabecera_despacho)
                                            ->where('estado','!=','Eliminada')
                                            ->where('entrada_salida','=','Entrada')
                                            ->where('id_vehiculo',$dato->id_vehiculo)
                                            ->get()->last();

                                            if(!is_null($ValorRecorrido)){
                                                if(!is_null($ValorRecorrido->km_hm_recorrido)){
                                                    $km_recorrido=$ValorRecorrido->km_hm_recorrido;
                                                }
                                                    
                                            }else{
                                                $km_recorrido=0;
                                            }
                                        @endphp

                                        @if($km_recorrido>0)
                                            <p style="margin-left: 0px">{{$km_recorrido}}</p> 
                                        @else
                                            <p style="margin-left: 0px">-----</p> 
                                        @endif
                                           
                                       
                                    </td>

                                    <td align="right" style="border :0px;border-color: #D3D3D3">
                                        {{$dato->galones}}
                                    </td>

                                    <td align="right" style="border-top: 0px;border-bottom: 0px;border-left:0px;border-right:0px;border-color: #D3D3D3">
                                        {{number_format(($dato->total),2,'.', '')}}
                                    </td>

                                </tr>
                                @php
                                    $total_final=number_format($total_final,2,'.','')+number_format($dato->total,2,'.','');
                                    $total_galones=number_format($total_galones,2,'.','')+number_format($dato->galones,2,'.','');
                                    $total_km_recorridos=$total_km_recorridos+$km_recorrido;

                                    $total_facturado=number_format($total_facturado,2,'.','')+number_format($dato->total,2,'.','');
                                    
                                @endphp

                            @endforeach		
                        @endif
                    </tbody>

                    <tfoot >
                        <tr style="font-size:9px !important;line-height:5px" style="">
                            <td   colspan="8"style="font-size:9px;border: 0px; border-color: #D3D3D3;  text-align: right;">
                                <b>TOTAL</b>
                            </td>
                            <td style="border: 0px;border-color: #D3D3D3;  text-align: center; font-size:9px">
                                @if($total_km_recorridos==0)
                                    -----
                                @else
                                    <b>{{$total_km_recorridos}}</b>
                                @endif 
                               
                            </td>
                            <td style="border: 0px;border-color: #D3D3D3;  text-align: right; font-size:9px">
                                <b> {{number_format(($total_galones),2,'.', '')}}</b>
                            </td>

                            <td style="border: 0px;border-color: #D3D3D3;  text-align: right; font-size:9px">
                                <b>$ {{number_format(($total_final),2,'.', '')}}</b>
                            </td>
                        </tr>

                        <tr style="font-size:9px !important;margin-bottom:72px;height:22px">
                            <td  colspan="13"style="border: 0px; border-color: #D3D3D3;  text-align: right;">
                                <span style="color:white">x</span>
                                @if($key != $ultima_key)
                                    <div style="page-break-after: always;"></div>
                                @endif
                            </td>
                        </tr>
                    </tfoot>

                    @if($key == $ultima_key)
                        <tfoot style="font-size: 11px !important;line-height:20px">    
                            <tr class="text-right fuenteSubtitulo" style="margin-bottom:72px;">
                                <td colspan="11"align="center"style="border: 0px; "> <strong>TOTAL: ${{number_format($total_facturado,2)}}</strong></td>
                            </tr>
                        </tfoot>
                    @endif
                    
                
                @endforeach
            @endif

        </table>
      
       
    </div>

   
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