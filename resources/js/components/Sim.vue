<!-- Componente SIM per il ciclo -->

<template>
    <div class="card" :class="card_bg">
        <div class="card-body">
            <div class="card-title">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="fs-3">Sim #{{ sim.id }}</h2>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="btn-group">
                            <a :href="`/sim/qrcode/${sim.iccid}/${sim.msisdn}`" target="_qrcode" class="btn btn-outline-primary" title="Show QR Code">
                                <i class="bi bi-qr-code"></i>
                            </a>
                            <button title="Update SIM data" type="button" class="btn" :class="{'btn-success' : !updating , 'btn-secondary disabled' : updating}" @click="updateSimData">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <p class="card-text">
                <strong>Alias</strong> : <span>{{ sim.name }}</span>
            </p>
            <div class="d-flex justify-content-between flex-column flex-md-row">
                <p class="card-text"><strong>ICCID</strong> : <span>{{ sim.iccid ?? 'Not setted' }}</span></p>
                <p class="card-text"><strong>MSISDN</strong> : <span>{{ sim.msisdn ?? 'Not setted' }}</span></p>
            </div>
            <div class="d-flex justify-content-between">
                <p class="card-text"><strong>Status</strong> : <span>{{ status_str ?? 'Not setted' }}</span></p>
                <p class="card-text"><strong>Voice</strong> : <span>{{ sim.voice ?? 'Not setted' }}</span></p>
            </div>
            <div class="d-flex justify-content-between">
                <p class="card-text"><strong>Data</strong> : <span>{{ sim.data ?? 'Not setted' }}</span></p>
                <p class="card-text"><strong>SMS</strong> : <span>{{ sim.sms ?? 'Not setted' }}</span></p>
            </div>
            <div class="d-flex justify-content-between">
                <p class="card-text"><strong>Rate</strong> : <span>{{ sim.rate ?? 'Not setted' }}</span></p>
                <p class="card-text"><strong>Expire</strong> : <span>{{ sim.expires_in ?? 'Not setted' }}</span></p>
            </div>
            <hr>
            <div class="d-flex justify-content-center">
                <button v-if="sim.status == 5" class="btn btn-success" @click="reactivateSim(sim.id)" title="Riattiva">
                    <i class="bi bi-check2-circle"></i>
                </button>
                <div class="btn-group" v-if="sim.status == 3">
                    <button v-if="sim.status" class="btn btn-warning" @click="suspendSim(sim.id)" title="Sospendi">
                        <i class="bi bi-pause-fill"></i>
                    </button>
                    <button v-if="sim.status" class="btn btn-danger" @click="deactivateSim(sim.id)" title="De-attiva">
                        <i class="bi bi-exclamation-circle"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-footer">
            Assigned to: <a :class="a_bg" :href="`/users/edit/${user_id}`"><i>{{ user }}</i></a>
        </div>
    </div>
</template>
<script>
import axios from 'axios';

export default {
    props : ['sim'],
    data() {
        return {
            user        : null,
            user_id     : null,
            updating    : false,
            card_bg     : '',
            a_bg        : '',
            status_str  : '',
            errors      : [],
        }
    },
    methods : {
        cardBg() {
            switch(this.sim.status) {
            //Inactive
            case 1:
                this.card_bg = 'text-bg-light';
                this.a_bg = '';
                this.status_str = 'Inactive'
                break;

            //Pre-activated
            case 2:
                this.card_bg = 'text-bg-celestial';
                this.a_bg = 'link-light';
                this.status_str = 'Pre-Active'
                break;

            //Active
            case 3:
                this.card_bg = 'text-bg-green';
                this.a_bg = '';
                this.status_str = 'Active'
                break;

            //Cancelled
            case 4:
                this.card_bg = 'text-bg-danger';
                this.a_bg = '';
                this.status_str = 'Cancelled'
                break;

            //Suspended
            case 5:
                this.card_bg = 'text-bg-sunrise';
                this.a_bg = '';
                this.status_str = 'Suspended'
                break;

            //Retired
            case 6:
                this.card_bg = 'text-bg-dark';
                this.a_bg = '';
                this.status_str = 'Retired'
                break;

            }
        },
        // Chiamata asincrona per il caricamento tramite API delle informazioni delle SIM
        updateSimData() {
            this.updating = true;
            this.card_bg = 'text-bg-warning';

            axios.get(`/api/updateSimData/${this.sim.id}`).then(response => {

                this.sim.iccid      = response.data.iccid;
                this.sim.msisdn     = response.data.msisdn;
                this.sim.status     = response.data.status;
                this.sim.rate       = response.data.rate;
                this.sim.voice      = response.data.voice;
                this.sim.data       = response.data.data;
                this.sim.sms        = response.data.sms;

                this.updating = false;

                this.cardBg()
            }).catch(error => {
                if(error.request.status == 400) {
                    const errors = error.response.data;

                    errors.forEach(er => {
                        /**
                         * * Gestione degli errori con display di essi
                         */
                        this.errors += er.error
                    })
                    console.log(this.errors);
                }
            })
        },
        reactivateSim(id) {
            const sim = {
                id : id,
                status : 3
            };
            axios.post('/api/sim/changeSimStatus', sim).then(response => {

            }).catch(error => {

            }) 
        },
        suspendSim(id) {
            const sim = {
                id : id,
                status : 5
            };
            
            axios.post('/api/sim/changeSimStatus', sim).then(response => {

            }).catch(error => {

            }) 
        },
        deactivateSim(id) {
            const sim = {
                id : id,
                status : 4
            };

            let r = confirm("Quest'azione cancellerà la SIM e sarà recuperabile solo dopo l'intervento di CSL. Sei sicuro?");

            if(r) {
                axios.post('/api/sim/changeSimStatus', sim).then(response => {
    
                }).catch(error => {
    
                }) 
            }
        },
    },
    created() {
        /**
         * Alla creazione del componente, recupera se la SIM è stata assegnata o no e quindi il nome, cognome e l'email dell'utente.
         * Successivamente cambia lo sfondo della card a seconda dello stato della SIM
         */

        if(this.sim.user_id != null) {
            axios.get(`/users/api/getUser/${this.sim.user_id}`).then(response => {
                this.user = `${response.data.Name} ${response.data.Surname} - ${response.data.email}`
                this.user_id = response.data.id;
            })
        } else {
            this.user = 'Not assigned';
        }

        this.cardBg();
    }
}
</script>
<style>
    
</style>