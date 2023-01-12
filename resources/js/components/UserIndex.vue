<template>
    <admin-table
        title="Users"
        :data="users"
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
    data() {
        return {
            loading: true,
            users: {
                data: null,
                links: null
            },
            columns: {
                id: 'ID',
                name: 'Name',
                email: 'Email',
                created_at: {
                    type: 'date',
                    label: 'Registration'
                }
            },
            searchText: null,
            resultsPage: 1
        };
    },
    methods: {
        fetchData(data) {
            this.loading = true;
            this.$axios
                .post(route('admin.api.users.search', {user: this.user}), data)
                .then((response) => {
                    this.users = response.data;
                    for (const [key, user] of Object.entries(this.users.data)) {
                        let actions = [];
                        if (user.access.show) {
                            actions.push({
                                icon: 'fas fa-eye',
                                url: route('admin.users.show', user)
                            });
                        }
                        this.users.data[key]['actions'] = actions;
                    }

                    this.loading = false;
                });
        },
        filter(data) {
            this.loading = true;
            this.fetchData(data);
        },
    },
    created() {
        this.fetchData = this.$lodash.debounce(this.fetchData, 500);
    },
    mounted() {
        this.fetchData();
    }
}
</script>