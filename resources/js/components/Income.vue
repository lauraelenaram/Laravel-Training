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
                        <button type="button" @click="showDetail()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <!--List-->
                    <template v-if="list==1">
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
                                                <button type="button" class="btn btn-success btn-sm" @click="showIncome(income.id)"> 
                                                <i class="icon-eye"></i>
                                                </button> &nbsp;
                                                <template v-if="income.status=='Registrado'">
                                                    <button class="btn btn-danger btn-sm" type="button"  @click="desactivateIncome(income.id)">
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
                    </template>
                    <!--List end-->
                    <!--Detail-->
                    <template v-else-if="list==0">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="">Proveedor(*)</label>
                                        <v-select
                                            :on-search="selectSupplier"
                                            label="name"
                                            :options="supplierArray"
                                            placeholder="Buscar proveedores..."
                                            :onChange="getSupplierData"
                                        >
                                        </v-select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Impuesto(*)</label>
                                    <input type="text" class="form-control" v-model="tax">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Tipo de comprobante(*)</label>
                                        <select class="form-control" v-model="voucher_type">
                                            <option value="seleccione">Seleccione</option>
                                            <option value="Boleta">Boleta</option>
                                            <option value="Factura">Factura</option>
                                            <option value="Ticket">Ticket</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Serie comprobante</label>
                                        <input type="text" class="form-control" v-model="voucher_serie" placeholder="000x">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Número de comprobante(*)</label>
                                        <input type="text" class="form-control" v-model="voucher_number" placeholder="000xx">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div v-show="incomeError" class="form-group row div-error"> 
                                        <div class="text-center text-error">
                                            <div v-for="error in showIncomeMsgError" :key="error" v-text="error">

                                            </div> 
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row border">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">
                                            Artículo
                                            <span style="color:red;" v-show="article_id==0">(*Seleccione)</span>
                                        </label>
                                        <div class="form-inline">
                                            <input type="text" class="form-control" v-model="code" @keyup.enter="searchArticle()" placeholder="Ingrese artículo">
                                            <button class="btn btn-primary" @click="openModal()">...</button>
                                            <input type="text" class="form-control" readonly v-model="article">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">
                                            Precio
                                            <span style="color:red;" v-show="price==0">(*Ingrese precio)</span>
                                        </label>
                                        <input type="number" step="any" class="form-control" v-model="price" value="0">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">
                                            Cantidad
                                            <span style="color:red;" v-show="quantity==0">(*Ingrese)</span>
                                        </label>
                                        <input type="number" step="any" class="form-control" v-model="quantity" value="0">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                            <button class="btn btn-success form-control btnadd" @click="addDetail()">
                                                <i class="icon-plus"></i>
                                            </button>
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
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="detailArray.length">
                                            <tr v-for="(detail, index) in detailArray" :key="detail.id">
                                                <td>
                                                    <button @click="eliminateDetail(index)" class="btn btn-danger btn-sm">
                                                        <i class="icon-close"></i>
                                                    </button>
                                                </td>
                                                <td v-text="detail.article"></td>
                                                <td>
                                                    <input type="number" value="3" class="form-control" v-model="detail.price">
                                                </td>
                                                <td>
                                                    <input type="number" value="2" class="form-control" v-model="detail.quantity">
                                                </td>
                                                <td>
                                                    {{detail.price*detail.quantity}}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total parcial:</strong></td>
                                                <td>$ {{parcialTotal= (total-taxTotal).toFixed(2)}} </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total impuesto:</strong></td>
                                                <td>$ {{taxTotal= ((total*tax) / (1+tax)).toFixed(2) }} </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total neto:</strong></td>
                                                <td> ${{total=calculateTotal}} </td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="5">
                                                    NO hay artículos agregados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button class="btn btn-secondary" @click="closeDetail()">Cerrar</button>
                                    <button class="btn btn-primary" @click="registerIncome()">Registrar comprar</button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <!--Detail end-->
                    <!--Show income-->
                    <template v-else-if="list==2">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="">Proveedor</label>
                                        <p v-text="supplier"></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Impuesto</label>
                                    <p v-text="tax"></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Tipo de comprobante</label>
                                        <p v-text="voucher_type"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Serie comprobante</label>
                                        <p v-text="voucher_serie"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Número de comprobante</label>
                                        <p v-text="voucher_number"></p>
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
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="detailArray.length">
                                            <tr v-for="detail in detailArray" :key="detail.id">
                                                <td v-text="detail.article"></td>
                                                <td v-text="detail.price"></td>
                                                <td v-text="detail.quantity"></td>
                                                <td>
                                                    {{detail.price*detail.quantity}}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total parcial:</strong></td>
                                                <td>$ {{parcialTotal= (total-taxTotal).toFixed(2)}} </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total impuesto:</strong></td>
                                                <td>$ {{taxTotal= ((total*tax)).toFixed(2) }} </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="4" align="right"><strong>Total neto:</strong></td>
                                                <td> ${{total}} </td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="5">
                                                    NO hay artículos agregados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button class="btn btn-secondary" @click="closeDetail()">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <!--Show income end-->
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
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="judgmentA">
                                            <option value="name" selected="selected">Nombre</option>
                                            <option value="description">Descripción</option>
                                            <option value="code">Código</option>
                                        </select>
                                        <input type="text" v-model="searchA" @keyup.enter="listArticle(searchA,judgmentA)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listArticle(searchA,judgmentA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-response">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Código</th>
                                            <th>Nombre</th>
                                            <th>Categoría</th>
                                            <th>Precio de venta</th>
                                            <th>Stock</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="article in articleArray" :key="article.id"> 
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm " @click="addDetailModal(article)"> 
                                                    <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td v-text="article.code"></td>
                                            <td v-text="article.name"></td>
                                            <td v-text="article.category_name"></td>
                                            <td v-text="article.sale_price"></td>
                                            <td v-text="article.stock"></td>
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
                            </div>
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
    import vSelect from 'vue-select';
    export default {
        data() {
            return {
                income_id: 0,
                supplier_id: 0,
                article_id:0,
                supplier:'',
                price:0,
                quantity:0,
                name: '',
                voucher_type:'Boleta',
                voucher_number:'',
                voucher_serie:'',
                tax: 0.16,
                total: 0.0,
                taxTotal: 0.0,
                parcialTotal: 0.0,
                incomeArray: [],
                detailArray: [],
                supplierArray: [],
                list:1,
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
                search: '',
                judgmentA: 'name',
                searchA: '',
                articleArray: [],
                code: '',
                article: ''
            }
        },
        components:
        {
            vSelect
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
            },
            calculateTotal: function()
            {
                var result=0;
                for(var i=0; i<this.detailArray.length; i++)
                {
                    result= result + (this.detailArray[i].price * this.detailArray[i].quantity)
                }
                return result;
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
            selectSupplier(search, loading)
            {
                let me= this;
                loading(true)

                var url= '/suppliers/selectSupplier?filter='+search;
                axios.get(url).then(function (response) {
                    var answer= response.data;
                    q: search
                    me.supplierArray= answer.suppliers;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getSupplierData(val1)
            {
                let me= this;
                me.loading= true;
                me.supplier_id= val1.id;
            },
            searchArticle()
            {
                let me= this;
                var url= '/articles/searchArticle?filter='+ me.code;

                axios.get(url).then(function(response)
                {
                    var response= response.data;
                    me.articleArray= response.articles;

                    if(me.articleArray.length>0)
                    {
                        me.article= me.articleArray[0]['name'];
                        me.article_id= me.articleArray[0]['id'];
                    }
                    else
                    {
                        me.article= 'No existe el artículo';
                        me.article_id= 0;
                    }
                })
                .catch(function(error)
                {
                    console.log(error);
                });
            },
            changePage(page, search, judgment)
            {
                let me= this;
                me.pagination.current_page= page;
                me.listIncome(page, search, judgment);
            },
            findArticle(id)
            {
                var sw=0;
                for(var i=0; i<this.detailArray.length; i++)
                {
                    if(this.detailArray[i].article_id==id)
                    {
                        sw= true;
                    }
                }
                return sw;
            },
            eliminateDetail(index)
            {
                let me= this;
                me.detailArray.splice(index,1);
            },
            addDetail()
            {
                let me= this;

                if(me.article_id==0 || me.quantity==0 || me.price==0)
                {}
                else
                {
                    if(me.findArticle(me.article_id))
                    {
                        Swal.fire({
                            type: 'error',
                            title: 'Error...',
                            text: 'Este artículo ya se encuentra agregado',
                        }) 
                    }
                    else
                    {
                        me.detailArray.push({
                            article_id: me.article_id,
                            article: me.article,
                            quantity: me.quantity,
                            price: me.price
                        });
                        me.code='';
                        me.article_id=0;
                        me.article='';
                        me.quantity=0;
                        me.price=0;
                    } 
                }
            },
            addDetailModal(data= [])
            {
                let me=this;
                if(me.findArticle(data['id']))
                    {
                        Swal.fire({
                            type: 'error',
                            title: 'Error...',
                            text: 'Este artículo ya se encuentra agregado',
                        }) 
                    }
                    else
                    {
                        me.detailArray.push({
                            article_id: data['id'],
                            article: data['name'],
                            quantity: 1,
                            price: 1
                        });
                    } 
            },
            listArticle(search, judgment) 
            {
                let me= this;
                var url= '/articles/listArticle?search=' + search + '&judgment=' + judgment;
                axios.get(url).then(function (response) {
                    var response= response.data;
                    me.articleArray= response.articles.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registerIncome()
            {
                if(this.validateIncome())
                {
                    return;
                }

                let me= this;
                  
                axios.post('/incomes/register',
                {
                    'supplier_id': this.supplier_id,
                    'voucher_type': this.voucher_type,
                    'voucher_serie': this.voucher_serie,
                    'voucher_number': this.voucher_number,
                    'tax': this.tax,
                    'total': this.total,
                    'data': this.detailArray
                }).then(function(response)
                {
                    me.list=1;
                    me.listIncome(1,'','voucher_number');
                    me.supplier_id=0;
                    me.voucher_type='Boleta';
                    me.voucher_number= '';
                    me.voucher_serie= '';
                    me.tax=0.16;
                    me.total=0;
                    me.article_id=0;
                    me.article='';
                    me.quantity=0;
                    me.price=0;
                    me.detailArray= [];
                }).catch(function (error)
                {
                    console.log(error)
                });
            }, 
            validateIncome()
            {
                this.incomeError=0;
                this.showIncomeMsgError=[];
                
                if(this.supplier_id==0) this.showIncomeMsgError.push('Seleccione un proveedor');
                if(this.voucher_type==0) this.showIncomeMsgError.push('Seleccione el comprobante');
                if(!this.voucher_number) this.showIncomeMsgError.push('Ingrese el número de comprobante'); 
                if(!this.tax) this.showIncomeMsgError.push('Ingrese el impuesto de compra'); 
                if(this.detailArray.length<=0) this.showIncomeMsgError.push('Ingrese detalles')

                if(this.showIncomeMsgError.length) this.incomeError=1;
                return this.incomeError;
            },
            desactivateIncome(id)
            {
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })
                    swalWithBootstrapButtons.fire({
                    title: '¿Estás seguro de anular este ingreso?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value)
                    {
                        let me= this;
                        axios.put('/incomes/desactivate',
                        {
                            'id': id
                        }).then(function(response)
                        {
                            me.listIncome(1,'','voucher_number');
                            swalWithBootstrapButtons.fire(
                            '¡Anulado!',
                            'El ha sido anulado con éxito.',
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
            showDetail()
            {
                let me=this;
                me.list=0;
                me.supplier_id=0;
                me.voucher_type='Boleta';
                me.voucher_number= '';
                me.voucher_serie= '';
                me.tax=0.16;
                me.total=0;
                me.article_id=0;
                me.article='';
                me.quantity=0;
                me.price=0;
                me.detailArray= [];
            },
            closeDetail()
            {
                this.list=1;
            },
            showIncome(id)
            {
                let me=this;
                me.list=2;

                var incomeArrayT= [];
                var url= '/incomes/getHeader?id=' + id;
                axios.get(url).then(function (response) {
                    var response= response.data;
                    incomeArrayT= response.income;

                    me.supplier= incomeArrayT[0]['name'];
                    me.voucher_type= incomeArrayT[0]['voucher_type'];
                    me.voucher_serie= incomeArrayT[0]['voucher_serie'];
                    me.voucher_number= incomeArrayT[0]['voucher_number'];
                    me.tax= incomeArrayT[0]['tax'];
                    me.total= incomeArrayT[0]['total'];
                })
                .catch(function (error) {
                    console.log(error);
                });


                var urlDetails= '/incomes/getDetails?id=' + id;
                axios.get(urlDetails).then(function (response) {
                    var response= response.data;
                    me.detailArray= response.details;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            closeModal()
            {
                this.modal=0;
            },
            openModal()
            {
                this.articleArray=[];
                this.modal=1;
                this.modalTitle='Seleccione uno o varios artículos';
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
    @media (min-width:600px)
    {
        .btnadd
        {
            margin-top: 2rem;
        }
    }
    
</style>
