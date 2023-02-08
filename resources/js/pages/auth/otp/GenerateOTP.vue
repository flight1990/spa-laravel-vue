<template>
    <div class="bg-light">
        <div class="container d-flex align-items-center justify-content-center min-vh-100">
            <form @submit.prevent="login">
                <h1 class="fw-bold">OTP Login</h1>

                <div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" v-model="form.phone" @focus="validationErrors.phone = []">
                        <small class="text-danger" v-if="validationErrors.phone">{{ validationErrors.phone[0] }}</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary me-2" :disabled="processing">Generate OPT</button>

                <router-link :to="{name: 'login'}">Login With Email</router-link>
                <router-link :to="{name: 'register'}" class="ms-3">Don't have an account?</router-link>

            </form>
        </div>
    </div>
</template>

<script>

import {mapActions} from "vuex";

export default {
    name: "LoginOTP",
    data() {
        return {
            processing: false,
            validationErrors: {},
            form: {
                phone: ""
            }
        }
    },
    methods: {
        ...mapActions({saveUserOtp: "otp/saveUserOtp"}),
        login() {
            this.processing = true
            axios.post('/api/otp/generate', this.form).then(response => {

                const userOtp = response.data
                this.saveUserOtp(userOtp);


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

<style scoped>

</style>
