<template>
    <div class="bg-light">
        <div class="container d-flex align-items-center justify-content-center min-vh-100">
            <form @submit.prevent="login">
                <h1 class="fw-bold">OTP Login</h1>

                <div class="alert alert-primary text-center" role="alert" v-if="userOtp">

                    <small>the code had to be sent via sms to a mobile phone: </small>
                    <b>{{ userOtp.otp }}</b>
                </div>

                <div>
                    <div class="mb-3">
                        <label for="otp" class="form-label">OTP code</label>
                        <input type="text" class="form-control" id="otp" v-model="form.otp" @focus="validationErrors.otp = []">
                        <small class="text-danger" v-if="validationErrors.otp">{{ validationErrors.otp[0] }}</small>
                    </div>
                </div>

                <button class="btn btn-primary me-2" :disabled="processing">Login with OTP</button>
                <router-link :to="{name: 'otp.generate'}">Generate OTP</router-link>
            </form>
        </div>
    </div>
</template>

<script>

import {mapActions, mapGetters} from 'vuex';

export default {
    name: "LoginOTP",
    computed: {
        ...mapGetters({userOtp: "otp/userOtp"})
    },
    data() {
        return {
            processing: false,
            validationErrors: {},
            form: {
                otp: "",
            }
        }
    },
    methods: {
        ...mapActions({signIn: "auth/signIn"}),
        login() {

            const data = {
                user_id: this.userOtp.user_id,
                ...this.form
            }

            this.processing = true
            axios.post('/api/otp/login', data).then(response => {

                const token = response.data.token
                this.signIn(token)

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

