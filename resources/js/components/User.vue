<template lang="">
    <div class="card text-white" :class="{'bg-success' : data.Active, 'bg-danger' : !data.Active}">
        <div class="card-body">
            <h3>User: {{ data.Name }} {{ data.Surname }}</h3>
            <p class="card-text">
                <strong>EMail:</strong> {{ data.email }}
            </p>
            <p class="card-text">
                <strong>Sim assigned:</strong> 
                <span v-for="sim in sims[data.id]">&nbsp;
                     {{ sim.user_id == data.id ? `#${sim.id}` : `No sim` }}
                </span>
            </p>
            <p class="card-text">
                <strong>Notes:</strong> {{ data.Notes }}
            </p>
            <p class="card-text">
                <strong>Verified:</strong> <i class="bi" :class="{ 'bi-check-lg' : data.email_verified_at != undefined, 'bi-x-lg' : data.email_verified_at == undefined}"></i>
            </p>
        </div>
        <div class="card-footer">
            <div class="text-center">
                <div class="btn-group">
                    <a v-bind:href="'/users/edit/' + data.id" class="btn btn-warning">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <button type="button" @click="$emit('changeStatus', data.id)" class="btn" :class="{'btn-success' : !data.Active, 'btn-danger' : data.Active}">
                        <i class="bi" :class="{'bi-toggle-off': !data.Active, 'bi-toggle-on': data.Active}"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props : ['data', 'sims'],
    methods : {

    },
    data() {
        return {

        }
    }
}
</script>
<style>
    
</style>