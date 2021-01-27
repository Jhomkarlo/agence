<template>
    <v-tabs v-model="current_index">
        <v-tab v-for="route of routes" :key="route.id" :href="route.uri">{{ route.label }}</v-tab>
    </v-tabs>
</template>
<script>
export default {
    props: ['currentroutename', 'routes'],
    data: () =>  ({
        current_index: {},
    }),
    methods: {
        setcurrent_index() {
            for (const routeIndex in this.routes) {
                var route = this.routes[routeIndex];
                if (route.name == this.currentroutename) return route.uri;  
            };
            return null;
        },
    },
    mounted() {
        this.current_index = this.setcurrent_index();
    },
}
</script>