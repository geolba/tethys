import { Component, Vue, Prop, Provide } from 'vue-property-decorator';
import FilterItem from '../models/filter-item';

@Component
export default class FacetCategory extends Vue {

    ITEMS_PER_FILTER = 2;
    bar = '';
    collapsed = true;
    // filterItems = [];     

    @Prop()
    data;
    @Prop([String])
    filterName;
    // @Prop([String])
    // alias;
  
    get alias(): string {      
        return this.filterName == 'datatype' ? 'doctype' : this.filterName
    }
    // get filterItems() {
    //     var facetValues = this.data.map((facet, i) => {
    //         if (i % 2 === 0) {
    //             // var rObj = {};
    //             // rObj['value'] = facet;
    //             // rObj['count'] = solrArray[i +1];
    //             var rObj = { value: facet, count: this.data[i + 1], category: this.alias };
    //             return rObj;
    //         }
    //     }).filter(function (el) {
    //         return el != null && el.count > 0;
    //     });
    //     // var facetValues = this.data.language.filter(function(facet, i) {                                
    //     //     return i % 2 === 0;
    //     // }).map(function (facet, i) {
    //     //     var rObj = { value: facet, count: this.data.language[i + 1] };
    //     //     return rObj;
    //     // }, this);
    //     return facetValues;
    // }
    get filterItems(): Array<FilterItem> {
        return this.data;
    }

    get overflowing(): boolean {
        //ko.observable(self.filterItems().length - self.activeFilterItems().length > ITEMS_PER_FILTER);
        return (this.filterItems.length) > this.ITEMS_PER_FILTER;
    }

    get uncollapseLabelText() : string {
        if (this.collapsed == true) {
            // return myLabels.viewer.sidePanel.more; //"More results";
            return "More results";
        }
        else {
            // return myLabels.viewer.sidePanel.collapse; //"Collapse";
            return "Collapse";
        }
    }

    toggle = function (): void {
        if (this.collapsed == true) {
            this.collapsed = false;
        }
        else if (this.collapsed == false) {
            this.collapsed = true;
            //list.children("li:gt(4)").hide();             
        }
    }

    activateItem = function (filterItem: FilterItem): void {  
        filterItem.Category = this.alias;
        filterItem.Active = true;
        this.$emit("filter", filterItem);
    }

    mounted() {
    }
}