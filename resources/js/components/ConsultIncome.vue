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
                                                </button>
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
            changePage(page, search, judgment)
            {
                let me= this;
                me.pagination.current_page= page;
                me.listIncome(page, search, judgment);
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
