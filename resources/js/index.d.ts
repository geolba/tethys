 import Vue from 'vue';
 import VueToast from 'vue-toast-notification';

 declare module 'vue/types/vue' {
   interface Vue {
     $toast: VueToast
   }
}