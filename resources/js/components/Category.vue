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
                        <i class="fa fa-align-justify"></i> Categorías
                        <button type="button" @click="openModal('category','register')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="judgment">
                                      <option value="name" selected="selected">Nombre</option>
                                      <option value="description">Descripción</option>
                                    </select>
                                    <input type="text" v-model="search" @keyup.enter="listCategory(1,search,judgment)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listCategory(1,search,judgment)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="category in categoryArray" :key="category.id"> 
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" @click="openModal('category','update',category)"> 
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template>
                                            <button class="btn btn-danger btn-sm" type="button"  @click="ActivateDesactivateCategory(category.id)">
                                                <i v-if="category.condition" class="icon-trash"></i>
                                                <i v-else class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="category.name"></td>
                                    <td v-text="category.description"></td>
                                    <td>
                                        <div v-if="category.condition">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                    </td>
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
                                        <input type="text" v-model="name" class="form-control" placeholder="Nombre de categoría">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="description" class="form-control" placeholder="Ingrese descripción">
                                    </div>
                                </div>
                                <div v-show="categoryError" class="form-group row div-error"> 
                                    <div class="text-center text-error">
                                        <div v-for="error in showCategoryMsgError" :key="error" v-text="error">
                                        </div> 
                                    </div> 
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" v-if="actionType==1" @click="registerCategory()">Guardar</button>
                            <button type="button" class="btn btn-primary" v-if="actionType==2" @click="updateCategory()">Actualizar</button>
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
    export default {
        data() {
            return {
                name: '',
                description: '',
                categoryArray: [],
                modal: 0,
                modalTitle: '',
                actionType: 0,
                categoryError: 0,
                showCategoryMsgError: [],
                idCategory: 0,
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
            listCategory(page, search, judgment) 
            {
                let me= this;
                var url= '/categories?page=' + page + '&search=' + search + '&judgment=' + judgment;
                axios.get(url).then(function (response) {
                    var response= response.data;
                    me.categoryArray= response.categories.data;
                    me.pagination= response.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            changePage(page, search, judgment)
            {
                let me= this;
                me.pagination.current_page= page;
                me.listCategory(page, search, judgment);
            },
            registerCategory()
            {
                if(this.validateCategory())
                {
                    return;
                }

                let me= this;

                axios.post('/categories/register',
                {
                    'name': this.name,
                    'description': this.description,
                }).then(function(response)
                {
                    me.closeModal();
                    me.listCategory(1,'','name');
                }).catch(function (error)
                {
                    console.log(error)
                });
            },
            updateCategory()
            {
                if(this.validateCategory())
                {
                    return;
                }

                let me= this;

                axios.put('/categories/update',
                {
                    'name': this.name,
                    'description': this.description,
                    'id': this.idCategory
                }).then(function(response)
                {
                    me.closeModal();
                    me.listCategory(1,'','name');
                }).catch(function (error)
                {
                    console.log(error)
                });
            },
            ActivateDesactivateCategory(id,condition)
            {
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })

        
                    swalWithBootstrapButtons.fire({
                    title: '¿Estás seguro de cambiar el estado de  esta categoría?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value)
                    {
                        let me= this;
                        axios.put('/categories/update/update_condition',
                        {
                            'id': id
                        }).then(function(response)
                        {
                            me.listCategory(1,'','name');
                            swalWithBootstrapButtons.fire(
                            '¡Listo!',
                            'El estado de tu categoría ha sido cambiado.',
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
            validateCategory()
            {
                this.categoryError=0;
                this.showCategoryMsgError=[];
                if(!this.name) this.showCategoryMsgError.push("El nombre de la categoría no puede estar vacío");
                if(this.showCategoryMsgError.length) this.categoryError=1;
                return this.categoryError;
            },
            closeModal()
            {
                this.modal=0;
                this.modalTitle='';
                this.name='';
                this.description='';
            },
            openModal(model, action, data=[])
            {
                switch(model)
                {
                    case "category":
                    {
                        switch(action)
                        {
                            case "register":
                            {
                                this.modal=1;
                                this.modalTitle= 'Registrar categoría';
                                this.name='';
                                this.description='';
                                this.actionType=1;
                                break;
                            }
                            case "update":
                            {
                                this.idCategory=data['id'];
                                this.modal=1;
                                this.modalTitle="Actualizar categoría";
                                this.actionType=2;
                                this.name= data['name'];
                                this.description= data['description'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listCategory(1, this.search, this.judgment);
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
