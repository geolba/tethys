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
                preferred_reviewer: '',
                preferred_reviewer_email: ''
            },
            submitted: false,
            preferation: "no_preferation",
        }
    },
    computed: {
        isPreferationRequired() {
            return this.preferation === "yes_preferation";
        },
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
