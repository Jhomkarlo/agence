<template>
    <v-card elevation="5">
        <v-row class="justify-center">
            <v-col cols="12" xs="12" md="8">
                <canvas id="graph-bar" ref="graph_bar"></canvas>
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
            if (!!this.chart) this.chart.destroy();
            let dataSets = [];
            let promedioSet = {
                label: 'Custo Fixo Médio',
                data: [],
                type: 'line'
            };
            let periodoList = [];

            for (const reportItem of report) {
                dataSets.push({
                    label: reportItem.cao_usuario.no_usuario,
                    backgroundColor: '#'+Math.floor(Math.random()*16777215).toString(16),
                    data: [],
                });
                for (const reportPeriodo of reportItem.report) {
                    if (!periodoList.find(periodo => periodo == reportPeriodo.periodo)) periodoList.push({label: reportPeriodo.periodo, value: reportPeriodo.data_emissao_year_month});
                }
            }
            periodoList.sort((periodoA, periodoB) => periodoA.value - periodoB.value);

            for (const periodo of periodoList) {
                let promedioSalario = 0;
                for (const reportItem of report) {
                    let dataSet = dataSets.find(dataSet =>dataSet.label == reportItem.cao_usuario.no_usuario);
                    let reportPeriodo = reportItem.report.find(reportPeriodo => reportPeriodo.data_emissao_year_month == periodo.value);
                    dataSet.data.push(!!reportPeriodo ? reportPeriodo.receita_liquida : 0);
                    promedioSalario += reportItem.cao_salario?.brut_salario ?? 0;
                }
                promedioSet.data.push(promedioSalario / report.length);
            }

            dataSets.push(promedioSet);

            this.chart = new Chart(document.getElementById('graph-bar'), {
                type: 'bar',
                data: {
                    labels: periodoList.map(periodo => periodo.label),
                    datasets: dataSets,
                },
                options: {
                    barValueSpacing: 10,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                callback: (value, index, values) => {
                                    return this.formatterComponent.asRealFormatted(value);
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: (t, d) => {
                                var xLabel = d.datasets[t.datasetIndex].label;
                                var yLabel = this.formatterComponent.asRealFormatted(t.yLabel);
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