import { Component, Vue, Watch, Prop } from 'vue-property-decorator';
//import FilterItem from '../models/filter-item';

@Component
export default class ActiveFacetCategory extends Vue {
   
    bar = '';      

    @Prop([Array])
    data!: string[];

    @Prop([String])
    categoryName;
    
    // @Prop([String])
    // alias;
  
    get alias(): string {      
        return this.categoryName == 'doctype' ? 'datatype' : this.categoryName
    }

  
    
    get filterItems(): Array<string> {
        return this.data;
    }

   

    // get uncollapseLabelText() : string {
    //     if (this.collapsed == true) {
    //         // return myLabels.viewer.sidePanel.more; //"More results";
    //         return "More results";
    //     }
    //     else {
    //         // return myLabels.viewer.sidePanel.collapse; //"Collapse";
    //         return "Collapse";
    //     }
    // }

    // toggle = function (): void {
    //     if (this.collapsed == true) {
    //         this.collapsed = false;
    //     }
    //     else if (this.collapsed == false) {
    //         this.collapsed = true;
    //         //list.children("li:gt(4)").hide();             
    //     }
    // }

    deactivateFacetCategory = function (): void {  
        // filterItem.Category = this.alias;
        // filterItem.Active = true;
        this.$emit("clearFacetCategory", this.categoryName, this.alias);
    }

    mounted() {
    }
}