<template>
    <v-card elevation="5">
        <v-row class="justify-center">
            <v-col cols="12" xs="12" md="8">
                <canvas id="graph-pizza"></canvas>
            </v-col>
        </v-row>
    </v-card>
</template>
<script>
export default {
    props: ['report'],
    data: () => ({
        chart: null,
    }),
    mounted: function () {
        this.drawChart(this.report);
    },
    destroyed: function () {
        if (!!this.chart) this.chart.destroy();
    },
    methods: {
        drawChart: function (report) {

            let dataSets = [];

            let dataSet = {
                fill: true,
                backgroundColor: [],
                data: []
            };

            let consultorReceitaLiquida = [];
            let receitaLiquidaTotal = 0;
            
            for (const reportItem of report) {
                let receitaLiquida = reportItem.report.reduce((receitaLiquida, reportPeriodo) => receitaLiquida + reportPeriodo.receita_liquida, 0);
                consultorReceitaLiquida.push(receitaLiquida);
                receitaLiquidaTotal += receitaLiquida;
            }

            for (const receitaLiquida of consultorReceitaLiquida) {
                dataSet.backgroundColor.push('#'+Math.floor(Math.random()*16777215).toString(16));
                dataSet.data.push(this.formatterComponent.asFloat(receitaLiquida / receitaLiquidaTotal * 100).toFixed(2));
            }

            dataSets.push(dataSet);

            this.chart = new Chart(document.getElementById('graph-pizza'), {
                type: 'pie',
                data: {
                    labels: report.map(reportItem => reportItem.cao_usuario.no_usuario),
                    datasets: dataSets,
                },
                options: {
                    tooltips: {
                        callbacks: {
                            label: (t, d) => {
                                var xLabel = report[t.index].cao_usuario.no_usuario;
                                var yLabel = this.formatterComponent.asFloat(d.datasets[0]?.data[t.index]).toFixed(2) + '%';
                                return xLabel + ': ' + yLabel;
                            }
                        }
                    }
                }
            });
        },
    },
}
</script>