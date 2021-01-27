<template>
    <div>
        <user-report v-for="(user_report, index) in report" :key="index" :user_report="user_report" class="mb-2"/>
    </div>
</template>
<script>
import UserReport from './user.report.vue';

export default {
    props: ['report', 'loading'],
    components: {
        'user-report': UserReport,
    },
}
</script>