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
                        <button type="button" class="btn btn-secondary" @click="showDetail()">
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
                                            <td v-else-if="ret.voucher=='credit'">Nota de Credito</td>
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
                                        <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page -1, search, criterion)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActive ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="changePage(page, search, criterion)" v-text="page"></a>
                                    </li>
                                    
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page +1, search, criterion)">Sig</a>
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
                                        <v-select  @search="clientSelect" label="name" :options="arrayClient"
                                        placeholder="Buscar Cliente"
                                        @input="getClientInfo"
                                        > 
                                            
                                        </v-select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label >Razon Social(*):</label>
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
                                        <label >Direcciòn(*):</label>
                                        <h4><span v-text="address" style="text-transform: uppercase;"> </span></h4>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Impuesto(*)</label>
                                    <input type="text" class="form-control" v-model="tax" name="" >
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tipo comprobante(*)</label>
                                        <select class="form-control" v-model="voucher">
                                            <option value="0">Seleccione</option>
                                            <option value="note">Vale</option>
                                            <option value="bill">Factura</option>
                                            <option value="credit">Nota de Credito</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Numero Comprobante(*)</label>
                                        <input type="text" class="form-control" v-model="voucher_num" placeholder="000x" name="">
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-group row border">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Facturas <span style="color:red;" v-show="product_id==0">(*Seleccione)</span></label>
                                        <div class="form-inline">
                                            <input type="text" class="form-control" v-model="code" @keyup.enter="saleSearch()" placeholder="Ingrese numero de Venta" name="">
                                            <button @click="openModalR()" class="btn btn-primary">...</button>
                                            <input type="text" readonly class="form-control" v-model="sale" name="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Precio <span style="color:red;" v-show="price==0">(*Ingrese precio)</span></label>
                                        <input type="number" step="any" class="form-control" v-model="price" name="">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Diponible</label>
                                        <span class="form-control" v-text="stock"></span>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Cantidad <span style="color:red;" v-show="quantity==0">(*Ingrese cantidad)</span></label>
                                        <input type="number" class="form-control" v-model="quantity" name="">
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
                                                <th>Artículo</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                                <th>Disponibilidad</th>
                                                <th>subTotal</th> 
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetail.length">
                                            <tr v-for="(detail, index) in arrayDetail" :key="detail.id">
                                                <td>
                                                    <button @click="deleteDetail(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td v-text="detail.sale"></td>
                                                <td>
                                                    <input v-model="detail.price" type="number" class="form-control" name="">
                                                </td>
                                                <td>
                                                    <span style="color:red;" v-show="detail.quantity>detail.stock">Disponible: {{ detail.stock }}</span>
                                                    <input v-model="detail.quantity" type="number"  class="form-control" name="">
                                                </td>
                                                <td>
                                                    <span class="form-control" >{{ dispo = detail.stock-detail.quantity }}</span>
                                                </td>
                                                <td>
                                                    {{ detail.price*detail.quantity }}
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
                                                    No hay articulos agredados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary" @click="hideDetail()">Cerrar</button>
                                    <button type="button" class="btn btn-primary" @click="registerRet()">Registrar Venta</button>
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
                                        <p v-text="client"></p>
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
                                        <label>Tipo comprobante</label>

                                        <p v-if="voucher=='bill'">Factura</p>
                                        <p v-else-if="voucher=='note'">Vale</p>
                                        <p v-else-if="voucher=='credit'">Nota de credito</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Serie Comprobante</label>
                                        <p v-text="voucher_serie"></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Numero Comprobante</label>
                                        <p v-text="voucher_num"></p>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="form-group row border">
                                <div class="table-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Artículo</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                                <th>subTotal</th> 
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetail.length">
                                            <tr v-for="detail in arrayDetail" :key="detail.id">
                                                
                                                <td v-text="detail.sale" ></td>
                                                <td v-text="detail.price" ></td>
                                                <td v-text="detail.quantity" ></td>
                                                <td>
                                                    {{ detail.price*detail.quantity }}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECFS;">
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
                                            </tr>
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
                                    <button type="button" class="btn btn-secondary" @click="hideDetail()">Cerrar</button>
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
                client: '',
                voucher_num : '',
                date : '',
                tax : 0,
                total : 0.0,
                status: '',
                sale_num: '',
                totals: '',
                user_id: 0,
                client_id: 0,
                user: '',
                name:'',
                type: '',
                rif: 0,
                arrayRet : [],
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
                for (var i = 0; i <this.arrayDetail.length; i++) {
                    result = result +(this.arrayDetail[i].price*this.arrayDetail[i].quantity)
                }
                return result;
            }

        },
        methods : {
            listRetention (page,search,criterion){
                
                let me=this;

                var url='retencion?page=' + page + '&search=' + search + '&criterion=' + criterion;
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
                me.list=2;

                //obtener los detalles de la compra
                var arrayRetTemp=[];

                var url= 'sale/getHeader?id='+id;
                axios.get(url).then(function(response) {
                    var response = response.data; 
                    me.arrayRetTemp = response.sale;
                    
                    me.client = me.arrayRetTemp[0]['name'];
                    me.user = me.arrayRetTemp[0]['user'];
                    me.voucher = me.arrayRetTemp[0]['voucher'];
                    me.voucher_serie = me.arrayRetTemp[0]['voucher_serie'];
                    me.voucher_num = me.arrayRetTemp[0]['voucher_num'];
                    me.tax = me.arrayRetTemp[0]['tax'];
                    me.total = me.arrayRetTemp[0]['total'];

                })
                .catch(function (error) {
                    console.log(error);
                });
                
                // obtener los datos de los detalles de la compra

                var urld= 'sale/getDetail?id='+id;
                axios.get(urld).then(function(response) {
                    var response = response.data; 
                    me.arrayDetail = response.details;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            clientSelect(search,loading){
                let me=this;
                loading(true)

                var url= 'client/clientSelect?filter='+search;
                axios.get(url).then(function(response) {
                    var response = response.data; 
                    /*console.log(response);*/

                    me.arrayClient = response.clients;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getClientInfo(val1){

                let me=this;
                me.loading = true;
                me.client_id = val1.id;
                me.type = val1.type;
                me.rif = val1.rif;
                me.address = val1.address;
                me.name = val1.name;
            },
            saleSearch(){
                let me=this;
                var url='sale/saleSearch?filter=' + me.code;
                axios.get(url).then(function(response){
                    var response = response.data;
                    me.arraySale = response.products;
                    if (me.arraySale.length>0) {
                        me.sale= me.arraySale[0]['name'];
                        me.product_id= me.arraySale[0]['id'];
                        me.stock= me.arraySale[0]['stock'];
                        me.price= me.arraySale[0]['price_sell'];
                    }else {
                        me.sale = 'No existe le Producto';
                        me.product_id =0;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
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
                me.listRetention(page, search, criterion);
            },
            find(id){
                var sw=0;
                for(var i=0;i<this.arrayDetail.length;i++){
                    if (this.arrayDetail[i].product_id == id) {
                        sw=true;
                    }
                }
                return sw;
            },
            deleteDetail(index){
                let me=this;
                me.arrayDetail.splice(index, 1);
            },
            addDetail(){
                let me = this;

                if ( me.product_id==0 || me.quantity==0 || me.price==0 ) {
                }else {
                    if (me.find(me.product_id)) {
                        Swal.fire({
                            type: 'error',
                            title: 'Error...',
                            text: 'El Artículo ya se encuentra agregado!',
                            })
                            /*alert('Ya se encuentra registrado el producto');*/
                    }else {
                        if (me.quantity>me.stock) {
                            Swal.fire({
                            type: 'error',
                            title: 'Error...',
                            text: 'El stock no es suficiente!',
                            })
                        }else{
                            me.arrayDetail.push({
                        product_id: me.product_id,
                            sale: me.sale,
                            quantity: me.quantity,
                            price: me.price,
                            stock: me.stock
                            });
                            me.code='';
                            me.product_id=0;
                            me.sale="";
                            me.quantity=0;
                            me.price=0;
                            me.stock=0; 
                        }
                    }
                } 
            },

            addDetailModal(data =[]){
                let me = this;

                if (me.find(data['id'])) {
                   Swal.fire({
                        type: 'error',
                        title: 'Error...',
                        text: 'El Artículo ya se encuentra agregado!',
                    })
                        /*alert('Ya se encuentra registrado el producto');*/
                }else {
                    me.arrayDetail.push({
                        product_id: data['id'],
                        sale: data['name'],
                        quantity: 1,
                        price: data['price_sell'],
                        stock: data['stock']
                    });
                }
                
            },
            listProductSale (searchR,criteryR){
                let me=this;

                var url='sale/listProductSale?search=' + searchR + '&critery=' + criteryR;
                axios.get(url).then(function(response) {
                    var response = response.data;
                     me.arraySale = response.products.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registerRet (){
                if (this.validateSale()) {
                    return;
                };
                let me=this;
                
                axios.post('sale/register', {

                    
                    'client_id':this.client_id,
                    'user_id': this.user_id,
                    'product_id': this.product_id,
                    'voucher': this.voucher,
                    'voucher_num': this.voucher_num,
                    'voucher_serie': this.voucher_serie,
                    'tax': this.tax,
                    'total': this.total,
                    'data': this.arrayDetail
                    
                }).then(function(response) {
                    
                    me.list=1;
                    me.listRetention(1,'','voucher_num');
                    me.client_id=0;
                    me.vouche="bill";
                    me.user_id=0;
                    me.product_id=0;
                    me.voucher_num='';
                    me.voucher_serie='';
                    me.tax=0.16;
                    me.total=0.0;
                    me.sale='';
                    me.quantity=0;
                    me.price=0;
                    me.stock=0;
                    me.code='';
                    me.arrayDetail=[];
                    /*window.open('https://bacoop.com/laravel/public/sale/pdf/'+ id + ','+ '_blank');*/
                    window.open('http://localhost:8080/sistema1/public/sale/pdf/'+ response.data.id + ','+ '_blank');

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validateSale(){
                let me=this;
                me.errorSmsR=0;
                me.errorSmsListR =[];

                var prod;

                me.arrayDetail.map(function(x){
                    if (x.quantity>x.stock) {
                        prod=x.sale + " con Stock insuficiente";
                        me.errorSmsListR.push(prod);
                    }
                })

                if (me.client_id==0) me.errorSmsListR.push("Por favor Selecione un cliente");

                if (me.voucher_num == 0) me.errorSmsListR.push("Ingrese un numero de Factura o nota de credito");

                if (me.arrayDetail.length<=0) me.errorSmsListR.push("Por favor ingrese productos a la compra");

                if (!me.tax) me.errorSmsListR.push("ingrese un impuesto valido");

                if (me.arrayDetail.length<=0) me.errorSmsListR.push("Ingrese productos");



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
           
            showDetail(){
                let me=this;
                me.list=0;

                me.provider_id=0;
                me.vouche="bill";
                me.user_id=0;
                me.product_id=0;
                me.voucher_num='';
                me.voucher_serie='';
                me.tax=0.16;
                me.total=0.0;
                me.sale='';
                me.quantity=0;
                me.price=0;
                me.arrayDetail=[];

            },
            hideDetail(){
                this.list=1;
            },
            closeModal(){
                this.modal=0;
            },
            openModalR(){
                this.arraySale=[];
                this.titleModal    = 'Seleccione uno o mas Productos';
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
                                'success'
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