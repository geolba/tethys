!function(t){var e={};function a(s){if(e[s])return e[s].exports;var r=e[s]={i:s,l:!1,exports:{}};return t[s].call(r.exports,r,r.exports,a),r.l=!0,r.exports}a.m=t,a.c=e,a.d=function(t,e,s){a.o(t,e)||Object.defineProperty(t,e,{configurable:!1,enumerable:!0,get:s})},a.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return a.d(e,"a",e),e},a.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},a.p="/",a(a.s=0)}([function(t,e,a){t.exports=a(1)},function(t,e){new Vue({el:"#app",data:function(){return{rows:[],errors:[],uploadedFiles:[],uploadError:null,currentStatus:null,uploadFieldName:"photos",fileCount:0,step:1,dataset:{type:"",state:"",rights:0,project_id:"",creating_corporation:"GBA",embargo_date:"",belongs_to_bibliography:0,title_main:{value:"",language:""},abstract_main:{value:"",language:""},checkedPersons:[],checkedLicenses:[],files:[]}}},mounted:function(){this.step=1,this.reset()},computed:{isInitial:function(){return 0===this.currentStatus},isSaving:function(){return 1===this.currentStatus},isSuccess:function(){return 2===this.currentStatus},isFailed:function(){return 3===this.currentStatus}},methods:{reset:function(){this.currentStatus=0,this.uploadedFiles=[],this.uploadError=null},resetDropbox:function(){this.currentStatus=0,this.dataset.files=[]},save:function(){var t=this;this.errors=[];for(var e=new FormData,a=0;a<this.dataset.files.length;a++){var s=this.dataset.files[a];e.append("files["+a+"][file]",s.file),e.append("files["+a+"][label]",s.label),e.append("files["+a+"][sorting]",a+1)}e.append("type",this.dataset.type),e.append("server_state",this.dataset.state),e.append("rights",this.dataset.rights),e.append("creating_corporation",this.dataset.creating_corporation),e.append("project_id",this.dataset.project_id),e.append("embargo_date",this.dataset.embargo_date),e.append("belongs_to_bibliography",this.dataset.belongs_to_bibliography),e.append("title_main[value]",this.dataset.title_main.value),e.append("title_main[language]",this.dataset.title_main.language),e.append("abstract_main[value]",this.dataset.abstract_main.value),e.append("abstract_main[language]",this.dataset.abstract_main.language);for(a=0;a<this.dataset.checkedLicenses.length;a++)e.append("licenses["+a+"]",this.dataset.checkedLicenses[a]);axios.post("/publish/dataset/store",e,{headers:{"Content-Type":"multipart/form-data"}}).then(function(e){console.log(e.data),t.currentStatus=2,e.data.redirect&&(window.location=e.data.redirect)}).catch(function(e){var a=JSON.parse(JSON.stringify(e));if(a.response.data.errors){var s=a.response.data.errors;for(var r in s)console.log(s[r]),t.errors.push(s[r])}if(a.response.data.error){e=a.response.data.error;t.errors.push(e.message)}t.currentStatus=3})},filesChange:function(t,e){for(var a=e,s=0;s<a.length;s++){var r=a[s].name.replace(/\.[^/.]+$/,""),n={file:a[s],label:r,sorting:0};this.dataset.files.push(n)}},removeFile:function(t){this.dataset.files.splice(t,1)},prev:function(){this.step--},next:function(){this.step++},submit:function(){this.save()}}})}]);