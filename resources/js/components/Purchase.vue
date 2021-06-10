 <template>
    <main class="main" :class="dim == 1 ? 'blur' : '' ">
       <ol class="breadcrumb">
          <li class="breadcrumb-item">Inicio</li>
          <li class="breadcrumb-item">
            <a href="#">Dilia Software</a>
          </li>
          <li class="breadcrumb-item active"> Compras&nbsp;&nbsp;<i class="fa fa-cart-plus"></i></li>
          <!-- Breadcrumb Menu-->
          <li class="breadcrumb-menu d-md-down-none">

          </li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header pb-1">
                    <button type="button" class="float-xl-right btn btn-outline-primary" @click="showCreditForm('debit')">
                        <p class="h5"><i class="fa fa-plus fa-fw"></i> &nbsp; Nueva Nota de Debito</p>
                    </button>
                    <button type="button" class="float-xl-right btn btn-outline-danger" @click="showCreditForm('credit')">
                        <p class="h5"><i class="fa fa-plus fa-fw"></i> &nbsp; Nueva Nota de Credito</p>
                    </button>

                    <button type="button" class="float-xl-left btn btn-outline-success" @click="showDetail()">
                        <p class="h5"> <i class="fa fa-cart-plus fa-fw"> </i>&nbsp; Ingresar Compra</p>
                    </button>
                </div>
                <!-- litado registros -->
                <template v-if="list==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterion">
                                      <option value="voucher_num">Número </option>
                                      <option value="date">Fecha</option>
                                    </select>
                                    <input v-if="criterion == 'voucher_num'" type="text" v-model="search" @keyup.enter="listPurchase(1,search,criterion)" class="form-control" placeholder="Buscar">
                                    <input v-if="criterion == 'date'" type="date" v-model="search" @keyup.enter="listPurchase(1,search,criterion)" class="form-control" placeholder="Buscar">
                                    <button type="submit" @click="listPurchase(1,search,criterion)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="box-header">
                                <center><h3 class="box-title">Listado de Compras</h3></center>
                            </div>  <br><hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Nombre Usuario</th>
                                        <th>Proveedor</th>
                                        <th>Tipo de Documento</th>
                                        <th>Número</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="purchase in arrayPurchase" :key="purchase.id">
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm" @click="showPurchase(purchase.id)">
                                              Detalles
                                            </button> &nbsp;
                                        </td>
                                        <td v-text="purchase.user"></td>
                                        <td v-text="purchase.name"></td>

                                        <td v-if="purchase.voucher=='bill'">Factura</td>
                                        <td v-else-if="purchase.voucher=='debit'">Nota de Debito</td>
                                        <td v-else-if="purchase.voucher=='credit'">Nota de Crédito</td>

                                        <td v-text="purchase.voucher_num"></td>
                                        <td v-text="purchase.date"></td>
                                        <td >{{moneyFormat(purchase.total)}}</td>
                                        <td v-text="purchase.status"></td>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Proveedor(*)</label>
                                    <v-select  @search="providerSelect" label="name" :options="arrayProvider"
                                    placeholder="Buscar Proveedor"
                                    @input="getProviderInfo"
                                    >

                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Ingrese Fecha de Factura</label>
                                    <input type="date" class="form-control" v-model="date">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo comprobante(*)</label>
                                    <select class="form-control" v-model="voucher">
                                        <option value="0">Seleccione</option>
                                        <option value="bill" default>Factura</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Documento</label>
                                    <input type="text" class="form-control" v-model="voucher_serie" placeholder="000x" name="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Documento(*)</label>
                                    <input type="text" class="form-control" v-model="voucher_num" placeholder="000x" name="">
                                </div>
                            </div>

                        </div>
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Articulo <span style="color:red;" v-show="product_id==0">(*Seleccione)</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="code" @keyup.enter="productSearch()" placeholder="Ingrese código Producto" name="">
                                        <button @click="openModal()" class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="product" name="">
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
                                            <th>Sub-total</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetail.length">
                                        <tr v-for="(detail, index) in arrayDetail" :key="detail.id">
                                            <td>
                                                <button @click="deleteDetail(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detail.product"></td>
                                            <td>
                                                <input v-model="detail.price" type="number" class="form-control" name="">
                                            </td>
                                            <td>
                                                <input v-model="detail.quantity" type="number"  class="form-control" name="">
                                            </td>
                                            <td>{{ moneyFormat(detail.price*detail.quantity) }} </td>
                                        </tr>
                                        <tr style="background-color: #CEECFS;">
                                            <td colspan="4" align="right"><strong>Total Parcial: </strong></td>
                                            <td>{{ moneyFormat(totalPartial=(calculateTotalPartial)) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECFS;">
                                            <td colspan="4" align="right"><strong>Total Impuesto: </strong></td>
                                            <td>{{ moneyFormat(totalTax=(calcTax)) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECFS;">
                                            <td colspan="4" align="right"><strong>Exento: </strong></td>
                                            <td>{{ moneyFormat(exempt=(calcExempt)) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECFS;">
                                            <td colspan="4" align="right"><strong>Total a Pagar: </strong></td>
                                            <td>{{ moneyFormat(total=(calculateTotal)) }}</td>
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
                                <button type="button" class="btn btn-primary" @click="registerPurchase()">Registrar Compra</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- Fin panel -->

                <!-- Panel de vista de compras -->
                <template v-else-if="list==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for=""><h6>Proveedor</h6></label>
                                    <h3><p v-text="provider"></p></h3>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for=""><h6>Impuesto</h6></label>
                                <h3><p v-text="tax"></p></h3>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><h6>Tipo comprobante</h6></label>
                                   <h3> <p v-if="voucher=='bill'">Factura</p>
                                    <p v-else-if="voucher=='debit'">Nota de Debito</p>
                                    <p v-else-if="voucher=='credit'">Nota de crédito</p></h3>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><h6>Nro Control</h6></label>
                                    <h3><p v-text="voucher_serie"></p></h3>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><h6>Número Comprobante</h6></label>
                                    <h3><p v-text="voucher_num"></p></h3>
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
                                            <th>Sub-Total</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetail.length">
                                        <tr v-for="detail in arrayDetail" :key="detail.id">

                                            <td v-text="detail.product" ></td>
                                            <td>{{ moneyFormat(detail.price) }} </td>
                                            <td v-text="detail.quantity" ></td>
                                            <td>{{ moneyFormat(detail.price*detail.quantity) }} </td>
                                        </tr>
                                        <tr style="background-color: #CEECFS;">
                                            <td colspan="3" align="right"><strong>Total Parcial: </strong></td>
                                            <td>{{ moneyFormat(totalPartial=(total-tax_mount)) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECFS;">
                                            <td colspan="3" align="right"><strong>Total Impuesto: </strong></td>
                                            <td>{{ moneyFormat(tax_mount) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECFS;">
                                            <td colspan="3" align="right"><strong>Total Exento: </strong></td>
                                            <td>{{ moneyFormat(exempt) }}</td>
                                        </tr>
                                        <tr style="background-color: #CEECFS;">
                                            <td colspan="3" align="right"><strong>Total a Pagar: </strong></td>
                                            <td>{{ moneyFormat(total) }}</td>
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
                                <template  v-if="status=='Registrado'">
                                    <button type="button" @click="desactivePurchase(purchase_id)" class="btn btn-danger btn-sm" >Anular</button>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- Fin panel vistas compras -->

                <!-- Panel de creacion notas de credito -->
                <template v-else-if="list==4">
                    <div class="card-body">
                        <div class="modal-header">
                            <center>
                                <h3 class="box-title" v-if="voucher == 'credit'">Crear Nota de Credito</h3>
                                <h3 class="box-title" v-else>Crear Nota de Debito</h3>
                            </center>
                            <button type="button" class="close btn btn-outline-light" @click="hideDetail()" aria-label="Close"><i class="fa fa-times fa-lg" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Proveedor(*) <span class="pl-4" style="color:red;" v-show="!provider_id"> &nbsp;Selecione un Proveedor</span></label>
                                    <v-select  @search="providerSelect" label="name" :options="arrayProvider"
                                    placeholder="Buscar Proveedor"
                                    @input="getProviderInfo"
                                    >
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Ingrese Fecha de Nota <span class="pl-4" style="color:red;" v-show="!note.date"></span></label>
                                    <input type="date" class="form-control" v-model="note.date">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nº de Control</label>
                                    <input type="text" class="form-control" v-model="note.voucher_serie" placeholder="0000-0" name="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>N° Nota  <span class="pl-4" style="color:red;" v-show="!note.voucher_num">Agregue numero de Nota</span></label>
                                    <input type="text" class="form-control" v-model="note.voucher_num" name="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><h6>Seleccion de Factura</h6>  <span style="color:red;" v-show="!purchase_num"> &nbsp;&nbsp;Agregue una factura</span></label>
                                    <div class="form-inline">
                                        <button @click="openModalInvoice()" class="btn btn-primary">Buscar&nbsp; Factura &nbsp;&nbsp;<i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>N° de Factura</th>
                                            <th>Iva</th>
                                            <th>Monto Factura</th>
                                            <th>Monto Iva Factura</th>
                                            <th>Monto Exento Factura</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="purchase_num !=''">
                                        <tr >
                                            <td>
                                                <button @click="deleteDetailInvoice()" type="button" class="btn btn-danger btn-sm"><i class="fa fa-window-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="purchase_num"></td>
                                            <td>{{ moneyFormat(tax) }} </td>
                                            <td>{{ moneyFormat(total) }} </td>
                                            <td>{{ moneyFormat(tax_mount) }} </td>
                                            <td>
                                                <span style="color:gray;" v-show="exempt==''">0</span>
                                            </td>
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
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>
                                                <span v-if="voucher == 'credit'">Nro. de Nota de Credito</span>
                                                <span v-else>Nro. de Nota de Debito</span>
                                            </th>
                                            <th>Monto </th>
                                            <th>Monto Iva</th>
                                            <th>Exento </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td v-text="note.voucher_num"></td>
                                            <td>
                                                <input v-model="note.total" type="number" class="form-control">
                                            </td>
                                            <td>
                                                <input v-model="note.tax_mount" type="number" class="form-control" name="">
                                            </td>
                                            <td>
                                                <input v-model="note.exempt" type="number" class="form-control" name="">
                                            </td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger" @click="hideDetail()">Cerrar</button>
                                <button v-if="actionType==1" type="button" class="btn btn-primary" @click="registerNote(note)">
                                    <span v-if="voucher == 'credit'">Registrar Nota de Credito</span>
                                    <span v-else>Registrar Nota de Debito</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <!-- Fin panel -->

            </div>
            <!-- Fin vistas -->
        </div>

        <!--Inicio del modal agregar Factura-->
        <div class="modal fade" tabindex="-1" :class="{'show' : modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                    <select class="form-control col-md-3" v-model="criteryS">
                                      <option value="date">Fecha de Registro</option>
                                      <option default value="voucher_num">N° de Factura</option>
                                    </select>
                                    <input v-if="criteryS == 'voucher_num'" type="text" v-model="searchP" @keyup.enter="searchPurchase(1,searchP,criteryS)" class="form-control" placeholder="Numero de Factura">
                                    <input v-if="criteryS == 'date'" type="date" v-model="searchP" @keyup.enter="searchPurchase(1,searchP,criteryS)" class="form-control" placeholder="Fecha de factura">
                                    <button type="submit" @click="searchPurchase(1,searchP,criteryS)" class="btn btn-primary"><i class="fa fa-search"></i> search</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>N° de Factura</th>
                                        <th>Monto</th>
                                        <th>I.V.A.</th>
                                        <th>Exento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="invoice in arrayPurchase" :key="invoice.id">
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm" @click="addDetailNote(invoice)">
                                              <i class="icon-check"></i>
                                            </button>
                                        </td>
                                        <td v-text="invoice.purchase_num"></td>
                                        <td >{{moneyFormat(invoice.totalp)}}</td>
                                        <td >{{moneyFormat(invoice.tax_mount)}}</td>
                                        <td >{{moneyFormat(invoice.exempt)}}</td>
                                    </tr>

                                </tbody>
                            </table>
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="changePageMonalNotes(pagination.current_page -1, search, criterion)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActive ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="changePageMonalNotes(page, search, criterion)" v-text="page"></a>
                                    </li>

                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="changePageMonalNotes(pagination.current_page +1, search, criterion)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->

        <!--Inicio del modal agregar Productos-->
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
                                    <select class="form-control col-md-3" v-model="criteryP">
                                      <option value="name">Nombre</option>
                                      <option value="code">Codigo</option>
                                    </select>

                                    <input type="text" v-model="search" @keyup.enter="listProduct(1,searchP,criteryP)" class="form-control" placeholder="Ingrese datos a Buscar">
                                    <button type="submit" @click="listProduct(1,searchP,criteryP)" class="btn btn-primary"><i class="fa fa-search"></i> search</button>
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
                                        <th>Stock</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="product in arrayProduct" :key="product.id">
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm" @click="addDetailModal(product)">
                                              <i class="icon-check"></i>
                                            </button>
                                        </td>
                                        <td v-text="product.code"></td>
                                        <td v-text="product.name"></td>
                                        <td v-text="product.category_name"></td>
                                        <td v-text="product.stock"></td>
                                        <td>
                                            <div v-if="product.condition">
                                                <span class="badge badge-success">Activo</span>
                                            </div>

                                            <div v-else>
                                                <span class="badge badge-secondary">Inactivo</span>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="changePageModal(pagination.current_page -1, search, criterion)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActive ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="changePageModal(page, search, criterion)" v-text="page"></a>
                                    </li>

                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="changePageModal(pagination.current_page +1, search, criterion)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                        <button v-if="actionType==1" type="button" class="btn btn-primary" @click="registerPurchase()">Guardar</button>
                        <button v-if="actionType==2" type="button" class="btn btn-primary" @click="updatePurchase()">Actualizar</button>
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
                purchase_id : 0,
                product_id : 0,
                user_id : 0,
                provider_id : 0,
                provider: '',
                nombre: '',
                voucher: 'bill',
                voucher_num : '',
                purchase_num : '',
                voucher_serie : '',
                note:{},
                doc_type : '',
                date : '',
                arrayProduct : [],
                arrayPurchase : [],
                arrayDetail : [],
                arrayProvider : [],
                list : 1,
                code : '',
                product : '',
                tax : 0.0,
                taxp: 0.0,
                tax_mount:0.0,
                price : 0,
                quantity : 0,
                total: 0.0,
                exempt: 0.0,
                totalTax: 0.0,
                totalPartial: 0.0,
                totalExempt: 0.0,
                modal2 : 0,
                modal : 0,
                detail : {},
                search:{},
                modalInvoice : 0,
                titleModal : '',
                actionType : 0,
                errorSmsP : 0,
                errorSmsListP : [],
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
                criteryP: 'name',
                criteryS: 'voucher_num',
                searchP: '',
                dim: 0
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
            // CALCULA EL TOTAL DEL MONTO DE LA FACTURA
            calculateTotalPartial: function(){
                var result=0.0;
                for (var i = 0; i <this.arrayDetail.length; i++) {
                    result = result +(this.arrayDetail[i].price*this.arrayDetail[i].quantity)
                }
                return result;
            },
            // FUNCIÓN PARA CALCULAR EL IVA
            calcTax: function (){
                var result2 = 0.0;
                var taxp = 0.0;
                var divisor = 0.0;
                for (var i = 0; i < this.arrayDetail.length; i++) {
                    if (this.arrayDetail[i].tax > 0) {
                        divisor = (this.arrayDetail[i].tax/100);

                        result2 = result2 +((this.arrayDetail[i].price*this.arrayDetail[i].quantity)*divisor);
                        this.taxp = this.arrayDetail[i].tax;
                    }
                }
                // console.log(divisor);
                return result2;
            },
            // FUNCIÓN PARA CALCULAR EL MONTO EXENTO
            calcExempt: function (){
                var result3 = 0.0;
                for (var i = 0; i < this.arrayDetail.length; i++) {
                    if (this.arrayDetail[i].tax <= 0) {
                        result3 = result3 +(this.arrayDetail[i].price*this.arrayDetail[i].quantity)
                    }
                }
                return result3;
            },
            // CALCULAR EL TOTAL DE LA FACTURA
            calculateTotal: function(){
                return parseFloat(this.totalTax) + parseFloat(this.totalPartial);
            }

        },
        methods : {
            listPurchase (page,search,criterion){

                let me=this;

                var url='purchase?page=' + page + '&search=' + search + '&criterion=' + criterion;
                axios.get(url).then(function(response) {
                    var response = response.data;
                     me.arrayPurchase = response.purchases.data;
                     me.pagination = response.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            showPurchase(id){
                let me=this;
                me.list=2;

                //obtener los detalles de la compra
                var arrayPurchaseTemp=[];

                var url= 'purchase/getHeader?id='+id;
                axios.get(url).then(function(response) {
                    var response = response.data;
                    me.arrayPurchaseTemp = response.purchase;
                    me.purchase_id =id;
                    me.provider = me.arrayPurchaseTemp[0]['name'];
                    me.voucher = me.arrayPurchaseTemp[0]['voucher'];
                    me.voucher_serie = me.arrayPurchaseTemp[0]['voucher_serie'];
                    me.voucher_num = me.arrayPurchaseTemp[0]['voucher_num'];
                    me.tax_mount = me.arrayPurchaseTemp[0]['tax_mount'];
                    me.total = me.arrayPurchaseTemp[0]['total'];
                    me.exempt = me.arrayPurchaseTemp[0]['exempt'];
                    me.status = me.arrayPurchaseTemp[0]['status'];

                })
                .catch(function (error) {
                    console.log(error);
                });

                // obtener los datos de los detalles de la compra

                var urld= 'purchase/getDetail?id='+id;
                axios.get(urld).then(function(response) {
                    var response = response.data;
                    me.arrayDetail = response.details;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            providerSelect(search,loading){
                let me=this;
                loading(true)

                var url= 'provider/providerSelect?filter='+search;
                axios.get(url).then(function(response) {
                    var response = response.data;
                    me.arrayProvider = response.providers;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getProviderInfo(val1){

                let me=this;
                me.loading = true;
                me.provider_id = val1.id;
            },
            productSearch(){
                let me=this;
                var url='product/productSearch?filter=' + me.code;
                axios.get(url).then(function(response){
                    var response = response.data;
                    me.arrayProduct = response.products;
                    if (me.arrayProduct.length>0) {
                        me.product= me.arrayProduct[0]['name'];
                        me.product_id= me.arrayProduct[0]['id'];
                    }else {
                        me.product = 'No existe le Producto';
                        me.product_id =0;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            changePage(page, search, criterion){
                let me = this;
                // actualiza la pagina
                me.pagination.current_page = page;
                // envia la peticion para visualizar la data de esa pagina
                me.listPurchase(page, search, criterion);
            },
            changePageModal(page, search, criterion){
                let me = this;
                // actualiza la pagina
                me.pagination.current_page = page;
                // envia la peticion para visualizar la data de esa pagina
                me.listProduct(page, search, criterion);
            },
            changePageMonalNotes(page, search, criterion){
                let me = this;
                // actualiza la pagina
                me.pagination.current_page = page;
                // envia la peticion para visualizar la data de esa pagina
                me.searchPurchase(page, search, criterion);
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
                        me.arrayDetail.push({
                            product_id: me.product_id,
                            product: me.product,
                            quantity: me.quantity,
                            price: me.price,
                            tax: me.tax
                        });
                        me.code='';
                        me.product_id=0;
                        me.product="";
                        me.quantity=0;
                        me.price=0;
                        me.tax=0.0;
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
                        product: data['name'],
                        tax: data['tax'],
                        quantity: 1,
                        price: 1
                    });
                }

            },
            listProduct (page,searchP,criteryP){
                let me=this;

                var url='product/listProduct?page='+ page + '&search=' + searchP + '&critery=' + criteryP;
                axios.get(url).then(function(response) {
                    var response = response.data;
                     me.arrayProduct = response.products.data;
                     me.pagination = response.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registerPurchase (){
                if (this.validatePurchase()) {
                    return;
                };
                let me=this;
                me.dim=1;
                axios.post('purchase/register', {
                    'provider_id':this.provider_id,
                    'user_id': this.user_id,
                    'product_id': this.product_id,
                    'date': this.date,
                    'voucher': this.voucher,
                    'voucher_num': this.voucher_num,
                    'voucher_serie': this.voucher_serie,
                    'tax': this.taxp,
                    'tax_mount': this.totalTax,
                    'exempt': this.exempt,
                    'total': this.total,
                    'data': this.arrayDetail

                }).then(function(response) {
                    me.list=1;
                    me.listPurchase(1,'','voucher_num');
                    me.provider_id=0;
                    me.vouche="bill";
                    me.user_id=0;
                    me.product_id=0;
                    me.voucher_num='';
                    me.voucher_serie='';
                    me.tax=0.0;
                    me.taxp=0.0;
                    me.totalTax=0.0;
                    me.tax_mount=0.0;
                    me.total=0.0;
                    me.product='';
                    me.exempt = 0.0;
                    me.quantity=0;
                    me.price=0;
                    me.arrayDetail=[];
                    me.dim=0;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validatePurchase(){
                this.errorSmsP=0;
                this.errorSmsListP =[];

                if (!this.provider_id) this.errorSmsListP.push("Por favor Selecione un cliente");

                if (this.voucher_num == 0) this.errorSmsListP.push("Ingrese un Número de Factura o nota de crédito");

                if (this.arrayDetail.length<=0) this.errorSmsListP.push("Por favor ingrese productos a la compra");

                if (this.errorSmsListP.length) this.errorSmsP = 1;
                /*console.log(this.errorSmsListP);*/

                if (this.errorSmsListP.length >= 1) {
                        Swal.fire({
                    confirmButtonText: 'Aceptar!',
                    confirmButtonClass: 'btn btn-danger',
                    confirmButtonColor: '#3085d6',
                    html: `${this.errorSmsListP.map( er =>`<br><br>${er}`)}`,
                    showCancelButton: false
                    });
                };
                return this.errorSmsP;

            },
            showCreditForm(t){
                let me=this;
                me.voucher = t;
                me.doc_type = ("debit"==t) ? "02" : "03";
                me.list = 4;
                me.actionType = 1;
                me.user_id=0;
                me.voucher_num='';
                me.voucher_serie='';
                me.tax='';
                me.tax_mount=0.0;
                me.total=0.0;
                me.description='';
                me.note={};
                me.purchase_num='';

            },
            validateSearchProvider(){
                let me=this;
                me.errorSearchProvider = 0;
                me.errorSearchProviderList=[];

                var prod;
                if(me.provider_id == 0) {
                    me.errorSearchProvider = 1;
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        confirmButtonText: '<i class="fa fa-thumbs-up"></i> Aceptar!',
                        confirmButtonAriaLabel: 'Thumbs up, great!',
                        confirmButtonClass: 'btn btn-danger',
                        confirmButtonColor: '#3085d6',
                        text: 'Por favor seleccione un proveedor antes de realizas la busqueda',
                        showCancelButton: false
                    });
                };
                return me.errorSearchProvider;
            },
            searchPurchase (page,searchP,criteryP){
                let me=this;

                var url='purchase/search?page='+ page + '&search=' + searchP + '&critery=' + criteryP + '&id=' + me.provider_id;
                axios.get(url).then(function(response) {
                    var response = response.data;
                    me.arrayPurchase = response.purchases.data;
                    me.pagination = response.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            addDetailNote(data){
                let me = this;
                me.purchase_id =data.id;
                me.purchase_num = data.purchase_num;
                me.tax_mount = data.tax_mount;
                me.total = data.totalp;
                me.exempt = data.exempt;
                me.tax = data.tax;
                me.modal2=0;

            },
            deleteDetailInvoice(){
                let me=this;
                me.purchase_id =0;
                me.purchase_num = '';
                me.tax_mount = 0.0;
                me.total = 0.0;
                me.exempt = 0.0;
                me.tax = 0.0;
                me.modal2=0;
            },
            registerNote(note){
                if (this.validateNote()) {
                    return;
                };
                let me=this;
                me.dim=1;
                axios.post('purchase/store-note', {
                    'purchase_id': me.purchase_id,
                    'provider_id': me.provider_id,
                    'voucher' : me.voucher,
                    'doc_type' : me.doc_type,
                    'voucher_num': note.voucher_num,
                    'date': note.date,
                    'note_num': me.purchase_num,
                    'voucher_serie': note.voucher_serie,
                    'tax': me.tax,
                    'tax_mount': note.tax_mount,
                    'exempt': note.exempt,
                    'total': note.total

                }).then(function(response) {
                    me.list=1;
                    me.listPurchase(1,'','voucher_num');
                    me.provider_id=0;
                    me.vouche="bill";
                    me.doc_type = '';
                    me.user_id=0;
                    me.voucher_num='';
                    me.voucher_serie='';
                    me.tax=0.0;
                    me.tax_mount=0.0;
                    me.total=0.0;
                    me.product='';
                    me.exempt = 0.0;
                    me.dim=0;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validateNote(){
                this.errorSmsP=0;
                this.errorSmsListP =[];

                if (!this.provider_id) this.errorSmsListP.push("Por favor Selecione un Proveedor");
                if (!this.note.date) this.errorSmsListP.push("Ingrese Fecha de Nota de crédito");
                if (!this.note.voucher_serie) this.errorSmsListP.push("Ingrese un Número de CONTROL");
                if (!this.note.voucher_num) this.errorSmsListP.push("Ingrese un Número Nota de crédito");
                if (this.purchase_num == 0) this.errorSmsListP.push("Por Favor selecione una Factura");

                if (!this.note.total) this.errorSmsListP.push("Ingrese Monto de Nota de Credito");
                if (!this.note.tax_mount) this.errorSmsListP.push("Ingrese monto de impuesto");


                if (this.errorSmsListP.length) this.errorSmsP = 1;
                /*console.log(this.errorSmsListP);*/

                if (this.errorSmsListP.length >= 1) {
                        Swal.fire({
                    title:'Hey!! Nos falta(n) Datos',
                    type: 'info',
                    confirmButtonText: 'Aceptar!',
                    confirmButtonClass: 'btn btn-danger',
                    confirmButtonColor: '#3085d6',
                    html: `${this.errorSmsListP.map( er =>`<br><span style="color:red;" class="mb-3"><i class="fa fa-ban pl-3" aria-hidden="true"></i>${er}</span>`)}`,
                    showCancelButton: false
                    });
                };
                return this.errorSmsP;

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
                me.tax=0.0;
                me.total=0.0;
                me.product='';
                me.quantity=0;
                me.price=0;
                me.totalTax = 0.0;
                me.exempt = 0.0;
                me.arrayDetail=[];
            },
            hideDetail(){
                this.list=1;
            },
            closeModal(){
                this.modal=0;
                this.modal2=0;
            },
            openModal(){
                this.arrayProduct=[];
                this.titleModal    = 'Seleccione uno o mas Productos';
                this.modal         = 1;
            },
            openModalInvoice(){
                if (this.validateSearchProvider()) {
                    return;
                }
                this.arrayPurchase =[];
                this.titleModal     = 'Seleccione la Factura';
                this.modal2  =1;
            },
            desactivePurchase(purshase_id){
                Swal.fire({
                    title: 'Esta seguro de anular esta factura?',
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

                        axios.post('purchase/desactive', {
                            'id': purshase_id
                        }).then(function (response){
                           me.listPurchase(1,'','voucher_num');
                           me.list =1;
                            Swal.fire(
                                'Anulado!',
                                'La compra ha sido anulada con éxito.',
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
            },
            moneyFormat(price){
                const format = {style: 'currency', currency: 'VES'};
                const formatter = new Intl.NumberFormat('es-Ve', format);
                return formatter.format(price);
            },
        },
        mounted() {
            this.listPurchase(1,this.search,this.name);
        }
    };
</script>
