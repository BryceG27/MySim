import {createApp} from 'vue/dist/vue.esm-bundler';
import App from './components/App.vue';
import UsersView from './components/UsersView.vue';
import SimsView from './components/SimsView.vue';
import AdminsView from './components/AdminsView.vue';
import RatesView from './components/RatesView.vue';
import axios from 'axios';

const options = {
    data() {
        return {
            checkData       : true,
            isCompany       : false,
            showRegister    : false,
            updating        : false,
            updateUser      : false,
            registered      : false,
            use_captcha     : false,
            aliasError      : false,
            errorName       : '',
            page            : 1,
            icc             : '',
            msisdn          : '',
            simError        : '',
            simStatus       : '',
            loginPanel      : 'Login',
            simName         : '',
        }
    },
    methods: {
        checkSim() {
            const sim = {
                icc : this.icc,
                msisdn : this.msisdn
            }

            axios.post('/api/checkSim', sim).then((response) => {

                switch (response.data) {
                    case 'ok':
                        this.checkData = false;
                        return true;
                
                    case 'ko':
                        this.simError = '- Sim not found';
                        return false;

                    case 'in use':
                        this.simError = '- Sim already in use. Please Login.';
                        return false;

                    default:
                        break;
                }
            })
        },
        getICC_MSISDN() {
            const urlParams = new URLSearchParams(window.location.search);
            this.icc = urlParams.get('icc');
            this.msisdn = urlParams.get('msisdn');

            if((this.icc == null && this.msisdn == null) || urlParams.get('check') == 'ok')
                this.checkData = false;
        },
        changePage() {
            if(!this.isCompany) {
                this.isCompany = true;
                this.page = 2;
            } else {
                this.isCompany = false;
                this.page = 1;
            }
        },
        changeUserStatus(id) {

            axios.put('/users/api/active/' + id).then((response) => {
                if(response) 
                    window.location.reload();
            })
        },
        iHaveASim() {
            flip_card_inner.style = "transform: rotateY(180deg)";
            this.showRegister = true;
        },
        iDontHaveASim() {
            flip_card_inner.style = "transform: rotateY(0deg)";
            this.showRegister = false;
            this.simError = ''
        },
        async register() {
            const sim = {
                icc : this.icc,
                msisdn : this.msisdn
            }

            axios.post('/api/checkSim', sim).then((response) => {

                switch (response.data) {
                    case 'ok':
                        location.assign(`register/${this.icc}/${this.msisdn}/ok`);
                        break;
                
                    case 'ko':
                        this.simError = '- Sim not found';
                        break;

                    case 'in use':
                        this.simError = '- Sim already in use. Please Login.';
                        break;

                    default:
                        break;
                }
            })
        },
        updateSimData(id) {
            this.updating = true;

            axios.get(`/api/updateSimData/${id}`).then(response => {
                
                document.querySelector(`#iccid_${id}`).innerText = response.data.iccid
                document.querySelector(`#msisdn_${id}`).innerText = response.data.msisdn
                document.querySelector(`#status_${id}`).innerText = response.data.status
                document.querySelector(`#rate_${id}`).innerText = response.data.rate
                document.querySelector(`#voice_${id}`).innerText = response.data.voice
                document.querySelector(`#data_${id}`).innerText = response.data.data
                document.querySelector(`#sms_${id}`).innerText = response.data.sms

                this.updating = false;
            })
        },
        updateSimDataByUser(id) {
            this.updating = true;

            axios.get(`/api/updateSimDataByUser/${id}`).then(response => {

                window.location.reload()
                this.updating = false;
            })
        },
        associateSim() {
            const sim = {
                'icc' : this.icc,
                'msisdn' : this.msisdn,
                'directAdd' : true
            }

            axios.post('/api/checkSim', sim).then(response => {
                switch(response.data) {
                    case 'in use':
                        this.simStatus  = 'alert-warning';
                        this.simError   = 'Sim already in use';
                        this.icc = '';
                        this.msisdn = '';
                        break;

                    case 'ok':
                        this.simStatus  = 'alert-success';
                        this.simError   = 'Sim added to your account';
                        this.icc = '';
                        this.msisdn = '';
                        break;
                        
                    case 'ko':
                    default:
                        this.simStatus  = 'alert-danger';
                        this.simError   = 'Sim not found.';
                        break;
                }

            })
        },
        verifyCaptcha(type) {
            if(type == 'button')
                this.use_captcha = true;
            else
                this.use_captcha = false;
        },
        editName(simID) {
            document.getElementById(`divSim-${simID}`).classList.add('d-none');
            document.getElementById(`divSimInput-${simID}`).classList.remove('d-none');
        },
        saveSimName(simID) {

            const sim = {
                'id' : simID,
                'newName' : this.simName,
            }

            const regex = /^[a-zA-Z0-9_ -]+$/;

            if(this.simName != '') {
                if (this.simName.length <= 10) {
                    if (regex.test(this.simName)) {
                        axios.post('/api/update/simName', sim).then(response => {
                            document.getElementById(`spanSimName-${simID}`).textContent = this.simName;
                        }).catch(error => {
                            console.log(error);
                        })
                        this.errorName = '';
                        this.aliasError = false;

                        document.getElementById(`divSim-${simID}`).classList.remove('d-none');
                        document.getElementById(`divSimInput-${simID}`).classList.add('d-none');
                    } else {
                        this.errorName = 'special';
                        this.aliasError = true;
                    }
                } else {
                    this.errorName = 'length';
                    this.aliasError = true;
                }
            } else {
                this.errorName = 'empty';
                this.aliasError = true;
            }
        }
    },
    mounted() {
        this.getICC_MSISDN();
    },
    updated() {

    }
};

const app = createApp(options);
app.component('App', App);
app.component('UsersView', UsersView);
app.component('SimsView', SimsView);
app.component('AdminsView', AdminsView);
app.component('RatesView', RatesView);
app.mount('#app');