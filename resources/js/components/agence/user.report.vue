<template>
    <v-card tile elevation="4">
        <v-card-title class="d-flex justify-space-between flex-wrap">
            <div class="d-flex">
                <h5><v-icon large left color="grey">mdi-account-box</v-icon>{{ user_report.cao_usuario.no_usuario }}</h5>
            </div>
            <v-chip
                :color="color_total_lucro"
                label
                text-color="white"
                >
                <v-icon left>
                    mdi-currency-usd
                </v-icon>
                <b class="mr-1">Lucro:</b> {{ formatterComponent.asRealFormatted(total_lucro) }}
            </v-chip>
        </v-card-title>
        <v-card-subtitle>
            <div v-if="!!user_report.report.length">Desde {{ user_report.report[0].periodoReadable }} a {{ user_report.report[user_report.report.length - 1].periodoReadable }}. {{ user_report.report.length }} meses</div>
        </v-card-subtitle>
        <v-card-text>
            <div class="d-flex flex-wrap">
                <v-chip
                    class="ma-2 ml-0"
                    label
                    >
                    <b class="mr-1">Receita Líquida:</b>{{ formatterComponent.asRealFormatted(total_receita_liquida) }}
                </v-chip>
                <v-chip
                    class="ma-2 ml-0"
                    label
                    >
                    <b class="mr-1">Custo Fixo:</b>{{ formatterComponent.asRealFormatted(total_custo_fixo) }}
                </v-chip>
                <v-chip
                    label
                    class="ma-2 ml-0 mr-0"
                    >
                    <b class="mr-1">Commisao:</b>{{ formatterComponent.asRealFormatted(total_comissao) }}
                </v-chip>
            </div>
        </v-card-text>
        <v-expansion-panels focusable accordion>
            <v-expansion-panel>
                <v-expansion-panel-header>
                    <div class="d-flex align-center"><v-icon class="mr-2">mdi-table-large</v-icon>Relatório por período</div>
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                    <v-data-table
                        :headers="headers"
                        :items="user_report.report"
                        item-key="id"
                        dense
                        hide-default-footer
                    >
                        <template v-slot:item.periodo="{ item }">
                            <div class="d-inline-flex align-center">
                                {{ stringHelper.capitalize(item.periodo_month_readable) }} de {{ item.periodo_year }}
                            </div>
                        </template>
                        <template v-slot:item.receita_liquida="{ item }">
                            <div class="d-inline-flex align-center">
                                {{ formatterComponent.asRealFormatted(item.receita_liquida) }}
                            </div>
                        </template>
                        <template v-slot:item.custo_fixo="{ item }">
                            <div class="d-inline-flex align-center">
                                {{ formatterComponent.asRealFormatted(item.brut_salario * -1) }}
                            </div>
                        </template>
                        <template v-slot:item.comissao="{ item }">
                            <div class="d-inline-flex align-center">
                                {{ formatterComponent.asRealFormatted(item.comissao * -1) }}
                            </div>
                        </template>
                        <template v-slot:item.lucro="{ item }">
                            <div class="d-inline-flex align-center">
                                {{ formatterComponent.asRealFormatted(item.lucro) }}
                            </div>
                        </template>
                    </v-data-table>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-expansion-panels>
    </v-card>
</template>
<script>
export default {
    props: ['user_report'],
    data: () => ({
        headers: [
            {
                text: 'Período',
                value: 'periodo'
            },
            {
                text: 'Receita Líquida',
                value: 'receita_liquida'
            },
            {
                text: 'Custo Fixo',
                value: 'custo_fixo'
            },
            {
                text: 'Comissão',
                value: 'comissao',
            },
            {
                text: 'Lucro',
                value: 'lucro',
            },
        ],
    }),
    computed: {
        total_receita_liquida: function() {
            return this.user_report.report.reduce((sum, report) => sum + report.receita_liquida, 0);
        },
        color_total_receita_liquida: function() {
            return this.total_receita_liquida >= 0 ? 'green' : 'deep-orange';
        },
        total_custo_fixo: function() {
            return this.user_report.report.reduce((sum, report) => sum - report.brut_salario, 0);
        },
        color_total_custo_fixo: function() {
            return this.total_custo_fixo >= 0 ? 'green' : 'deep-orange';
        },
        total_comissao: function() {
            return this.user_report.report.reduce((sum, report) => sum - report.comissao, 0);
        },
        color_total_comissao: function() {
            return this.total_comissao >= 0 ? 'green' : 'deep-orange';
        },
        total_lucro: function() {
            return this.user_report.report.reduce((sum, report) => sum + report.lucro, 0);
        },
        color_total_lucro: function() {
            return this.total_lucro >= 0 ? 'green' : 'deep-orange';
        },
    },
}
</script>