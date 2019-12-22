<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="{{asset('css/leaflet.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/skysatu.css') }}"/>
</head>
<body>
<div id="googleMap" style="width: 600px ; height: 400px"></div>

<script src="{{asset('js/leaflet.js')}}"></script>
<script src="{{asset('js/leaflet.rotatedMarker.js')}}"></script>
<script>
    var locations = {!! json_encode($data) !!};

    var LeafIcon = L.Icon.extend({
        options: {
            iconSize: [17, 17],
            shadowSize: [10, 12],
            shadowAnchor: [4, 62],
            iconAnchor: [10, 10],//changed marker icon position
            popupAnchor: [0, -16]//changed popup position
        }
    });

    var map = L.map('googleMap', {center: [locations.latitude, locations.longitude], zoom: 10});


    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    function getIcon(message) {
        var yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);

        var oneHoursBefore = new Date();
        oneHoursBefore.setHours(oneHoursBefore.getHours() - 1);

        var notActivityMoreThan24h = message.eventTime <= yesterday.getTime();
        var notActivityMoreThan1h = message.eventTime <= oneHoursBefore.getTime();

        var speedMoreThen05 = notActivityMoreThan24h ? "{{asset('/images/0.5red-ship.png')}}" : notActivityMoreThan1h ? "{{asset('/images/0.5orange-ship.png')}}" : "{{asset('/images/0.5green-ship.png')}}";
        var speedLessThen05 = notActivityMoreThan24h ? "{{asset('/images/0.05red-ship.png')}}" : notActivityMoreThan1h ? "{{asset('/images/0.05orange-ship.png')}}" : "{{asset('/images/0.05green-ship.png')}}";

        return message.speed > 0.5 ? speedMoreThen05 : speedLessThen05;
    }

    var greenIcon = new LeafIcon({iconUrl: getIcon(locations)});
    var rotation = locations.speed > 0.49 ? Math.round(locations.heading * 0.7) : 0;
    var marker = L.marker([locations.latitude, locations.longitude],
        {rotationAngle: rotation, icon: greenIcon});
    marker.addTo(map);
</script>
</body>
</html>
