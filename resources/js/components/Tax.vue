<template>
	<main class="main">
		            <!-- Breadcrumb -->
		            <ol class="breadcrumb">
		                <li class="breadcrumb-item"><a href="./dashboard">Escritorio</a></li>
		            </ol>
		            <div class="container-fluid">
		                <!-- Ejemplo de tabla Listado -->
		                <div style="width: fit-content" class=" card">
		                    <div  class="card-header">
		                        <h5>I.v.a. Actual para ventas, compras y retenciones</h5>
		                    </div>
		                    <div class="card-body" >
                                <div class="form-group row ">
                                    <div class="col-12" v-for="taxes in arrayTax" :key="taxes.id" v-if="arrayTax.length">

                                        <div class="form-group">
                                            <h3 for="tax">I.V.A. Actual</h3>
                                            <h4 class="upper" v-text="taxes.tax"></h4>
                                        </div>

                                    </div>
                                    <hr>
                                    
                                </div>
                                <div class="card-body">
                                    <div class="col-12" >

                                        <div class="form-group row col-md-6">
                                            <p for="tax" class="form-group">Ingrese <b>%</b> I.V.A.</p>
                                            <br>
                                            <input type="number" v-model="tax" placeholder="Ingreve valor de i.v.a.">
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <button type="button" v-if="arrayTax.length" class="btn btn-primary" @click="updateTax()">Actualizar I.v.a.</button>
                                                <button type="button" v-else class="btn btn-primary" @click="registerTax()">Guardar</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
		                    </div>
		                </div>
                        
		                <!-- Fin ejemplo de tabla mostar -->
		            </div>
		            <!--Inicio del modal agregar/actualizar-->
		            
	</main>
</template>
	  		
<script>
	export default {
		data (){
			return{
			id: 0,
			tax: 0,
            arrayTax: [],
            errorTax: 0,
            
			}

		},
		computed: {
			
		},
		methods: {
			listTax (){
				let me = this;

				var url='tax';
                axios.get(url).then(function(response) {
                    me.arrayTax = response.data; 
                    console.log(me.arrayTax);
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registerTax (){
                if (this.validateTax()) {
                    return;
                }
                let me = this;

                axios.post('tax/register', {
                    'tax':this.tax
                }).then(function(response){
                    me.listTax();
                }).catch(function(error){
                    console.log(error);
                });
            },
            
            updateTax(){
            	if (this.validateTax()){
            		return;
            	}
            	let me = this;

            	axios.put('tax/update', {
            		'id':this.id,
            		'tax': this.tax

            	}).then(function(response) {
                    me.listTax();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            validateTax(){
            	this.errorTax=0;
                this.errorTaxList =[];

                if(!this.tax) this.errorTaxList.push("Ingrese un impuesto valido");
                

                if (this.errorTaxList.length) this.errorTax = 1;
                    if (this.errorTaxList.length >= 1) {
                        Swal.fire({
                    confirmButtonText: 'Aceptar!',
                    confirmButtonClass: 'btn btn-danger',
                    confirmButtonColor: '#3085d6',
                    html: `${this.errorTaxList.map( er =>`<br><br>${er}`)}`,
                    showCancelButton: false
                    });
                };
                return this.errorTax;
            }
            
		},
		mounted(){
			this.listTax();
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
    .upper{
        text-transform: uppercase;
    }

</style>