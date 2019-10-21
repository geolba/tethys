import { Vue, Component, Prop } from 'vue-property-decorator';
// import Vue from "vue";

@Component
export default class VsPagination extends Vue {

    pageNumber: number = 0; // default to page 0

    @Prop()
    data;

    @Prop({ default: 4 }) readonly offset: number;

    changePage(page) {   
        if (page == this.data.current_page) {
            return;
        }    
        this.data.current_page = page;
        let from = (page * this.data.per_page) - this.data.per_page;
        this.$emit('paginate', from);      
    }
    
    get numberOfPages() {
        return Math.ceil(this.data.total / this.data.per_page);
    }

    get pages() {
        let numberOfPages = Math.ceil(this.data.total / this.data.per_page);

        // if (!this.data.to) {
        //     return [];
        // }
        let from = this.data.current_page - this.data.per_page;
        if (from < 1) {
            from = 1;
        }
        let to = from + (this.data.per_page * 2);
        if (to >= numberOfPages) {
            to = numberOfPages;
        }
        let pagesArray = [];
        for (let page = from; page <= to; page++) {
            pagesArray.push(page);
        }
        return pagesArray;
    }

    mounted() {           
    }
}
