<div id="defibMapWrapper">
    <div id="defibMapCanvas"></div>
</div>

<script>
    function initMap() {
        const locations = {!! json_encode($locations) !!};

        const map = new google.maps.Map(document.getElementById("defibMapCanvas"), {
            center: { lat: 52.930991, lng: -6.2325032 },
            zoom: 14,
            mapTypeId: 'hybrid',
            disableDefaultUI: true,
        });

        const infoWindow = new google.maps.InfoWindow();

        locations.map(function (location) {
            const latLng = {lat: parseFloat(location.lat), lng: parseFloat(location.lng)}

            const marker = new google.maps.Marker({
                position: latLng,
                title: location.name,
                map: map,
            });

            (function (marker, location) {
                google.maps.event.addListener(marker, "click", function (e) {
                    infoWindow.setContent("<div style='width:200px;min-height:40px'>" + location.location +"</div>");
                    infoWindow.open(map, marker);
                });
            })(marker, location);
        });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsAPIKey }}&callback=initMap"></script>
