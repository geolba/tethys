import axios from "axios";

export default {

    async search(term) {
        // solr endpoint
        // const host = 'http://voyagerdemo.com/';
        const host = 'https://repository.geologie.ac.at/';
        const path = 'solr/rdr_data/select';
        const fields = 'id,server_date_published,abstract_output,title_output,title_additional,author,subject'; // fields we want returned
        const dismaxFields = "title^3 abstract^2 subject^1";
        const facetFields = "facet.field=language&facet.field={!key=datatype}doctype";//&fq=year:(2019)";//&facet.query=year:2018";

        // $dismax->setQueryFields('title^3 abstract^2 subject^1');
        const api = `${host}${path}?defType=dismax&q=${term}&fl=${fields}&qf=${dismaxFields}&facet=on&${facetFields}&wt=json&rows=20&indent=on`;

        const res = await axios.get(api);
        return res.data;//.response;//.docs;
    }

}