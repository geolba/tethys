// releaseDataset.js
import Vue from 'vue';
import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);


const app = new Vue({
    el: '#app1',
    data() {
        return {
            dataset: {
                firstName: '',
                editor_id: ''
            },
            submitted: false
        }
    },
    methods: {
        checkForm(e) {
            // Log entire model to console
            // console.log(this.dataset);
            this.submitted = true;
            this.$validator.validate().then(result => {
                if (result) {
                    console.log('From Submitted!');
                    document.getElementById("releaseForm").submit();
                    return;
                }
            });
        }
    }
});
