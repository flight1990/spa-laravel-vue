<template>

    <div class="bg-light">
        <div class="container d-flex align-items-center justify-content-center min-vh-100">

            <form @submit.prevent="login">
                <h1 class="fw-bold">Login</h1>

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

                <button type="submit" class="btn btn-primary" :disabled="processing">Login</button>
                <router-link :to="{name: 'register'}" class="ms-3">Don't have an account?</router-link>

            </form>
        </div>
    </div>

</template>

<script>

import {mapActions} from "vuex";

export default {
    name: "Login",
    data() {
        return {
            validationErrors: {},
            processing: false,
            form: {
                email: "",
                password: ""
            }
        }
    },
    methods: {
        ...mapActions({
            signIn: "auth/signIn"
        }),
        login() {
            this.processing = true
            axios.post('/api/login', this.form).then(resposne => {
                let token = resposne.data.token
                this.signIn(token);
            }).catch(({response}) => {
                if (response.status === 422 || response.status === 401 || response.status === 403) {
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

