<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>


<script>
  // Initialize the map and set its view
  var map = L.map('map', {
    center: [28.3949, 84.1240],
    zoom: 7,
    minZoom: 7.2,
    maxZoom: 16,
    maxBounds: nepalBounds,
    maxBoundsViscosity: 1.0
  });

  // Add OpenStreetMap tiles
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  // Restrict the map to Nepal bounding box
  var nepalBounds = [
    [26.347, 80.058], // Southwest coordinates (Lat, Lng)
    [30.447, 88.201]  // Northeast coordinates (Lat, Lng)
  ];
    map.setMaxBounds(nepalBounds);
    map.on('drag', function () {
        map.panInsideBounds(nepalBounds, { animate: false });
    });

    let currentMarker = null;
   map.on('click', function (e) {

    const lat = e.latlng.lat;
    const lng = e.latlng.lng;

    let location = fetchLocation(lat, lng);
    console.log('Clicked location:', location);
    // Remove previous marker if it exists
    if (currentMarker) {
      map.removeLayer(currentMarker);
    }

    // Add new marker
    currentMarker = L.marker([lat, lng]).addTo(map)
      .bindPopup('Latitude: ' + lat.toFixed(5) + '<br>Longitude: ' + lng.toFixed(5) + '<br>Location: ' + location)
      .openPopup();

      document.getElementById('latitude').value = lat.toFixed(5);
      document.getElementById('longtitude').value = lng.toFixed(5);
  });


  function fetchLocation(lat, lng) {
    // Use a geocoding service to get the location name
    fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
      .then(response => response.json())
      .then(data => {
        if (data && data.display_name) {
          document.getElementById('message-body').value += '\nअनुमानित केन्द्र: ' + data.display_name;
          return data.display_name;
        } else {
          console.log('Location not found');
        }
      })
      .catch(error => console.error('Error fetching location:', error));
  }
  
</script>