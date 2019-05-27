 <template>
        <main class="main">
            <!-- Breadcrumb -->
            <!-- <ol class="breadcrumb">
                
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
                
            </ol> -->
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Retenciones
                        <button type="button" class="btn btn-secondary" @click="showInsert()">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <!-- litado registros -->
                    <template v-if="list==1">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterion">
                                          <option value="voucher">Tipo de Comprobante</option>
                                          <option value="voucher_num">numero de comprobante</option>
                                          <option value="date">Fecha-hora</option>
                                        </select>
                                        <input type="text" v-model="search" @keyup.enter="listRetention(1,search,criterion)" class="form-control" placeholder="Texto a Buscar">
                                        <button type="submit" @click="listRetention(1,search,criterion)" class="btn btn-primary"><i class="fa fa-search"></i> search</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Numero retención</th>
                                            <th>Cliente</th>
                                            <th>Tipo de comprobante</th>
                                            <th>Número comprobante</th>
                                            <th>Fecha Hora</th>
                                            <th>Total Retención</th>
                                            <th>Total Factura</th>
                                            <th>Impuesto</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="ret in arrayRet" :key="ret.id">
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm" @click="showRet(ret.id)">
                                                  <i class="icon-eye"></i>
                                                </button> &nbsp;
                                                <button type="button" class="btn btn-info btn-sm" @click="pdfRet(ret.id)">
                                                  <i class="icon-doc"></i>
                                                </button> &nbsp;

                                                <template  v-if="ret.status=='Registrado'">
                                                    <button type="button" @click="desactiveRet(ret.id)" class="btn btn-danger btn-sm" >
                                                      <i class="icon-trash"></i>
                                                    </button>
                                                </template>
                                                
                                            </td>
                                            <td v-text="ret.voucher_num"></td>
                                            <td v-text="ret.name"></td>
                                            <td v-if="ret.voucher=='bill'">Factura</td>
                                            <td v-else-if="ret.voucher=='note'">Vale</td>
                                            <td v-else-if="ret.voucher=='credit'">Nota de Crédito</td>
                                            <td v-text="ret.sale_num"></td>
                                            <td v-text="ret.date"></td>
                                            <td v-text="ret.total"></td>
                                            <td v-text="ret.totals"></td>
                                            <td v-text="ret.tax"></td>
                                            <td v-text="ret.status"></td>                                     
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>

                            <nav>
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page -1, search, criterion)">Ant.</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActive ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="changePage(page, search, criterion)" v-text="page"></a>
                                    </li>
                                    
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page +1, search, criterion)">Sig.</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>
                    <!-- Fin Listado -->

                    <!-- Panel de ingreso de productos -->
                    <template v-else-if="list==0">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="">Cliente(*)</label>
                                        <v-select  @search="clientSelectr" label="name" :options="arrayClient"
                                        placeholder="Buscar Cliente"
                                        @input="getClientInfo"
                                        > 
                                            
                                        </v-select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label >Razón Social(*):</label>
                                        <h4><span v-text="name" style="text-transform: uppercase;"> </span></h4>
                                    </div>
                                </div>
                                <div  class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Rif o C.I(*)</label>
                                        
                                          <h4><span v-text="type + '-' + rif" style="text-transform: uppercase;"></span> </h4> 
                                          
                                        
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label >Dirección(*):</label>
                                        <h4><span v-text="address" style="text-transform: uppercase;"> </span></h4>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Impuesto(*)</label>
                                    <input type="text" class="form-control" v-model="tax" name="" >
                                </div>
                                <div class="col-md-3">
                                    <label for=""> % Retenido</label>
                                    <input type="text" class="form-control" v-model="retention" name="" >
                                </div>
                                
                            </div>
                            <div class="form-group row border">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Facturas <span style="color:red;" v-show="sale_id==0">(*Seleccione)</span></label>
                                        <div class="form-inline">
                                            <input type="text" class="form-control" v-model="sale_num" @keyup.enter="saleSearch()" placeholder="Ingrese numero de Venta" name="">
                                            <button @click="openModalr()" class="btn btn-primary">...</button>
                                            <input type="text" readonly class="form-control" v-model="sale_num" name="" placeholder="ENTER para buscar">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Tipo de Documento</label>
                                        <span class="form-control" v-if="voucher=='bill'">Factura</span>
                                            <span class="form-control" v-else-if="voucher=='note'">Vale</span>
                                            <span class="form-control" v-else-if="voucher=='credit'">Nota de Crédito</span>
                                        
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Total de factura</label>
                                        <input type="number" readonly step="any" class="form-control" v-model="totals" name="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Base Imponible</label>
                                        <input type="number" readonly class="form-control" v-model="tax" name="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <button @click="addDetail()" class="btn btn-success form-control btn-add"> <i class="icon-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row border">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Opciones</th>
                                                <th>Numero de documento</th>
                                                <th>Documento</th>
                                                <th>Monto Factura</th>
                                                <th>Impuesto</th>
                                                <th>Monto de Impuesto</th>
                                                <th>Monto Retenido</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetailr.length">
                                            <tr v-for="(detail, index) in arrayDetailr" :key="detail.sale_id">
                                                <td>
                                                    <button @click="deleteDetail(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td v-text="detail.sale_num"></td>
                                                <td>
                                                    <span class="form-control" v-if="detail.voucher=='bill'">Factura</span>
                                                    <span class="form-control" v-else-if="detail.voucher=='note'">Vale</span>
                                                    <span class="form-control" v-else-if="detail.voucher=='credit'">Nota de Crédito</span>
                                                </td>
                                                <td>
                                                    
                                                    <input v-model="detail.totals" type="number"  class="form-control" name="">
                                                </td>
                                                <td>
                                                    <input v-model="detail.tax_mount" type="number"  class="form-control" name="">
                                                </td>
                                                <td>
                                                    {{ detail.tax_mount*retention }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECFS;">
                                                <td colspan="5" align="right"><strong>Total Parcial: </strong></td>
                                                <td>$ {{ totalPartial=(total-totalTax).toFixed(2) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECFS;">
                                                <td colspan="5" align="right"><strong>Total Impuesto: </strong></td>
                                                <td>$ {{ totalTax=((total*tax)/(1+tax)).toFixed(2) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECFS;">
                                                <td colspan="5" align="right"><strong>Total a Pagar: </strong></td>
                                                <td>$ {{ total=calculateTotal }}</td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td >
                                                    No hay artículos agregados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary" @click="hideRet()">Cerrar</button>
                                    <button type="button" class="btn btn-primary" @click="registerRet()">Registrar Retención</button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <!-- Fin panel -->

                    <!-- Panel de vista de ventas -->
                    <template v-else-if="list==2">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="client">Cliente</label>
                                        <strong><p class="upper" v-text="name"></p></strong>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="client">rif</label>
                                        <strong><p v-text="type + ': '+ rif"></p></strong>

                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="user">Vendedor</label>
                                        <p v-text="user"></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="tax">Impuesto</label>
                                    <p v-text="tax"></p>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Numero de Retención</label>
                                        <span v-text="voucher_num"></span>
                                        <span v-text="status"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha de Retención</label>
                                        <p v-text="date"></p>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="form-group row border">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Tipo de Documento</th>
                                                <th>Nro.</th>
                                                <th>Fecha Documento</th>
                                                <th>Total</th>
                                                <th>Opciones</th> 
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetailr.length">
                                            <tr v-for="detail in arrayDetailr" :key="detail.id">
                                                
                                                <td v-text="detail.voucher" ></td>
                                                <td v-text="detail.sale_num" ></td>
                                                <td v-text="detail.dates" ></td>
                                                <td v-text="detail.totals"></td>
                                            </tr>
                                            <!-- <tr style="background-color: #CEECFS;">
                                                <td colspan="3" align="right"><strong>Total Parcial: </strong></td>
                                                <td>$ {{ totalPartial=(total-totalTax).toFixed(2) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECFS;">
                                                <td colspan="3" align="right"><strong>Total Impuesto: </strong></td>
                                                <td>$ {{ totalTax=((total*tax)).toFixed(2) }}</td>
                                            </tr>
                                            <tr style="background-color: #CEECFS;">
                                                <td colspan="3" align="right"><strong>Total a Pagar: </strong></td>
                                                <td>$ {{ total }}</td>
                                            </tr> -->
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td >
                                                    No hay articulos agredados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>  
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary" @click="hideRet()">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <!-- Fin panel vistas ventas -->

                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'show' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="titleModal"></h4>
                            <button type="button" class="close" @click="closeModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criteryR">
                                          <option value="name">Nombre</option>
                                          <option value="code">Codigo</option>
                                        </select>
                                        <input type="text" v-model="search" @keyup.enter="listProductSale(searchR,criteryR)" class="form-control" placeholder="Ingrese datos a Buscar">
                                        <button type="submit" @click="listProductSale(searchR,criteryR)" class="btn btn-primary"><i class="fa fa-search"></i> search</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th>Categoría</th>
                                            <th>Precio Venta</th>
                                            <th>Stock</th>
                                            
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="sale in arraySale" :key="sale.id">
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm" @click="addDetailModal(sale)">
                                                  <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td v-text="sale.code"></td>
                                            <td v-text="sale.name"></td>
                                            <td v-text="sale.category_name"></td>
                                            <td v-text="sale.price_sell"></td>
                                            <td v-text="sale.stock"></td>
                                            <td>
                                                <div v-if="sale.condition">
                                                    <span class="badge badge-success">Activo</span>
                                                </div>

                                                <div v-else>
                                                    <span class="badge badge-secondary">Inactivo</span>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button v-if="actionType==1" type="button" class="btn btn-primary" @click="registerRet()">Guardar</button>
                            <button v-if="actionType==2" type="button" class="btn btn-primary" @click="updatesale()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            
        </main>
</template>

<script>
    import vSelect from 'vue-select';
    import 'vue-select/dist/vue-select.css';

    export default {
        data (){
            return {
                user_id : 0,
                client_id : 0,
                sale_id: 0,
                id: 0,
                ret_id: 0,
                client: '',
                voucher_num : '',
                voucher: '',
                date : '',
                dates: '',
                tax : 0.16,
                total : 0.0,
                status: '',
                sale_num: '',
                totals: '',
                user: '',
                name:'',
                type: '',
                rif: 0,
                retention: '',
                arrayRet : [],
                arrayDetailr: [],
                arrayClient : [],
                arraySale: [],
                list : 1,
                totalTax: 0.0,
                totalPartial: 0.0,
                modal1 : 0,
                modal : 0,
                titleModal : '',
                actionType : 0,
                errorSmsR : 0,
                errorSmsListR : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterion : 'voucher_num',
                search : '',
                criteryR: 'name',
                searchR: ''
            }
        },
        components: {
            vSelect
        },
        computed: {
            isActive: function(){
                return this.pagination.current_page;
            },
            //esta calcula los elementos de la paginacion
            pagesNumber: function(){
                if(!this.pagination.to) {
                    return[];
                }
                var from = this.pagination.current_page - this.offset;
                if(from < 1) {
                    from = 1;
                }


                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while(from <= to ){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
            calculateTotal: function(){
                var result=0.0;
                for (var i = 0; i <this.arrayDetailr.length; i++) {
                    result = result +(this.arrayDetailr[i].totals)
                }
                return result;
            }

        },
        methods : {
            listRetention (page,search,criterion){
                
                let me=this;

                var url='retention?page=' + page + '&search=' + search + '&criterion=' + criterion;
                axios.get(url).then(function(response) {
                    var response = response.data; 
                     me.arrayRet = response.retentions.data;
                     me.pagination = response.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            showRet(id){
                let me=this;

                // otorga el valor 2 a la variable Lista para abrir la vista 
                me.list=2;

                var arrayRetTemp = [];

                // obtener los datos de la retencion, el usuario, el cliente mediante el id del cliente
                var url = 'retention/getHeader?id='+id;
                axios.get(url).then(function(response) {
                    var responseR = response.data;
                    me.arrayRetTemp = responseR.retention;

                    me.voucher_num = me.arrayRetTemp[0]['voucher_num'];
                    me.date = me.arrayRetTemp[0]['date'];
                    me.tax = me.arrayRetTemp[0]['tax'];
                    me.tax_mount = me.arrayRetTemp[0]['tax_mount'];
                    me.total = me.arrayRetTemp[0]['total'];
                    me.status = me.arrayRetTemp[0]['status'];
                    me.user_id = me.arrayRetTemp[0]['user_id'];
                    me.client_id = me.arrayRetTemp[0]['client_id'];
                    me.user = me.arrayRetTemp[0]['user'];
                    me.name = me.arrayRetTemp[0]['name'];
                    me.type = me.arrayRetTemp[0]['type'];
                    me.rif = me.arrayRetTemp[0]['rif'];
                    me.address = me.arrayRetTemp[0]['address'];
                })
                .catch(function(error) {
                    console.log(error);
                });

                // obtener las faturas asignadas a la retencion seleccionada
                
                var urlr = 'retention/getDetail?id='+id;
                axios.get(urlr).then(function(response) {
                    var responseD = response.data;
                    me.arrayDetailr = responseD.details;
                })
                .catch(function(error) {
                    console.log(error);
                });
            },
            showInsert(){
                let me=this;
                me.list=0;

                me.date='';
                me.total=0;
                me.totals=0;
                me.client_id=0;
                me.user_id=0;
                me.name='';
                me.type='';
                me.rif='';
                me.address='';
                me.voucher_num=0;
                me.voucher='bill';
                me.sale_num='';
                me.arrayDetailr=[];

            },
            clientSelectr(search,loading){
                let me=this;
                loading(true)

                var url='client/clientSelect?filter='+search;
                axios.get(url).then(function(response) {
                    var responser = response.data;
                    me.arrayClient = responser.clients;
                    loading(false)
                })
                .catch(function(error) {
                    console.log(error);
                });
            },
            getClientInfo(val){
                let me=this;
                me.loading=true;
                me.client_id=val.id;
                me.name=val.name;
                me.type=val.type;
                me.rif=val.rif;
                me.address=val.address;
                me.retention=val.retention;

            },
            saleSearch(){
                let me=this;
                var url='sale/saleSearchRet?filter='+me.sale_num + '&id='+me.client_id;
                axios.get(url).then(function(response){
                    var response = response.data;
                    me.arraySale = response.sales;
                    console.log(me.arraySale);

                    if (me.arraySale.length>0) {
                        me.sale_id = me.arraySale[0]['id'];
                        me.sale_num = me.arraySale[0]['sale_num'];
                        me.voucher = me.arraySale[0]['voucher'];
                        me.totals = me.arraySale[0]['totals'];
                        me.tax = me.arraySale[0]['tax'];
                    } else {
                        me.sale_num = 'El nro. factura no existe o no pertenece al cliente';
                        me.sale_id = 0;
                    }
                })
                .catch(function (error) {
                        console.log(error);
                });
            },
            addDetail(){
                let me = this;

                if(me.find(me.sale_id)){
                    Swal.fire({
                    type: 'error',
                    title: 'Error...',
                    text: 'El Artículo ya se encuentra agregado!',
                    });
                }else {
                    me.arrayDetailr.push({
                        sale_id: me.sale_id,
                        sale_num: me.sale_num,
                        voucher: me.voucher,
                        totals: me.totals,
                        tax: me.tax
                    });
                    me.sale_id=0;
                    me.sale_num='';
                    me.voucher='';
                    me.totals=0;
                    me.tax=0.0;
                }
            },
            find(id){
                var sw=0;
                for(var i=0; i<this.arrayDetailr.length; i++){
                    if (this.arrayDetailr[i].sale_id == id) {
                        sw=true;
                    }
                }
                return sw;
            },
            hideRet(){
                this.list=1;
            },
            pdfRet(id){
                /*window.open('https://bacoop.com/laravel/public/sale/pdf/'+ id + ','+ '_blank');*/
                window.open('http://localhost:8080/sistema1/public/sale/pdf/'+ id + ','+ '_blank');
            },
            changePage(page, search, criterion){
                let me = this;
                // actualiza la pagina 
                me.pagination.current_page = page;
                // envia la peticion para visualizar la data de esa pagina
                me.listSale(page, search, criterion);
            },
            validateSale(){
                let me=this;
                me.errorSmsR=0;
                me.errorSmsListR =[];

                var prod;

                me.arrayDetailr.map(function(x){
                    if (x.quantity>x.stock) {
                        prod=x.sale + " con Stock insuficiente";
                        me.errorSmsListR.push(prod);
                    }
                })

                if (me.client_id==0) me.errorSmsListR.push("Por favor Seleccione un cliente");

                if (me.voucher_num == 0) me.errorSmsListR.push("Ingrese un numero de Factura o nota de crédito");

                if (me.arrayDetailr.length<=0) me.errorSmsListR.push("Por favor ingrese productos a la compra");

                if (!me.tax) me.errorSmsListR.push("ingrese un impuesto valido");

                if (me.arrayDetailr.length<=0) me.errorSmsListR.push("Ingrese productos");



                if (me.errorSmsListR.length) me.errorSmsR = 1;
                Swal.fire({
                    confirmButtonText: 'Aceptar!',
                    confirmButtonClass: 'btn btn-danger',
                    confirmButtonColor: '#3085d6',
                    html: `${this.errorSmsListR.map( er =>`<br><br>${er}`)}`,
                    showCancelButton: false
                });
                return this.errorSms;
            },
            closeModal(){
                this.modal=0;
            },
            openModalr(){
                this.arraySale=[];
                this.titleModal    = 'Seleccione una o mas Facturas';
                this.modal         = 1;
            },
            desactiveRet(id){
                Swal.fire({
                    title: 'Esta seguro de anular esta venta?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar!',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass:'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        let me=this;

                        axios.put('sale/desactive', {
                            'id': id
                        }).then(function (response){
                           me.listRetention(1,'','voucher_num');
                            Swal.fire(
                                'Anulado!',
                                'La venta ha sido anulada con éxito.',
                                'Aceptar'
                                )
                        }) .catch(function (error) {
                            console.log(error);
                        });
                    } else if (
                        result.dismiss === swal.DismissReason.cancel
                        ){

                    }
                })
            }
        },
        mounted() {
            this.listRetention(1,this.search,this.name);
        }
    };
</script>

<style type="text/css">
    .modal-content{
        margin-top: 1vh;
        width: 100% !important;
        position: absolute !important;
    }
    .show {
        display: list-item !important;
        opacity: 1 !important;
        position: absolute;
        background-color: #3c29297a !important; 
    }
    .div-error{
        display: flex;
        justify-content: center;

    }
    .upper{
        text-transform: uppercase;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px) {
        .btn-add {
            margin-top: 2rem;
        }    
    }
    

</style> 