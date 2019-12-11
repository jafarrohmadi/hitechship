@extends('layouts.admin')
@section('style')
 <link rel="stylesheet" href="{{asset('css/leaflet.css') }}"/>
@endsection
@section('content')
<div class="card">
  <div id="map" style="height: 300px"></div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/leaflet.js') }}"></script>
<script type="text/javascript">
     var map = L.map('map',{
    center: [43.64701, -79.39425],
    zoom: 15
    });

    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
</script>
@endsection