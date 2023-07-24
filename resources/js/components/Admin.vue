<!-- 
    Componente Admin per il ciclo degli stessi
 -->

<template>
    <div class="card" :class="{'bg-info' : admin.Active, 'bg-warning' : !admin.Active}">
        <div class="card-body">
            <div class="card-title">
                <div class="row">
                    <h2 class="text-center">{{ admin.Name }} {{ admin.Surname }}</h2>
                    <p class="card-text text-center">
                        {{ admin.email }}
                    </p>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="text-center">
                <button class="btn" @click="$emit('changeStatus', admin.id)" :class="{'btn-success' : !admin.Active, 'btn-danger': admin.Active}">
                    <i class="bi" :class="{'bi-toggle-on' : !admin.Active, 'bi-toggle-off': admin.Active}"></i>
                </button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props : ['admin']
}
</script>
<style>
    
</style>