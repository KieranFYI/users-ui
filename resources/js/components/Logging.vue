<template>
    <admin-table
        title="Logs"
        :data="logs"
        :columns="columns"
        :loading="loading"
        @filter="filter"
    />
</template>

<script>
import AdminTable from '../../../vendor/kieranfyi/admin/resources/js/components/AdminTable';
export default {
    components: {
        AdminTable
    },
    props: {
        endpoint: {
            required: true,
            type: String
        },
    },
    data() {
        return {
            loading: false,
            columns: {
                id: 'ID',
                level: 'Level',
                message: 'Message',
                model_title: 'Model',
                user_title: 'User',
                created_at: {
                    type: 'date',
                    label: 'Created'
                }
            },
            logs: {}
        }
    },
    methods: {
        fetchData(data) {
            this.loading = true;
            this.$axios
                .post(this.endpoint, data)
                .then((response) => {
                    this.logs = response.data;
                    this.loading = false;
                });
        },
        filter(data) {
            this.loading = true;
            this.fetchData(data);
        },
    },
    created() {
        this.fetchData = this.$lodash.debounce(this.fetchData, 300);
    },
    mounted() {
        this.fetchData();
    }
}
</script>

<style scoped>

</style>