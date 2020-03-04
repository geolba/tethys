import { Component, Vue, Prop } from 'vue-property-decorator';

@Component
export default class ShowDataset extends Vue {

    @Prop()
    dataset;

    get results() {
        return this.dataset;
    };
}