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

   
    <div style="margin-bottom:40px; margin-top:12px">

        <table class="ltable" style="" border="0" width="100%" style="padding-bottom:22px !important">

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
                @foreach($datos as $key=>$datodesp)
                    @foreach($datodesp as $e1=>$dato1)
                        @if($e1==0)
                            <tr style="font-size: 11px"  class="fuenteSubtitulo" style=""> 
                                <th colspan="9" style="border-color:white,height:35px;text-align: center;border:0 px" width="100%"  >FORMULARIO NRO. 007<br> INFORMACIÓN RESUMIDO DE COMBUSTIBLE  Y KILOMETRAJE<br>
                                INFORME DESDE {{date('d-m-Y',strtotime($desde))}} HASTA {{date('d-m-Y',strtotime($hasta))}} </th>
                            
                            </tr>
                            <tr style="line-height: 20px">
                                <td  colspan="11" width="5%" style=" text-transform: uppercase;font-size: 11px !important;border-left:0px;border-right: 0px;border-top: 0px; border-bottom:0px;border-color: white !important; text-align: center"  ><b>{{$dato1->vehiculo->departamento->descripcion}}</b></td>
                            </tr>
                        @endif
                    @endforeach
                                
                    <tr style="font-size: 10px !important; background-color: #D3D3D3;line-height:10px; "> 
                        <th  rowspan="1" width="15%" style="border-right: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: center"  ></th>
                    
                        <th rowspan="1" width="15%" style="border: 0px; ;border-color: #D3D3D3; text-align: center"></th>

                        <th rowspan="1"  width="10%" style="border-left: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: center;"></th> 

                        <th rowspan="1" width="10%" style="border-left: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: right;"></th> 

                        <th rowspan="1" width="10%" style="border-left: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: right;"></th> 

                        
                        <th rowspan="1" width="10%" style="border-left: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: right;"></th> 
                    
                        <th  rowspan="1" width="10%" style="border-left: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: right;"></th> 

                        <th colspan="2"width="10%" style="border-left: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: center;">RENDIMIENTO</th> 

                    </tr>
                    
                    
                    <tr style="font-size: 10px !important; background-color: #D3D3D3;line-height:10px; "> 
                        
                        <th rowspan="1" width="15%" style="border: 0px; ;border-color: #D3D3D3; text-align: center">VEHICULO</th>

                        <th rowspan="1"  width="15%" style="border-left: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: center;">TIPO</th> 

                        <th rowspan="1" width="10%" style="border-left: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: center;">COMBUSTIBLE</th> 

                        <th rowspan="1" width="10%" style="border-left: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: center;">N° FACTURA</th> 

                        <th  rowspan="1" width="10%" style="border: 0px; text-align: right">KILOMETROS</th>                         
                        
                        <th rowspan="1" width="10%" style="border-left: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: right;">GALONES</th> 
                      
                        <th  rowspan="1" width="10%" style="border-left: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: right;">VALOR</th> 

                        <th colspan="1"width="10%" style="border-left: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: center;">KM.GL</th> 

                        <th colspan="1"width="10%" style="border-left: 0px;border-top: 0px; border-bottom:0px;border-color: #D3D3D3; text-align: center;">GL.KM</th> 

                       
                    </tr>

                    <tbody>
                        @php
                            $total_final=0;
                            $total_galones=0;
                            $total_km_recorridos=0;
                        @endphp
                        @if(isset($datodesp))
                            @foreach($datodesp as $e=>$dato)
                                <tr style="font-size: 10px !important; line-height:28px"> 

                                    <td align="center" style="border-top: 0px; border-bottom: 0px;border-left:0px;border-right:0px;border-color: #D3D3D3">
                                        {{$dato->vehiculo->descripcion}} N.- {{$dato->vehiculo->codigo_institucion}}
                                    </td>

                                    <td align="center" style="border :0px;border-color: #D3D3D3;text-transform: uppercase;">
                                        {{$dato->vehiculo->descripcion}}
                                    </td>

                                    <td align="center" style="border :0px;border-color: #D3D3D3;text-transform: uppercase;">
                                        {{$dato->tipocombustible->detalle}}
                                    </td>

                                    <td align="center" style="border :0px;border-color: #D3D3D3;text-transform: uppercase;">
                                        {{$dato->num_factura_ticket}}
                                    </td>

                                    <td align="right" style="border-top: 0px; border-bottom: 0px;border-left:0px;border-right:0px;border-color: #D3D3D3">

                                        {{-- @php
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
                                           {{$km_recorrido}} 
                                        @else
                                            -----
                                        @endif --}}
                                       
                                        
                                       
                                    </td>

                                   
                                    <td align="right" style="border :0px;border-color: #D3D3D3">
                                        {{$dato->galones}}
                                    </td>
                                
                                    <td align="right" style="border-top: 0px;border-bottom: 0px;border-left:0px;border-right:0px;border-color: #D3D3D3">
                                        {{number_format(($dato->total),2,'.', '')}}
                                    </td>
                                   

                                </tr>
                                @php
                                    $km_recorrido=0;
                                    $total_final=number_format($total_final,2,'.','')+number_format($dato->total,2,'.','');
                                    $total_galones=number_format($total_galones,2,'.','')+number_format($dato->galones,2,'.','');
                                    $total_km_recorridos=$total_km_recorridos+$km_recorrido;

                                    $total_facturado=number_format($total_facturado,2,'.','')+number_format($dato->total,2,'.','');
                                    
                                @endphp

                            @endforeach		
                        @endif
                    </tbody>

                    <tfoot>
                        <tr style="font-size:10px !important;margin-bottom:12px; line-height:20px">
                            <td  colspan="4"style="border: 0px; border-color: #D3D3D3;  text-align: right;">
                                <b>TOTAL</b>
                            </td>
                            <td style="border: 0px;border-color: #D3D3D3;  text-align: right;">
                                @if($total_km_recorridos==0)
                                    -----
                                @else
                                    <b>{{$total_km_recorridos}}</b>
                                @endif 
                               
                            </td>
                            <td style="border: 0px;border-color: #D3D3D3;  text-align: right;">
                                <b> {{number_format(($total_galones),2,'.', '')}}</b>
                            </td>

                            <td style="border: 0px;border-color: #D3D3D3;  text-align: right;">
                                <b>$ {{number_format(($total_final),2,'.', '')}}</b>
                            </td>
                        </tr>

                        <tr style="font-size:10px !important;margin-bottom:22px;height:22px">
                            <td  colspan="9"style="border: 0px; border-color: #D3D3D3;  text-align: right;">
                               <span style="color:white">x</span>
                                @if($key != $ultima_key)
                                    <div style="page-break-after: always;"></div>
                                @endif
                            </td>
                        </tr>
                    </tfoot>

                    @if($key == $ultima_key)
                        <tfoot style="font-size: 11px !important;line-height:20px">    
                            <tr class="fuenteSubtitulo" style="margin-bottom:72px;">
                                <td colspan="9"align="center"style="border: 0px; "> <strong>TOTAL:  ${{number_format($total_facturado,2)}}</strong></td>
                                
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