<template>
    <h1>Login</h1>

    <form @submit.prevent="login">

        <div>
            <label for="email">Email</label>
            <input type="text" id="email" v-model="form.email" @focus="validationErrors.email = []">
            <small v-if="validationErrors.email">{{ validationErrors.email[0] }}</small>
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" id="password" v-model="form.password" @focus="validationErrors.password = []">
            <small v-if="validationErrors.password">{{ validationErrors.password[0] }}</small>
        </div>

        <button type="submit" :disabled="processing">Login</button>
        <router-link :to="{name: 'register'}" class="ms-3">Don't have an account?</router-link>

    </form>
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
            axios.post('api/login', this.form).then(resposne => {
                let token = resposne.data.token
                this.signIn(token);
            }).catch(({response}) => {
                if (response.status === 422 || response.status === 401) {
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

