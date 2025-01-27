// map.js
mapboxgl.accessToken = 'pk.eyJ1IjoiZ29kZWdrb2xhIiwiYSI6ImNsb2EwaWVzcTBmdHAycXFicTlsMmxyeXYifQ.wEYJUoOoqnFzHFURicvCgQ';

// Initialize maps and markers
let map, mobileMap;
let marker, mobileMarker;

// Function to initialize the desktop map
export function initDesktopMap() {
    const mapContainer = document.getElementById('map');
    if (!mapContainer) return; // Exit if the desktop map container is not rendered

    map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        zoom: 4,
        center: [120.984222, 14.599512]
    });

    // Initialize the desktop marker
    marker = new mapboxgl.Marker()
        .setLngLat([120.984222, 14.599512])
        .addTo(map);

    // Hide the marker initially
    const markerElement = marker.getElement();
    markerElement.style.display = 'none';
}

// Function to initialize the mobile map
export function initMobileMap() {
    const mobileMapContainer = document.getElementById('mobileMap');
    if (!mobileMapContainer) return; // Exit if the mobile map container is not rendered

    mobileMap = new mapboxgl.Map({
        container: 'mobileMap',
        style: 'mapbox://styles/mapbox/streets-v11',
        zoom: 4,
        center: [120.984222, 14.599512]
    });

    // Initialize the mobile marker
    mobileMarker = new mapboxgl.Marker()
        .setLngLat([120.984222, 14.599512])
        .addTo(mobileMap);

    // Hide the marker initially
    const mobileMarkerElement = mobileMarker.getElement();
    mobileMarkerElement.style.display = 'none';
}

// Function to update both maps and markers
export function showMap(longitude, latitude) {
    // Update the desktop map if it exists
    if (map) {
        map.flyTo({
            center: [longitude, latitude],
            zoom: 14,
            essential: true
        });

        marker.setLngLat([longitude, latitude]);
        marker.getElement().style.display = 'block'; // Show the marker
    }

    // Update the mobile map if it exists
    if (mobileMap) {
        mobileMap.flyTo({
            center: [longitude, latitude],
            zoom: 14,
            essential: true
        });

        mobileMarker.setLngLat([longitude, latitude]);
        mobileMarker.getElement().style.display = 'block'; // Show the marker
    }
}