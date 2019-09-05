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
                        <i class="fa fa-align-justify"></i> Ingresos
                        <button type="button" @click="openModal('income','register')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="judgment">
                                      <option value="voucher_type">Tipo de comprobante</option>
                                      <option value="voucher_number">Número de comprobante</option>
                                      <option value="date_hour">Fecha-Hora</option>
                                    </select>
                                    <input type="text" v-model="search" @keyup.enter="listIncome(1,search,judgment)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listIncome(1,search,judgment)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Proveedor</th>
                                        <th>Tipo de comprobante</th>
                                        <th>Serie de comprobante</th>
                                        <th>Número de comprobante</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>Impuesto</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="income in incomeArray" :key="income.id"> 
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm" @click="openModal('income','update',income)"> 
                                            <i class="icon-eye"></i>
                                            </button> &nbsp;
                                            <template v-if="income.status=='Registrado'">
                                                <button class="btn btn-danger btn-sm" type="button"  @click="desactivate(income.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                        </td>
                                        <td v-text="income.user"></td>
                                        <td v-text="income.name"></td>
                                        <td v-text="income.voucher_type"></td>
                                        <td v-text="income.voucher_serie"></td>
                                        <td v-text="income.voucher_number"></td>
                                        <td v-text="income.date_hour"></td>
                                        <td v-text="income.total"></td>
                                        <td v-text="income.tax"></td>
                                        <td v-text="income.status"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1, search, judgment)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="changePage(page, search, judgment)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" @click.prevent="changePage(pagination.current_page + 1, search, judgment)" href="#">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'show': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="modalTitle"></h4>
                            <button type="button" class="close" @click="closeModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="name" class="form-control" placeholder="Nombre de la persona">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo de documento</label>
                                    <div class="col-md-9">
                                        <select v-model="document_type" class="form-control">
                                            <option value="DNI">DNI</option>
                                            <option value="RUC">RUC</option>
                                            <option value="PASS">PASAPORTE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Número de documento</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="document_number" class="form-control" placeholder="Número de documento">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="address" class="form-control" placeholder="Dirección">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Teléfono</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="telephone" class="form-control" placeholder="Teléfono">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Rol (*)</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="rol_id">
                                            <option value="0">Seleccione un rol</option>
                                            <option v-for="rol in rolArray" :key="rol.id" :value="rol.id"  v-text="rol.name"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Usuario (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="user" class="form-control" placeholder="Nombre de usuario">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Contraseña (*)</label>
                                    <div class="col-md-9">
                                        <input type="password" v-model="password" class="form-control" placeholder="Contraseña de acceso">
                                    </div>
                                </div>

                                <div v-show="personError" class="form-group row div-error"> 
                                    <div class="text-center text-error">
                                        <div v-for="error in showPersonMsgError" :key="error" v-text="error">
                                        </div> 
                                    </div> 
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" v-if="actionType==1" @click="registerPerson()">Guardar</button>
                            <button type="button" class="btn btn-primary" v-if="actionType==2" @click="updatePerson()">Actualizar</button>
                        </div>
                    </div>-->
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
    export default {
        data() {
            return {
                income_id: 0,
                supplier_id: 0,
                name: '',
                voucher_type:'',
                voucher_number:'',
                voucher_serie:'',
                tax: 0.16,
                total: 0.0,
                incomeArray: [],
                detailArray: [],
                modal: 0,
                modalTitle: '',
                actionType: 0,
                incomeError: 0,
                showIncomeMsgError: [],
                pagination: 
                {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0
                },
                offset: 3,
                judgment: 'voucher_number',
                search: ''
            }
        },
        computed: {
            isActived: function()
            {
                return this.pagination.current_page;
            },
            pagesNumber: function()
            {
                if(!this.pagination.to)
                {
                    return [];
                }

                var from= this.pagination.current_page-this.offset;
                if(from < 1)
                {
                    from=1;
                }

                var to= from + (this.offset*2)
                if(to >= this.pagination.last_page)
                {
                    to= this.pagination.last_page;
                }

                var pagesArray=[];
                while(from <= to)
                {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods: {
            listIncome(page, search, judgment) 
            {
                let me= this;
                var url= '/incomes?page=' + page + '&search=' + search + '&judgment=' + judgment;
                axios.get(url).then(function (response) {
                    var response= response.data;
                    me.incomeArray= response.incomes.data;
                    me.pagination= response.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectRol()
            {
                let me= this;
                var url= '/roles/selectRol';
                axios.get(url).then(function (response) {
                    var response= response.data;
                    me.rolArray= response.roles;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            changePage(page, search, judgment)
            {
                let me= this;
                me.pagination.current_page= page;
                me.listIncome(page, search, judgment);
            },
            registerPerson()
            {
                if(this.validatePerson())
                {
                    return;
                }

                let me= this;
                  
                axios.post('/users/register',
                {
                    'name': this.name,
                    'document_type': this.document_type,
                    'document_number': this.document_number,
                    'address': this.address,
                    'telephone': this.telephone,
                    'email': this.email,
                    'user': this.user,
                    'password': this.password,
                    'rol_id': this.rol_id,
                }).then(function(response)
                {
                    me.closeModal();
                    me.listPerson(1,'','name');
                }).catch(function (error)
                {
                    console.log(error)
                });
            },
            updatePerson()
            {
                if(this.validatePerson())
                {
                    return;
                }

                let me= this;

                axios.put('/users/update',
                {
                    'name': this.name,
                    'document_type': this.document_type,
                    'document_number': this.document_number,
                    'address': this.address,
                    'telephone': this.telephone,
                    'email': this.email,
                    'user': this.user,
                    'password': this.password,
                    'rol_id': this.rol_id,
                    'id': this.person_id
                }).then(function(response)
                {
                    me.closeModal();
                    me.listPerson(1,'','name');
                }).catch(function (error)
                {
                    console.log(error)
                });
            },  
            validatePerson()
            {
                this.personError=0;
                this.showPersonMsgError=[];

                if(!this.name) this.showPersonMsgError.push("El nombre de la persona no puede estar vacío");
                if(!this.user) this.showPersonMsgError.push("El nombre de usuario no puede estar vacío");
                if(!this.password) this.showPersonMsgError.push("La contraseña no puede estar vacía");
                if(this.rol_id==0) this.showPersonMsgError.push("Debes seleccionar un rol");

                if(this.showPersonMsgError.length) this.personError=1;
                return this.personError;
            },
            ActivateDesactivateUser(id)
            {
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

        
                    swalWithBootstrapButtons.fire({
                    title: '¿Estás seguro de cambiar el estado de  este usuario?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value)
                    {
                        let me= this;
                        axios.put('/users/update/update_condition',
                        {
                            'id': id
                        }).then(function(response)
                        {
                            me.listPerson(1,'','name');
                            swalWithBootstrapButtons.fire(
                            '¡Listo!',
                            'El estado del usuario ha sido cambiado.',
                            'success'
                            )
                        }).catch(function (error)
                        {
                            console.log(error)
                        });

                    
                    } else if (
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        
                    }
                    })
            },
            closeModal()
            {
                this.modal=0;
                this.modalTitle='';
                this.name='';
                this.document_type='DNI';
                this.document_number='';
                this.address='';
                this.telephone='';
                this.email='';
                this.user='';
                this.password= '';
                this.rol_id=0;
                this.personError=0;
            },
            openModal(model, action, data=[])
            {
                this.selectRol(); 
                switch(model)
                {
                    case "person":
                    {
                        switch(action)
                        {
                            case "register":
                            {
                                this.modal=1;
                                this.modalTitle='Registrar usuario';
                                this.name='';
                                this.document_type='DNI';
                                this.document_number='';
                                this.address='';
                                this.telephone='';
                                this.email='';
                                this.user='';
                                this.password='';
                                this.rol_id=0;
                                this.actionType=1;
                                break;
                            }
                            case "update":
                            {
                                this.modal=1;
                                this.modalTitle="Actualizar usuario";
                                this.actionType=2;
                                this.person_id=data['id'];
                                this.name=data['name'];
                                this.document_type= data['document_type'];
                                this.document_number= data['document_number'];
                                this.address= data['address'];
                                this.telephone= data['telephone'];
                                this.email= data['email'];
                                this.user= data['user'];
                                this.password= data['password'];
                                this.rol_id= data['rol_id'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listIncome(1, this.search, this.judgment);
        }
    }
</script>
<style>
    .modal-content
    {
        width: 100% !important;
        position: absolute !important; 
    }
    .show
    {
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;  
    }
    .div-error
    {
        display: flex;
        justify-content: center;
    }
    .text-error
    {
        color: red !important;
        font-weight: bold;
    }
</style>
