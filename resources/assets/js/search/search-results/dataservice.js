import axios from "axios";

export default {

    async search(term, filterItems) {
        // solr endpoint
        // const host = 'http://voyagerdemo.com/';
        const host = 'https://repository.geologie.ac.at/';
        const path = 'solr/rdr_data/select';
        const fields = 'id,server_date_published,abstract_output,title_output,title_additional,author,subject'; // fields we want returned
        const dismaxFields = "title^3 abstract^2 subject^1";
        const facetFields = "facet.field=language&facet.field={!key=datatype}doctype&facet.field=subject";//&fq=year:(2019)";//&facet.query=year:2018";

        var filterFields = "";
        // filterItems.forEach(function (item) {
        //    console.log(item.value + " " + item.category);
        //     filterFields += "&fq=" + item.category +":("+ item.value + ")";
        //   });
        Object.entries(filterItems).forEach(([key, valueArray]) => {
          // console.log(`${key} ${valueArray}`);
          valueArray.forEach(function (value) {             
                filterFields += "&fq=" + key +":("+ value + ")";
              });
          
        });


        // $dismax->setQueryFields('title^3 abstract^2 subject^1');
        const api = `${host}${path}?defType=dismax&q=${term}&fl=${fields}&qf=${dismaxFields}&facet=on&${facetFields}&${filterFields}&wt=json&rows=20&indent=on`;

        const res = await axios.get(api);
        return res.data;//.response;//.docs;
    }

}