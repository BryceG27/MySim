<template>
    <div class="container">
        <div class="row">
            <div class="col-md-3 text-start">
                <div class="btn-group">
                    <button class="btn btn-outline-primary" @click="getUsers">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <h1 class="text-center">Users 
                </h1>
            </div>
        </div>
        <div class="row">
            <div v-for="user in users" class="col-12 col-md-4 my-2">
                <User @changeStatus="changeStatusFn" :data="user" :sims="sims"/>
            </div>
        </div>
    </div>
</template>
<script>
import User from './User.vue';

export default {
    components : {
        User : User
    },
    data() {
        return {
            users : [],
            sims : []
        }
    },
    methods : {
        getUsers() {
            axios.get('/users/api').then((response) => {
                this.users = response.data.users;
                this.sims = response.data.sims;
            })
        },
        changeStatusFn(id) {
            const user = this.users.find((user) => user.id === id);
            
            axios.put('/users/api/active/' + id).then((response) => {
                if(response) 
                    user.Active = !user.Active;
            })
        }
    },
    mounted() {
        this.getUsers();
    },
}
</script>
<style>
    
</style>