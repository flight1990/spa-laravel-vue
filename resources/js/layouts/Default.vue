<template>

    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <router-link class="nav-link" :to="{name: 'users.index'}">User</router-link>
                    </li>
                    <li class="nav-item dropdown" v-if="user">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ user.name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" @click="logout">Logout</a></li>
                        </ul>
                    </li>
                </ul>

            </div>
        </div>
    </nav>

    <div class="container my-4">
        <router-view></router-view>
    </div>

</template>

<script>

import {mapActions, mapGetters} from "vuex";

export default {
    name: "Default",
    computed: {
        ...mapGetters({token: "auth/token", user: "auth/user"})
    },
    methods: {
        ...mapActions(
            {signOut: "auth/signOut", deleteUserOtp: "otp/deleteUserOtp"},
        ),
        logout() {

            const config = {
                headers: {'Authorization': 'Bearer ' + this.token}
            }

            axios.post('/api/logout', {}, config).then(() => {
                this.deleteUserOtp()
                this.signOut()
            })
        },
    }
}
</script>

