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
// window.Vue = require('vue');
// Vue.prototype.$http = axios;

// Vue.component('example', require('./components/Example.vue'));
const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;

const app = new Vue({
    el: '#app',

    data() {
        return {
            rows: [
                //initial data
                // { qty: 5, value: "Something", language: 10, type: "additional", sort_order: 0 },
                // { qty: 2, value: "Something else", language: 20, type: "additional", sort_order: 0 },
            ],
            errors: [],
            uploadedFiles: [],
            uploadError: null,
            currentStatus: null,
            uploadFieldName: 'photos',
            fileCount: 0,

            step: 1,
            dataset: {
                type: '',
                state: '',
                rights: 0,
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
                checkedPersons: [],
                checkedLicenses: [],
                files: []
            }
        }
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
            this.errors = [];
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
            formData.append('rights', this.dataset.rights);
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
                    this.currentStatus = STATUS_SUCCESS;

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
                            this.errors.push(errorsArray[index]);
                        }
                    }
                    if (errorObject.response.data.error) {
                        var error = errorObject.response.data.error;
                        this.errors.push(error.message);
                    }
                    this.currentStatus = STATUS_FAILED;
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
        /*
        Removes a select file the user has uploaded
        */
        removeFile(key) {
            this.dataset.files.splice(key, 1);
        },

        prev() {
            this.step--;
        },
        next() {
            this.step++;
        },
        submit() {
            // alert('Submit to blah and show blah and etc.');
            // save it
            this.save();
        }
    }
});
