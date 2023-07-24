<template>
    <td class="text-center">
        <div class="btn-group">
            <button class="btn btn-outline-warning" @click="editRate(rate.id)">
                <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-outline-danger" @click="$emit('delete', rate.id)">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </td>
    <td class="text-center">
        {{ rate.id }}
    </td>
    <td class="text-center">
        <span :id="'rate-' + rate.id">{{ rate.rate }}</span>
    </td>
    <td class="text-center">
        <span :id="`alias-${rate.id}`">{{ rate.alias }}</span>
        <input type="numeric" class="form-control text-end d-none" :id="`input-alias-${rate.id}`" :placheholder="rate.alias">
    </td>
    <td class="text-end">
        <span :id="`voice-${rate.id}`">{{ rate.voice }}</span>
        <input type="numeric" class="form-control text-end d-none" :id="`input-voice-${rate.id}`" :placeholder="rate.voice">
    </td>
    <td class="text-end">
        <span :id="`data-${rate.id}`">{{ rate.data }}</span>
        <input type="numeric" class="form-control text-end d-none" :id="`input-data-${rate.id}`" :placeholder="rate.data">
    </td>
    <td class="text-end">
        <span :id="`sms-${rate.id}`">{{ rate.sms }}</span>
        <input type="numeric" class="form-control text-end d-none" :id="`input-sms-${rate.id}`" :placeholder="rate.sms">
    </td>
    <td class="text-center">
        <span :id="`note-${rate.id}`">{{ rate.notes }}</span>
        <div class="form-floating d-none" :id="`div-note-${rate.id}`">
            <textarea class="form-control" placeholder="Nota su Profilo" :id="`input-note-${rate.id}`">{{ rate.notes }}</textarea>
            <label :for="`input-note-${rate.notes}`">Note</label>
        </div>
    </td>
</template>
<script>
import axios from 'axios';

export default {
    props : {
        rate : Object,
        keyProp: {
            type : Number,
            required : true
        }
    },
    methods: {
        editRate(id) {
            document.getElementById(`input-voice-${id}`).classList.toggle("d-none");
            document.getElementById(`input-data-${id}`).classList.toggle("d-none");
            document.getElementById(`input-sms-${id}`).classList.toggle("d-none");
            document.getElementById(`input-alias-${id}`).classList.toggle("d-none");
            document.getElementById(`div-note-${id}`).classList.toggle("d-none");
            document.getElementById(`voice-${id}`).classList.toggle("d-none");
            document.getElementById(`alias-${id}`).classList.toggle("d-none");
            document.getElementById(`data-${id}`).classList.toggle("d-none");
            document.getElementById(`sms-${id}`).classList.toggle("d-none");

            const voice = document.getElementById(`input-voice-${id}`).value;
            const data = document.getElementById(`input-data-${id}`).value;
            const sms = document.getElementById(`input-sms-${id}`).value;
            const alias = document.getElementById(`input-alias-${id}`).value;
            const note = document.getElementById(`input-note-${id}`).value;

            const ratesParams = {
                id : id,
                voice : voice,
                data : data,
                sms : sms,
                alias : alias,
                note : note
            }

            if(document.getElementById(`input-alias-${id}`).classList.contains('update')) {
                axios.put('/sims/rates/api/update', ratesParams).then(response => {

                    document.getElementById(`voice-${id}`).textContent = voice;
                    document.getElementById(`alias-${id}`).textContent = alias;
                    document.getElementById(`data-${id}`).textContent = data;
                    document.getElementById(`sms-${id}`).textContent = sms;
                    document.getElementById(`note-${id}`).textContent = note;

                }).catch(errors => {
                    if(error.response.status == 500) {
                        alert("Errore nella modifica del Profilo SIM")
                    }
                });
            }

            document.getElementById(`input-alias-${id}`).classList.toggle("update");
        }
    },
    data() {
        return {

        }
    },
    mounted() {
    },
}
</script>
<style>
    
</style>