<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    @php
        $lat = $getInitialLat();
        $lng = $getInitialLng();
    @endphp
    <div 
        x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }"
        x-init="
            const map = L.map($refs.map).setView([
                @js($lat), 
                @js($lng)
            ], 13)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				maxZoom: 20
			}).addTo(map)

            if (null !== state) {
				const parsedCoordinates = JSON.parse(state)
				const restoredPolygon = L.polygon(parsedCoordinates).addTo(map)
				map.fitBounds(restoredPolygon.getBounds())
			}
            
			const drawControl = new L.Control.Draw({
				draw: {
					polygon: true,
					polyline: false,
					rectangle: false,
					circle: false,
					marker: false
				},
				edit: {
					featureGroup: new L.FeatureGroup(),
					edit: false
				}
			})
			map.addControl(drawControl)

            map.on('draw:created', function (event) {
				const layer = event.layer
				const coordinates = layer.getLatLngs()
                state = JSON.stringify(coordinates)
                map.addLayer(layer)
			})
        "
    >
        <!-- Interact with the `state` property in Alpine.js -->
        <input type="hidden" x-model="state" />
        <div wire:ignore x-ref="map" style="width: 100%; height: 60vh"></div>
    </div>
</x-dynamic-component>
