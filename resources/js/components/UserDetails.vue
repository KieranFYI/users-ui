<template>
    <h3 class="profile-username text-center py-3">{{ user.name }}</h3>
    <ul class="list-group list-group-unbordered mb-0">
        <li :class="'list-group-item px-2 py-1 ' + (hasInfo ? 'border-bottom-0' : '')">
            <h6 class="text-center">
                Details
                <span class="spinner-border spinner-border-sm float-right" role="status" v-if="loading"></span>
                <template v-else-if="user.access.edit">
                    <template v-if="edit">
                        <i class="fas fa-save float-right text-success" style="cursor: pointer" @click="update"></i>
                        <i class="fas fa-times float-right text-danger mr-1" style="cursor: pointer"
                           @click="edit = false"></i>
                    </template>
                    <i class="fas fa-pencil-alt float-right text-primary" style="cursor: pointer" @click="toggle"
                       v-else></i>
                </template>
            </h6>

            <div v-if="edit">
                <div class="form-group mb-1">
                    <div class="input-group">
                        <input name="name" v-model="values.name" placeholder="Name" class="form-control form-control-sm"
                               type="text" :disabled="loading">
                    </div>
                    <span class="invalid-feedback d-block" v-if="errors.name">
                        {{ errors.name }}
                    </span>
                </div>
                <div class="form-group mb-1">
                    <div class="input-group">
                        <input name="email" v-model="values.email" placeholder="Email"
                               class="form-control form-control-sm"
                               type="email" :disabled="loading">
                    </div>
                    <span class="invalid-feedback d-block" v-if="errors.email">
                        {{ errors.email }}
                    </span>
                </div>
                <div class="form-group mb-1">
                    <div class="input-group">
                        <input id="password" name="password" v-model="values.password" placeholder="Password"
                               class="form-control form-control-sm"
                               type="password" :disabled="loading">
                    </div>
                    <span class="invalid-feedback d-block" v-if="errors.password">
                        {{ errors.password }}
                    </span>
                </div>
                <div class="form-group mb-1">
                    <div class="input-group">
                        <input id="password_confirmation" name="password_confirmation"
                               v-model="values.password_confirmation"
                               placeholder="Confirm Password"
                               class="form-control form-control-sm"
                               type="password" :disabled="loading">
                    </div>
                    <span class="invalid-feedback d-block" v-if="errors.password_confirmation">
                        {{ errors.password_confirmation }}
                    </span>
                </div>
            </div>

            <div v-else>
                <dl class="d-flex mb-0">
                    <dt>ID</dt>
                    <dd class="text-right flex-grow-1 mb-0">{{ user.id }}</dd>
                </dl>
                <dl class="d-flex mb-0">
                    <dt>Email</dt>
                    <dd class="text-right flex-grow-1 mb-0">
                        <span class="text-success mr-1" v-if="user.email_verified_at !== null"
                              title="Verified">&check;</span>
                        <span class="text-danger mr-1" v-else title="Unverified">&times;</span>
                        <span class="user-select-all">
                        {{ user.email }}
                    </span>
                    </dd>
                </dl>
                <dl class="d-flex mb-0">
                    <dt>Registration</dt>
                    <dd class="text-right flex-grow-1 mb-0">
                        {{ formatDate(user.created_at) }}
                    </dd>
                </dl>
                <dl class="d-flex mb-0">
                    <dt>Last Updated</dt>
                    <dd class="text-right flex-grow-1 mb-0">
                        {{ formatDate(user.updated_at) }}
                    </dd>
                </dl>
            </div>
        </li>
    </ul>
</template>

<script>
export default {
    props: {
        userData: {
            required: true,
            type: Object
        },
        hasInfo: {
            required: true,
            type: Boolean
        }
    },
    data() {
        return {
            user: {
                name: null,
                email: null,
                email_verified_at: null,
                created_at: null,
                updated_at: null,
                access: {
                    edit: false
                },
            },
            loading: false,
            edit: false,
            values: {
                name: null,
                email: null,
                password: null,
                password_confirmation: null,
            },
            errors: {
                name: null,
                email: null,
                password: null,
                password_confirmation: null,
            }
        }
    },
    methods: {
        resetErrors() {
            let $this = this;
            Object.keys($this.errors).forEach(key => {
                $this.errors[key] = null;
            });
        },
        toggle: function () {
            if (!this.edit) {
                this.resetErrors();
                this.values.name = this.user.name;
                this.values.email = this.user.email;
                this.values.password = null;
                this.values.password_confirmation = null;
                this.edit = true;
            } else {
                this.edit = false;
            }
        },
        update: function () {
            let $this = this;
            $this.loading = true;
            $this.resetErrors();

            $this.$axios
                .patch(route('admin.api.users.update', {user: this.userData}), $this.values)
                .then((response) => {
                    $this.user = response.data;
                    $this.edit = false;
                })
                .catch((error) => {
                    if (!error.response) {
                        return;
                    }
                    if (!error.response.data.errors) {
                        if (error.response.data.message) {
                            alert(error.response.data.message);
                        } else {
                            alert('An unknown error has occurred');
                        }
                        return;
                    }

                    Object.keys(error.response.data.errors).forEach(key => {
                        $this.errors[key] = error.response.data.errors[key][0]
                    });
                })
                .finally(() => {
                    $this.loading = false;
                })
        }
    },
    created() {
        this.update = this.$lodash.debounce(this.update, 300);
    },
    beforeMount() {
        this.user = this.userData;
    }
}
</script>

<style scoped>

</style>