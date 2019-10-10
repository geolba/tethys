export default class FilterItem {
    category;
    value;
    count;
    active;

    constructor(value, count) {
        // this.category = category;
        this.value = value;
        this.count = count;
        this.active = false;
        this.category = "";
    }

    get Category() {
        return this.category;
    }
    set Category(theCategory) {
        this.category = theCategory;
    }

    set Active(isActive) {
        this.active = isActive;
    }
}