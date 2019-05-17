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
        border: 1px solid #000;
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
        border-collapse: collapse;
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
        
        #gracias{
        text-align: left;
        padding: 2% 6%; 
        }
       div#footer { width: 100%; font-size: 13px; height: 30%; position: absolute; bottom: -.15in; text-align: right; border-top: 1px solid black; }
       div#footer2 { width: 100%; font-size: 12px; height: 25%; position: absolute; bottom: -.15in; text-align: left;  }
    

    </style>
    
    <body  >
        @foreach ($sale as $s)
        <nav>
            
        </nav>
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
<<<<<<< HEAD
                {{ '000'.$s->voucher_num }}</p>
=======
                {{ $s->voucher_serie }}-{{ $s->voucher_num }}<b></p>
>>>>>>> 5f8e7667df792db8ed06b362f144a849cd8241ed
            </div>
        </header>
        <br>
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
        @endforeach
        <section style="height: 200px !important ; overflow: hidden;">
            <div style="height: 200px !important ; overflow: hidden;">
                <table id="facarticulo"  >
                    <thead>
                        <tr style="border-right: 1px; ">
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
       
            
                <div id="footer">
                    @foreach ($sale as $s)
                    <p>SUBTOTAL Bs:&nbsp;&nbsp;{{ round($s->total-($s->total*$s->tax),2) }}</p>
                    <p>IVA Bs:&nbsp;&nbsp;{{ round($s->total*$s->tax,2) }}</p>
                    <p id="total"><b>TOTAL Bs:&nbsp;&nbsp; {{ $s->total }}<b></p>
                      @endforeach
 
</div>
               
                
            
            
        

        <footer>
            <div id="footer2">
                <p>Recibido por: ____________________________</p>
                <p><b>Observaciones:</b></p>
            </div>
        </footer>
    </body>
</html>