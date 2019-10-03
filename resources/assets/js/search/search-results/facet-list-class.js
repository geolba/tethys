import { Component, Vue, Prop, Provide } from 'vue-property-decorator';




@Component
export default class FacetList extends Vue {
    
    ITEMS_PER_FILTER = 5;
    bar = 'bar';

    @Prop()
    data;

    get myLanguageFilters() {
        var facetValues = this.data.language.map((facet, i) => {
            if (i % 2 === 0) {
                // var rObj = {};
                // rObj['value'] = facet;
                // rObj['count'] = solrArray[i +1];
                var rObj = { value: facet, count: this.data.language[i + 1] };
                return rObj;
            }
        }).filter(function (el) {
            return el != null && el.count > 0;
        });
        // var facetValues = this.data.language.filter(function(facet, i) {                                
        //     return i % 2 === 0;
        // }).map(function (facet, i) {
        //     var rObj = { value: facet, count: this.data.language[i + 1] };
        //     return rObj;
        // }, this);
        return facetValues;
    };  

    get facets() {
        return this.data;
    };

    mounted() {        
    };

    test(solrArray) {
        //this.facetValues = this.data.language.filter((facet, i) => i % 2 === 0);

        // var facetValues = this.data.language.map((facet, i) =>  {
        var facetValues = solrArray.map((facet, i) => {
            if (i % 2 === 0) {
                // var rObj = {};
                // rObj['value'] = facet;
                // rObj['count'] = solrArray[i +1];
                var rObj = { value: facet, count: solrArray[i + 1] };
                return rObj;
            }
        });


        // this.facetCounts = this.data.filter((facet, i) => i % 2 === 1);
        return facetValues;
    }
}