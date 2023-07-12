<template>
    <ol-map @click="setMarker" :loadTilesWhileAnimating="true"
        :loadTilesWhileInteracting="true" style="height: 400px" ref="map">
        <ol-view ref="view" :center="center" :rotation="rotation" :zoom="zoom"
            :projection="projection" />

        <ol-tile-layer>
            <ol-source-osm />
        </ol-tile-layer>

        <ol-vector-layer>
            <ol-source-vector>
                <ol-feature>
                    <ol-geom-point :coordinates="coordinate ?? []"></ol-geom-point>
                    <ol-style>
                        <ol-style-icon :src="hereIcon" :scale="0.1"></ol-style-icon>
                    </ol-style>
                </ol-feature>
            </ol-source-vector>
        </ol-vector-layer>
    </ol-map>
</template>
  
<script setup lang="ts">
import hereIcon from "/images/map-marker.png";
import { View } from "ol";

const center = ref([51.4, 35.7]);
const projection = ref("EPSG:4326");
const zoom = ref(13);
const rotation = ref(0);
const view = ref<View>();
const map = ref(null);
const coordinate = ref(null);

const setMarker = (event: any) => {
    coordinate.value = event.target.getCoordinateFromPixel(event.pixel);
}
</script>