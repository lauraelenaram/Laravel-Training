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
                        <i class="fa fa-align-justify"></i> Artículos
                        <button type="button" @click="openModal('article','register')" class="btn btn-secondary">
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
                                    <input type="text" v-model="search" @keyup.enter="listArticle(1,search,judgment)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listArticle(1,search,judgment)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Precio de venta</th>
                                    <th>Stock</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="article in articleArray" :key="article.id"> 
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" @click="openModal('article','update',article)"> 
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template>
                                            <button class="btn btn-danger btn-sm" type="button"  @click="ActivateDesactivateCategory(article.id)">
                                                <i v-if="article.condition" class="icon-trash"></i>
                                                <i v-else class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="article.code"></td>
                                    <td v-text="article.name"></td>
                                    <td v-text="article.category_name"></td>
                                    <td v-text="article.sales_price"></td>
                                    <td v-text="article.stock"></td>
                                    <td v-text="article.description"></td>
                                    <td>
                                        <div v-if="article.condition">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Categoría</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="category_id">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="category in arrayCategory" :key="category.id" :value="category.id" v-text="category.name"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Código</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="code" class="form-control" placeholder="Código de barras">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="name" class="form-control" placeholder="Nombre de artículo">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Precio de venta</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="sale_price" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="stock" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="description" class="form-control" placeholder="Ingrese descripción">
                                    </div>
                                </div>
                                <div v-show="articleError" class="form-group row div-error"> 
                                    <div class="text-center text-error">
                                        <div v-for="error in showArticleMsgError" :key="error" v-text="error">
                                        </div> 
                                    </div> 
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button type="button" class="btn btn-primary" v-if="actionType==1" @click="registerArticle()">Guardar</button>
                            <button type="button" class="btn btn-primary" v-if="actionType==2" @click="updateArticle()">Actualizar</button>
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
                article_id: 0,
                category_id: 0,
                category_name: '',
                code: '',
                name: '',
                sale_price: 0,
                stock: 0,
                description: '',
                articleArray: [],
                modal: 0,
                modalTitle: '',
                actionType: 0,
                articleError: 0,
                showArticleMsgError: [],
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
                judgment: 'nombre',
                search: '',
                arrayCategory: []
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
            listArticle(page, search, judgment) 
            {
                let me= this;
                var url= '/articles?page=' + page + '&search=' + search + '&judgment=' + judgment;
                axios.get(url).then(function (response) {
                    var response= response.data;
                    me.articleArray= response.articles.data;
                    me.pagination= response.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectCategory()
            {
                let me= this;
                var url= '/categories/selectCategory' ;
                axios.get(url).then(function (response) {
                   var response= response.data;
                    me.arrayCategory= response.categories;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            changePage(page, search, judgment)
            {
                let me= this;
                me.pagination.current_page= page;
                me.listArticle(page, search, judgment);
            },
            registerArticle()
            {
                if(this.validateArticle())
                {
                    return;
                }

                let me= this;

                axios.post('/articles/register',
                {
                    'category_id': this.category_id,
                    'code': this.code,
                    'stock': this.stock,
                    'sale_price': this.sale_price,
                    'name': this.name,
                    'description': this.description,
                }).then(function(response)
                {
                    me.closeModal();
                    me.listArticle(1,'','nombre');
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
                    me.listCategory(1,'','nombre');
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
                            me.listCategory(1,'','nombre');
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
            validateArticle()
            {
                this.articleError=0;
                this.showArticleMsgError=[];

                if(this.category_id==0) this.showArticleMsgError.push("Seleccione una categoría");
                if(!this.name) this.showArticleMsgError.push("El nombre del artículo no puede estar vacío");
                if(!this.stock) this.showArticleMsgError.push("El stock del artículo debe ser un número y no puede estar vacío.");
                if(!this.sale_price) this.showArticleMsgError.push("El precio de venta del artículo debe ser un número y no puede estar vacío.");
                
                if(this.showArticleMsgError.length) this.articleError=1;
                return this.articleError;
            },
            closeModal()
            {
                this.modal=0;
                this.modalTitle='';
                this.category_id= 0,
                this.category_name= '',
                this.code= '',
                this.name='';
                this.sale_price= 0,
                this.stock= 0,
                this.description='';
                this.articleError= 0
            },
            openModal(model, action, data=[])
            {
                switch(model)
                {
                    case "article":
                    {
                        switch(action)
                        {
                            case "register":
                            {
                                this.modal=1;
                                this.modalTitle= 'Registrar artículo';
                                this.category_id= 0;
                                this.category_name='';
                                this.code= '';
                                this.name= '';
                                this.sale_price= 0;
                                this.stock= 0;
                                this.description='';
                                this.actionType=1;
                                break;
                            }
                            case "update":
                            {
                                this.modal=1;
                                this.modalTitle="Actualizar artículo";
                                this.actionType=2;
                                this.article_id=data['id'];
                                this.category_id=data['category_id'];
                                this.code= data['code'];
                                this.name= data['name'];
                                this.stock= data['stock'];
                                this.sale_price= data['sale_price'];
                                this.description= data['description'];
                                break;
                            }
                        }
                    }
                }
                this.selectCategory();
            }
        },
        mounted() {
            this.listArticle(1, this.search, this.judgment);
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
