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

    fetchLocationWithCoordinate(lat, lng);
    // Remove previous marker if it exists
    if (currentMarker) {
      map.removeLayer(currentMarker);
    }

    
    

      
  });


  function fetchLocationWithCoordinate(lat, lng) {
    // Use a geocoding service to get the location name
    fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
      .then(response => response.json())
      .then(data => {
        if (data && data.display_name) {
          document.getElementById('location').value = '\nअनुमानित केन्द्र: ' + data.display_name;
          document.getElementById('latitude').value = lat.toFixed(5);
          document.getElementById('longtitude').value = lng.toFixed(5);
          // Add new marker
          currentMarker = L.marker([lat, lng]).addTo(map)
            .bindPopup('Location: ' + data.display_name)
            .openPopup();
        } else {
          console.log('Location not found');
        }
      })
      .catch(error => console.error('Error fetching location:', error));
  }

  function search(loction){
    // Use a geocoding service to get the location name
    fetch(`https://nominatim.openstreetmap.org/search?q=${loction}&countrycodes=np&format=json`)
      .then(response => response.json())
      .then(data => {
        if (data && data.length > 0) {
          const lat = data[0].lat;
          const lon = data[0].lon;
          map.setView([lat, lon], 10);
          L.marker([lat, lon]).addTo(map)
            .bindPopup('Location: ' + data[0].display_name)
            .openPopup();
            document.getElementById('location').value = '\nअनुमानित केन्द्र: ' + data[0].display_name;
            document.getElementById('latitude').value = lat;
            document.getElementById('longtitude').value = lon;
        } else {
          alert('Location not found');
        }
      })
      .catch(error => console.error('Error fetching location:', error));
  }

  function getRadius(){
    const radius = document.getElementById('radius').value;
    if (radius) {
      const lat = parseFloat(document.getElementById('latitude').value);
      const lng = parseFloat(document.getElementById('longtitude').value);
      if (lat && lng) {
      // Remove previous circle if it exists
      if (window.currentCircle) {
        map.removeLayer(window.currentCircle);
      }
      window.currentCircle = L.circle([lat, lng], {
        color: 'blue',
        fillColor: '#30f',
        fillOpacity: 0.2,
        radius: radius * 1000 // Convert km to meters
      }).addTo(map);
      } else {
      alert('Please enter valid latitude and longitude.');
      }
    } else {
      // Remove the circle if radius is empty
      if (window.currentCircle) {
        map.removeLayer(window.currentCircle);
        window.currentCircle = null;
      }
    }
  }
  
</script>