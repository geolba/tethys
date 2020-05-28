// releaseDataset.js
import Vue from 'vue';
import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);


const app = new Vue({
    el: '#app1',
    data() {
        return {
            // dataset: {
            //     firstName: '',
            //     preferred_reviewer: null,
            //     preferred_reviewer_email: null
            // },
            dataset: window.Laravel.dataset,
            submitted: false,
            preferation: "no_preferation",
        }
    },
    computed: {
        isPreferationRequired() {
            return this.preferation === "yes_preferation";
        },
    },
    watch: {
        preferation(val) {
            if (val === "no_preferation") {
                this.dataset.preferred_reviewer = "";
                this.dataset.preferred_reviewer_email = "";
            }
        }
    },
    methods: {
        checkForm(e) {
            // Log entire model to console
            // console.log(this.dataset);
            this.submitted = true;
            this.$validator.validate().then(result => {
                if (result) {
                    if (this.preferation === "no_preferation") {
                        this.dataset.preferred_reviewer = "";
                        this.dataset.preferred_reviewer_email = "";
                    }
                    // console.log('From Submitted!');
                    document.getElementById("releaseForm").submit();
                    return;
                }
            });
        }
    }
});
