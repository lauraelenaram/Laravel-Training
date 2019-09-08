<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/">Escritorio</a>
            </li>
        </ol>
        <div class="container-fluid">
            <div class="card">
                <div class="card-head">

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <h4>Ingresos</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="incomes"></canvas>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p>Compras de los últimos meses.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <h4>Ventas</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="sales"></canvas>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p>Ventas de los últimos meses.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
<script>
export default {
    data()
    {
        return {
            varIncome:null,
            charIncome:null,
            incomes: [],
            varTotalIncome: [],
            varMonthIncome: []
        }
    },
    methods:
    {
        getIncomes()
        {
            let me= this;
            var url= '/dashboard';
            axios.get(url).then(function (response) {
                var response= response.data;
                me.incomes= response.incomes;
                me.loadIncomes();
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        loadIncomes()
        {
            let me= this;
            me.incomes.map(function(x)
            {
                me.varMonthIncome.push(x.month);
                me.varTotalIncome.push(x.total);
            });
            me.varIncome= document.getElementById('incomes').getContext('2d');

            me.charIncome = new Chart(me.varIncome, {
                type: 'bar',
                data: {
                    labels: me.varMonthIncome,
                    datasets: [{
                        label: 'Ingresos',
                        data: me.varTotalIncome,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }
    },
    mounted()
    {
        this.getIncomes();
    }
}
</script>