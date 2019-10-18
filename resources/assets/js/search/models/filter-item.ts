export default class FilterItem {
    private category: string;
    value: string;
    count: number;
    private active: boolean;

    constructor(value: string, count: number) {      
        this.value = value;
        this.count = count;
        this.active = false;
        this.category = "";
    }

    //#region properties

    get Category(): string {
        return this.category;
    }
    set Category(theCategory: string) {
        this.category = theCategory;
    }

    get Active(): boolean {
        return this.active;
    }
    set Active(isActive: boolean) {
        this.active = isActive;
    }

    //#endregion
}