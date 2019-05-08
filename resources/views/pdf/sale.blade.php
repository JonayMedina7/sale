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
        font-size: 14px;
        /*font-family: SourceSansPro;*/
        }
        nav{
            height: 100px;
        }
        header{

            margin: 0px;
            padding: 0px;
            /*top: 0px;*/
            /*bottom: 0px;*/
        }

       
        #data{
        float: left;
        margin: 0px;
        padding: 0px;
        font-size: 15px;

        /*text-align: justify;*/
        }


        #inv{
        /*position: relative;*/
        float: right;
        margin: 0px;
        padding: 0px;
        font-size: 20px;
        }

        section{
        clear: left;
        border: 1px solid #000;
        }

        #client th{
        text-align: left;

        }

        #facliente{
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 15px;
        }

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
        margin-bottom: 15px;
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
        margin-bottom: 15px;
        }

        #facarticulo thead{
        padding: 20px;
        background: #2183E3;
        text-align: center;
        border-bottom: 1px solid #FFFFFF;  
        }

        #gracias{
        text-align: center; 
        }
    </style>
    <body>
        @foreach ($sale as $s)
        <nav>
            
        </nav>
        <header class="clearfix">
            {{-- <div id="logo">
                <img src="img/logo.png" alt="dilia soluciones" id="imagen">
            </div> --}}
            <div id="data">
                <p > Fecha de Emisión: {{ $s->date }}</p>
            </div> 
            <div id="inv">
                <p> @if ($s->voucher=='bill')
                    Factura n°: 
                @elseif ($s->voucher=='credit')
                    Nota de Credito n°: 
                @elseif ($s->voucher=='note')
                    Vale n°: 
                @endif
                {{ $s->voucher_serie }}-{{ $s->voucher_num }}</p>
            </div>
        </header>
        <br>
        <section>
            <div>
                <table id="facliente">
                    <thead>                        
                        <tr>
                            <th id="fac">Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th><p id="client">Razón Social: {{ $s->name }}<br>
                            Rif/c.i.:{{ $s->tyse }}: {{ $s->rif }}<br>
                            Dirección Fiscal: {{ $s->address }}<br>
                            Telefono: {{ $s->phone }}<br>
                            Email: {{ $s->email }}</p></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <br>
        <section>
            <div>
                <table id="facvendedor">
                    <thead>
                        <tr id="fv">
                            <th>VENDEDOR</th>
                            <th>FECHA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td> {{ $s->user }}</td>
                            <td>{{ $s->date }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        @endforeach
        <br>
        <section>
            <div>
                <table id="facarticulo">
                    <thead>
                        <tr id="fa">
                            <th>CANT</th>
                            <th>DESCRIPCION</th>
                            <th>PRECIO UNIT</th>
                            <th>PRECIO TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($details as $d)
                            {{-- expr --}}
                        
                        <tr>
                            <td>{{ $d->quantity }}</td>
                            <td>{{ $d->product }}</td>
                            <td>{{ $d->price }}</td>
                            
                            <td>{{ $d->quantity*$d->price }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        @foreach ($sale as $s)
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>SUBTOTAL</th>
                            <td>Bs: {{ round($s->total-($s->total*$s->tax),2) }}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>IVA</th>
                            <td>Bs: {{ round($s->total*$s->tax,2) }}</td>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>TOTAL</th>
                            <td>bs: {{ $s->total }}</td>
                        </tr>
                        @endforeach
                    </tfoot>
                </table>
            </div>
        </section>

        
        <br>
        <br>

        <footer>
            <div id="gracias">
                <p><b>Gracias por su compra!</b></p>
            </div>
        </footer>
    </body>
</html>