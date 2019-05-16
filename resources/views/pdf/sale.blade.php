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
        font-size: 12px;
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
            font-size: 14px;
        }

       
        #data{
        float: left;
        margin: 0px 2%;
        
        font-size: 15px;

        /*text-align: justify;*/
        }


        #inv{
        /*position: relative;*/
        float: right;
        margin: 0px;
        padding: 0px;
        font-size: 17px;
        }

        table{
        /*position: relative;*/
        clear: left;
        border: 1px solid #000;
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
        }
        /*tr #fac{
            width: 100%;
        }*/

        #fac, #fv, #fa{
        color: #FFFFFF;
        font-size: 15px;
        }

        #facliente thead{
        padding: 20px;
        background: #2183E3;
        text-align: left;
        border-bottom: 1px solid #FFFFFF;  
        }

        #facvendedor{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        text-align: center;
        margin-bottom: 12px;
        }

        #facvendedor thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        #facarticulo{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 12px;
        }
        #facarticulo td{
             text-align: center;
        }

        #facarticulo thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        #total {
        width: 100%;
        
        border-spacing: 0;
        margin-top: 15px;
        margin-bottom: 12px; 
        }

        #total thead{
        /*display: block;*/
        align-content: right;
        text-align: right;
        
        }
        
        #gracias{
        text-align: left;
        padding: 2% 6%; 
        }
    </style>
    {{-- <link href="css/template.css" rel="stylesheet"> --}}
    <body>
        @foreach ($sale as $s)
        <nav>
            
        </nav>
        <header class="clearfix">
            {{-- <div id="logo">
                <img src="img/logo.png" alt="dilia soluciones" id="imagen">
            </div> --}}
            <div id="data" class="clearfix">
                <p > Fecha de Emisión: {{ $s->date }}</p>
            </div> 
            <div id="inv" class="clearfix">
                <p> @if ($s->voucher=='bill')
                    FACTURA N°: 
                @elseif ($s->voucher=='credit')
                    NOTA DE CRÉDITO N°: 000
                @elseif ($s->voucher=='note')
                    VALE N°: 00
                @endif
                {{ '000'.$s->voucher_num }}</p>
            </div>
        </header>
        <br>
        <section>
            <div>
                <table id="facliente" >
                    <thead>                        
                        <tr>
                            <th colspan="6" id="fac">DATOS DE CLIENTE</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th colspan="2" id="client">RAZÓN SOCIAL: </th>
                            <td  colspan="4">{{ strtoupper($s->name) }}</td>
                            
                        </tr>
                        <tr>
                            <th colspan="2" id="client">DIRECCIÓN FISCAL: </th>
                            <td colspan="4">{{ strtoupper($s->address) }}</td>
                           
                        </tr>
                        <tr>
                            <td colspan="1" ><p style="text-align: center;">Rif/C.I.:</p></td>
                            <td colspan="1" >
                                {{ $s->rif }}</td>
                            <td colspan="2" ><p style="text-align: center;">Telefono: {{ $s->phone }}</p></td>
                            <td colspan="2" ><p style="text-align: center;">Email: {{ strtoupper($s->email) }}</p></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        @endforeach
        <section>
            <div>
                <table id="facarticulo">
                    <thead>
                        <tr id="fa">
                            <th style="width: 10%;">CANT</th>
                            <th style="width: 60%;">DESCRIPCION</th>
                            <th style="width: 20%;">PRECIO UNIT</th>
                            <th style="width: 20%;">PRECIO TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($details as $d)
                            
                        <tr>
                            <td>{{ $d->quantity }}</td>
                            <td>{{ strtoupper($d->product) }}</td>
                            <td>{{ $d->price }}</td>
                            <td>{{ $d->quantity*$d->price }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        <section>
            <div>
                
                <table id="total">
                    
                    <thead >
                        @foreach ($sale as $s)

                        <tr>
                            <th style="width: 50%;"></th>
                            <th style="width: 18%;"></th>
                            <td ><strong>SUBTOTAL</strong></td>
                            <td>Bs: {{ round($s->total-($s->total*$s->tax),2) }}</td>
                        </tr>
                        <tr>
                            <th style="width: 50%;"></th>
                            <th style="width: 18%;"></th>
                            <td><strong>IVA</strong></td>
                            <td>Bs: {{ round($s->total*$s->tax,2) }}</td>
                        </tr>
                        <tr>
                            <th style="width: 50%;">Recibido por: ____________________________</th>
                            <th style="width: 18%;"></th>
                            <td ><strong>TOTAL</strong></td>
                            <td>Bs: {{ $s->total }}</td>
                        </tr>
                        @endforeach
                    </thead>

                </table>
                <div>
                </div>
            </div>
        </section>

        <footer>
            <div id="gracias">
                <p><b>Observaciones:</b></p>
            </div>
        </footer>
    </body>
</html>