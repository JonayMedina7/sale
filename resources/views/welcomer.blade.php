<!DOCTYPE>
<html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de venta</title>
    <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('js/jquery.min.js') }}" defer></script>
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <style>
        body {
        /*position: relative;*/
        /*width: 16cm;  */
        /*height: 29.7cm; */
        /*margin: 0 auto; */
        /*color: #555555;*/
        background: #FFFFFF; 
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

    </style>
    
    <body  >
        
        <nav>
            
        </nav>
        {{-- Inicio Header --}}
        <header class="clearfix">
            <div class="col-md-12">
                <h3>COMPROBANTE DE RETENCIÓN DEL IMPUESTO AL VALOR AGREGADO I.V.A.</h3>
            </div>
        </header>
        <main class="container-fluid">
            @foreach ($retention as $r)
            <div class="row">
                <div class="col-md-7 float-left">
                    <p><strong>Ley IVA - Art. 11:</strong>Decreto con Rango, Valor y Fuerza de Ley de Reforma de la Ley del Impuesto al Valor Agregado N° 1.436 del 17 de noviembre de 2014 "La Administración Tributaria podrá designar como responsables del pago del impuesto, en calidad de agentes de retención, a quienes por sus funciones públicas o por razón de sus actividades privadas intervengan en operaciones gravadas con el impuesto establecido en esta Ley. (...)" </p>
                </div>
                <div class=" col-md-5 float-right card">
                    <div class=" card-text">
                        <label>0. NRO COMPROBANTE</label>
                        <p>{{ $r->voucher_num }}</p>
                    </div>
                    <div class=" card-text">
                        <label>1. FECHA </label>
                        <p>{{ date("d-m-Y",strtotime($r->date)) }}</p>
                    </div>
                    <div class=" card-text">
                        <label>4. PERIODO FISCAL </label>
                        <p><strong>AÑO: {{ $r->year }} / MES: {{ $r->month }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6 border" >
                        <div class="form-group">
                            <label>2. NOMBRE O RAZÓN SOCIAL DEL AGENTE DE RETENCIÓN</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group border">
                            <label>3. REGISTRO DE INFORMACIÓN FISCAL DEL AGENTE DE RETENCIÓN</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group border">
                            <label>5. DIRECCIÓN FISCAL DEL AGENTE DE RETENCIÓN</label>
                            <p class="text-uppercase"><strong> </strong></p>
                        </div>  
                    </div>
                    <div class="col-md-6">
                        <div class="form-group border">
                            <label>6. NOMBRE O RAZÓN SOCIAL DEL SUJETO RETENIDO</label>
                            <p class="text-uppercase"><strong> {{ $r->name }}</strong></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group border">
                            <label>7. REGISTRO DE INFORMACIÓN FISCAL DEL SUJETO RETENIDO</label>
                            <p class="text-uppercase"><strong> {{ $r->type.$r->rif }}</strong></p>
                        </div>
                        
                    </div>
                </div>
            </div>
            @endforeach
            <div class="d-flex flex-column bd-highlight mb-2">
                <div class="d-flex bd-highlight justify-content-start mb-14">
                    <div class="p-2 flex-fill bd-highlight">Oper.</div>
                    <div class="p-2 flex-fill bd-highlight">Fecha</div>
                    <div class="p-2 flex-fill bd-highlight">Factura</div>
                    <div class="p-2 flex-fill bd-highlight">Control</div>
                    <div class="p-2 flex-fill bd-highlight">N. Debito</div>
                    <div class="p-2 flex-fill bd-highlight">N. Credito</div>
                    <div class="p-2 flex-fill bd-highlight">Tipo Trans.</div>
                    <div class="p-2 flex-fill bd-highlight">Fact Afect.</div>
                    <div class="p-2 flex-fill bd-highlight">Compras + IVA</div>
                    <div class="p-2 flex-fill bd-highlight">Sin Crédito</div>
                    <div class="p-2 flex-fill bd-highlight">Base Imponible</div>
                    <div class="p-2 flex-fill bd-highlight">Alic</div>
                    <div class="p-2 flex-fill bd-highlight">Impuesto IVA</div>
                    <div class="p-2 flex-fill bd-highlight">IVA Retenido</div>
                </div>
                @foreach ($detailret as $d)
                <div class="d-flex bd-highlight justify-content-start mb-14">
                    
                    <div class="p-2 flex-fill bd-highlight">{{ $d->id }}</div>
                    <div class="p-2 flex-fill bd-highlight">{{ $d->date }}</div>
                    <div class="p-2 flex-fill bd-highlight">{{ $d->purchase_num }}</div>
                    <div class="p-2 flex-fill bd-highlight">{{ $d->voucher_serie }}</div>
                    <div class="p-2 flex-fill bd-highlight">0</div>
                    <div class="p-2 flex-fill bd-highlight">0</div>
                    <div class="p-2 flex-fill bd-highlight">01 - Reg</div>
                    <div class="p-2 flex-fill bd-highlight">0</div>
                    <div class="p-2 flex-fill bd-highlight">{{ $d->totalp }}</div>
                    <div class="p-2 flex-fill bd-highlight">0,00</div>
                    <div class="p-2 flex-fill bd-highlight">{{ $d->totalp - $d->tax_mount }}</div>
                    <div class="p-2 flex-fill bd-highlight">{{ $d->tax }}</div>
                    <div class="p-2 flex-fill bd-highlight">{{ $d->tax_mount }}</div>
                    <div class="p-2 flex-fill bd-highlight">{{ $d->tax_mount }}</div>
                    
                </div>
                @endforeach
            </div>
            
        </main>
        {{-- end main --}} 
            
               

            
        
        
        <br>
        {{-- Inicio Seccion info Cliente --}}
       
        {{-- Fin Seccion info Cliente --}}
       

        {{-- Inicio Seccion detalles factura --}}
        
        {{-- Fin totales factura --}}
    </body>
</html>