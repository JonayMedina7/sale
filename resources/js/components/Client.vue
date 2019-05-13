 <template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
                
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Cliente
                        <button type="button" class="btn btn-secondary" @click="openModal('client','register')">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterion">
                                      <option value="name">Nombre</option>
                                      <option value="rif">Rif</option>
                                    </select>
                                    <input type="number" v-model="search" @keyup.enter="listClient(1,search,criterion)" class="form-control" placeholder="Texto a Buscar">
                                    <button type="submit" @click="listClient(1,search,criterion)" class="btn btn-primary"><i class="fa fa-search"></i> search</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Rif</th>
                                    <th>Razón Social</th>
                                    <th>Teléfono</th>
                                    <th>Correo Electrónico</th>
                                    <th>Agente de retención</th>
                                    <th>Dirección</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="client in arrayClient" :key="client.id">
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" @click="openModal('client','update', client)">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <button v-if="client.condition" type="button" @click="openModal('client','desactive',client)" class="btn btn-danger btn-sm" >
                                          <i class="icon-trash"></i>
                                        </button>
                                        <button v-else type="button" @click="openModal('client','active',client)" class="btn btn-success btn-sm" >
                                          <i class="icon-check"></i>
                                        </button>
                                    </td>
                                    <td v-text="client.type + '-' + client.rif"></td>
                                    <td v-text="client.name"></td>
                                    <td v-text="client.phone"></td>
                                    <td v-text="client.email"></td>
                                    <div >
                                    	<td v-if="client.retention>=75" v-text="client.retention + '%' "></td>
										<td v-else>No es Agente de retención</td>
									</div>
                                    <td v-text="client.address"></td>
                                    <td>
                                        <div v-if="client.condition">
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
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="rif">Rif o C.I.</label>
                                    <div class="col-md-2">
                                        <select class="form-control" v-model="type">

											<option value="j" default>J</option>
											<option value="g" >G</option>
											<option value="v" >V</option>
											<option value="c" >Cedula</option>

                                        </select>
								
                                    </div>
									<div class="col-md-5">
                                        <input type="number" v-model="rif" class="form-control" maxlength="9" minlength="6">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Razon Social</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="name" class="form-control" placeholder="Razon Social">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="phone">Numero teléfonico</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="phone" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="stock">Correo electronico</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="email" class="form-control" placeholder="">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="description">Dirección</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="address" class="form-control" placeholder="Ingrese dirección">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="retention">Es Agente de Retención?</label>
                                    <div class="col-md-5">
                                        <select class="form-control" v-model="retention" required>
											<option value="0" >No</option>
											<option value="75" >75%</option>
											<option value="100" >100%</option>
                                        </select>
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button v-if="actionType==1" type="button" class="btn btn-primary" @click="registerClient()">Guardar</button>
                            <button v-if="actionType==2" type="button" class="btn btn-primary" @click="updateClient()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
             <div class="modal fade" tabindex="-1" :class="{'show' : modal1}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-danger" role="document">
                    <div v-if="condition" class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Desactivar Cliente</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Estas seguro de desactivar al Cliente?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cancelar</button>
                            <button type="button" class="btn btn-danger" @click="desactiveClient()">Desactivar</button>
                            
                        </div>
                    </div>
                    <div v-else class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Activar Cliente</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Pulse Activar para activar al Cliente</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cancelar</button>
                            <button type="button" class="btn btn-success" @click="activeClient()">Activar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- Fin del modal Eliminar -->
            
        </main>
</template>

