import axios from "axios";

var SOLR_CONFIG = {
  "server": "https://arcticdata.io/metacat/d1/mn/v2/query/solr?",  // Solr server
  "filter": "knb-lter-bnz",  // Filter results for an organization or user
  "limit": 10,  // Max number of results to retrieve per page
  "resultsElementId": "searchResults",  // Element to contain results
  "urlElementId": "searchUrl",  // Element to display search URL
  "countElementId": "resultCount",  // Element showing number of results
  "pagesElementId": "pagination",  // Element to display result page links
  "showPages": 5  // MUST BE ODD NUMBER! Max number of page links to show
};

export default {

 
  async search(term: string, filterItems: Object, start?: string): Promise<any> {
    // solr endpoint
    // const host = 'http://voyagerdemo.com/';
    const host = 'https://repository.geologie.ac.at/';
    const path = 'solr/rdr_data/select?';
    var base = host + path;

    //const fields = 'id,server_date_published,abstract_output,title_output,title_additional,author,subject'; // fields we want returned
    var fields = ["id", "licence",
      "server_date_published",
      "abstract_output",
      "title_output",
      "title_additional",
      "author",
      "subject", "doctype"].toString();
    var limit = "&rows=" + SOLR_CONFIG["limit"];  
    // var limit = solrConfig.limit;

    var qfFields = "title^3 author^2 subject^1";
    var params = "fl=" + fields;
    if (term == "*%3A*") {
      params += "&defType=edismax&wt=json&indent=on"; //edismax
    } else {
      params += "&defType=dismax&qf=" + qfFields + "&wt=json&indent=on"; //dismax
    }

    if (start === undefined) start = "0";
    start = "&start=" + start;

    
    const facetFields = "&facet=on&facet.field=language&facet.field={!key=datatype}doctype&facet.field=subject";//&fq=year:(2019)";//&facet.query=year:2018";

    var filterFields = "";
    // filterItems.forEach(function (item) {
    //    console.log(item.value + " " + item.category);
    //     filterFields += "&fq=" + item.category +":("+ item.value + ")";
    //   });
    Object.entries(filterItems).forEach(([key, valueArray]) => {
      // console.log(`${key} ${valueArray}`);
      valueArray.forEach(function (value) {
        filterFields += '&fq=' + key + ':("' +  value + '")';
      });

    });
    var query ="&sort=server_date_published desc" + "&q=" + term;


    // $dismax->setQueryFields('title^3 abstract^2 subject^1');
    //const api = `${host}${path}?defType=dismax&q=${term}&fl=${fields}&qf=${dismaxFields}&facet=on&${facetFields}&${filterFields}&wt=json&rows=20&indent=on`;
    //const api = `${host}${path}?q=${term}&fl=${fields}&facet=on&${facetFields}&${filterFields}&wt=json&rows=20&indent=on`;

    var api = base + params + limit + start + query + filterFields + facetFields;

    let res = await axios.get(api);
    // let { data } = res.data;
    return res.data;//.response;//.docs;
  },

  // for the autocomplete search      
  async searchTerm(term: string): Promise<any> {
    // solr endpoint
    // const host = 'http://voyagerdemo.com/';
    const host = 'https://repository.geologie.ac.at/';
    const path = 'solr/rdr_data/select?';
    var base = host + path;

    //const fields = 'id,server_date_published,abstract_output,title_output,title_additional,author,subject'; // fields we want returned
    var fields = ["id", "licence",
      "server_date_published",
      "abstract_output",
      "title_output",
      "title_additional",
      "author",
      "subject", "doctype"].toString();
   

    //var dismaxFields = "title^3 abstract^2 subject^1";
    var qfFields = "title^3 author^2 subject^1";
    var params = "fl=" + fields;
    // if (term == "*%3A*") {
    //   params += "&defType=edismax&wt=json&indent=on"; //edismax
    // } else {
      params += "&defType=edismax&qf=" + qfFields + "&wt=json&indent=on"; //dismax
    // }  

    var query = "&q=" + term + "*";
    var api = base + params + query;

    let res = await axios.get(api);    
    return res.data;//.response;//.docs;
  }

}