<template>
<div id="app">
    
  
    <main>
   
      <div class="content">
      <dataset v-bind:id="id"></dataset>
      </div>
    </main>
</div>
</template>

<script lang="js">
import { Component, Vue } from 'vue-property-decorator';
import axios from "axios";
import Dataset from './components/dataset.vue';


@Component({
  components: {
   Dataset
  }
})
export default class App extends Vue {  
  posts = [];
  endpoint = "https://jsonplaceholder.typicode.com/posts/";
  msg = "Welcome to Your Vue.js App";
 
  created() {
    //this.getAllPosts();
  }

  beforeMount() {
        // this.form = window.Laravel.form;
       
        this.id = window.Laravel.id;


    }

 
  getAllPosts() {
    axios
      .get(this.endpoint)
      .then(response => {
        this.posts = response.data;
      })
      .catch(error => {
        console.log("-----error-------");
        console.log(error);
      });
  }  
}
</script>


<style lang="scss">
body {
  margin: 0;
  padding: 0;
}
#app {
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
h1,
h2 {
  font-weight: normal;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
// header {
//   position: fixed;
//   top: 0;
//   width: 100%;
//   min-height: 90px;
//   border-bottom: 1px solid #42b983;
//   text-align: center;
//   background: #ffffff;
// }
main {
  display: flex;
  height: calc(100vh - 90px);
  max-width: 1200px;
  margin-top: 90px;
  margin-left: auto;
  margin-right: auto;
  overflow: hidden;
}
aside {
  flex: 1 0 30%;
  height: 100%;
  overflow-y: auto;
  width: 30%;
  padding: 50px 30px;
  box-sizing: border-box;
  border-right: 1px solid #42b983;
}
.content {
  // flex: 1 1 80%;
  // display: flex;
  align-items: center;
  justify-content: center;
  
}
.link {
  display: block;
  text-decoration: none;
  margin-bottom: 10px;
  color: #2c3e50;
  &--home {
    text-transform: uppercase;
    margin-bottom: 30px;
  }
  &.is-active {
    color: #42b983;
  }
}
</style>
      