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
        {{-- background-image: url('{{ asset('img/fondo.png') }}'); --}}
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
        height: 80%;
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
        
        font-size: 11px;
        
        }
        thead {
            border-bottom: 1px solid black;
        }

        #facliente{
        width: 100%;
        border: 1px solid #000;
        
        border-spacing: 0;
        text-align: left;
        margin-bottom: 12px;
        font-size: 11px;
        }

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
        margin-bottom: 0;
        font-size: 11px;
        }
        /*table#facarticulo {
            height: 60%;
            width: 100%;
            border: solid 1px;
        }*/
        #facarticulo td{
             position: relative;
             font-size: 11px;
             border-left: 1px solid black;
             border-right: 1px solid black;
        }

        #facarticulo thead{
        padding: 20px;
        text-align: center;
        border-bottom: 1px solid #000;
        font-size: 11px;  
        }

        #total {
        width: 100%;
        
        border-spacing: 0;
        margin-top: 15px;
        margin-bottom: 12px; 
        font-size: 15px;
        }

        footer {
            margin-top: 5px;
        }

        #footer {
            padding: 2px 7px 0 22px;
            float: right;
            text-align: right;
            border: #000 1px solid;
            font-size: 12px;
            min-width: 20%;
            
        }
        #footer2 {
            float: left;
            padding: 6px 0px 0 12px;
            font-size: 13px;
            
        }
        #footer2 p {
            padding-top: 10px;
        }

        .border1 {
        position:absolute;
        left:56px;
        top:228px;
        width:1px;
        height:48.3%;
        border-left: 1px solid black;
        }

        .border2 {
        position:absolute;
        left: 532px;
        top:228px;
        width:1px;
        height: 48.3%;
        border-left: 1px solid black;
        }

        .border3 {
        position:absolute;
        left:629px;
        top:228px;
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
                <p > <b>Fecha de Emisión:</b> {{ date("d-m-Y",strtotime($s->date)) }}</p>
            </div> 
            <div id="inv" class="clearfix">
                <p><b> @if ($s->voucher=='bill')
                    FACTURA N°: 
                @elseif ($s->voucher=='credit')
                    NOTA DE CRÉDITO N°: 000
                @elseif ($s->voucher=='note')
                    VALE N°: 00
                @endif
                
                {{ '000'.$s->voucher_num }}</b></p>
                
               

            </div>
        </header>
        {{-- Fin Header --}}
        <br>
        {{-- Inicio Seccion info Cliente --}}
        <section>
            <div>
                <table id="facliente"  >
                                         
                        <tr>
                            <td colspan="6"  width="100%" align="center"><b>DATOS DE CLIENTE</b></td>
                        </tr>
                   
                    
                        <tr>
                            <td colspan="1"  ><b>RAZÓN SOCIAL: </b></td>
                            <td  colspan="4" >{{ strtoupper($s->name) }}</td>
                            
                        </tr>
                        <tr>
                            <td colspan="1" ><b>DIRECCIÓN FISCAL: </b></td>
                            <td colspan="4">{{ strtoupper($s->address) }}</td>
                           
                        </tr>
                        <tr>
                            <td colspan="1" ><p ><b>RIF/C.I.:</b></p></td>
                            <td colspan="1" > {{ strtoupper($s->type.$s->rif) }}</td>
                            <td colspan="2" ><p >TELÉFONO: {{ $s->phone }}</p></td>
                            <td colspan="2" ><p >CORREO ELECTRÓNICO: {{ strtoupper($s->email) }}</p></td>
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
                        <tr style="border-right: 1px; border-bottom: 1px;">
                            <th style="text-align: center; width: 5%;">CANT</th>
                            <th style="text-align: center; width: 65%;">DESCRIPCION</th>
                            <th style="text-align: right; width: 15%;">PRECIO UNIT</th>
                            <th style="text-align: right; width: 15%;">PRECIO TOTAL</th>
                        </tr>
                    </thead>
                    <tbody >
                        @foreach ($details as $d)
                            
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>

                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>

                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{ $d->quantity }}</td>
                            <td style="text-align: left;">{{ strtoupper($d->product) }}</td>
                            <td style="text-align: right;">{{ $d->price }}</td>
                            <td style="text-align: right;">{{ $d->quantity*$d->price }}</td>
                        </tr>
                        @endforeach
                        <tr class="tbody">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr> 

                    </tbody>
                                
                </table>
            </div>
        </section>
       {{-- Fin Seccion detalles factura --}}

       {{-- Inicio totales factura --}}
        <footer>
            <div class="row">
                <div id="footer" >

                    @foreach ($sale as $s)
                    <p><b>SUBTOTAL Bs:</b>&nbsp;&nbsp;{{ round($s->total-($s->tax_mount),2) }}</p>
                    <p><b>IVA Bs:</b>&nbsp;&nbsp;{{ $s->tax_mount }}</p>
                    <p id="total"><b>TOTAL Bs:&nbsp;&nbsp; {{ $s->total }}</b></p>
                    @endforeach
            </div>
            <div id="footer2">
                <p>Recibido por: ____________________________</p>
                <p><b>Observaciones:</b></p>
            </div>

            </div>
        </footer>
        {{-- Fin totales factura --}}
    </body>
</html>