<script>
    export default {
        data (){
            return {
                client_id : 0,
                type: '',
                rif : 0,
                name : '',
                phone : '',
                email: '',
                retention : 0,
                address : '',
                condition : '',
                type: '',
                arrayClient : [],
                modal1 : 0,
                modal : 0,
                titleModal : '',
                actionType : 0,
                errorSms : 0,
                errorSmsList : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterion : 'name',
                search : ''
            }
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
            }

        },
        methods : {
            listClient (page,search,criterion){
                
                let me=this;

                var url='client?page=' + page + '&search=' + search + '&criterion=' + criterion;
                axios.get(url).then(function(response) {
                    var response = response.data; 
                     me.arrayClient = response.clients.data;
                     me.pagination = response.pagination;
                     console.log(url);
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
                me.listClient(page, search, criterion);

            },
            registerClient (){

                if (this.validateClient()) {
                    return;
                };
                let me=this;
                console.log(this.name);
                axios.post('client/register', {
                    'type':this.type,
                    'rif':this.rif,
                    'name': this.name,
                    'phone': this.stock,
                    'email': this.email,
                    'retention': this.retention,
                    'address': this.address
                    
                    
                }).then(function(response) {
                    me.closeModal();
                    me.listClient(1,'','name');
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            updateClient() {
                if (this.validateClient()) {
                    return;
                };

                let me = this;

                axios.put('client/update', {
                    'id' : this.client_id,
                    'type':this.type,
                    'rif':this.rif,
                    'name': this.name,
                    'phone': this.phone,
                    'email': this.email,
                    'retention': this.retention,
                    'address': this.address
                }).then(function (response){
                    me.closeModal();
                    me.listClient(1,'', 'name');
                }).catch(function (error){
                    console.log(error);
                });
            },
            validateClient(){
                this.errorSms=0;
                this.errorSmsList =[];

                

                if (!this.name) this.errorSmsList.push("El Nombre del producto no puede estar vacio");

                if (!this.email) this.errorSmsList.push("El Correo Eléctronico del Cliente no puede estar vacio");

                if (!this.type) this.errorSmsList.push("Seleccione el tipo de Cliente: J, G, V, Cedula")

                if (!this.rif) this.errorSmsList.push("El Rif del cliente debe ser nuemro y no puede estar vacio");

                if (this.errorSmsList.length) this.errorSms = 1;
                Swal.fire({
                    confirmButtonText: 'Aceptar!',
                    confirmButtonClass: 'btn btn-danger',
                    confirmButtonColor: '#3085d6',
                    html: `${this.errorSmsList.map( er =>`<br><br>${er}`)}`,
                    showCancelButton: false
                });
                return this.errorSms;
            },
            desactiveClient(){
                        let me = this;

                        axios.put('client/desactive', {
                            'id': this.client_id,
                        }).then(function (response) {
                            me.closeModal();
                            me.listClient(1,'', 'name');
                        })
                        .catch(function (error) {
                           console.log(error); 
                        });
            },
            activeClient(){
                let me = this;

                axios.put('client/active', {
                    'id': this.client_id,
                }).then(function (response) {
                    me.closeModal();
                    me.listClient(1,'', 'name');
                }).catch(function (error) {
                    console.log(error); 
                });
            },

            closeModal(){
                this.modal=0;
                this.modal1=0;
                this.errorSms=0;
                this.titleModal= '';

                this.client_id= 0;
                this.name='';
                this.address='';
                this.retention=0;
                this.rif=0;
                this.phone='';
                this.email='';
                this.type = '';
            },
            openModal(modelo, accion, data = []){
                switch(modelo) {
                    case "client" :
                    {
                        switch(accion){
                            case 'register':
                            {
                                this.titleModal    = 'Registrar Client';
                                this.modal          = 1;
                                this.actionType     = 1;

                                this.name='';
				                this.address='';
				                this.retention=0;
				                this.rif=0;
				                this.phone='';
				                this.email='';
				                this.type='';
                                break;
                            }
                            case 'update':
                            {
                                this.titleModal ='Actualizar Cliente';
                                this.modal      = 1;
                                this.client_id  = data['id'];
                                this.name  		= data['name'];
                                this.phone		=data['phone'];
                                this.Type		=data['type'];
                                this.rif         = data['rif'];
                                this.email         = data['email'];
                                this.address   = data['address'];
                                this.retention = data['retention'];
                                this.actionType   = 2;
                                break;
                            }
                            case 'desactive':
                            {
                                this.modal1 = 1;
                                this.client_id = data['id'];
                                this.condition = data['condition'];
                                break;
                            }
                            case 'active':
                            {
                                this.modal1 = 1;
                                this.client_id = data['id'];
                                break;
                            }
                        }
                    }
                }
                
            }
        },
        mounted() {
            this.listClient(1,this.search,this.name);
        }
    };
</script>

<style type="text/css">
    .modal-content{
        margin-top: 11vh;
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

</style>