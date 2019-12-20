import { Component, Vue, Watch } from 'vue-property-decorator';
import axios from 'axios';
import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);
import LocationsMap from './components/locations-map.vue';
import Dataset from './components/Dataset';


@Component({
    components: {
        LocationsMap
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

    beforeMount() {
        // this.form = window.Laravel.form;
        this.realMerge(this.form, window.Laravel.form);
        this.projects = window.Laravel.projects;
        this.licenses = window.Laravel.licenses;
        this.checkeds = window.Laravel.checkeds;
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

    onSubmit() {
        // var dataform = new FormData();
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

}