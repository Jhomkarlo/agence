<template>
    <v-container fluid>
        <v-row class="mb-3">
            <v-col cols="12" sm="12">
                <list-filters ref="filter" :consultor_list="consultor_list" :available_month_list="available_month_list" :loading="loading" :current="current" @report="actionReport"/>
            </v-col>           
        </v-row>
        <v-row>
            <v-col cols="12" xs="12">
                <v-card ref="results" tile elevation="0" v-if="current != null">
                    <v-card-title>Resultados</v-card-title>
                    <div v-if="current == 'loading'" class="text-center">
                        <v-progress-circular
                            indeterminate
                            color="grey"
                            :size="70"
                            :width="7"
                            ></v-progress-circular>
                        <h5 class="text-h5">Carregando...</h5>
                    </div>
                    <report v-if="current == 'report'" :report="report" :loading="loading"/>
                    <graph :report="report" v-if="current == 'graph'" :loading="loading"/>
                    <pizza :report="report" v-if="current == 'pizza'" :loading="loading"/>
                </v-card>
            </v-col>
        </v-row> 
    </v-container>
</template>
<script>
import ListFilters from './list.filters.vue';

import Report from './report.vue';
import Graph from './graph.vue';
import Pizza from './pizza.vue';

export default {
    props: ['consultor_list', 'available_month_list'],
    components: {
        'list-filters': ListFilters,
        'report': Report,
        'graph': Graph,
        'pizza': Pizza,
    },
    data: () => ({
        current: null,
        report: [],
        loading: false,
    }),
    methods: {
        getReport(params) {
            this.loading = true;
            this.report = [];
            this.current = 'loading';
            axios.get('/agence/report', {
                params: {
                    consultores: params.consultores.map(consultor => consultor.co_usuario),
                    from: params.from,
                    to: params.to,
                },
            }).then((result) => {
                if (result.status != 200) return;
                var response = result.data;
                if (!response.success) return;
                this.report = this.initReport(response.data.report);
                this.loading = false;
                this.current = params.type;
                this.$vuetify.goTo(this.$refs.results);
            }).catch((err) => {
                this.loading = false;
                console.log(err);
            });
        },
        actionReport(params) {
            this.getReport(params);
        },
        initReport(report) {
            return report.map((reportItem) => {
                reportItem.report = reportItem.report.map((reportPerMonth, index) => {
                    reportPerMonth.id = index;
                    reportPerMonth.periodo_month_readable = DateTime.fromFormat(String(reportPerMonth.data_emissao_year_month), 'yyyyMM').monthLong;
                    reportPerMonth.periodo_year = DateTime.fromFormat(String(reportPerMonth.data_emissao_year_month), 'yyyyMM').toFormat('yyyy');
                    reportPerMonth.periodoReadable = `${reportPerMonth.periodo_month_readable} de ${reportPerMonth.periodo_year}`;
                    reportPerMonth.periodo = DateTime.fromFormat(String(reportPerMonth.data_emissao_year_month), 'yyyyMM').toFormat('MM/yyyy');
                    reportPerMonth.brut_salario = reportItem.cao_salario?.brut_salario ?? 0;
                    reportPerMonth.lucro = reportPerMonth.receita_liquida - reportPerMonth.brut_salario - reportPerMonth.comissao;
                    return reportPerMonth;
                });
                return reportItem;   
            });
        }
    },
}
</script>