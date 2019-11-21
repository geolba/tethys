<template>
    <div v-show="warningSeconds <= 600" class="vue-countdown">
        <div class="vue-countdown--time">
            remaining time: {{ time }}
        </div>
    </div>
</template>

<script lang="ts">
import VueCountdown from "./VueCountdown";
export default VueCountdown;
</script>

<style scoped>
.vue-countdown--time {
  color:red;
}
</style>