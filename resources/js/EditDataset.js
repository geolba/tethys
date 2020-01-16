import { Component, Vue, Watch } from 'vue-property-decorator';
import datetime from 'vuejs-datetimepicker';
import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);
import LocationsMap from './components/locations-map.vue';
import Dataset from './components/Dataset';
import PersonTable from './components/PersonTable.vue';
import MyAutocomplete from './components/MyAutocomplete.vue';
import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/index.css';
Vue.use(VueToast);

@Component({
    components: {
        LocationsMap,
        datetime,
        PersonTable,
        MyAutocomplete
    }
})
export default class EditDataset extends Vue {
    // form: {},
    projects = [];
    checkeds = '';

    form = new Dataset();
    // form = {
    //     // reject_reviewer_note: '',

    //     language: '',
    //     type: '',
    //     project_id: '',  
    //     creating_corporation: 'TETHYS Repository',
    //     embargo_date: '',
    //     coverage: {
    //         xmin: "",
    //         ymin: "",
    //         xmax: "",
    //         ymax: "",
    //         elevation_min: "",
    //         elevation_max: "",
    //         elevation_absolut: "",
    //         depth_min: "",
    //         depth_max: "",
    //         depth_absolut: "",
    //         time_min: "",
    //         time_max: "",
    //         time_absolut: ""
    //     },
    //     // checkedAuthors: [],
    //     // checkedLicenses: [],
    //     // files: [],
    //     // keywords: [],
    //     // references: [],
    //     titles: [],
    //     abstratcs: [],
    //     clicenses : []      

    //     // checkedContributors: []
    // };
    allErros = [];
    success = false;
    elevation = "no_elevation";
    depth = "no_depth";
    time = "no_time";
    titleTypes = [];

    get remainingTitleTypes() {
        // this.titleTypes.filter(e => e != 'Main');
        var filtered = Object.fromEntries(Object.entries(this.titleTypes).filter(([k,v]) => v != 'Main'));
        return filtered;
    }

    beforeMount() {
        // this.form = window.Laravel.form;
        this.realMerge(this.form, window.Laravel.form);
        this.titleTypes = window.Laravel.titleTypes;
        this.descriptionTypes = window.Laravel.descriptionTypes;
        this.languages = window.Laravel.languages;
        this.projects = window.Laravel.projects;
        this.licenses = window.Laravel.licenses;
        this.checkeds = window.Laravel.checkeds;
        this.referenceTypes = window.Laravel.referenceTypes;
        this.relationTypes = window.Laravel.relationTypes;

        
    }

    created() {
         // add the required rule
         VeeValidate.Validator.extend('translatedLanguage', {
            getMessage: field => 'The translated ' + field + ' must be in a language other than than the dataset language.',
            validate: (value, [mainLanguage, type]) => {
                if (type == "Translated") {
                    return value !== mainLanguage;
                }
                return true;

            }
        });
        const isUnique = (value, [objectArray, index, attribute]) =>
            new Promise(resolve => {
                setTimeout(() => {
                    if (objectArray.some((item, i) => item[attribute] === value && index !== i)) {
                        return resolve({
                            valid: false,
                            data: {
                                message: value + ' is already taken.'
                            }
                        });
                    }
                    return resolve({
                        valid: true
                    });
                }, 200);
            });
        VeeValidate.Validator.extend("unique", {
            getMessage: (field, params, data) => field + ' ' + data.message,
            validate: isUnique,
        });
    }

    mounted() {
        this.setRadioButtons();
    }

    /*
* Recursively merge properties of two objects 
*/
    realMerge(from, dbObject) {

        for (var prop in dbObject) {
            try {
                if (typeof dbObject[prop] !== 'object') {
                    from[prop] = dbObject[prop];
                } else if (this.isObject(dbObject[prop])) {
                    from[prop] = this.realMerge(from[prop], dbObject[prop]);
                }
                else if (this.isObject(dbObject[prop]) || this.isObject(from[prop])) {
                    // coverage relation if null from dbObject
                    from[prop] = from[prop];
                }
                else if (Array.isArray(dbObject[prop])) {
                    from[prop] = dbObject[prop];
                }
                else {
                    from[prop] = null;
                }
            } catch (e) {
                // Property in destination object not set; create it and set its value.
                from[prop] = dbObject[prop];
            }
        }
        if (from.embargo_date) {
            from.embargo_date = this.formatDateFormat(new Date(from.embargo_date), 'yyyy-MM-dd');
        }
        return from;
    }

    formatDateFormat(x, y) {
        var z = {
            M: x.getMonth() + 1,
            d: x.getDate(),
            h: x.getHours(),
            m: x.getMinutes(),
            s: x.getSeconds()
        };
        y = y.replace(/(M+|d+|h+|m+|s+)/g, function (v) {
            return ((v.length > 1 ? "0" : "") + eval('z.' + v.slice(-1))).slice(-2)
        });

        return y.replace(/(y+)/g, function (v) {
            return x.getFullYear().toString().slice(-v.length)
        });
    }

