<template>
  <div class="box" v-if="dataset">
    <div class="dataset_heaader">
      <div class="dataset__blog-meta">published: {{ toDayDateTimeString(dataset.server_date_published) }}</div>
      <h1 class="dataset__title">{{ dataset.title_output }}</h1>
    
      <p class="dataset__id">{{ dataset.id }}</p>
    </div>
    <div class="dataset">
        <p class="dataset__abstract">{{ dataset.abstract_output }}</p>
    

    <p class="dataset__abstract" v-if="dataset.subject && dataset.subject.length > 0">keywords: {{ dataset.subject.join(', ') }}</p>
    <p class="dataset__abstract">creating corporation: {{ dataset.creating_corporation }}</p>
   <p class="dataset__abstract">publisher: {{ dataset.publisher_name }}</p>
   <p class="dataset__abstract">coverage: {{ dataset.geo_location }}</p>
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
      title: this.dataset && this.dataset.title
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

  dataset = null;
  host = "https://repository.geologie.ac.at/";
  path = "solr/rdr_data/select?";
  endpoint = this.host + this.path;
  // endpoint = "https://jsonplaceholder.typicode.com/posts/";

  getPost(id) {
    axios(this.endpoint + "&q=id:" + id)
      .then(response => {
        this.dataset = response.data.response.docs[0];
        // this.dataset.title_output = "Ein etwas längerer Titel zum Testen!!! Test";
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
  padding: 100px 20px 70px;
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
    font-size: 250px;
    bottom: -40px;
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