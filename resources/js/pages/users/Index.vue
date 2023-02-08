<template>
    <h1 class="fw-bold">Users</h1>

    <router-link :to="{name: 'users.create'}">Create new user</router-link>

    <div class="row">

        <div class="col-md-2 mb-3">
            <label for="column" class="form-label">Column</label>
            <select class="form-select" id="column" v-model="filters.order.column">
                <option value="id">ID</option>
                <option value="name">Name</option>
                <option value="email">Email</option>
            </select>
        </div>

        <div class="col-md-2 mb-3">
            <label for="sort" class="form-label">Sort</label>
            <select class="form-select" id="sort" v-model="filters.order.sort">
                <option value="ASC">ASC</option>
                <option value="DESC">DESC</option>
            </select>
        </div>

        <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" v-model="filters.status">
                            <option value="" selected>All</option>
                            <option value="active">Active only</option>
                            <option value="banned">Banned only</option>
                            <option value="online">Online only</option>
                            <option value="offline">Offline only</option>
                        </select>
        </div>

        <div class="col-md-6 mb-3">
            <label for="search" class="form-label">Search</label>
            <input type="search" id="search" class="form-control" placeholder="Search..." v-model="filters.query">
        </div>

    </div>

    <div class="table-responsive mt-3">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Banned At</th>
                <th>Operation</th>
            </tr>
            </thead>
            <tbody v-if="users && users.data?.length">
            <tr v-for="user in users.data">
                <td>{{ user.id }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.phone }}</td>
                <td>{{ user.banned_at }}</td>
                <td>
                    <router-link :to="{name: 'users.edit', params: {id:user.id}}">Edit</router-link>
                </td>
            </tr>
            </tbody>
            <tbody v-else>
            <tr>
                <td colspan="8" class="text-muted text-center">{{ isLoadingUsers ? 'Loading data...' : 'No data found...' }}</td>
            </tr>
            </tbody>
        </table>
    </div>

    <Bootstrap5Pagination v-if="users"
        :data="users"
        @pagination-change-page="getUsers"/>

</template>

<script>

import {mapActions, mapGetters} from "vuex";
import {debounce} from "lodash";
import { Bootstrap5Pagination } from 'laravel-vue-pagination';

export default {
    name: "Index",
    components: {
        Bootstrap5Pagination
    },
    computed: {
        ...mapGetters({users: "users/users", isLoadingUsers: "users/isLoadingUsers"})
    },
    data() {
        return {
            filters: {
                query: "",
                status: "",
                order: {
                    column: "id",
                    sort: "ASC"
                }
            }
        }
    },
    watch: {
        filters: {
            handler: debounce(function () {
                this.getUsers();
            }, 200),
            deep: true,
            immediate: false
        }

    },
    methods: {
        ...mapActions({
            fetchUsers: "users/fetchUsers"
        }),
        getUsers(page = 1) {

            const params = {
                page: page,
                ...this.filters
            }

            this.fetchUsers(params);
        }
    },
    mounted() {
        this.getUsers()
    }
}
</script>
