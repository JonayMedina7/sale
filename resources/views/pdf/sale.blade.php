<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de venta</title>
    <style>
        body {
        /*position: relative;*/
        /*width: 16cm;  */
        /*height: 29.7cm; */
        /*margin: 0 auto; */
        /*color: #555555;*/
        /*background: #FFFFFF; */
        font-family: Arial, sans-serif; 
        font-size: 9px;
        background-image: url('{{ asset('img/fondo.png') }}');
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
        

        /*font-family: SourceSansPro;*/
        }
        html, body { height: 100%; }
        nav{
            height: 80px;
        }
        header{
            
            margin: 0px;
            padding: 0px;
            top: 0px;
            bottom: 0px;
        }
        tr th, tr td {
            padding: 4px;
            font-size: 11px;
        }

       
        #data{
        float: left;
        margin: 0px 2%;
        
        font-size: 11px;

        /*text-align: justify;*/
        }
        #sidebar {
        height: 48%;
        width: 100%;
        border: solid 1px;
        }


        #inv{
        /*position: relative;*/
        float: right;
        margin: 0px;
        padding: 0px;
        font-size: 15px;
        }

        table{
        /*position: relative;*/
        clear: left;
        font-size: 11px;
        }
        /*#fact{
        
        float: right;

        margin-top: 0%;
        margin-left: 2%;
        margin-right: 2%;
        font-size: 20px;
        }*/

        #facliente{
        width: 100%;
        border: 1px solid #000;
        
        border-spacing: 0;
        text-align: left;
        margin-bottom: 12px;
        font-size: 11px;
        }
        /*tr #fac{
            width: 100%;
        }*/

        #fac, #fv, #fa{
        color: #FFFFFF;
        font-size: 5px;
        }

        #facliente thead{
        padding: 20px;
        background: #2183E3;
        text-align: left;
        border-bottom: 1px solid #FFFFFF;  
        font-size: 11px;
        }

        #facvendedor{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        text-align: center;
        margin-bottom: 12px;
        font-size: 11px;
        }

        #facvendedor thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;
        font-size: 11px;  
        }

        #facarticulo{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 12px;
        font-size: 11px;
        }
        #facarticulo td{
             
             font-size: 11px;
        }

        #facarticulo thead{
        padding: 20px;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;
        font-size: 11px;  
        }

        #total {
        width: 100%;
        
        border-spacing: 0;
        margin-top: 15px;
        margin-bottom: 12px; 
        }

        #total thead{
        /*display: block;*/
        font-size: 13px;
        font-weight: bold;
        
        }
        footer {
            margin-top: 5px;
            margin-right: -2px;
        }
        #footer {
            padding: 2px 7px 0 22px;
            float: right;
            text-align: right;
            border: #000 1px solid;
            font-size: 12px;
        }
        #footer2 {
            float: left;
            padding: 6px 0px 0 12px;
            font-size: 13px;
            height: 15%;
        }
        #footer2 p {
            padding-top: 10px;
        }

        .border1 {
        position:absolute;
        left:60px;
        top:207px;
        width:1px;
        height:48.3%;
        border-left: 1px solid black;
        }

        .border2 {
        position:absolute;
        left: 682px;
        top:207px;
        width:1px;
        height: 48.3%;
        border-left: 1px solid black;
        }

        .border3 {
        position:absolute;
        left:802px;
        top:207px;
        width:1px;
        height: 48.3%;
        border-left: 1px solid black;
        }
        
       /*div#footer { width: 99%; font-size: 13px; height: 16%; position: absolute; bottom: -.15in; text-align: right; border-top: 1px solid black;  }
       div#footer2 { width: 100%; font-size: 12px; height: 15%; position: absolute; bottom: -.15in; text-align: left;  }*/
    

    </style>
    
    <body  >
        @foreach ($sale as $s)
        <nav>
            
        </nav>
        {{-- Inicio Header --}}
        <header class="clearfix">
         
            <div id="data" class="clearfix">
                <p > Fecha de Emisión: {{ date("d-m-Y",strtotime($s->date)) }}</p>
            </div> 
            <div id="inv" class="clearfix">
                <p><b> @if ($s->voucher=='bill')
                    FACTURA N°: 
                @elseif ($s->voucher=='credit')
                    NOTA DE CRÉDITO N°: 000
                @elseif ($s->voucher=='note')
                    VALE N°: 00
                @endif

                {{ '000'.$s->voucher_num }}</p>

               

            </div>
        </header>
        {{-- Fin Header --}}
        <br>
        {{-- Inicio Seccion info Cliente --}}
        <section>
            <div>
                <table id="facliente"  >
                                         
                        <tr>
                            <td colspan="6"  width="100%" align="center">DATOS DE CLIENTE</td>
                            
                   </tr>
                   
                    
                        <tr>
                            <td colspan="1"  >RAZÓN SOCIAL: </td>
                            <td  colspan="4" >{{ strtoupper($s->name) }}</td>
                            
                        </tr>
                        <tr>
                            <td colspan="1" >DIRECCIÓN FISCAL: </td>
                            <td colspan="4">{{ strtoupper($s->address) }}</td>
                           
                        </tr>
                        <tr>
                            <td colspan="1" ><p >Rif/C.I.:</p></td>
                            <td colspan="1" > {{ $s->rif }}</td>
                            <td colspan="2" ><p >Telefono: {{ $s->phone }}</p></td>
                            <td colspan="2" ><p >Email: {{ strtoupper($s->email) }}</p></td>
                        </tr>
                   
                </table>
            </div>
        </section>
        {{-- Fin Seccion info Cliente --}}
        @endforeach

        {{-- Inicio Seccion detalles factura --}}
        <section >
            <div id="sidebar">
                <table id="facarticulo"  >
                    <thead>
                        <tr style="border-right: 1px; ">
                            <p class="border1"></p>
                            <p class="border2"></p>
                            <p class="border3"></p>
                            <th style="text-align: center; width: 5%;">CANT</th>
                            <th style="text-align: center; width: 65%;">DESCRIPCION</th>
                            <th style="text-align: right; width: 15%;">PRECIO UNIT</th>
                            <th style="text-align: right; width: 15%;">PRECIO TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($details as $d)
                            
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: center;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
       {{-- Fin Seccion detalles factura --}}

       {{-- Inicio totales factura --}}
        <footer>
            <div id="footer" >
                    @foreach ($sale as $s)
                    <p>SUBTOTAL Bs:&nbsp;&nbsp;{{ round($s->total-($s->total*$s->tax),2) }}</p>
                    <p>IVA Bs:&nbsp;&nbsp;{{ round($s->total*$s->tax,2) }}</p>
                    <p id="total">TOTAL Bs:&nbsp;&nbsp; {{ $s->total }}</p>
                      @endforeach
            </div>
            <div id="footer2">
                <p>Recibido por: ____________________________</p>
                <p><b>Observaciones:</b></p>
            </div>
        </footer>
        {{-- Fin totales factura --}}
    </body>
</html>