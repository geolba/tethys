import axios from "axios";

export default {

    async search (term) {
        // solr endpoint
        const host = 'http://voyagerdemo.com/';
        const path = 'daily/solr/v0/select';
        const fields = 'id,name:[name],thumb:[thumbURL],abstract,description'; // fields we want returned
        const api = `${host}${path}?q=${term}&fl=${fields}&wt=json&rows=20`;
       
        const res = await axios.get(api);        
        // const call = await fetch(api);
        // const json = await res.json();
        return res.data.response.docs;
    }

}