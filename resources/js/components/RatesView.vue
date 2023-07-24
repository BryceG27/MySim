<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h1 class="text-center">Profili SIM</h1>
            </div>
        </div>

        <div class="row py-3">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr class="table-active">
                            <th></th>
                            <th class="text-center">id</th>
                            <th class="text-center">Alias</th>
                            <th class="text-center">Nome</th>
                            <th class="text-end">Voice</th>
                            <th class="text-end">Data</th>
                            <th class="text-end">SMS</th>
                            <th class="text-center">Note</th>
                        </tr>
                    </thead>
                    <tbody v-if="rates.length > 0">
                        <tr v-for="rate in rates" :id="rate.id" :key="rate.id">
                            <Rate :rate="rate" :key-prop="rate.id" @delete="deleteRate"/>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="8" class="text-center">
                                Nessun profilo SIM trovato
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import Rate from './Rate.vue';

export default {
    components : {
        Rate : Rate
    },
    data () {
        return {
            rates : [],
        }
    },
    methods: {
        getRates() {
            axios.get('/sims/rates/api').then((response) => {
                this.rates = response.data;
            })
        },
        deleteRate(id) {
            const params = {
                id : id
            };
            
            axios.post('/sims/rates/api/delete', params).then((response) => {
                if(response.status == 200) {
                    document.getElementById(id).remove()
                }
                    
            }).catch(error => {
                if(error.response.status == 404)
                    alert("Error deleting the Rate");
            })
        }
    },
    mounted() {
        this.getRates();
    }
}
</script>
<style>
    
</style>