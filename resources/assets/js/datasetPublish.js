/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
// window.axios = require('axios');
// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */
// let token = document.head.querySelector('meta[name="csrf-token"]');

// if (token) {
//     window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
// }
// else {
//     console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
// }
// window._ = require('lodash');
// window.Vue = require('vue');
// Vue.prototype.$http = axios;

import Vue from 'vue';
import axios from 'axios';

// Vue.component('example', require('./components/Example.vue'));
//Vue.component('my-autocomplete', require('./components/MyAutocomplete.vue'));
import MyAutocomplete from './components/MyAutocomplete.vue';
import VeeValidate from 'vee-validate';
// import { Validator } from 'vee-validate';

Vue.use(VeeValidate);

const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;
const app = new Vue({
    el: '#app',
    components: { MyAutocomplete },
    data() {
        return {
            rows: [
                //initial data
                // { qty: 5, value: "Something", language: 10, type: "additional", sort_order: 0 },
                // { qty: 2, value: "Something else", language: 20, type: "additional", sort_order: 0 },
            ],
            serrors: [],
            uploadedFiles: [],
            uploadError: null,
            currentStatus: null,
            uploadFieldName: 'photos',
            fileCount: 0,
            persons: [],
            contributors: [],

            step: 1,
            dataset: {
                type: '',
                state: '',
                rights: null,
                project_id: '',

                creating_corporation: "GBA",
                embargo_date: '',
                belongs_to_bibliography: 0,

                title_main: {
                    value: '',
                    language: ''
                },
                abstract_main: {
                    value: '',
                    language: ''
                },
                checkedAuthors: [],
                checkedLicenses: [],// [],
                files: [],
                checkedContributors: [],
            }
        }
    },   
    created: function () {
        VeeValidate.Validator.extend('Name', {
            getMessage: field => '* Enter valid ' + field + '',
            validate: value => /^[a-zA-Z]*$/.test(value)
        });
        // add the required rule
        VeeValidate.Validator.extend('oneChecked', {
            getMessage: field => 'At least one ' + field + ' needs to be checked.',
            validate: (value, [testProp]) => {
                const options = this.dataset.checkedLicenses;
                return value || options.some((option) => option[testProp]);           
            }
        });
    },
    mounted() {
        this.step = 1;
        this.reset();        
    },
    computed: {
        isInitial() {
            return this.currentStatus === STATUS_INITIAL;
        },
        isSaving() {
            return this.currentStatus === STATUS_SAVING;
        },
        isSuccess() {
            return this.currentStatus === STATUS_SUCCESS;
        },
        isFailed() {
            return this.currentStatus === STATUS_FAILED;
        }
    },
    methods: {
        reset() {
            // reset form to initial state
            this.currentStatus = STATUS_INITIAL;
            this.uploadedFiles = [];
            this.uploadError = null;
        },
        resetDropbox() {
            // reset form to initial state
            this.currentStatus = STATUS_INITIAL;
            this.dataset.files = [];
        },
        save() {
            var _this = this;
            this.serrors = [];
            /*
           Initialize the form data
           */
            let formData = new FormData();
            /*
            Iteate over any file sent over appending the files
            to the form data.
            */
            //    formData.append('files', []);
            for (var i = 0; i < this.dataset.files.length; i++) {
                let file = this.dataset.files[i];
                formData.append('files[' + i + '][file]', file.file);
                formData.append('files[' + i + '][label]', file.label);
                formData.append('files[' + i + '][sorting]', i + 1);

                // formData.append('files[' + i + ']', JSON.stringify(file));
            }
            /*
            Additional POST Data
            */
            formData.append('type', this.dataset.type);
            formData.append('server_state', this.dataset.state);
            formData.append('rights', Number(this.dataset.rights));
            formData.append('creating_corporation', this.dataset.creating_corporation);
            formData.append('project_id', this.dataset.project_id);
            formData.append('embargo_date', this.dataset.embargo_date);
            formData.append('belongs_to_bibliography', this.dataset.belongs_to_bibliography);
            formData.append('title_main[value]', this.dataset.title_main.value);
            formData.append('title_main[language]', this.dataset.title_main.language);
            formData.append('abstract_main[value]', this.dataset.abstract_main.value);
            formData.append('abstract_main[language]', this.dataset.abstract_main.language);

            for (var i = 0; i < this.dataset.checkedLicenses.length; i++) {
                formData.append('licenses[' + i + ']', this.dataset.checkedLicenses[i]);
            }

            for (var i = 0; i < this.dataset.checkedAuthors.length; i++) {
                formData.append('authors[' + i + ']', this.dataset.checkedAuthors[i]);
            }
            for (var i = 0; i < this.dataset.checkedContributors.length; i++) {
                formData.append('contributors[' + i + ']', this.dataset.checkedContributors[i]);
            }

            /*
            Make the request to the POST /multiple-files URL
            */
            axios.post('/publish/dataset/store',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then((response) => {
                    // success callback
                    console.log(response.data);
                    // this.loading = false;            
                    // this.items = response.data;          
                    //Vue.set(app.skills, 1, "test55");
                    _this.currentStatus = STATUS_SUCCESS;

                    if (response.data.redirect) {
                        window.location = response.data.redirect;
                    }
                })
                .catch((error) => {
                    // this.loading = false;
                    // console.log("test");
                    let errorObject = JSON.parse(JSON.stringify(error));
                    // console.log(errorObject);
                    if (errorObject.response.data.errors) {
                        var errorsArray = errorObject.response.data.errors;
                        for (var index in errorsArray) {
                            console.log(errorsArray[index]);
                            _this.serrors.push(errorsArray[index]);
                        }
                    }
                    if (errorObject.response.data.error) {
                        var error = errorObject.response.data.error;
                        _this.serrors.push(error.message);
                    }
                    _this.currentStatus = STATUS_FAILED;
                });
        },
        // filesChange(fieldName, fileList) {
        //     // handle file changes
        //     // const formData = new FormData();
        //     if (!fileList.length) return;
        //     // append the files to FormData
        //     Array
        //         .from(Array(fileList.length).keys())
        //         .map(x => {
        //             dataset.files.append(fieldName, fileList[x], fileList[x].name);
        //         });
        //     // save it
        //     // this.save(formData);

        // },
        /*
        Handles a change on the file upload
        */
        filesChange(fieldName, fileList) {
            // this.dataset.files = this.$refs.files.files; 
            let uploadedFiles = fileList;

            /*
           Adds the uploaded file to the files array
           */
            for (var i = 0; i < uploadedFiles.length; i++) {
                let fileName = uploadedFiles[i].name.replace(/\.[^/.]+$/, '');
                let uploadeFile = { file: uploadedFiles[i], label: fileName, sorting: 0 };
                //this.dataset.files.push(uploadedFiles[i]);
                this.dataset.files.push(uploadeFile);
            }
            // if (this.dataset.files.length > 0)
            // {
            //     this.currentStatus = STATUS_SAVING;
            // }       

        },
        onAddAuthor(person) {
            //if person is not in person array
            //if (this.persons.includes(person) == false) {
            if (this.persons.filter(e => e.id === person.id).length == 0) {    
                this.persons.push(person);
                this.dataset.checkedAuthors.push(person.id);
            }
        },
        onAddContributor(person) {
            //if person is not in person array
            //if (this.persons.includes(person) == false) {
            if (this.contributors.filter(e => e.id === person.id).length == 0) {    
                this.contributors.push(person);
                this.dataset.checkedContributors.push(person.id);
            }
        },
        /*
        Removes a select file the user has uploaded
        */
        removeFile(key) {
            this.dataset.files.splice(key, 1);
        },

        prev() {
            this.step--;
        },
        next(scope) {
            // if(this.validate(scope)) {
            //     this.step++;
            // }
            this.$validator.validateAll(scope).then((result) => {
                if (result) {
                    this.step++;
                }
            });

        },
        validate: function (scope) {
            this.$validator.validateAll(scope);
            if (this.errors.any()) {
                console.log('The form is invalid');
                return false;
            }
            return true;
        },
        submit() {
            // alert('Submit to blah and show blah and etc.');
            // save it
            this.save();
        }
    }
});
