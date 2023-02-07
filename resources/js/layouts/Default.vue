<template>


    <button @click="logout">Logout</button>

    <router-view></router-view>
</template>

<script>

import {mapActions, mapGetters} from "vuex";

export default {
    name: "Default",
    computed: {
        ...mapGetters(
            {token: "auth/token", user: "auth/user"}
        )
    },
    methods: {
        ...mapActions(
                {signOut: "auth/signOut"},
        ),
        logout() {
            axios.post('api/logout', {}, {headers: {'Authorization': 'Bearer ' + this.token}}).then(response => {
                this.signOut()
            })
        },

    }
}
</script>

