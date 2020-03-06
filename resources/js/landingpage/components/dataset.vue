<template>
  <div class="box" v-if="post">
    <div class="dataset_heaader">
      <div class="dataset__blog-meta">published: {{ toDayDateTimeString(post.server_date_published) }}</div>
      <h1 class="dataset__title">{{ post.title_output }}</h1>
    
      <p class="dataset__id">{{ post.id }}</p>
    </div>
    <div class="dataset">
        <p class="dataset__abstract">{{ post.abstract_output }}</p>
    

    <p class="dataset__abstract" v-if="post.subject && post.subject.length > 0">keywords: {{ post.subject.join(', ') }}</p>
    <p class="dataset__abstract">creating corporation: {{ post.creating_corporation }}</p>
   <p class="dataset__abstract">publisher: {{ post.publisher_name }}</p>

    </div>
  </div>
</template>

<script>
import axios from "axios";
import { Component, Vue, Prop } from "vue-property-decorator";

@Component
export default class Dataset extends Vue {
  // props: ['id'];
  name = "dataset";

  @Prop()
  id;

  metaInfo() {
    return {
      title: this.post && this.post.title
    };
  }

  toDayDateTimeString(UNIX_timestamp) {
    var a = new Date(UNIX_timestamp * 1000);
    var months = [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec"
    ];
    var year = a.getFullYear();
    var month = months[a.getMonth()];
    var date = a.getDate();
    var hour = a.getHours();
    var min = a.getMinutes();
    var sec = a.getSeconds();
    var time =
      date + " " + month + " " + year + " " + hour + ":" + min + ":" + sec;
    return time;
  }

  post = null;
  host = "https://repository.geologie.ac.at/";
  path = "solr/rdr_data/select?";
  endpoint = this.host + this.path;
  // endpoint = "https://jsonplaceholder.typicode.com/posts/";

  getPost(id) {
    axios(this.endpoint + "&q=id:" + id)
      .then(response => {
        this.post = response.data.response.docs[0];
        // this.post.title_output = "Ein etwas längerer Titel zum Testen!!! Test";
      })
      .catch(error => {
        console.log("-----error-------");
        console.log(error);
      });
  }

  created() {
    this.getPost(this.id);
  }
}
</script>

<style lang="scss" scoped>
.box {
  margin: 0 auto;
  padding: 115px 20px 70px;
  // background-color: salmon;
  
   
}
.dataset_heaader {
//  background-color: lightgray;
 position: relative;
}
.dataset {  
  // max-width: 500px;
  
  &__title {
    position: relative;
    text-transform: uppercase;
    z-index: 1;
  }
  &__abstract {
    position: relative;
    z-index: 1;
  }
  &__id {
    position: absolute;
    font-size: 280px;
    bottom: -50px;
    margin: 0;
    color: #eeeeee;
    right: -20px;
    line-height: 1;
    font-weight: 900;
    z-index: 0;
  }
  &__blog-meta {
    padding: 10px 0 20px;
    color: #c2c2c2;
    // font-size: 0.8em;
    margin-top: -1.7em;
  }
}
</style>