    isObject(item) {
        return (typeof item === "object" && !Array.isArray(item) && item !== null);
    }

    setRadioButtons() {
        if (this.form.coverage.time_absolut != null) {
            this.time = "absolut";
        } else if (this.form.coverage.time_min != null) {
            this.time = "range";
        } else {
            this.time = "no_time";
        }
        
        if (this.form.coverage.elevation_absolut != null) {
            this.elevation = "absolut";
        } else if (this.form.coverage.elevation_min != null) {
            this.elevation = "range";
        } else {
            this.elevation = "no_elevation";
        }
        
        if (this.form.coverage.depth_absolut != null) {
            this.depth = "absolut";
        } else if (this.form.coverage.depth_min != null) {
            this.depth = "range";
        } else {
            this.depth = "no_depth";
        }
    }

    onSubmit() {
        // var dataform = new FormData();
        // var dataform = document.getElementById('submitEditForm');
        // var length = this.form.files.length;   
        // for (var i = 0; i < length; i++) {
        //     if (this.form.files[i].file != undefined) {
        //         var file = this.form.files[i];
        //         dataform.append('files[undefined][file]', file.file, file.label);
        //     }
        // }
        // dataform.append('name', this.form.name);
        // // dataform.append('comments', this.form.comments);
        // console.log(this.form.name);
        // axios.post('/vuevalidation/form', dataform).then(response => {
        //     console.log(response);
        //     this.allerros = [];
        //     this.form.name = '';
        //     this.form.comments = [];
        //     this.success = true;
        // }).catch((error) => {
        //     this.allerros = error.response.data.errors;
        //     this.success = false;
        // });
        this.submitted = true;
        this.$validator.validate().then(result => {
            if (result) {
                // console.log('From Submitted!');
                document.getElementById("submitEditForm").submit();
                return;
            }
        });
    }

    /*
      adds a new Keyword
      */
    addKeyword() {
        let newKeyword = { value: '', type: 'uncontrolled', language: this.form.language };
        //this.dataset.files.push(uploadedFiles[i]);
        this.form.subjects.push(newKeyword);
    }
    /*
       Removes a selected keyword
       */
    removeKeyword(key) {
        this.form.subjects.splice(key, 1);
    }

    /*
        Handles a change on the file upload
        */
    addReference() {
        let newReference = { value: '', label: '', relation: '', type: '' };
        //this.dataset.files.push(uploadedFiles[i]);
        this.form.references.push(newReference);
    }

    /*
    Removes a selected reference
    */
    removeReference(key) {
        this.form.references.splice(key, 1);
    }

    addTitle() {
        let newTitle = { value: '', language: this.form.language, type: '' };
        //this.dataset.files.push(uploadedFiles[i]);
        this.form.titles.push(newTitle);
    }

    /*
    Removes a selected title
    */
    removeTitle(key) {
        this.form.titles.splice(key, 1);
    }

    addDescription() {
        let newTitle = { value: '', language: this.form.language, type: '' };
        //this.dataset.files.push(uploadedFiles[i]);
        this.form.abstracts.push(newTitle);
    }

    /*
    Removes a selected description
    */
    removeDescription(key) {
        this.form.abstracts.splice(key, 1);
    }

    onAddAuthor(person) {
        //if person is not in person array
        //if (this.persons.includes(person) == false) {
        if (this.form.authors.filter(e => e.id === person.id).length > 0) {
            this.$toast.error("person is already defined as author");
        }  else if (this.form.contributors.filter(e => e.id === person.id).length > 0) {
            this.$toast.error("person is already defined as contributor");
        }
        else {
            //person.sort_order = this.dataset.persons.length;
            this.form.authors.push(person);
            // this.dataset.checkedAuthors.push(person.id);
            this.$toast.success("person has been successfully added as author");
        }
        
        // else if (this.dataset.contributors.filter(e => e.id === person.id).length > 0) {
        //     this.$toast.error("person is already defined as contributor");
        // } 
    }

    filesChange(fieldName, fileList) {
        var fileCount = fileList.length
        // this.dataset.files = this.$refs.files.files; 
        let uploadedFiles = fileList;

        /*
        Adds the uploaded file to the files array
        */
        for (var i = 0; i < uploadedFiles.length; i++) {
            let fileName = uploadedFiles[i].name.replace(/\.[^/.]+$/, '');
            let uploadeFile = { file: uploadedFiles[i], label: fileName, sort_order: 0 };
            //this.dataset.files.push(uploadedFiles[i]);
            this.form.files.push(uploadeFile);
        }
        // if (this.dataset.files.length > 0)
        // {
        //     this.currentStatus = STATUS_SAVING;
        // } 
    }

    /*
        Removes a select file the user has uploaded
        */
       removeFile(key) {
        this.form.files.splice(key, 1);
    }


}