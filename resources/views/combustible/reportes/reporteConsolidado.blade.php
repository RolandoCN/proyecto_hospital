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
       
       

        .ltable
        {
            border-collapse: collapse;
            font-family: sans-serif;
        }
        td, th 
        {
            border: 1px solid white;
        }

        .sinbordeencabezado 
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
          
            <tr style="font-size: 11px"  class="fuenteSubtitulo " style=""> 
                <th colspan="11" style="border-color:white;height:35px;text-align: center;border:0 px" width="100%"  >REGISTRO CONSOLIDADO DE MOVILIZACIONES Y COMBUSTIBLE DE LOS VEHÍCULOS<br>
                DESDE {{date('d-m-Y',strtotime($desde))}} HASTA  {{date('d-m-Y',strtotime($hasta))}}<br><br>
              
                </th>
            
            </tr>

          
        </table>

        <table class="ltable" style="width:99%;table-layout: fixed;  " width="100%">
            <tbody style="font-size:11px">
                <tr style="border-color:blue">
                    
                    <td style="height:25px;text-align: center;border-color:black; " rowspan="2"colspan="1"
                    width="45%"><b>Fecha </b>  </td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="2" colspan="1"
                    width="40%"><b>Orden</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="3"
                    width="60%"><b>Vehículo</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="2"
                    width="60%"><b>Km</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="3"
                    width="60%"><b>Valor Combustible</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="4"
                    width="60%"><b>Detalle Movilización</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="2" colspan="1"
                    width="35%"><b>Total</b></td>

                  
                </tr>
                    
                <tr style="border-color:blue">
                    
                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="20%"><b>N°</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="50%"><b>Marca</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="50%"><b>Tipo</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="35%"><b>Salida</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="35%"><b>LLegada</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="30%"><b>Eco</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="30%"><b>Super</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="30%"><b>Diesel</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="60%"><b>Área</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="60%"><b>Destino</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="60%"><b>Chofer</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="60%"><b>Autorizado por</b></td>
                </tr>
                @php
                    $total_final=0;
                @endphp
                @foreach($datos as $item)
                    <tr style="border-color:blue">
                    
                        <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                        width="30%">
                           {{date('d-m-Y',strtotime($item->ticket->f_despacho))}}
                        </td>

                        <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                        width="20%">
                            {{$item->movimiento->codigo_orden}}
                        </td>

                        <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                        width="40%">
                            {{$item->vehiculo->codigo_institucion}}
                        </td>

                        <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                        width="50%">
                            {{$item->vehiculo->marca->detalle}}
                        </td>

                        <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                        width="50%">
                            {{$item->vehiculo->descripcion}}
                        </td>

                        <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                        width="35%">
                            {{$item->movimiento->km_salida_patio}}
                        </td>

                        <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                        width="35%">
                            {{$item->movimiento->km_llegada_patio}}
                        </td>

                        <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                        width="30%">
                            @if($item->tipocombustible->detalle=="Eco")
                                {{$item->total}}
                            @endif
                        </td>

                        <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                        width="30%">    
                            @if($item->tipocombustible->detalle=="Super")
                                {{$item->total}}
                            @endif
                        </td>

                        <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                        width="30%">
                            @if($item->tipocombustible->detalle=="Diesel")
                                {{$item->total}}
                            @endif
                        </td>

                        <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                        width="60%">
                            {{$item->movimiento->area->descripcion}}
                        </td>

                        <td style="height:25px;text-align: left;border-color:black; " rowspan="1" colspan="1"
                        width="60%">
                            {{$item->movimiento->lugar_llegada_destino}}
                        </td>
                        
                        <td style="height:25px;text-align: left;border-color:black; " rowspan="1" colspan="1"
                        width="60%">
                            {{$item->chofer->nombres}} {{$item->chofer->apellidos}}
                        </td>

                        <td style="height:25px;text-align: left;border-color:black; " rowspan="1" colspan="1"
                        width="60%">
                            {{$item->movimiento->autoriza->nombres}}
                        </td>

                        <td style="height:25px;text-align: right;border-color:black; " rowspan="1" colspan="1"
                        width="35%">
                            {{$item->total}}
                        </td>
                  

                    </tr>
                    @php
                        $total_final=$total_final + $item->total;
                    @endphp
                @endforeach
               
    
            </tbody>
            <tfoot style="font-size:12px">
                <tr >

                    <td style="height:25px;text-align: left;border-color:black;  border-left:0px;border-bottom:0px;border-right:0px" rowspan="1" colspan="13">
                       
                    </td>
                    <td style="height:25px;text-align: center;border-color:black;  border-left:0px;border-bottom:0px; border-right:0px " rowspan="1" colspan="1">
                        <b>TOTAL</b>
                    </td>

                    <td style="height:25px;text-align: right;border-color:black;border-left:0px;border-bottom:0px; border-right:0px  " rowspan="1" colspan="1">
                        $ {{number_format(($total_final),2,'.', '')}}   
                    </td>
                </tr>
            </tfoot>
           
        </table>

        <table class="ltable" style="width:99%;table-layout: fixed; margin-top:32px  " width="100%">
            <tbody style="font-size:11px">

                <tr style="border-color:blue">
                    
                   

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="3"
                    width="45%"><b>Vehiculo</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="3"
                    width="45%"><b>Tipo Combustible</b></td>

                    
                    <td style="height:25px;text-align: center;border-color:black; " rowspan="2" colspan="1"
                    width="10%"><b>Total</b>
                    </td>

                   
                </tr>
              
                <tr style="border-color:blue">
                    
                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="5%"><b>N°</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="20%"><b>Marca</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="20%"><b>Tipo</b></td>


                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="15%"><b>Eco</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="15%"><b>Super</b></td>

                    <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1"
                    width="15%"><b>Diesel</b></td>

                   
                </tr>
                @php
                    $total_veh=0;
                    $comb="";
                    $id_veh=0;
                    $total_final_veh=0;
                    $total_pagado=0;
                @endphp
                @foreach ($data_vehiculo as $veh)

                    @php
                      
                        $desde = date('Y-m-d 00:00:00', strtotime($desde));
                        $hasta = date('Y-m-d 23:59:59', strtotime($hasta));
                        
                    
                        $despacho_veh=\DB::table('vc_ticket as t')
                        ->leftJoin('vc_tipocombustible as c', 'c.id_tipocombustible', 't.id_tipocombustible')
                        ->leftJoin('vc_detalle_despacho as m', 'm.num_factura_ticket', 't.numero_ticket')
                        ->where('t.estado','A')
                        ->where('m.estado','Aprobado')
                        ->whereBetween('f_despacho', [$desde, $hasta])
                        ->where('t.id_vehiculo',$veh->id_vehiculo)
                        ->select(DB::raw('t.id_vehiculo, sum(t.total) as total, c.detalle as combustible'))
                        ->groupBy('id_vehiculo', 'combustible')                        
                        ->get(); 

                        $super=0;
                        $diesel=0;
                        $eco=0;
                        $total_comb=0;
                        $total_veh=0;
                        $total_veh_diesel=0;
                        $total_veh_diesel=0;
                        $total_veh_super=0;
                        $total_veh_eco=0;
                        foreach($despacho_veh as $desp){
                            $tipo=$desp->combustible;
                            switch ($tipo) { 
                            case 'Diesel': 
                                $total_veh_diesel=$desp->total;
                                $total_veh_diesel=number_format(($total_veh_diesel),2,'.', '');
                                break; 
                            case 'Super':     
                                $total_veh_super=$desp->total;
                                $total_veh_super=number_format(($total_veh_super),2,'.', '');
                                break;
                            
                            case 'Eco':     
                                $total_veh_eco=$desp->total;
                                $total_veh_eco=number_format(($total_veh_eco),2,'.', '');
                                break;
                            }

                            $id_veh=$desp->id_vehiculo;
                            $comb=$desp->combustible;
                            
                        }

                       
                    @endphp
                    <tr style="border-color:blue">
                        
                        <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1" width="5%">
                            {{$veh->codigo_institucion}}
                        </td>

                        <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1" width="20%">
                            {{$veh->marca}}
                        </td>

                        <td style="height:25px;text-align: center;border-color:black; " rowspan="1" colspan="1" width="20%">
                            {{$veh->tipo}}
                        </td>


                        <td style="height:25px;text-align: right;border-color:black; " rowspan="1" colspan="1" width="15%">
                           
                            @if($veh->id_vehiculo == $id_veh )
                                {{ $total_veh_eco > 0 ? number_format($total_veh_eco, 2, '.', '') : '' }}
                            @endif
                           
                        </td>

                        <td style="height:25px;text-align: right;border-color:black; " rowspan="1" colspan="1"
                        width="15%">
                           
                            @if($veh->id_vehiculo == $id_veh )
                                {{ $total_veh_super > 0 ? number_format($total_veh_super, 2, '.', '') : '' }}
                            @endif
                            

                           
                        </td>

                        <td style="height:25px;text-align: right;border-color:black; " rowspan="1" colspan="1" width="15%">
                          
                            @if($veh->id_vehiculo == $id_veh )
                                {{ $total_veh_diesel > 0 ? number_format($total_veh_diesel, 2, '.', '') : '' }}

                            @endif
                        </td>

                        <td style="height:25px;text-align: right;border-color:black; " rowspan="1" colspan="1" width="10%">
                           
                            @php
                                $total_comb=$total_comb + $total_veh_eco +$total_veh_super +$total_veh_diesel;
                                $total_pagado=$total_pagado + $total_comb;
                            @endphp

                            @if($total_comb>0)
                                {{number_format(($total_comb),2,'.', '')}} 
                            @endif
                            
                        </td>

                    
                    </tr>
                @endforeach
               
    
            </tbody>

            <tfoot style="font-size:12px">
                <tr >

                    <td style="height:25px;text-align: left;border-color:black;  border-left:0px;border-bottom:0px;border-right:0px" rowspan="1" colspan="5">
                       
                    </td>
                    <td style="height:25px;text-align: center;border-color:black;  border-left:0px;border-bottom:0px; border-right:0px " rowspan="1" colspan="1">
                        <b>TOTAL</b>
                    </td>

                    <td style="height:25px;text-align: right;border-color:black;border-left:0px;border-bottom:0px; border-right:0px  " rowspan="1" colspan="1">
                        $ {{number_format(($total_pagado),2,'.', '')}}   
                    </td>
                </tr>
            </tfoot>
         
           
        </table>
      
        
        {{-- $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
        $pdf->text(490, 820, "Página $PAGE_NUM de $PAGE_COUNT", $font, 9); --}}
       
    </div>

    <table width="100%">
        <tr style="font-size:11px">
           
            <td width="35%" style="text-align: center; border:0px">
               
            </td>
            <td width="30%" style="border:0px">
            
                <br><br><br><br>
                <hr >
                <p style="text-align: center">
                    {{ $responsable->nombre }}<br>
                    Responsable de Servicios Institucionales 
                   
                </p>
            </td>
            <td width="35%" style="border:0px">
                
            </td>
           
        </tr>
    </table>

   
  <script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
            $pdf->text(490, 820, "Página $PAGE_NUM de $PAGE_COUNT", $font, 9); 
        ');
    }
</script>
</body>
</html>