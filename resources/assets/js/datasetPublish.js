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
import dataset from './components/Dataset';
import LocationsMap from './components/LocationsMap.vue';
import modal from './components/ShowModal.vue';
// import datetime from 'vuejs-datetimepicker';
// import { Validator } from 'vee-validate';
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/index.css';
Vue.use(VueToast);

// Vue.use(VeeValidate);
Vue.use(VeeValidate, {
    // validity: true
    useConstraintAttrs: true
});

const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;
const app = new Vue({
    el: '#app',
    components: { MyAutocomplete, LocationsMap, modal },
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
            redirectLink: null,

            isModalVisible: false,

            step: 0,
            dataset: dataset,
            elevation: "no_elevation",
            depth: "no_depth",
            time: "no_time",
            // dataset: {
            //     type: '',
            //     state: '',
            //     rights: null,
            //     project_id: '',

            //     creating_corporation: "GBA",
            //     embargo_date: '',
            //     belongs_to_bibliography: 0,

            //     title_main: {
            //         value: '',
            //         language: ''
            //     },
            //     abstract_main: {
            //         value: '',
            //         language: ''
            //     },
            //     checkedAuthors: [],
            //     checkedLicenses: [],// [],
            //     files: [],
            //     references: [],
            //     checkedContributors: [],
            //     checkedSubmitters: [],
            // }
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
        // add the required rule
        VeeValidate.Validator.extend('translatedLanguage', {
            getMessage: field => 'The translated ' + field + ' must be in a language other than than the dataset language.',
            validate: (value, [mainLanguage, type]) => {
                if (type == "translated") {
                    return value !== mainLanguage;
                }
                return true;

            }
        });
    },
    mounted() {
        //this.step = 2;
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
        },
        isElevationAbsolut() {
            return this.elevation === "absolut";
        },
        isElevationRange() {
            return this.elevation === "range";
        },
        isDepthAbsolut() {
            return this.depth === "absolut";
        },
        isDepthRange() {
            return this.depth === "range";
        },
        isTimeAbsolut() {
            return this.time === "absolut";
        },
        isTimeRange() {
            return this.time === "range";
        }
    },
    methods: {

        showModal() {
            this.isModalVisible = true;
        },
        closeModal() {
            this.isModalVisible = false;
        },
        reset() {
            // reset form to initial state
            this.currentStatus = STATUS_INITIAL;
            this.uploadedFiles = [];
            this.uploadError = null;
            this.dataset.reset();//reset methods will trigger property changed.
            this.step = 0;
        },
        retry() {
            // reset form to initial state
            this.currentStatus = STATUS_INITIAL;
            // this.uploadedFiles = [];
            // this.uploadError = null;
            // this.dataset.reset();//reset methods will trigger property changed.
            // this.step = 1;
        },
        editNewDataset() {
            window.location = this.redirectLink;
        },
        resetDropbox() {
            // reset form to initial state
            this.currentStatus = STATUS_INITIAL;
            this.dataset.files = [];
        },
        save(status) {
            // upload data to the server
            var _this = this;
            this.currentStatus = STATUS_SAVING;
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
            formData.append('server_state', status);
            formData.append('type', this.dataset.type);
            formData.append('language', this.dataset.language);
            // formData.append('server_state', this.dataset.state);
            formData.append('rights', Number(this.dataset.rights));
            formData.append('creating_corporation', this.dataset.creating_corporation);
            formData.append('project_id', this.dataset.project_id);
            formData.append('embargo_date', this.dataset.embargo_date);
            formData.append('belongs_to_bibliography', this.dataset.belongs_to_bibliography);
            formData.append('title_main[value]', this.dataset.title_main.value);
            formData.append('title_main[language]', this.dataset.title_main.language);
            formData.append('abstract_main[value]', this.dataset.abstract_main.value);
            formData.append('abstract_main[language]', this.dataset.abstract_main.language);

            if (this.dataset.coverage.xmin !== "" && this.dataset.coverage.ymin != '' &&
                this.dataset.coverage.xmax !== '' && this.dataset.coverage.ymax !== '') {
                formData.append('coverage[xmin]', this.dataset.coverage.xmin);
                formData.append('coverage[ymin]', this.dataset.coverage.ymin);
                formData.append('coverage[xmax]', this.dataset.coverage.xmax);
                formData.append('coverage[ymax]', this.dataset.coverage.ymax);
            }

            if (this.isElevationAbsolut) {
                formData.append('coverage[elevation_absolut]', this.dataset.coverage.elevation_absolut);
            }
            else if (this.isElevationRange) {
                formData.append('coverage[elevation_min]', this.dataset.coverage.elevation_min);
                formData.append('coverage[elevation_max]', this.dataset.coverage.elevation_max);
            }
            if (this.isDepthAbsolut) {
                formData.append('coverage[depth_absolut]', this.dataset.coverage.depth_absolut);
            }
            else if (this.isDepthRange) {
                formData.append('coverage[depth_min]', this.dataset.coverage.depth_min);
                formData.append('coverage[depth_max]', this.dataset.coverage.depth_max);
            }
            if (this.isTimeAbsolut) {
                formData.append('coverage[time_absolut]', this.dataset.coverage.time_absolut);
            }
            else if (this.isTimeRange) {
                formData.append('coverage[time_min]', this.dataset.coverage.time_min);
                formData.append('coverage[time_max]', this.dataset.coverage.time_max);
            }


            for (var i = 0; i < this.dataset.checkedLicenses.length; i++) {
                formData.append('licenses[' + i + ']', this.dataset.checkedLicenses[i]);
            }

            for (var i = 0; i < this.dataset.persons.length; i++) {
                let person = this.dataset.persons[i];               
                formData.append('authors[' + i + '][first_name]', person.first_name);
                formData.append('authors[' + i + '][last_name]', person.last_name);
                formData.append('authors[' + i + '][email]', person.email);
                formData.append('authors[' + i + '][identifier_orcid]', person.identifier_orcid);
                formData.append('authors[' + i + '][status]', person.status);
                if (person.id !== undefined) {
                    formData.append('authors[' + i + '][id]', person.id)
                }
            }

            // for (var i = 0; i < this.dataset.checkedAuthors.length; i++) {
            //     formData.append('authors[' + i + ']', this.dataset.checkedAuthors[i]);
            // }
            for (var i = 0; i < this.dataset.checkedContributors.length; i++) {
                formData.append('contributors[' + i + ']', this.dataset.checkedContributors[i]);
            }
            // for (var i = 0; i < this.dataset.checkedSubmitters.length; i++) {
            //     formData.append('submitters[' + i + ']', this.dataset.checkedSubmitters[i]);
            // }

            for (var i = 0; i < this.dataset.references.length; i++) {
                let reference = this.dataset.references[i];
                formData.append('references[' + i + '][value]', reference.value);
                formData.append('references[' + i + '][label]', reference.label);
                formData.append('references[' + i + '][type]', reference.type);
                formData.append('references[' + i + '][relation]', reference.relation);
            }

            for (var i = 0; i < this.dataset.keywords.length; i++) {
                let keyword = this.dataset.keywords[i];
                formData.append('keywords[' + i + '][value]', keyword.value);
                formData.append('keywords[' + i + '][type]', keyword.type);
            }

            for (var i = 0; i < this.dataset.titles.length; i++) {
                let title = this.dataset.titles[i];
                formData.append('titles[' + i + '][value]', title.value);
                formData.append('titles[' + i + '][language]', title.language);
                formData.append('titles[' + i + '][type]', title.type);
            }

            for (var i = 0; i < this.dataset.descriptions.length; i++) {
                let description = this.dataset.descriptions[i];
                formData.append('descriptions[' + i + '][value]', description.value);
                formData.append('descriptions[' + i + '][language]', description.language);
                formData.append('descriptions[' + i + '][type]', description.type);
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
                    _this.redirectLink = response.data.redirect;
                    // if (response.data.redirect) {
                    //     window.location = response.data.redirect;
                    // }
                })
                .catch((error) => {
                    // this.loading = false;
                    this.uploadError = error.response;
                    console.log('FAILURE!!');
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
        addReference() {
            let newReference = { value: '', label: '', relation: '', type: '' };
            //this.dataset.files.push(uploadedFiles[i]);
            this.dataset.references.push(newReference);
        },
        /*
        Removes a selected reference
        */
        removeReference(key) {
            this.dataset.references.splice(key, 1);
        },
        /*
       adds a new Keyword
       */
        addKeyword() {
            let newKeyword = { value: '', type: '', language: this.dataset.language };
            //this.dataset.files.push(uploadedFiles[i]);
            this.dataset.keywords.push(newKeyword);
        },
        /*
        Removes a selected keyword
        */
        removeKeyword(key) {
            this.dataset.keywords.splice(key, 1);
        },
        addTitle() {
            let newTitle = { value: '', language: '', type: '' };
            //this.dataset.files.push(uploadedFiles[i]);
            this.dataset.titles.push(newTitle);
        },
        /*
        Removes a selected title
        */
        removeTitle(key) {
            this.dataset.titles.splice(key, 1);
        },
        addDescription() {
            let newTitle = { value: '', language: '', type: '' };
            //this.dataset.files.push(uploadedFiles[i]);
            this.dataset.descriptions.push(newTitle);
        },
        /*
        Removes a selected description
        */
        removeDescription(key) {
            this.dataset.descriptions.splice(key, 1);
        },
        filesChange(fieldName, fileList) {
            this.fileCount = fileList.length
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
        addNewAuthor() {
            let newAuthor = { status: 0, first_name: '', last_name: '', email: '', academic_title: '', identifier_orcid: '' };
            this.dataset.persons.push(newAuthor);
        },        
        removeAuthor(key) {
            this.dataset.persons.splice(key, 1);
        },
        onAddAuthor(person) {
            //if person is not in person array
            //if (this.persons.includes(person) == false) {
            if (this.dataset.persons.filter(e => e.id === person.id).length == 0) {
                //person.sort_order = this.dataset.persons.length;
                this.dataset.persons.push(person);
                this.dataset.checkedAuthors.push(person.id);
            }
        },
        onAddContributor(person) {
            //if person is not in contributors array
            //if (this.contributors.includes(person) == false) {
            if (this.dataset.contributors.filter(e => e.id === person.id).length == 0) {
                this.dataset.contributors.push(person);
                this.dataset.checkedContributors.push(person.id);
            }
        },
        // onAddSubmitter(person) {
        //     //if person is not in submitters array
        //     //if (this.submitters.includes(person) == false) {
        //     if (this.dataset.submitters.filter(e => e.id === person.id).length == 0) {
        //         this.dataset.submitters.push(person);
        //         this.dataset.checkedSubmitters.push(person.id);
        //     }
        // },
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
        submit(status) {
            // alert('Submit to blah and show blah and etc.');
            // save it
            this.save(status);
        }
    }
});
