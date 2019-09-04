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
                        <i class="fa fa-align-justify"></i> Usuarios
                        <button type="button" @click="openModal('person','register')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="judgment">
                                      <option value="name">Nombre</option>
                                      <option value="document_number">Número de documento</option>
                                      <option value="email">Email</option>
                                      <option value="telephone">Teléfono</option>
                                    </select>
                                    <input type="text" v-model="search" @keyup.enter="listPerson(1,search,judgment)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listPerson(1,search,judgment)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Tipo de documento</th>
                                    <th>Número de documento</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Usuario</th>
                                    <th>Rol</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="person in personArray" :key="person.id"> 
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" @click="openModal('person','update',person)"> 
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                    </td>
                                    <td v-text="person.name"></td>
                                    <td v-text="person.document_type"></td>
                                    <td v-text="person.document_number"></td>
                                    <td v-text="person.address"></td>
                                    <td v-text="person.telephone"></td>
                                    <td v-text="person.email"></td>
                                    <td v-text="person.user"></td>
                                    <td v-text="person.rol"></td>
                                </tr>
                            </tbody>
                        </table>
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
                person_id: 0,
                name: '',
                document_type: 'DNI',
                document_number: '',
                address: '',
                telephone: '',
                email: '',
                user: '',
                password: '',
                rol_id: 0,
                personArray: [],
                rolArray: [],
                modal: 0,
                modalTitle: '',
                actionType: 0,
                personError: 0,
                showPersonMsgError: [],
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
                judgment: 'name',
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
            listPerson(page, search, judgment) 
            {
                let me= this;
                var url= '/users?page=' + page + '&search=' + search + '&judgment=' + judgment;
                axios.get(url).then(function (response) {
                    var response= response.data;
                    console.log(response);
                    me.personArray= response.people.data;
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
                me.listPerson(page, search, judgment);
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
            this.listPerson(1, this.search, this.judgment);
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
