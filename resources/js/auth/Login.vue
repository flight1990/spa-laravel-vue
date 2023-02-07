<template>
    <h1>Login</h1>

    <form @submit.prevent="login">

        <div>
            <label for="email">Email</label>
            <input type="text" id="email" v-model="form.email">
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" id="password" v-model="form.password">
        </div>

        <button type="submit">Login</button>
    </form>

</template>

<script>

import {mapActions} from "vuex";

export default {
    name: "Login",
    data() {
        return {
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
            axios.post('api/login', this.form).then(resposne => {
                let token = resposne.data.token
                this.signIn(token);
            }).catch(({response}) => {
                console.log(response.data)
            })
        }
    }
}
</script>

