<template>
    <h1 class="fw-bold">Create New User</h1>

    <form @submit.prevent="saveUser">

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" v-model="form.name" @focus="validationErrors.name = []">
            <small class="text-danger" v-if="validationErrors.name">{{ validationErrors.name[0] }}</small>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control" id="email" v-model="form.email" @focus="validationErrors.email = []">
            <small class="text-danger" v-if="validationErrors.email">{{ validationErrors.email[0] }}</small>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" v-model="form.phone" @focus="validationErrors.phone = []">
            <small class="text-danger" v-if="validationErrors.phone">{{ validationErrors.phone[0] }}</small>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" v-model="form.password" @focus="validationErrors.password = []">
            <small class="text-danger" v-if="validationErrors.password">{{ validationErrors.password[0] }}</small>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Password confirmation</label>
            <input type="password" class="form-control" id="password_confirmation" v-model="form.password_confirmation" @focus="validationErrors.password_confirmation = []">
            <small class="text-danger" v-if="validationErrors.password_confirmation">{{ validationErrors.password_confirmation[0] }}</small>
        </div>

        <button type="submit" class="btn btn-primary me-2">Save</button>

        <router-link :to="{name: 'users.index'}">Return to users list</router-link>
    </form>

</template>

<script>

import {mapGetters} from "vuex";

export default {
    name: "Create",
    computed: {
        ...mapGetters({token: "auth/token"})
    },
    data() {
        return {
            validationErrors: {},
            processing: false,
            form: {
                name: "",
                email: "",
                phone: "",
                password: "",
                password_confirmation: ""
            }
        }
    },
    methods: {
        saveUser() {
            this.processing = true

            const config = {
                headers: {'Authorization': 'Bearer ' + this.token}
            }

            axios.post('/api/users', this.form, config).then(() => {
                this.$router.push({name: 'users.index'})
            }).catch(({response}) => {
                if (response.status === 422) {
                    this.validationErrors = response.data.errors
                } else {
                    this.validationErrors = {}
                }
            }).finally(() => {
                this.processing = false
            })
        }
    }
}
</script>
