<template>
    <v-card elevation="10" :loading="loading" tile>
        <v-card-title>Relatório de consultores</v-card-title>
        <v-card-subtitle>Selecione um intervalo de datas e os consultores para revisar</v-card-subtitle>
        <v-card-text>
            <v-row>
                <v-col cols="12" sm="9">
                    <v-form
                        ref="form"
                        v-model="valid"
                        lazy-validation
                    >
                        <div class="d-flex flex-wrap">
                            <v-menu
                                v-model="fromDateWrapper"
                                :close-on-content-click="false"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="auto"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="fromDateFormatted"
                                        label="Desde"
                                        prepend-inner-icon="mdi-calendar"
                                        readonly
                                        filled
                                        hint="Selecione um período inicial"
                                        class="ma-2"
                                        persistent-hint
                                        v-bind="attrs"
                                        v-on="on"
                                        :disabled="loading"
                                        :loading="loading"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="fromDate"
                                    :allowed-dates="allowed_months"
                                    type="month"
                                    no-title
                                    scrollable
                                    @input="fromDateWrapper = false"
                                >
                                </v-date-picker>
                            </v-menu>
                            <v-menu
                                v-model="toDateWrapper"
                                :close-on-content-click="false"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="auto"
                                class="ma-2"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="toDateFormatted"
                                        label="Até"
                                        prepend-inner-icon="mdi-calendar"
                                        readonly
                                        filled
                                        hint="Selecione um período final"
                                        persistent-hint
                                        class="ma-2"
                                        v-bind="attrs"
                                        v-on="on"
                                        :loading="loading"
                                        :disabled="loading"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="toDate"
                                    :allowed-dates="allowed_months"
                                    type="month"
                                    no-title
                                    scrollable
                                    @input="toDateWrapper = false"
                                >
                                </v-date-picker>
                            </v-menu>
                        </div>
                        <div>
                            <v-combobox
                                v-model="consultores"
                                :items="consultor_list"
                                :disabled="loading"
                                :loading="loading"
                                :rules="consultores_list_rules"
                                item-text="no_usuario"
                                hint="Selecione um ou mais consultores"
                                clearable
                                filled
                                multiple
                                persistent-hint
                                small-chips
                                deletable-chips
                                prepend-inner-icon="mdi-account-box"
                                class="ma-2"
                                ></v-combobox>
                        </div>
                    </v-form>
                </v-col>
                <v-col cols="12" sm="3" class="d-flex align-center justify-center flex-column">
                    <v-btn class="mb-2" @click.prevent="filter('report')" :loading="loading" :color="current == 'report'? 'secondary': ''" block depressed tile>RELATOIRO <v-icon right dark>mdi-format-list-text</v-icon></v-btn>
                    <v-btn class="mb-2" @click.prevent="filter('graph')" :loading="loading" :color="current == 'graph'? 'secondary': ''" block depressed tile>GRÁFICO <v-icon right dark>mdi-chart-bar</v-icon></v-btn>
                    <v-btn class="" @click.prevent="filter('pizza')" :loading="loading" :color="current == 'pizza'? 'secondary': ''" block depressed tile>PIZZA <v-icon right dark>mdi-chart-pie</v-icon></v-btn>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>
<script>

export default {
    props: ['consultor_list', 'available_month_list', 'loading', 'current'],
    data() {
        return {
            fromDateWrapper: false,
            fromDateFormatted: null,
            fromDate: null,
            toDateWrapper: false,
            toDateFormatted: null,
            toDate: null,
            consultores: [],
            consultores_list_rules: [
                v => !!v.length || "Você precisa escolher pelo menos um consultor"
            ],
            valid: true,
        };
    },
    methods: {
        filter(type) {
            if (this.$refs.form.validate()) {
                this.$emit('report', {
                    consultores: this.consultores,
                    from: this.fromDate ? DateTime.fromFormat(this.fromDate,'yyyy-MM').toFormat('yyyyMM') : '',
                    to: this.toDate ? DateTime.fromFormat(this.toDate,'yyyy-MM').toFormat('yyyyMM') : '',
                    type: type,
                });
            }
        },
        allowed_months(month) {
            return !!this.available_month_list.find(fatura => fatura.data_emissao_year_month_formatted == month);
        },
    },
    watch: {
        fromDate(fromDate) {
            return this.fromDateFormatted = DateTime.fromFormat(String(fromDate),'yyyy-MM').toFormat('MM/yyyy');
        },
        toDate(toDate) {
            return this.toDateFormatted = DateTime.fromFormat(String(toDate),'yyyy-MM').toFormat('MM/yyyy');
        },
    },
    mounted() {
        this.fromDate = this.available_month_list[0]?.data_emissao_year_month_formatted;
        this.toDate = this.available_month_list[this.available_month_list.length - 1]?.data_emissao_year_month_formatted;
    }
}
</script>