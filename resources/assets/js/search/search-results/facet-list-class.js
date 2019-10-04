import { Component, Vue, Prop, Provide } from 'vue-property-decorator';




@Component
export default class FacetList extends Vue {
    
    ITEMS_PER_FILTER = 5;
    bar = 'bar';
    // filterItems = [];

    @Prop()
    data;
    @Prop()
    filterName;

    get myLanguageFilters() {
        // console.log(this.filterName);
        // console.log(this.data);       
        var facetValues = this.data.map((facet, i) => {
            if (i % 2 === 0) {
                // var rObj = {};
                // rObj['value'] = facet;
                // rObj['count'] = solrArray[i +1];
                var rObj = { value: facet, count: this.data[i + 1] };
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
    get filterItems() {
        var facetValues = this.data.map((facet, i) => {
            if (i % 2 === 0) {
                // var rObj = {};
                // rObj['value'] = facet;
                // rObj['count'] = solrArray[i +1];
                var rObj = { value: facet, count: this.data[i + 1] };
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

    mounted() {        
    };
}