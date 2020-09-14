import EasyTimer from 'easytimer';
import { Component, Vue, Prop, Watch } from 'vue-property-decorator';

@Component
export default class VueCountdown extends Vue {
    // props: {
    //     seconds: Number,
    //     countdown: Boolean,
    //     message: String,
    //     date: String,
    //     units: Array,
    //     start: {
    //         type: Boolean,
    //         default: true
    //     }
    // },

    @Prop(Number)
    seconds;
    @Prop(Boolean)
    countdown;
    @Prop([String])
    message;
    @Prop([String])
    date;
    @Prop([Array])
    units;
    @Prop({ default: true, type: Boolean })
    start: boolean;


    // data () {
    //     return {
    //         timer: null,
    //         time: '',
    //         label: this.message ? this.message : 'Time\'s up!',
    //         timerUnits: this.units ? this.units : ['hours', 'minutes', 'seconds'],
    //         timerOptions: {}
    //     };
    // },

    warningSeconds: Number;// = this.seconds;
    timer: EasyTimer = null;
    time: string = '';
    label: string;// = this.message ? this.message : 'Time\'s up!';
    timerUnits: string[];// = this.units ? this.units : ['hours', 'minutes', 'seconds'];
    timerOptions = {
        startValues: {},
        // target: {},
        countdown: true,
        current_page: 0,
        data: []
    };

    get parsedDate(): number {
        if (!this.date) {
            return 0;
        }
        return Date.parse(this.date);
    }
    mounted() {
        this.warningSeconds = this.seconds;
        this.label =  this.message ? this.message : 'Time\'s up!';
        this.timerUnits = this.units ? this.units : ['hours', 'minutes', 'seconds'];
    }
    
    created() {
        this.timer = new EasyTimer();

        const parsedDate = this.parsedDate;
        const now = Date.now();

        let seconds = this.seconds;
        this.timerOptions.countdown = true;

        // = {
        //     countdown: true
        // };

        if (!parsedDate && this.date) {
            throw new Error('Please provide valid date');
        }

        if (now < parsedDate) {
            seconds = (parsedDate - now) / 1000;
        }

        this.timerOptions.startValues = {
            seconds: seconds
        };
       
        if (this.start) {
            this.timer.start(this.timerOptions);
        }

        this.time = this.timer.getTimeValues().toString(this.timerUnits);

        this.timer.addEventListener('secondsUpdated', this.onTimeChange.bind(this));
        this.timer.addEventListener('targetAchieved', this.onTimeExpire.bind(this));
    }


    onTimeChange() {
        this.warningSeconds = this.timer.getTotalTimeValues().seconds;        
        this.time = this.timer.getTimeValues().toString(this.timerUnits);
    }

    onTimeExpire() {
        this.$emit('time-expire');
        this.time = this.label;
    }


    @Watch('start')
    onStartChanged(newValue) {
        if (newValue) {
            this.timer.start(this.timerOptions);
        } else {
            this.timer.stop();
        }
    }

}