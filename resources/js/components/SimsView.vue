<template>
    <h1 class="mt-5 mx-auto" id="h1Loader" style="display: none; position: absolute; top: 25%; left: 35%; z-index: 1000;">
        Upload del file CSV in corso
        <p class="text-center fs-4 pt-3">Totale righe: <span class="text-warning" id="csv_count"></span></p>
    </h1>
    <div id="loader" style="display: none; position: absolute; top: 50%; left: 47%; z-index: 1000"></div>

    <div class="container">
        <div class="row">
            <div class="col-md-4 text-start">
                <div class="btn-group">

                    <button class="btn btn-outline-primary" @click="getSims" title="Update page" id="updatePage">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>

                    <a :href='"/sim/qrcode?from="+ codesFrom +"&to="+ codesTo +" "' target="_sims" class="btn btn-outline-danger" title="Print all QRCodes">
                        <i class="bi bi-qr-code"></i>
                    </a>

                    <button class="btn btn-outline-warning" @click="simCreated = false, simError = '', iccid = '', msisdn = ''" title="Create a new Sim" data-bs-toggle="modal" data-bs-target="#newSim">
                        <i class="bi bi-plus-lg"></i>
                    </button>

                    <button id="openModal" class="d-none" data-bs-toggle="modal" data-bs-target="#modalChoice"></button>

                    <button type="button" class="btn btn-outline-info" v-show="!csvForm" @click="csvForm = true">
                        <i class="bi bi-box-arrow-in-up"></i>
                    </button>

                    <div class="input-group" v-if="csvForm">
                        <input type="file" name="csv" ref="csv" id="uploadCSVSim" class="form-control" accept=".csv">
                        <button class="btn btn-outline-success" type="button" @click="uploadCSV">
                            <i class="bi bi-box-arrow-in-up"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <h1 class="text-center">SIM</h1>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-5" id="simContainer">
        <div class="row">
            <div class="col-2 border border-2 border-light">
                <h4 class="text-center py-2">Filtri</h4>

                <hr>

                <div class="mb-3">
                    <label for="printQR" class="form-label fw-bold">Stampa QR Code <strong>da a</strong> per ID</label>
                    <div class="input-group">
                        <input type="numeric" class="form-control" v-model="codesFrom">
                        <span class="input-group-text">a</span>
                        <input type="numeric" class="form-control" v-model="codesTo">
                    </div>
                </div>

                <hr>

                <div class="form-check">
                    <input type="radio" name="assigned" class="form-check-input" v-model="filter_assigned" value="1" id="assigned">
                    <label for="assigned" class="form-check-label">SIM assegnata</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="assigned" class="form-check-input" id="notAssigned" v-model="filter_assigned" value="0">
                    <label for="notAssigned" class="form-check-label">SIM non assegnata</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="assigned" class="form-check-input" id="allCases" v-model="filter_assigned" value="2">
                    <label for="allCases" class="form-check-label">Tutte</label>
                </div>
                <h6 class="fw-bold pt-3">Stato</h6>
                <div class="ms-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="1" v-model="filter_status" id="inactive">
                        <label for="inactive" class="form-check-label">Inactive</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="2" v-model="filter_status" id="pre_active">
                        <label for="pre_active" class="form-check-label">Pre-Active</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="3" v-model="filter_status" id="active">
                        <label for="active" class="form-check-label">Active</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="4" v-model="filter_status" id="cancelled">
                        <label for="cancelled" class="form-check-label">Cancelled</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="5" v-model="filter_status" id="suspended">
                        <label for="suspended" class="form-check-label">Suspended</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" value="6" v-model="filter_status" id="retired">
                        <label for="retired" class="form-check-label">Retired</label>
                    </div>
                </div>

                <div class="my-3">
                    <label for="iccid_search" class="form-label fw-bold">ICCID</label>
                    <input type="numeric" class="form-control" id="iccid_search" v-model="iccid_search">
                </div>

                <div class="my-3">
                    <label for="msisdn_search" class="form-label fw-bold">MSISDN</label>
                    <input type="numeric" class="form-control" id="msisdn_search" aria-describedby="emailHelp" v-model="msisdn_search">
                </div>

                <div class="my-3">
                    <label for="email" class="form-label fw-bold">EMail</label>
                    <input type="text" class="form-control" id="email" aria-describedby="emailHelp" v-model="email">
                </div>

                <div class="btn-group mb-3 d-flex justify-content-center">
                    <button type="button" @click="setFilters" class="btn btn-outline-primary">Set</button>
                    <button type="button" @click="resetFilters" class="btn btn-primary">Reset</button>
                </div>

                <hr>

            </div>
            <div class="col-10">
                <div class="row">
                    <div class="col-12 col-md-6 col-xxl-4 my-2" v-for="sim in sims">
                        <Sim :sim="sim" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newSim" tabindex="-1" aria-labelledby="newSimLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-fullscreen-lg-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="newSimLabel">Create a new sim</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" v-show="simCreated">
                        <div class="col-md-8 offset-md-2 alert alert-info">Sim created.</div>
                    </div>
                    <div class="row" v-show="simError">
                        <div class="col-md-8 offset-md-2 alert alert-danger">- {{ simError }}</div>
                    </div>
                    <form action="">
                        <div class="form-floating mb-3">
                            <input type="numeric" class="form-control" id="iccid" placeholder="1111111111" v-model="iccid">
                            <label for="iccid">ICCID</label>
                            <i class="text-danger" ref="iccid"></i>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="numeric" class="form-control" id="msisdn" placeholder="1111111111" v-model="msisdn">
                            <label for="msisdn">MSISDN</label>
                            <i class="text-danger" ref="msisdn"></i>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <button type="submit" @click.prevent="submit" class="btn btn-outline-success">
                            <i class="bi bi-check-lg"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger " data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-alert py-5" tabindex="-1" role="dialog" id="modalChoice">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-body p-4 text-center">
                    <h5 class="mb-3">Errore</h5>
                    <p class="mb-2" id="errors"></p>
                </div>
                <div class="modal-footer flex-nowrap p-0">
                    <button type="button"  class="btn btn-lg btn-link fs-6 text-decoration-none col-12 m-0 rounded-0 border-end" data-bs-dismiss="modal"><strong>Ok</strong></button>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import axios from 'axios';
