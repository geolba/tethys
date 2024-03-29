import { Component, Vue, Prop } from 'vue-property-decorator';
import { DATACITE_PREFIX, APP_URL } from "../../constants";

@Component
export default class VsResults extends Vue {

    openAccessLicences: Array<string> = ['CC-BY-4.0', 'CC-BY-SA-4.0'];
    datacite_prefix= DATACITE_PREFIX;
    app_url=APP_URL;

    @Prop()
    data;

    get results() {
        return this.data;
    };

    getDomainWithoutSubdomain () {
        const urlParts = new URL(window.location.href).hostname.split('.')
      
        return urlParts
          .slice(0)
          .slice(-(urlParts.length === 4 ? 3 : 2))
          .join('.')
      }

    truncate(text, limit) {
        text = text === undefined ? '' : text;
        const content = text.split(' ').slice(0, limit);
        return content.join(' ');
    };

    error(item) {
        delete item.thumb;
        this.$forceUpdate();
    };

    convert(unixtimestamp: number) {

        // Unixtimestamp
        // var unixtimestamp = document.getElementById('timestamp').value;
        // Months array
        var months_arr = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        // Convert timestamp to milliseconds
        var date = new Date(unixtimestamp * 1000);
        // Year
        var year = date.getFullYear();
        // Month
        var month = months_arr[date.getMonth()];
        // Day
        var day = date.getDate();
        // Hours
        var hours = date.getHours();
        // Minutes
        var minutes = "0" + date.getMinutes();
        // Seconds
        var seconds = "0" + date.getSeconds();
        // Display date time in MM-dd-yyyy h:m:s format
        var convdataTime = month + '-' + day + '-' + year + ' ' + hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
        // document.getElementById('datetime').innerHTML = convdataTime;
        return convdataTime;
    };


}