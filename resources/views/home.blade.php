@extends('layouts.app2')

    @section('content')
        
        @if (Auth::check())
                @if (Auth::user()->role_id == 1)
                  <template v-if="menu==0">
                    <example-component></example-component>
                  </template>

                  <template v-if="menu==1">
                    <category></category>
                  </template>

                  <template v-if="menu==2">
                    <product></product>
                  </template>

                  <template v-if="menu==3">
                    
                    <purchase></purchase>
                  </template>

                  <template v-if="menu==4">
                    <provider></provider>
                  </template>

                  <template v-if="menu==5">
                    <sale></sale>
                  </template>

                  <template v-if="menu==6">
                    <client></client>
                  </template>

                  <template v-if="menu==7">
                    <user></user>
                  </template>

                  <template v-if="menu==8">
                    <role></role>
                  </template>

                  <template v-if="menu==9">
                    <h1> contenido reposte ingresos</h1>
                  </template>

                  <template v-if="menu==10">
                    <h1> contenido reporte ventas</h1>
                  </template>

                  <template v-if="menu==11">
                    <h1> contenido ayuda de sistema</h1>
                  </template>

                  <template v-if="menu==12">
                    <h1> contenido menu acerca del sistema</h1>
                  </template>                    
                @elseif (Auth::user()->role_id == 2)
                  <template v-if="menu==0">
                    <example-component></example-component>
                  </template>

                  <template v-if="menu==5">
                    <sale></sale>
                  </template>

                  <template v-if="menu==6">
                    <client></client>
                  </template>
                            
                  <template v-if="menu==10">
                    <h1> contenido reporte ventas</h1>
                  </template>

                  <template v-if="menu==11">
                    <h1> contenido ayuda de sistema</h1>
                  </template>

                  <template v-if="menu==12">
                    <h1> contenido menu acerca del sistema</h1>
                  </template>
                @elseif (Auth::user()->role_id == 3)
                    <template v-if="menu==0">
                      <example-component></example-component>
                    </template>

                    <template v-if="menu==1">
                      <category></category>
                    </template>

                    <template v-if="menu==2">
                      <product></product>
                    </template>

                    <template v-if="menu==3">                        
                      <purchase></purchase>
                    </template>

                    <template v-if="menu==4">
                      <provider></provider>
                    </template>
                    <template v-if="menu==9">
                      <h1> contenido reposte ingresos</h1>
                    </template>

                    <template v-if="menu==11">
                      <h1> contenido ayuda de sistema</h1>
                    </template>

                    <template v-if="menu==12">
                      <h1> contenido menu acerca del sistema</h1>
                    </template>

                @endif
        @endif

    @endsection
