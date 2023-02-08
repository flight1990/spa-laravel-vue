<template>

    <div class="bg-light">
        <div class="container d-flex align-items-center justify-content-center min-vh-100">
            <form @submit.prevent="register">
                <h1 class="fw-bold">Register</h1>

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
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" v-model="form.password" @focus="validationErrors.password = []">
                    <small class="text-danger" v-if="validationErrors.password">{{ validationErrors.password[0] }}</small>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Password confirmation</label>
                    <input type="password" class="form-control" id="password_confirmation" v-model="form.password_confirmation" @focus="validationErrors.password_confirmation = []">
                    <small class="text-danger" v-if="validationErrors.password_confirmation">{{ validationErrors.password_confirmation[0] }}</small>
                </div>

                <button type="submit" class="btn btn-primary" :disabled="processing">Register</button>
                <router-link :to="{name: 'login'}" class="ms-3">Already have an account?</router-link>
            </form>
        </div>
    </div>

</template>

<script>

import {mapActions} from "vuex";

export default {
    name: "Register",
    data() {
        return {
            validationErrors: {},
            processing: false,
            form: {
                name: "",
                email: "",
                password: "",
                password_confirmation: ""
            }
        }
    },
    methods: {
        ...mapActions({
            signIn: "auth/signIn"
        }),
        register() {
            this.processing = true
            axios.post('/api/register', this.form).then(resposne => {
                let token = resposne.data.token
                this.signIn(token);
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
