// import Vue from 'vue';
// import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
// import Essentials from '@ckeditor/ckeditor5-essentials/src/essentials';

// const app = new Vue({
//     name: 'app',
//     components: {
//         // Use the <ckeditor> component in this view.
//         ckeditor: CKEditor.component
//     },
//     props: ['desc_markup'],
//     data() {
//         return {
//             editor: ClassicEditor,
//             //desc_markup: '<p>Content of the editor.</p>',
//             editorConfig: {
//                 // The configuration of the editor.
//             }
//         };
//     }
// });
var allEditors = document.querySelectorAll('.ckeditor');

for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor
        .create(allEditors[i], {

            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote']
        })
        .then(editor => {
            console.log(editor);
        })
        .catch(error => {
            console.error(error);
        });
}