import Sim from './Sim.vue';

export default {
    components : {
        Sim : Sim
    },
    data() {
        return {
            sims            : [],
            codesFrom       : 0,
            codesTo         : 0,
            iccid           : null,
            msisdn          : null,
            simCreated      : false,
            csvForm         : false,
            // --- Filters section
            iccid_search    : '',
            msisdn_search   : '',
            filter_assigned : 2,
            filter_status   : [],
            email           : '',
            // --- End filters section
            status          : '',
            voice           : '',
            sms             : '',
            rate            : '',
            data            : '',
            simError        : '',
        }
    },
    methods : {
        getSims() {
            axios.get('/api/getSims').then((response) => {
                this.sims = response.data
            })
        },
        // Metodo per aaggiungere una nuova SIM al DB
        submit() {
            this.simCreated = false;

            var newSim = {
                'iccid'      : this.iccid,
                'status'     : this.status,
                'voice'      : this.voice,
                'sms'        : this.sms,
                'msisdn'     : this.msisdn,
                'rate'       : this.rate,
                'data'       : this.data,
            };

            Object.keys(this.$refs).forEach(field => {
                this.$refs[field].textContent = '';
            })

            axios.post('/api/sim/new', newSim).then((response) => {

                Object.keys(newSim).forEach(field => {
                    console.log(this[field]);
                    this[field] = '';
                })

                if(response.statusText === 'OK') {
                    this.getSims();
                    this.simCreated = true;
                }

            }).catch(error => { // Se la chiamata non va a buon fine, catch dell'errore e mostralo
                if(error.response) {
                    if(error.response.status == 400 || error.response.status == 404) {

                        this.simError = error.response.data;

                    } else {
                        const errors = error.response.data.errors;
    
                        Object.keys(errors).forEach(field => {
                            this.$refs[field].textContent = `- ${errors[field]}`;
                        })
                    }
                }
            });

        },
        uploadCSV() {
            // Metodo per l'upload del file CSV
            const file = this.$refs.csv.files[0];

            if(file == undefined) {
                errors.textContent = "Non hai selezionato alcun file.";
                document.getElementById('openModal').click();
            } else {
                simContainer.style.opacity = '0.1';
                document.getElementById('simContainer').classList.add('loading');
                loader.style.display = 'inline';
                h1Loader.style.display = 'inline';

                let formData = new FormData();
                let formDataRows = new FormData();

                formData.append('csv', file);

                formDataRows.append('csv', file);
                formDataRows.append('getRows', true);

                axios.post('/upload/csv', formDataRows).then(response => {
                    csv_count.textContent = response.data
                })

                axios.post('/upload/csv', formData).then(response => {

                    if(response.statusText == 'OK' || response.status == 200) {
                        simContainer.style.opacity = '100';
                        document.getElementById('simContainer').classList.remove('loading');
                        loader.style.display = 'none';
                        h1Loader.style.display = 'none';

                        document.getElementById('updatePage').click();
                    }
                    
                    var responses = response.data;

                    responses.forEach(response => {
                        if(response[0] != 200)
                            console.log(response[1]);
                    })
                }).catch(error => {
                    if(error.response) {
                        console.log(error.response);

                        if(error.response.status == 400 || error.response.status == 404) {
                            simContainer.style.opacity = '100';
                            document.getElementById('simContainer').classList.remove('loading');
                            loader.style.display = 'none';
                            h1Loader.style.display = 'none';
                            errors.textContent = error.response.data;
                            document.getElementById('openModal').click();
                        }
                    }
                })
            }
        },
        setFilters() {
            const filters = {
                status      : this.filter_status,
                iccid       : this.iccid_search,
                msisdn      : this.msisdn_search,
                assigned    : this.filter_assigned,
                email       : this.email
            }

            axios.post('/api/getSimsByFilter', filters).then(response => {
                this.sims = response.data;
            });
        },
        resetFilters() {
            this.filter_status = '';
            this.iccid_search = '';
            this.msisdn_search = '';
            this.filter_assigned = 2;
            this.email = '';
            this.getSims();
        }
    },
    mounted() {
        // Al mount, recupeaa tutte le SIM
        this.getSims();
    }
}
</script>
<style>
#loader {
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading * {
    pointer-events: none;
    cursor: wait;
}
</style>