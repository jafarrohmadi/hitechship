<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{asset('css/leaflet.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/tabs.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/tabstyles.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/skysatu.css') }}"/>
</head>
<body>
<div id="googleMap"></div>
<div id="googleMapHistory" style="display: none"></div>
<div id="floating-panel">
    <div class="close">
        <i class="fa fa-angle-double-left"></i>
    </div>
    <section>
        <div class="tabs tabs-style-linebox">
            <nav>
                <ul>
                    <li id="tab-track" class="tab-current"><a href="#section-linebox-1"><span>Track</span></a></li>
                    <li id="tab-history"><a href="#section-linebox-2"><span>History</span></a></li>
                </ul>
            </nav>
            <div class="content-wrap">
                <section id="section-linebox-1" class="content-current">
                    <table class="fixed_headers" id="tracking_table">
                        <thead>
                        <tr>
                            <td><input id="checkAll" type="checkbox" checked="checked"/></td>
                            <td>Name</td>
                            <td>Last</td>
                            <td>Speed (knots)</td>
                        </tr>
                        </thead>
                        <tbody id="shipData">

                        </tbody>
                    </table>
                </section>
                <section id="section-linebox-2">
                    <div style="text-align: right;">
                        <input type="text" class="datepicker startDate">&nbsp;
                        <input type="text" class="datepicker endDate">
                        <button id="setDate"><i class="fa fa-search"></i>&nbsp;Search</button>
                        <button id="downloadCSV"><i class="fa fa-download"></i></button>
                    </div>
                    <table class="fixed_headers history" id="history_table">
                        <thead>
                        <tr>
                            <td></td>
                            <td>Name</td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody id='historyShipData'>
                        </tbody>
                    </table>
                </section>
            </div><!-- /content -->
        </div><!-- /tabs -->
    </section>
</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('js/MeasureTool.min.js') }}"></script>
    <script src="{{ asset('js/cbpFWTabs.js') }}"></script>
    <script src="{{asset('js/jquery-dateFormat.min.js') }}"></script>

    <script src="{{asset('js/leaflet.js')}}"></script>
    <script src="{{asset('js/leaflet.rotatedMarker.js')}}"></script>

    <script src="{{asset('js/customleaflet.js')}}"></script>

</body>
</html>
