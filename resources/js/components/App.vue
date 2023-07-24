<!-- Template di base per la selezione dei componenti viste -->

<template lang="">
    <component :is='selectedComponent' /> <!-- Componenti dinamici -->
</template>
<script>
export default {
    data() {
        return {
            selectedComponent : '',
        }
    },
    mounted() {
        // Recupera la URI e switcha il componenete Vista
        switch (window.location.pathname) {
            case '/sims':
                this.selectedComponent = 'SimsView';
                break;
        
            case '/users':
                this.selectedComponent = 'UsersView';
                break;

            case '/admins':
                this.selectedComponent = 'AdminsView';
                break;

            case '/sims/rates':
                this.selectedComponent = 'RatesView';
                break;
            default:
                break;
        }
    }
}
</script>
<style lang="">
    
</style>