w<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{asset('css/leaflet.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/skysatu.css') }}"/>
</head>
<body>
<div id="googleMap" ></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="{{asset('js/leaflet.js')}}"></script>
<script src="{{asset('js/leaflet.rotatedMarker.js')}}"></script>
<script>
    let locations = {!! json_encode($data) !!};

    let LeafIcon = L.Icon.extend({
        options: {
            iconSize: [17, 17],
            shadowSize: [10, 12],
            shadowAnchor: [4, 62],
            iconAnchor: [10, 10],//changed marker icon position
            popupAnchor: [0, -16]//changed popup position
        }
    });

    let map = L.map('googleMap', {center: [locations.latitude, locations.longitude], zoom: 8});


    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    function getIcon(message) {
        let yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);

        let oneHoursBefore = new Date();
        oneHoursBefore.setHours(oneHoursBefore.getHours() - 1);

        let notActivityMoreThan24h = message.eventTime <= yesterday.getTime();
        let notActivityMoreThan1h = message.eventTime <= oneHoursBefore.getTime();

        let speedMoreThen05 = notActivityMoreThan24h ? "{{asset('/images/0.5red-ship.png')}}" : notActivityMoreThan1h ? "{{asset('/images/0.5orange-ship.png')}}" : "{{asset('/images/0.5green-ship.png')}}";
        let speedLessThen05 = notActivityMoreThan24h ? "{{asset('/images/0.05red-ship.png')}}" : notActivityMoreThan1h ? "{{asset('/images/0.05orange-ship.png')}}" : "{{asset('/images/0.05green-ship.png')}}";

        return message.speed > 0.5 ? speedMoreThen05 : speedLessThen05;
    }

    let greenIcon = new LeafIcon({iconUrl: getIcon(locations)});
    let rotation = locations.speed > 0.49 ? Math.round(locations.heading * 0.7) : 0;
    let marker = L.marker([locations.latitude, locations.longitude],
        {rotationAngle: rotation, icon: greenIcon});
    marker.addTo(map);
</script>
</body>
</html>
