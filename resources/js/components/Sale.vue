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
                        <i class="fa fa-align-justify"></i> Ventas
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
                                        <input type="text" v-model="search" @keyup.enter="listSale(1,search,judgment)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listSale(1,search,judgment)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Usuario</th>
                                            <th>Cliente</th>
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
                                        <tr v-for="sale in saleArray" :key="sale.id"> 
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm" @click="showSale(sale.id)"> 
                                                    <i class="icon-eye"></i>
                                                </button> &nbsp;
                                                <button type="button" class="btn btn-info btn-sm" @click="pdfSale(sale.id)"> 
                                                    <i class="icon-doc"></i>
                                                </button> &nbsp;
                                                <template v-if="sale.status=='Registrado'">
                                                    <button class="btn btn-danger btn-sm" type="button"  @click="desactivateSale(sale.id)">
                                                        <i class="icon-trash"></i>
                                                    </button>
                                                </template>
                                            </td>
                                            <td v-text="sale.user"></td>
                                            <td v-text="sale.name"></td>
                                            <td v-text="sale.voucher_type"></td>
                                            <td v-text="sale.voucher_serie"></td>
                                            <td v-text="sale.voucher_number"></td>
                                            <td v-text="sale.date_hour"></td>
                                            <td v-text="sale.total"></td>
                                            <td v-text="sale.tax"></td>
                                            <td v-text="sale.status"></td>
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
                                        <label for="">Cliente(*)</label>
                                        <v-select
                                            :on-search="selectClient"
                                            label="name"
                                            :options="clientArray"
                                            placeholder="Buscar cliente..."
                                            :onChange="getClientData"
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
                                    <div v-show="saleError" class="form-group row div-error"> 
                                        <div class="text-center text-error">
                                            <div v-for="error in showSaleMsgError" :key="error" v-text="error">

                                            </div> 
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row border">
                                <div class="col-md-4">
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
                                        <label for="">
                                            Descuento
                                        </label>
                                        <input type="number" step="any" class="form-control" v-model="discount" value="0">
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
                                                <th>Descuento</th>
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
                                                    <input type="number" class="form-control" v-model="detail.price">
                                                </td>
                                                <td>
                                                    <span style="color:red;" v-show="detail.quantity>detail.stock">Stock disponible: {{detail.stock}}</span>
                                                    <input type="number" class="form-control" v-model="detail.quantity">
                                                </td>
                                                <td>
                                                    <span style="color:red;" v-show="detail.discount>(detail.price * detail.quantity)">Descuento superior</span>
                                                    <input type="number" class="form-control" v-model="detail.discount">
                                                </td>
                                                <td>
                                                    {{detail.price*detail.quantity-detail.discount}}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="5" align="right"><strong>Total parcial:</strong></td>
                                                <td>$ {{parcialTotal= (total-taxTotal).toFixed(2)}} </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="5" align="right"><strong>Total impuesto:</strong></td>
                                                <td>$ {{taxTotal= ((total*tax) / (1+tax)).toFixed(2) }} </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5;">
                                                <td colspan="5" align="right"><strong>Total neto:</strong></td>
                                                <td> ${{total=calculateTotal}} </td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="6">
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
                                    <button class="btn btn-primary" @click="registerSale()">Registrar venta</button>
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
                                        <label for="">Cliente</label>
                                        <p v-text="client"></p>
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
                                                <th>Descuentos</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="detailArray.length">
                                            <tr v-for="detail in detailArray" :key="detail.id">
                                                <td v-text="detail.article"></td>
                                                <td v-text="detail.price"></td>
                                                <td v-text="detail.quantity"></td>
                                                <td v-text="detail.discount"></td>
                                                <td>
                                                    {{detail.price*detail.quantity-detail.discount}}
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
                sale_id: 0,
                client_id: 0,
                client: '',
                voucher_type:'Boleta',
                voucher_number:'',
                voucher_serie:'',
                tax: 0.16,
                total: 0.0,
                taxTotal: 0.0,
                parcialTotal: 0.0,
                saleArray: [],
                clientArray: [],
                detailArray: [],
                list:1,
                modal: 0,
                modalTitle: '',
                actionType: 0,
                saleError: 0,
                showSaleMsgError: [],
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
                article: '',
                price:0,
                quantity:0, 
                discount: 0,
                stock:0
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
                    result= result + (this.detailArray[i].price * this.detailArray[i].quantity-this.detailArray[i].discount)
                }
                return result;
            }
        },
        methods: {
            listSale(page, search, judgment) 
            {
                let me= this;
                var url= '/sales?page=' + page + '&search=' + search + '&judgment=' + judgment;
                axios.get(url).then(function (response) {
                    var response= response.data;
                    me.saleArray= response.sales.data;
                    me.pagination= response.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectClient(search, loading)
            {
                let me= this;
                loading(true)

                var url= '/clients/selectClient?filter='+search;
                axios.get(url).then(function (response) {
                    var answer= response.data;
                    q: search
                    me.clientArray= answer.clients;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getClientData(val1)
            {
                let me= this;
                me.loading= true;
                me.client_id= val1.id;
            },
            searchArticle()
            {
                let me= this;
                var url= '/articles/searchArticleSale?filter='+ me.code;

                axios.get(url).then(function(response)
                {
                    var response= response.data;
                    me.articleArray= response.articles;

                    if(me.articleArray.length>0)
                    {
                        me.article= me.articleArray[0]['name'];
                        me.article_id= me.articleArray[0]['id'];
                        me.price= me.articleArray[0]['sale_price'];
                        me.stock= me.articleArray[0]['stock'];
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
                me.listSale(page, search, judgment);
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
            pdfSale(id)
            {
                window.open('http://127.0.0.1:8000/sales/pdf/' + id + ',' + '_blank');
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
                        if(me.quantity>me.stock)
                        {
                            Swal.fire({
                            type: 'error',
                            title: 'Error...',
                            text: 'NO hay stock disponible.',
                            })
                        }
                        else
                        {
                            me.detailArray.push({
                            article_id: me.article_id,
                            article: me.article,
                            quantity: me.quantity,
                            price: me.price,
                            discount: me.discount,
                            stock: me.stock
                            });
                            me.code='';
                            me.article_id=0;
                            me.article='';
                            me.quantity=0;
                            me.price=0;
                            me.discount=0;
                            me.stock=0;
                        }
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
                            price: data['sale_price'],
                            discount: 0,
                            stock: data['stock'],
                        });
                    } 
            },
            listArticle(search, judgment) 
            {
                let me= this;
                var url= '/articles/listArticleSale?search=' + search + '&judgment=' + judgment;
                axios.get(url).then(function (response) {
                    var response= response.data;
                    me.articleArray= response.articles.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registerSale()
            {
                if(this.validateSale())
                {
                    return;
                }

                let me= this;
                  
                axios.post('/sales/register',
                {
                    'client_id': this.client_id,
                    'voucher_type': this.voucher_type,
                    'voucher_serie': this.voucher_serie,
                    'voucher_number': this.voucher_number,
                    'tax': this.tax,
                    'total': this.total,
                    'data': this.detailArray
                }).then(function(response)
                {
                    me.list=1;
                    me.listSale(1,'','voucher_number');
                    me.client_id=0;
                    me.voucher_type='Boleta';
                    me.voucher_number= '';
                    me.voucher_serie= '';
                    me.tax=0.16;
                    me.total=0;
                    me.article_id=0;
                    me.article='';
                    me.quantity=0;
                    me.price=0;
                    me.stock=0;
                    me.code='';
                    me.discount=0;
                    me.detailArray= [];
                }).catch(function (error)
                {
                    console.log(error)
                });
            }, 
            validateSale()
            {
                let me= this;
                me.saleError=0;
                me.showSaleMsgError=[];
                var art;
                
                me.detailArray.map(function(x){
                    if(x.quantity>x.stock)
                    {
                        art=x.article + " con stock insuficiente";
                        me.showSaleMsgError.push(art);
                    }
                });
                
                if(me.client_id==0) me.showSaleMsgError.push('Seleccione un cliente');
                if(me.voucher_type==0) me.showSaleMsgError.push('Seleccione el comprobante');
                if(!me.voucher_number) me.showSaleMsgError.push('Ingrese el número de comprobante'); 
                if(!me.tax) me.showSaleMsgError.push('Ingrese el impuesto de compra'); 
                if(me.detailArray.length<=0) me.showSaleMsgError.push('Ingrese detalles')

                if(me.showSaleMsgError.length) me.saleError=1;
                return me.saleError;
            },
            desactivateSale(id)
            {
                const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
                })
                    swalWithBootstrapButtons.fire({
                    title: '¿Estás seguro de anular esta venta?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.value)
                    {
                        let me= this;
                        axios.put('/sales/desactivate',
                        {
                            'id': id
                        }).then(function(response)
                        {
                            me.listSale(1,'','voucher_number');
                            swalWithBootstrapButtons.fire(
                            '¡Anulada!',
                            'La venta ha sido anulada con éxito.',
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
            showSale(id)
            {
                let me=this;
                me.list=2;

                var saleArrayT= [];
                var url= '/sales/getHeader?id=' + id;
                axios.get(url).then(function (response) {
                    var response= response.data;
                    saleArrayT= response.sales;

                    me.client= saleArrayT[0]['name'];
                    me.voucher_type= saleArrayT[0]['voucher_type'];
                    me.voucher_serie= saleArrayT[0]['voucher_serie'];
                    me.voucher_number= saleArrayT[0]['voucher_number'];
                    me.tax= saleArrayT[0]['tax'];
                    me.total= saleArrayT[0]['total'];
                })
                .catch(function (error) {
                    console.log(error);
                });


                var urlDetails= '/sales/getDetails?id=' + id;
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
            this.listSale(1, this.search, this.judgment);
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
