<script>
        function setMessage() {
            const alertType = document.getElementById('alert').value;
            let messageBodyElement = document.getElementById('message-body');
            let earthquakeScaleElement = document.getElementById('earthquakeScale');
            let earthquakeScaleCol = document.getElementById('scale');
            
            if (alertType === 'earthquake') {
                messageBodyElement.value = 'भूकम्प गएको पत्ता लाग्यो । कृपया सुरक्षित रहनुहोस्!\nEarthquake detected. Please stay safe!';
                earthquakeScaleCol.style.display = 'block';
            } else if (alertType === 'flood') {
                messageBodyElement.value = 'बाढीको चेतावनी जारी गरिएको छ। उच्च जमिनमा खाली गर्नुहोस्!\nFlood warning issued. Evacuate to higher ground!';
                earthquakeScaleElement.value = null;
                earthquakeScaleCol.style.display = 'none';
            } else if (alertType === 'landslide') {
                messageBodyElement.value = 'तपाईंको क्षेत्रमा पहिरोको जोखिम। ठाडो ढलानबाट बच्नुहोस्!\nLandslide risk in your area. Avoid steep slopes!';
                earthquakeScaleElement.value = null;
                earthquakeScaleCol.style.display = 'none';
            }
        }

        function setScale(){
            let earthquakeScaleElement = document.getElementById('earthquakeScale');
            let messageBodyElement = document.getElementById('message-body');
            messageBodyElement.value +='\nEstimated Richter scale:' + earthquakeScaleElement.value;
        }
    </script>