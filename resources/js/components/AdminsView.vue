<!-- 
    Componenete padre degli admin
 -->

<template>
    <div class="container">
        <div class="row">
            <div class="col-md-3 text-start">
                <div class="btn-group">
                    <button class="btn btn-outline-primary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <h1 class="text-center">Admins</h1>
            </div>
        </div>
        <div class="row">
            <div v-for="admin in admins" class="col-12 col-md-4 my-2">
                <Admin @changeStatus="changeStatusFn" :admin="admin"/>
            </div>
        </div>
    </div>
</template>
<script>
// Import del componente per il ciclo
import Admin from './Admin.vue';

export default {
    components : {
        Admin : Admin
    },
    data () {
        return {
            admins : [],
        }
    },
    methods : {
        // Metodo asincrono per recuperare dal DB tutti gli admin
        getAdmins() {
            axios.get('/admins/api').then((response) => {
                this.admins = response.data;
            })
        },

        // Metodo asincrono per attivare/disattivare un admin
        changeStatusFn( id ) {
            const admin = this.admins.find((admin) => admin.id === id);
            
            axios.put('/users/api/active/' + id).then((response) => {
                if(response) 
                    admin.Active = !admin.Active;
            })
        }
    },
    mounted() {
        // All'apertura della pagina, carica tutti gli admin
        this.getAdmins();
    },
}
</script>
<style>
    
</style>