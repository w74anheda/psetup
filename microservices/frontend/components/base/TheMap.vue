<template>
    <div class="page">
        <NeshanMap ref="map" mapKey="web.e5b6324377214692ad3d44d3aaac196f"
            serviceKey="service.48f807b63362496c9ff5da49a79fc3c0"
            :center="{ latitude: 35.69672648316882, longitude: 51.36281969540723 }"
            :zoom="12" :traffic="false" hide-layers :cluster="false"
            @on-click="$emit('SetCoordinate', $event.coords)" />
    </div>
</template>

<script setup lang="ts">
import NeshanMap from "@neshan-maps-platform/vue3-openlayers"

</script>

<style>
@import "@neshan-maps-platform/vue3-openlayers/dist/style.css";

.page {
    height: 100%;
}
</style>