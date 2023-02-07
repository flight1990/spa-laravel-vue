<template>
    <h1>Register</h1>

    <form @submit.prevent="register">
        <div>
            <label for="name">Name</label>
            <input type="text" id="name" v-model="form.name" @focus="validationErrors.name = []">
            <small v-if="validationErrors.name">{{ validationErrors.name[0] }}</small>
        </div>

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

        <div>
            <label for="password_confirmation">Password confirmation</label>
            <input type="password" id="password_confirmation" v-model="form.password_confirmation" @focus="validationErrors.password_confirmation = []">
            <small v-if="validationErrors.password_confirmation">{{ validationErrors.password_confirmation[0] }}</small>
        </div>

        <button type="submit" :disabled="processing">Register</button>
        <router-link :to="{name: 'login'}" class="ms-3">Already have an account?</router-link>
    </form>

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
            axios.post('api/register', this.form).then(resposne => {
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
